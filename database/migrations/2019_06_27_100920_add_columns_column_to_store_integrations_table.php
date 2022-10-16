<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsColumnToStoreIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('store_integrations', function (Blueprint $table): void {
            $table->text('columns')->nullable()->after('config');
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
            $table->dropColumn('columns');
        });
    }
}
