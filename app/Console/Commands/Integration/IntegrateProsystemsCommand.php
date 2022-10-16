<?php

namespace App\Console\Commands\Integration;

use App\Models\Mall;
use Illuminate\Console\Command;
use App\Integration\Mall\Prosystems;
use App\Models\MallIntegrationSystem;
use App\Jobs\ImportChequeProsystem;

class IntegrateProsystemsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'keruenmonitor:integrate-prosystems';

    /**
     * @var string
     */
    protected $description = 'Integrate with Prosystems';

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
     * @throws \SoapFault
     */
    public function handle(): void
    {
        $this->mall = Mall::find(Mall::KERUEN_CITY);

        $this->integration = Prosystems::init(
            $this->mall->getIntegration(MallIntegrationSystem::PROSYSTEMS)
        );

        if ($this->integration->authorize()) {
            if ($this->integration->provoidData()) {
                foreach ($this->integration->getData() as $item) {
                    $this->info("Adding {$item->UniqueId}");

                    ImportChequeProsystem::dispatch($this->mall, $item);
                }

                if ($this->integration->authorize()) {
                    $this->integration->confirmData();
                } else {
                    $this->error('Unauthorized');
                }
            } else {
                $this->error('There are no available files for import');
            }
        } else {
            $this->error('Unauthorized');
        }
    }
}
