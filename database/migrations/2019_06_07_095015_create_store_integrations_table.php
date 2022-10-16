<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('store_integrations', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('mall_id')->index();
            $table->unsignedInteger('store_id')->index();
            $table->longText('config')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('store_integrations');
    }
}
