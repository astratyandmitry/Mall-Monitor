<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatesColumnsToVisitsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table): void {
            $table->string('created_year', 4)->nullable()->after('created_at');
            $table->string('created_yearmonth', 7)->nullable()->after('created_at');
            $table->string('created_date', 10)->nullable()->after('created_at');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table): void {
            $table->dropColumn('created_date');
            $table->dropColumn('created_year');
            $table->dropColumn('created_yearmonth');
        });
    }

}
