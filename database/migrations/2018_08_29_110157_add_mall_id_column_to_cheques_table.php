<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMallIdColumnToChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('cheques', function (Blueprint $table): void {
            $table->integer('mall_id')->unsigned()->index()->after('data');
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
            $table->dropColumn('mall_id');
        });
    }
}
