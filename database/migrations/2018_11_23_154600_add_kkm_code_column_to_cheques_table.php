<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKkmCodeColumnToChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('cheques', function (Blueprint $table): void {
            $table->string('kkm_code', 200)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('cheques', function (Blueprint $table): void {
            $table->dropColumn('kkm_code');
        });
    }
}
