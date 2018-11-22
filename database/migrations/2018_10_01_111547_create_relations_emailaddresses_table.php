<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelationsEmailaddressesTable extends Migration {

	public function up()
	{
		Schema::create('relations__emailaddresses', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index();
			$table->integer('relation_id')->unsigned()->index();
			$table->string('name', 100)->index();
			$table->string('slug', 100)->index();
			$table->string('emailaddress', 100)->index();
			$table->integer('linktype_id')->unsigned();
			$table->tinyInteger('is_primary')->index();
			$table->tinyInteger('is_system');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('relations__emailaddresses');
	}
}