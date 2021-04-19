<?php

namespace App\Console\Commands\Import;

use App\Models\Cheque;
use App\Models\Mall;
use Illuminate\Console\Command;
use App\Integration\Mall\WebKassa;
use App\Jobs\ImportChequeWebKassa;
use App\Models\MallIntegrationSystem;

class IntegrateWebKassaCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'keruenmonitor:integrate-webkassa';

    /**
     * @var string
     */
    protected $description = 'Integrate with WebKassa';

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
        $this->mall = Mall::find(Mall::KERUEN_CITY);

        $this->integration = WebKassa::init(
            $this->mall->getIntegration(MallIntegrationSystem::WEBKASSA)
        );

        if ($this->integration->authorize()) {
            if ($cashboxes = $this->integration->availableForReadHistory()) {
                foreach ($cashboxes as $cashbox) {
                    $cashboxNumber = $cashbox->CashboxUniqueNumber;

                    $this->info("Woring with Cashbox {$cashboxNumber}");

                    /** @var \App\Models\Cheque $latestCheque */
                    $latestCheque = Cheque::query()->where('kkm_code', $cashboxNumber)->latest('created_at')->first();

                    $dateFrom = ($latestCheque) ? $latestCheque->created_at : '01.01.2000 00:00:00';
                    $skipShifts = 0;

                    while ($shifts = $this->integration->shiftHistory($cashboxNumber, $dateFrom, $skipShifts)) {
                        foreach ($shifts as $shift) {
                            $this->info("Woring with Shift {$shift->ShiftNumber}");

                            $this->info("Getting Cheques for Cashbox {$cashboxNumber} Shift {$shift->ShiftNumber}");

                            $skipCheques = Cheque::where('kkm_code', $cashboxNumber)->where('shift_number', $shift->ShiftNumber)->count();
                            $skipCheques = $skipShifts > 0 ? $skipCheques - 1 : 0;

                            while ($cheques = $this->integration->history($cashboxNumber, $shift->ShiftNumber, $skipCheques)) {
                                foreach ($cheques as $cheque) {
                                    $this->info("Working with Cheque {$cheque->Number}");

                                    ImportChequeWebKassa::dispatch($this->mall, $cheque, $cashbox);
                                }

                                $skipCheques += count($cheques);
                            }
                        }

                        $skipShifts += count($shifts);
                    }
                }
            } else {
                $this->error('There are no available files for import');
            }
        } else {
            $this->error('Unauthorized');
        }
    }
}
