<?php

namespace App\Http\Controllers\API;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ChecuesController extends Controller
{

    public function test_post()
    {
        $data = $this->request->all();

        \DB::table('tests')->insert([
            'body' => json_encode($data),
            'method' => 'POST',
        ]);

        return $data;
    }


    public function test_get()
    {
        $data = $this->request->all();

        \DB::table('tests')->insert([
            'body' => json_encode($data),
            'method' => 'GET',
        ]);

        return $data;
    }

}
