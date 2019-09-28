<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitCountmaxesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('visit_countmax', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('mall_id')->index();
            $table->unsignedInteger('store_id')->index()->nullable();
            $table->string('number', 80);
            $table->softDeletes();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_countmax');
    }

}
