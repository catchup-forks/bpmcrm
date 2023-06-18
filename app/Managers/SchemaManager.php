<?php

namespace App\Managers;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Model\PmTable;
use App\Model\ProcessVariable;
use stdClass;

final class SchemaManager
{
    /**
     * Returns the Doctrine/Eloquent type that corresponds to a MySql type
     *
     * @param string $databaseType
     *
     * @return string
     */
    public function schemaType($databaseType)
    {
        $dbalPlatform = new MySqlPlatform();
        return $dbalPlatform->getDoctrineTypeMapping($databaseType);
    }

    /**
     * Determines if a MySql type has a size in the doctrine\dbal definition
     *
     * @param string $databaseType
     *
     * @return bool
     */
    public function typeHasSizeAsParameter($databaseType)
    {
        $dbalPlatform = new MySqlPlatform();
        $dbalType = $this->schemaType($databaseType);
        $type = Type::getType($dbalType);
        return !is_null($type->getDefaultLength($dbalPlatform));
    }

    /**
     * Updates a column or creates it if it does not exist
     *
     * @param PmTable $pmTable
     *
     */
    public function updateOrCreateColumn(PmTable $pmTable, array $field): void
    {
        $tableName = $pmTable->physicalTableName();
        $columnType = $this->schemaType($field['type']);

        $existsColumn = Schema::hasColumn($tableName, $field['name']);
        $existsTable = Schema::hasTable($tableName);

        if ($existsTable && $existsColumn) {
            $this->changePhysicalColumn($pmTable, $field);
        }

        if ($existsTable && !$existsColumn) {
            $this->createPhysicalColumn($pmTable, $field);
        }

        if (!$existsTable && !$existsColumn) {
            $this->createPhysicalTableAndColumn($pmTable, $field);
        }

        // we add the nullable column property
        $canBeNull = isset($field['null']) && $field['null'] === 1;

        if (empty($field['size']) || !$this->typeHasSizeAsParameter($field['type'])) {
            Schema::table($tableName, function ($table) use ($columnType, $field, $canBeNull): void {
                $table->{$columnType}($field['name'])
                    ->nullable($canBeNull)
                    ->change();
            });
        } else {
            Schema::table($tableName, function ($table) use ($columnType, $field, $canBeNull): void {
                $table->{$columnType}($field['name'], $field['size'])
                    ->nullable($canBeNull)
                    ->change();
            });
        }

        // we set the field as primary key
        if (isset($field['key']) && $field['key'] === 1) {
            Schema::table($tableName, function ($table) use ($tableName, $field): void {
                if ($this->existsPrimaryKey($tableName)) {
                    $table->dropPrimary();
                }
                $table->primary($field['name']);
            });
        }

        // we add the autoincrement property
        if (isset($field['auto_increment']) && $field['auto_increment'] === 1) {
            Schema::table($tableName, function ($table) use ($tableName, $field): void {
                if ($this->existsPrimaryKey($tableName)) {
                    $table->dropPrimary();
                }
            });

            Schema::table($tableName, function ($table) use ($field): void {
                $table->dropColumn($field['name']);
            });

            Schema::table($tableName, function ($table) use ($field): void {
                $table->increments($field['name']);
            });
        }
    }

    /**
     * Removes a column from a physical table
     *
     * @param PmTable $pmTable
     * @param string $columnName
     */
    public function dropColumn(PmTable $pmTable, $columnName): void
    {
        Schema::table($pmTable->physicalTableName(), function ($table) use ($columnName): void {
            $table->dropColumn($columnName);
        });
    }

    /**
     * Removes a physical table
     *
     * @param string $tableName
     */
    public function dropPhysicalTable($tableName): void
    {
        Schema::dropIfExists($tableName);
    }

    /**
     * Sets the default values for fields of a report table
     *
     * @param ProcessVariable $variable
     * @return array
     */
    public function setDefaultsForReportTablesFields(array $field, ProcessVariable $variable)
    {
        $result = $field;

        if (!array_key_exists('type', $result)) {
            $result['type'] = $this->variableType2Mysql($variable->VAR_FIELD_TYPE);
        }

        if (!array_key_exists('size', $result)) {
            $result['size'] = $this->variableSize($variable->VAR_FIELD_TYPE);
        }

        //by default report tables allows nulls
        if (!array_key_exists('null', $result)) {
            $result['null'] = 1;
        }

        return $result;
    }

    /**
     * Returns the mysql type equivalente to a app process variable name
     *
     * @param string $varType
     * @return string
     */
    private function variableType2Mysql($varType)
    {
        return match ($varType) {
            'integer' => 'INTEGER',
            'float' => 'FLOAT',
            'boolean' => 'INTEGER',
            'datetime' => 'DATETIME',
            default => 'VARCHAR',
        };
    }

    /**
     * Returns the default size to use for a certain mysql type
     *
     * @param string $varType
     * @return int|null
     */
    private function variableSize($varType)
    {
        return ($this->variableType2Mysql($varType) === 'VARCHAR')
            ? 250
            : null;
    }

    /**
     * Changes a column with the metadata specified in the $field array to the PmTable
     *
     * @param PmTable $pmTable
     */
    private function changePhysicalColumn(PmTable $pmTable, array $field): void
    {
        $tableName = $pmTable->physicalTableName();
        $columnType = $this->schemaType($field['type']);
        if (empty($field['size']) || !$this->typeHasSizeAsParameter($field['type'])) {
            Schema::table($tableName, function ($table) use ($columnType, $field): void {
                $table->{$columnType}($field['name'])->change();
            });
        } else {
            Schema::table($tableName, function ($table) use ($columnType, $field): void {
                $table->{$columnType}($field['name'], $field['size'])->change();
            });
        }
    }

    /**
     * Adds a column with the metadata specified in the $field array to the PmTable
     *
     * @param PmTable $pmTable
     */
    private function createPhysicalColumn(PmTable $pmTable, array $field): void
    {
        $tableName = $pmTable->physicalTableName();
        $columnType = $this->schemaType($field['type']);
        if (empty($field['size']) || !$this->typeHasSizeAsParameter($field['type'])) {
            Schema::table($tableName, function ($table) use ($columnType, $field): void {
                $table->{$columnType}($field['name']);
            });
        } else {
            Schema::table($tableName, function ($table) use ($columnType, $field): void {
                $table->{$columnType}($field['name'], $field['size']);
            });
        }
    }

    /**
     * Creates the physical table with a column specified in the $field array
     *
     * @param PmTable $pmTable
     */
    private function createPhysicalTableAndColumn(PmTable $pmTable, array $field): void
    {
        $tableName = $pmTable->physicalTableName();
        $columnType = $this->schemaType($field['type']);

        Schema::create($tableName, function ($table) use ($tableName, $columnType, $field): void {
            if (empty($field['size']) || !$this->typeHasSizeAsParameter($field['type'])) {
                $table->{$columnType}($field['name']);
            } else {
                $table->{$columnType}($field['name'], $field['size']);
            }
        });
    }

    /**
     *  Returns an array with the metadata of the columns of the physical table of a PmTable
     *
     * @param PmTable $pmTable
     */
    public function getMetadataFromSchema(PmTable $pmTable): stdClass
    {
        $tableMetadata = new stdClass();
        $tableMetadata->columns = [];
        $tableMetadata->primaryKeyColumns = [];
        $tableMetadata->hasPrimaryKey = false;

        $conn = DB::connection()->getDoctrineConnection();
        $sm = $conn->getSchemaManager();
        $columns = $sm->listTableColumns($pmTable->physicalTableName());
        $tableDetail = $sm->listTableDetails($pmTable->physicalTableName());

        // we get the metadata for each column of the table and map their values to PM formats
        foreach ($columns as $column) {
            $columnMeta = new stdClass();
            $columnMeta->name = $column->getName();
            $columnMeta->description = $column->getComment();
            $columnMeta->type = $column->getType()->getName();
            $columnMeta->size = $column->getLength();
            $columnMeta->null = $column->getNotnull() ? 0 : 1;
            $columnMeta->auto_increment = $column->getAutoincrement() ? 1 : 0;
            $columnMeta->table_index = $this->columnHasIndex($column->getName(), $tableDetail->getIndexes()) ? 1 : 0;
            $columnMeta->key = false;
            if ($tableDetail->hasPrimaryKey()) {
                $columnMeta->key = in_array($column->getName(), $tableDetail->getPrimaryKeyColumns());
            }
            $tableMetadata->columns[] = $columnMeta;

            $tableMetadata->hasPrimaryKey = $tableDetail->hasPrimaryKey();
        }
        if ($tableDetail->hasPrimaryKey()) {
            $tableMetadata->primaryKeyColumns[] = $tableDetail->getPrimaryKeyColumns()[0];
        }
        return $tableMetadata;
    }

    /**
     * Determines if the table has a primary key
     *
     * @param string $tableName
     */
    private function existsPrimaryKey($tableName): bool
    {
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $indexesFound = $sm->listTableIndexes($tableName);
        return array_key_exists('primary', $indexesFound);
    }

    /**
     * Determines if the column has an index in the $indexes array
     *
     * @param string $columnName
     *
     */
    private function columnHasIndex($columnName, array $indexes): bool
    {
        foreach ($indexes as $index) {
            if (in_array($columnName, $index->getColumns())) {
                return true;
            }
        }
        return false;
    }
}
