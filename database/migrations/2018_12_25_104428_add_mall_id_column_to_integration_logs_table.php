<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMallIdColumnToIntegrationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('integration_logs', function (Blueprint $table): void {
            $table->unsignedInteger('mall_id')->after('system_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('integration_logs', function (Blueprint $table): void {
            $table->dropColumn('mall_id');
        });
    }
}
