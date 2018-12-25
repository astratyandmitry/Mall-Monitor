<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessIdentificationNumberColumnToStoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table): void {
            $table->string('business_identification_number', 12);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table): void {
            $table->dropColumn('business_identification_number');
        });
    }

}
