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
