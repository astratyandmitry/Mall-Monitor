<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ChequesImportExcelController extends Controller
{

    /**
     * @var array
     */
    protected $rules = [
        //
    ];

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {

        if ( ! $this->validate($request, $this->rules)) {
            return $this->responseError();
        }

        return $this->response([
            'class' => self::class,
        ]);
    }

}
