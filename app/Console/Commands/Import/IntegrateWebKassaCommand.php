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
        dd($this->integration->authorize());

        exit;
        if ($this->integration->authorize()) {
            if ($this->integration->provoidData()) {
                foreach ($this->integration->getData() as $item) {
                    $this->info("Adding {$item->UniqueId}");

                    ImportChequeWebKassa::dispatch($this->mall, $item);
                }

                $this->wsdl->confirmData();
            } else {
                $this->error('There are no available files for import');
            }
        } else {
            $this->error('Unauthorized');
        }
    }

}
