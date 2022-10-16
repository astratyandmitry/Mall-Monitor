<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCashboxIdColumnFromChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('cheques', function (Blueprint $table): void {
            $table->dropColumn('cashbox_id');
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
            $table->integer('cashbox_id')->unsigned()->index()->after('data');
        });
    }
}
