<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentsColumnToStoreIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('store_integrations', function (Blueprint $table): void {
            $table->text('payments')->nullable()->after('types');
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
            $table->dropColumn('payments');
        });
    }
}
