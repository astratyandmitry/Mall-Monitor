<?php

use App\Models\Visit;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMallIdColumnToVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table): void {
            $table->unsignedInteger('mall_id')->after('id')->index();
        });

        /** @var \App\Models\Visit[] $visits */
        $visits = Visit::query()->get();

        foreach ($visits as $visit) {
            $visit->update(['mall_id' => $visit->countmax->mall_id]);
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
            $table->dropColumn('mall_id');
        });
    }
}
