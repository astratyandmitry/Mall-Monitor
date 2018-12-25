<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMallIntegrationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('mall_integrations', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('mall_id')->index();
            $table->unsignedInteger('system_id')->index();
            $table->string('host', 200);
            $table->string('username', 80);
            $table->string('password', 80);
            $table->longText('data')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('mall_integrations');
    }

}
