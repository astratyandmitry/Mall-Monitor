<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCashboxTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists('cashbox_types');
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::create('cashbox_types', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name', 80);
            $table->string('system_key', 40)->unique();
        });
    }

}
