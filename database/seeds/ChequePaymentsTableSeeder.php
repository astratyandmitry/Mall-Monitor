<?php

use App\Models\ChequePayment;
use Illuminate\Database\Seeder;

class ChequePaymentsTableSeeder extends Seeder
{

    /**
     * @var array
     */
    protected $data = [
        [
            'name' => 'Наличными',
            'system_key' => 'cash',
        ],
        [
            'name' => 'Картой',
            'system_key' => 'card',
        ],
        [
            'name' => 'Кредит',
            'system_key' => 'credit',
        ],
        [
            'name' => 'Тара',
            'system_key' => 'tare',
        ],
        [
            'name' => 'Сдельный',
            'system_key' => 'piece-rate',
        ],
    ];


    /**
     * @return void
     */
    public function run(): void
    {
        ChequePayment::query()->truncate();

        foreach ($this->data as $data) {
            ChequePayment::create($data);
        }
    }

}
