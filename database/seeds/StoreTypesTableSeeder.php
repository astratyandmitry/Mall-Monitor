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
            'color' => 'cccccc',
        ],
        [
            'name' => 'Супермаркеты',
            'color' => '24c971',
        ],
        [
            'name' => 'Развлечения',
            'color' => '7b23c9',
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
