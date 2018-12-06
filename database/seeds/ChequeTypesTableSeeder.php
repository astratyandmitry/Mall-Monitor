<?php

use App\Models\ChequeType;
use Illuminate\Database\Seeder;

class ChequeTypesTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'Продажа',
            'system_key' => 'sale',
        ],
        [
            'name' => 'Возврат продажи',
            'system_key' => 'sale_return',
        ],
    ];


    /**
     * @return void
     */
    public function run(): void
    {
        ChequeType::query()->truncate();

        foreach ($this->data as $data) {
            ChequeType::create($data);
        }
    }

}
