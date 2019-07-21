<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearDuplicateCheuqesProsystemsCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'keruenmonitor:clear-duplicate-cheques-prosystems';

    /**
     * @var string
     */
    protected $description = 'Clear duplicate Cheuqes from Prosystems';


    /**
     * @return void
     */
    public function handle(): void
    {
        $codes = \DB::table('cheques')
            ->select(\DB::raw('count(id) as `count`, `code`'))
            ->groupBy('code')
            ->having('count', '>', 1)
            ->pluck('code')->toArray();

        if (count($codes)) {
            foreach ($codes as $code) {
                $this->info("Working with {$code}");

                $cheque = \App\Models\Cheque::where('code', $code)->oldest('id')->first();
                $ids = \DB::table('cheques')
                    ->select('id')
                    ->where('code', $code)
                    ->pluck('id', 'id')->toArray();

                unset($ids[$cheque->id]);

                \DB::table('cheques')->whereIn('id', $ids)->delete();
                \DB::table('cheque_items')->whereIn('cheque_id', $ids)->delete();
            }
        } else {
            $this->error('No available cheques.');
        }
    }

}
