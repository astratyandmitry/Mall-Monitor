<?php

use Illuminate\Database\Seeder;
use App\Models\StoreIntegrationType;

class StoreIntegrationTypesTableSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'XML',
            'system_key' => 'xml',
        ],
        [
            'name' => 'Excel',
            'system_key' => 'excel',
        ],
    ];

    /**
     * @return void
     */
    public function run(): void
    {
        StoreIntegrationType::query()->truncate();

        foreach ($this->data as $data) {
            StoreIntegrationType::query()->create($data);
        }
    }
}
