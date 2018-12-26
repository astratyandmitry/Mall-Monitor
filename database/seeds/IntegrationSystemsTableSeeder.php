<?php

use App\Models\IntegrationSystem;
use Illuminate\Database\Seeder;

class IntegrationSystemsTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'ПроСистемы',
            'system_key' => 'prosystems',
        ],
        [
            'name' => 'WebKassa',
            'system_key' => 'webkassa',
        ],
    ];


    /**
     * @return void
     */
    public function run(): void
    {
        IntegrationSystem::query()->truncate();

        foreach ($this->data as $data) {
            IntegrationSystem::create($data);
        }
    }

}
