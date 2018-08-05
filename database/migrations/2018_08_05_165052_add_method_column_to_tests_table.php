<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMethodColumnToTestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('tests', function (Blueprint $table): void {
            $table->enum('method', ['GET', 'POST'])->after('body');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table): void {
            $table->dropColumn('method');
        });
    }

}
