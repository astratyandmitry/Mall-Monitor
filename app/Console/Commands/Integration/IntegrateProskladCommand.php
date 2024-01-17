<?php

namespace App\Console\Commands\Import;

use App\Integration\Mall\Prosklad;
use App\Jobs\ImportChequeProsklad;
use App\Models\Mall;
use App\Models\Cheque;
use Illuminate\Console\Command;
use App\Models\MallIntegrationSystem;

class IntegrateProskladCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'keruenmonitor:integrate-prosklad {--cashbox=}';

    /**
     * @var string
     */
    protected $description = 'Integrate with Prosklad';

    /**
     * @var \App\Integration\Mall\Prosklad
     */
    protected $integration;

    /**
     * @var \App\Models\Mall
     */
    protected $mall;

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): void
    {
        $_optionCashbox = $this->option('cashbox');

        $this->mall = Mall::query()->find(Mall::KERUEN_CITY);

        $this->integration = Prosklad::init(
            $this->mall->getIntegration(MallIntegrationSystem::PROSKLAD)
        );

        if ($this->integration->authorize()) {
            $cashboxes = collect($this->integration->getCashboxes())->keyBy('factory_number');

            if (! count($cashboxes)) {
                $this->error('There are no available Cashboxes');

                return;
            }

            do {
                $latestCheque = Cheque::query()
                    ->whereIn('kkm_code', $cashboxes->pluck('factory_number'))
                    ->latest('created_at')->first();

                [$cheques, $hasMore] = $this->integration->getCheques(
                    $latestCheque ? $latestCheque->id : 0
                );

                if (! count($cheques)) {
                    $this->info('There are no new Cheques');

                    break;
                }

                foreach ($cheques as $cheque) {
                    $this->info("Working with Cheque {$cheque->receipt_number}");

                    $cashbox = $cashboxes->get($cheque->factory_number);

                    if (! $cashbox) {
                        $this->error("Cashbox {$cheque->factory_number} not found");

                        continue;
                    }

                    ImportChequeProsklad::dispatch($this->mall, $cheque, $cashboxes);
                }
            } while ($hasMore);
        } else {
            $this->error('Unauthorized');
        }
    }
}
