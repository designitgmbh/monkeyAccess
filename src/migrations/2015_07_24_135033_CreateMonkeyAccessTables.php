<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonkeyAccessTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//create settings
    	if (!Schema::hasTable('access_rights'))
    	{
    		Schema::create('access_rights', function(Blueprint $table) {
	    		$table->increments('id');
	    		$table->string('action', 45);
	    		$table->string('resource', 45);
	    	});	
    	} 
    	else 
    	{
    		$hasNeededColumns = 
    			Schema::hasColumn('access_rights', 'id') &&
    			Schema::hasColumn('access_rights', 'action') &&
    			Schema::hasColumn('access_rights', 'resource');

    		if(!$hasNeededColumns)
    			return "Table with name 'access_rights' is not compatible with MonkeySettings, sorry.";
    	}

    	if(!Schema::hasTable('profile_types'))
    	{
    		Schema::create('profile_types', function(Blueprint $table) {
    			$table->increments('id');
    			$table->string('name', 60);
	    	});
    	}
    	else
    	{
    		$hasNeededColumns = 
    			Schema::hasColumn('profile_types', 'id') &&
    			Schema::hasColumn('profile_types', 'name');

    		if(!$hasNeededColumns)
    			return "Table with name 'profile_types' is not compatible with MonkeySettings, sorry.";
    	}

    	if(!Schema::hasTable('profiles'))
    	{
    		Schema::create('profiles', function(Blueprint $table) {
    			$table->increments('id');
    			$table->string('name', 60);
    			$table->text('description');
	    		$table->integer('profile_type_id')->unsigned();
                
	    		$table->foreign('profile_type_id')->references('id')->on('profile_types');
	    	});
    	}
    	else
    	{
    		$hasNeededColumns = 
    			Schema::hasColumn('profiles', 'id') &&
    			Schema::hasColumn('profiles', 'name') &&
    			Schema::hasColumn('profiles', 'description') &&
    			Schema::hasColumn('profiles', 'profile_type_id');

    		if(!$hasNeededColumns)
    			return "Table with name 'profiles' is not compatible with MonkeyAccess, sorry.";
    	}

    	if(!Schema::hasTable('profile_allowed_access_right'))
    	{
    		Schema::create('profile_allowed_access_right', function(Blueprint $table) {
    			$table->integer('profile_id')->unsigned();
    			$table->integer('access_right_id')->unsigned();

    			$table->primary(['profile_id', 'access_right_id']);
    			$table->foreign('profile_id')->references('id')->on('profiles');
    			$table->foreign('access_right_id')->references('id')->on('access_rights');
	    	});
    	}
    	else
    	{
    		$hasNeededColumns = 
    			Schema::hasColumn('profile_allowed_access_right', 'profile_id') &&
    			Schema::hasColumn('profile_allowed_access_right', 'access_right_id');

    		if(!$hasNeededColumns)
    			return "Table with name 'profile_allowed_access_right' is not compatible with MonkeySettings, sorry.";
    	}

    	if(!Schema::hasTable('profile_type_allowed_access_right'))
    	{
    		Schema::create('profile_type_allowed_access_right', function(Blueprint $table) {
    			$table->integer('profile_type_id')->unsigned();
    			$table->integer('access_right_id')->unsigned();

    			$table->primary(['profile_type_id', 'access_right_id'], 'ptaar_pti_ari');
    			$table->foreign('profile_type_id')->references('id')->on('profile_types');
    			$table->foreign('access_right_id')->references('id')->on('access_rights');
	    	});
    	}
    	else
    	{
    		$hasNeededColumns = 
    			Schema::hasColumn('profile_type_allowed_access_right', 'profile_type_id') &&
    			Schema::hasColumn('profile_type_allowed_access_right', 'access_right_id');

    		if(!$hasNeededColumns)
    			return "Table with name 'profile_type_allowed_access_right' is not compatible with MonkeySettings, sorry.";
    	}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_type_allowed_access_right');
        Schema::dropIfExists('profile_allowed_access_right');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('profile_types');
        Schema::dropIfExists('access_rights');
    }
}