<?php

use App\Models\City;
use App\Models\Mall;
use Illuminate\Database\Seeder;

class MallsTableSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'Keruen City',
            'city_id' => City::ASTANA,
        ],
    ];

    /**
     * @return void
     */
    public function run(): void
    {
        Mall::query()->truncate();

        foreach ($this->data as $data) {
            Mall::create($data);
        }
    }
}
