<?php

namespace App\Http\Controllers\API;

use App\Models\Cheque;
use App\Models\StoreIntegrationLog;
use App\Models\StoreIntegrationType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Integration\Store\XMLChequeTransformer;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ChequesImportXMLController extends Controller
{

    /**
     * @var array
     */
    protected $rules = [
        'file' => 'required|file|mimetypes:text/xml,application/xml',
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

        $output = [
            'error' => [],
            'skip' => [],
            'success' => [],
        ];

        $items = simplexml_load_file($request->file('file')->getRealPath());

        if (count($items)) {
            foreach ($items as $item) {
                $transformer = (new XMLChequeTransformer($item));
                $attributes = $transformer->onlyRequired();

                /** @var \Illuminate\Validation\Validator $validator */
                $validator = $this->getValidationFactory()->make($attributes, [
                    'code' => 'required|max:200',
                    'number' => 'required|max:200',
                    'amount' => 'required|numeric',
                    'created_at' => 'required',
                ]);

                if ($validator->fails()) {
                    $output['error'][] = [
                        'data' => $attributes,
                        'validation' => $validator->errors(),
                    ];
                } else {
                    if (Cheque::uniqueAttrs($attributes)->first()) {
                        $output['skip'][] = [
                            'data' => $attributes,
                        ];
                    } else {
                        /** @var \App\Models\Cheque $cheque */
                        $cheque = Cheque::query()->create($transformer->toAttributes());

                        $output['success'][] = [
                            'data' => $cheque->toArray(),
                        ];
                    }
                }
            }
        }

        $log = StoreIntegrationLog::store(StoreIntegrationType::XML, auth('api')->user()->store, $output);

        return $this->response($output);
    }

}
