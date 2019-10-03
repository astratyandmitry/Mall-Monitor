<?php

use App\Models\Visit;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreIdColumnToVisitsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table): void {
            $table->integer('store_id')->after('mall_id')->nullable()->index();
        });

        /** @var \App\Models\Visit[] $visits */
        $visits = Visit::query()->get();

        foreach ($visits as $visit) {
            $visit->update(['store_id' => $visit->countmax->store_id]);
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table): void {
            $table->dropColumn('store_id');
        });
    }

}
