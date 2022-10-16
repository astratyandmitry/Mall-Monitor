<?php

use App\Models\Cashbox;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsColumnsToCashboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('cashboxes', function (Blueprint $table): void {
            $table->timestamps();
        });

        Cashbox::query()->update([
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 minutes')),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('cashboxes', function (Blueprint $table): void {
            $table->dropTimestamps();
        });
    }
}
