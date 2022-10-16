<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cheque_items', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('cheque_id');
            $table->string('name', 200)->nullable();
            $table->string('code', 200)->nullable();
            $table->integer('quantity');
            $table->decimal('price');
            $table->decimal('sum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cheque_items');
    }
}
