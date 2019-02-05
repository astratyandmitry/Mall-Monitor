<?php

namespace App\Console\Commands\Import;

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

                    while ($shifts = $this->integration->shiftHistory($cashboxNumber, $shiftsIndex * 50)) {
                        $shiftsIndex++;

                        foreach ($shifts as $shift) {
                            $this->info("Woring with Shift {$shift->ShiftNumber}");

                            $chequesIndex = 0;

                            $this->info("Getting Cheques for Cashbox {$cashboxNumber} Shift {$shift->ShiftNumber}");
                            while ($cheques = $this->integration->history($cashboxNumber, $shift->ShiftNumber, $chequesIndex * 50)) {
                                $chequesIndex++;

                                foreach ($cheques as $cheque) {
                                    $this->info("Working with Cheque {$cheque->Number}");

                                    ImportChequeWebKassa::dispatch($this->mall, $cheque, $cashbox->Xin);
                                }
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
