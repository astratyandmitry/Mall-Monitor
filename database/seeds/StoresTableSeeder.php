<?php

use App\Models\Mall;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoresTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'Galmart',
            'business_identification_number' => '100840010829',
            'mall_id' => Mall::KERUEN_CITY,
        ],
    ];


    /**
     * @return void
     */
    public function run(): void
    {
        Store::query()->truncate();

        foreach ($this->data as $data) {
            Store::create($data);
        }
    }

}
