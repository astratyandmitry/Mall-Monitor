<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('developers', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('mall_id')->unsigned()->index();
            $table->integer('store_id')->unsigned()->index();
            $table->string('username', 80);
            $table->string('password', 200);
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('developers');
    }

}
