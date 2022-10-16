<?php

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'Казахстан',
        ],
    ];

    /**
     * @return void
     */
    public function run(): void
    {
        Country::query()->truncate();

        foreach ($this->data as $data) {
            Country::create($data);
        }
    }
}
