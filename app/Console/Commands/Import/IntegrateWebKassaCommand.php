<?php

namespace App\Console\Commands\Import;

use App\Models\Cheque;
use App\Models\Mall;
use App\Integration\WebKassa;
use Illuminate\Console\Command;
use App\Models\IntegrationSystem;
use App\Jobs\ImportChequeWebKassa;

class IntegrateWebKassaCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'mallmonitor:integrate-webkassa';

    /**
     * @var string
     */
    protected $description = 'Integrate with WebKassa';

    /**
     * @var \App\Integration\Prosystems
     */
    protected $integration;

    /**
     * @var \App\Models\Mall
     */
    protected $mall;


    /**
     * IntegrateWebKassaCommand constructor.
     */
    public function __construct()
    {
        $this->mall = Mall::find(Mall::KERUEN_CITY);

        $this->integration = WebKassa::init(
            $this->mall->getIntegration(IntegrationSystem::WEBKASSA)
        );

        parent::__construct();
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        if ($this->integration->authorize()) {
            if ($cashboxes = $this->integration->availableForReadHistory()) {
                foreach ($cashboxes as $cashbox) {
                    $cashboxNumber = $cashbox->CashboxUniqueNumber;
                    $shiftsIndex = 0;

                    $this->info("Woring with Cashbox {$cashboxNumber}");

                    while ($shifts = $this->integration->shiftHistory($cashboxNumber, $shiftsIndex * 100)) {
                        $shiftsIndex++;

                        foreach ($shifts as $shift) {
                            $this->info("Woring with Shift {$shift->ShiftNumber}");

                            $this->info("Getting Cheques for Cashbox {$cashboxNumber} Shift {$shift->ShiftNumber}");

                            $skipCheques = Cheque::where('kkm_code', $cashboxNumber)->where('shift_number', $shift->ShiftNumber)->count();

                            while ($cheques = $this->integration->history($cashboxNumber, $shift->ShiftNumber, $skipCheques)) {
                                foreach ($cheques as $cheque) {
                                    $this->info("Working with Cheque {$cheque->Number}");

                                    ImportChequeWebKassa::dispatch($this->mall, $cheque, $cashbox->Xin);
                                }

                                $skipCheques += count($cheques);
                            }
                        }
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
