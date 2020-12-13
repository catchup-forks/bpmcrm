<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_versions', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->text('bpmn');
            $table->string('name', 100);
            $table->unsignedInteger('process_category_id');
            $table->unsignedInteger('process_id');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])
                    ->default('ACTIVE');
            $table->timestamps();

            // Indexes
            $table->index('process_id');

            // Foreign keys
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('process_category_id')->references('id')->on('process_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_versions');
    }
}
