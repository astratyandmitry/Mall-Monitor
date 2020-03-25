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

        $items = \DB::table('cheques')
            ->select(\DB::raw('count(id) as `count`, `code`, `created_at`'))
            ->groupBy('code')
            ->groupBy('created_at')
            ->having('count', '>', 1)
            ->limit((int)$this->option('limit'))
            ->get()->toArray();

        $this->info("Loaded: " . date('Y-m-d H:i:s'));

        if (count($items)) {
            foreach ($items as $item) {
                $this->info("Working with {$item['code']}");

                $cheque = \App\Models\Cheque::where('code', $item['code'])->oldest('id')->first();
                $ids = \DB::table('cheques')
                    ->select('id')
                    ->where('code', $item['code'])
                    ->where('created_at', $item['created_at'])
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
