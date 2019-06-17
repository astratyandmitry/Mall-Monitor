<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreIntegrationLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('store_integration_logs', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('type_id')->index();
            $table->unsignedInteger('mall_id')->index();
            $table->unsignedInteger('store_id')->index();
            $table->longText('output')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('store_integration_logs');
    }

}
