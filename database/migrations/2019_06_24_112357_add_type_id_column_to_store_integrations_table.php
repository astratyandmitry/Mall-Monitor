<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeIdColumnToStoreIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('store_integrations', function (Blueprint $table): void {
            $table->unsignedInteger('type_id')->after('store_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('store_integrations', function (Blueprint $table): void {
            $table->dropColumn('type_id');
        });
    }
}
