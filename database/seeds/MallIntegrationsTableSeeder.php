<?php

use App\Models\Mall;
use App\Models\MallIntegration;
use Illuminate\Database\Seeder;
use App\Models\IntegrationSystem;

class MallIntegrationsTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'system_id' => IntegrationSystem::PROSYSTEMS,
            'mall_id' => Mall::KERUEN_CITY,
            'host' => 'https://88.204.142.178:8014/FSCDataProvider/KERUENBONUS/STREAMING/INSTANCE-A.asmx?wsdl',
            'username' => 'keruen',
            'password' => 'h^M1IpW3ovxq1$5I',
            'data' => null,
        ],
    ];


    /**
     * @return void
     */
    public function run(): void
    {
        MallIntegration::query()->truncate();

        foreach ($this->data as $data) {
            MallIntegration::create($data);
        }
    }

}
