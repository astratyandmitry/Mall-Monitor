<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('integration_logs', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('system_id')->index();
            $table->string('operation', 80);
            $table->integer('code')->default(0);
            $table->string('message', 200)->nullable();
            $table->text('data')->nullable();
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
        Schema::dropIfExists('integration_logs');
    }
}
