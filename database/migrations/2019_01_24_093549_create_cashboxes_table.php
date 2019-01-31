<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashboxesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cashboxes', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('code', 120)->unique();
            $table->integer('mall_id')->unsigned()->index();
            $table->integer('store_id')->unsigned()->index();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cashboxes');
    }

}
