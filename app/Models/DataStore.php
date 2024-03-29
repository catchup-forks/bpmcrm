<?php
namespace App\Models;

use App\Nayra\Bpmn\DataStoreTrait;
use App\Nayra\Contracts\Bpmn\DataStoreInterface;
use App\Nayra\Contracts\Bpmn\ItemDefinitionInterface;
use App\Nayra\Contracts\Bpmn\ProcessInterface;

/**
 * Application Data
 *
 * @package app\Models
 */
final class DataStore implements DataStoreInterface
{

    use DataStoreTrait;

    private array $data = [];

    /**
     *
     * @var \app\Nayra\Contracts\Bpmn\ProcessInterface
     */
    private $process;

    /**
     *
     * @var \app\Nayra\Contracts\Bpmn\ItemDefinitionInterface
     */
    private $itemSubject;

    /**
     * Get owner process.
     *
     * @return ProcessInterface
     */
    public function getOwnerProcess()
    {
        return $this->process;
    }

    /**
     * Get Process of the application.
     *
     * @param \app\Nayra\Contracts\Bpmn\ProcessInterface $process
     *
     * @return ProcessInterface
     */
    public function setOwnerProcess(ProcessInterface $process)
    {
        $this->process = $process;
        return $this;
    }

    /**
     * Get data from store.
     *
     *
     * @return mixed
     */
    public function getData(mixed $name = null, $default = null)
    {
        return $name === null ? $this->data : ($this->data[$name] ?? $default);
    }

    /**
     * Set data of the store.
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Put data to store.
     *
     * @param string $name
     *
     * @return $this
     */
    public function putData($name, mixed $data)
    {
        $this->data[$name] = $data;
        return $this;
    }

    /**
     * Get the items that are stored or conveyed by the ItemAwareElement.
     *
     * @return ItemDefinitionInterface
     */
    public function getItemSubject()
    {
        return $this->itemSubject;
    }
}
