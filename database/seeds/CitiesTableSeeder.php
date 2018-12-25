<?php

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'Астана',
            'country_id' => Country::KAZAKHSTAN,
        ],
    ];


    /**
     * @return void
     */
    public function run(): void
    {
        City::query()->truncate();

        foreach ($this->data as $data) {
            City::create($data);
        }
    }

}
