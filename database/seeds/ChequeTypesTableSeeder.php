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
        [
            'name' => 'Покупка',
            'system_key' => 'buy',
        ],
        [
            'name' => 'Возврат покупки',
            'system_key' => 'buy_return',
        ],
        [
            'name' => 'Депозит',
            'system_key' => 'deposit',
        ],
        [
            'name' => 'Выплата',
            'system_key' => 'withdrawal',
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
