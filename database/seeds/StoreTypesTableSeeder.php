<?php

use App\Models\StoreType;
use Illuminate\Database\Seeder;

class StoreTypesTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'Прочее',
        ],
        [
            'name' => 'Супермаркеты',
        ],
        [
            'name' => 'Развлечения',
        ],
    ];


    /**
     * @return void
     */
    public function run(): void
    {
        StoreType::query()->truncate();

        foreach ($this->data as $data) {
            StoreType::create($data);
        }
    }

}
