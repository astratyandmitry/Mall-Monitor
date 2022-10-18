<?php

namespace App\Console\Commands\Import;

use App\Models\Mall;
use App\Models\Cheque;
use Illuminate\Console\Command;
use App\Integration\Mall\Trinity;
use App\Jobs\ImportChequeTrinity;
use App\Models\MallIntegrationSystem;

class IntegrateTrinityCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'keruenmonitor:integrate-trinity {--cashbox=}';

    /**
     * @var string
     */
    protected $description = 'Integrate with Trinity';

    /**
     * @var \App\Integration\Mall\Prosystems
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

        $this->integration = Trinity::init(
            $this->mall->getIntegration(MallIntegrationSystem::TRINITY)
        );

        if ($this->integration->authorize()) {
            if ($cashboxes = $this->integration->availableForReadHistory()) {
                foreach ($cashboxes as $cashbox) {
                    if ($_optionCashbox && $cashbox->cashboxUniqueNumber != $_optionCashbox) {
                        continue;
                    }

                    $cashboxNumber = $cashbox->cashboxUniqueNumber;

                    $this->info("Working with Cashbox {$cashboxNumber}");

                    $this->info('— '.date('H:i:s').' START CALCULATING LAST CHEQUE...');

                    /** @var \App\Models\Cheque $latestCheque */
                    $latestCheque = Cheque::query()->where('kkm_code', $cashboxNumber)->latest('created_at')->first();

                    $dateFrom = ($latestCheque) ? $latestCheque->created_at : '01.01.2000 00:00:00';
                    $skipShifts = 0;

                    $this->info("— ".date('H:i:s')."  END CALCULATING LAST CHEQUE ($dateFrom)");

                    while ($shifts = $this->integration->shiftHistory($cashboxNumber, $dateFrom, $skipShifts)) {
                        foreach ($shifts as $shift) {
                            $this->info("Working with Shift {$shift->shiftNumber}");

                            $this->info("Getting Cheques for Cashbox {$cashboxNumber} Shift {$shift->shiftNumber}");

                            $this->info('— '.date('H:i:s').' START CALCULATING LAST SHIFT...');

                            $skipCheques = Cheque::query()->where('kkm_code', $cashboxNumber)->where('shift_number', $shift->shiftNumber)->count();

                            $this->info("— ".date('H:i:s')."  END CALCULATING LAST SHIFT ($skipCheques)");

                            while ($cheques = $this->integration->history($cashboxNumber, $shift->shiftNumber, $skipCheques)) {
                                foreach ($cheques as $cheque) {
                                    $this->info("Working with Cheque {$cheque->number}");

                                    ImportChequeTrinity::dispatch($this->mall, $cheque, $cashbox);
                                }

                                $skipCheques += count($cheques);
                            }
                        }

                        $skipShifts += count($shifts);
                    }
                }
            } else {
                $this->error('There are no available Cashboxes');
            }
        } else {
            $this->error('Unauthorized');
        }
    }
}
