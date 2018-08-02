<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.4.1)
 *
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateMallsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('malls', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name', 200);
            $table->integer('city_id')->unsigned()->index();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('malls');
    }

}
