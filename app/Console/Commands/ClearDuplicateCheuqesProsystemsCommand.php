<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearDuplicateCheuqesProsystemsCommand extends Command
{

    /**
     * @var string
     */

    protected $signature = 'keruenmonitor:clear-duplicate-cheques-prosystems {--limit=1000}';

    /**
     * @var string
     */
    protected $description = 'Clear duplicate Cheques from Prosystems';


    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info("Started: " . date('Y-m-d H:i:s'));

        $codes = \DB::table('cheques')
            ->select(\DB::raw('count(id) as `count`, `code`'))
            ->groupBy('code')
            ->having('count', '>', 1)
            ->limit((int) $this->option('limit'))
            ->pluck('code')
            ->toArray();

        $this->info("Loaded: " . date('Y-m-d H:i:s'));

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

            $this->info("Finished: " . date('Y-m-d H:i:s'));
        } else {
            $this->error('No available cheques.');
        }
    }

}
