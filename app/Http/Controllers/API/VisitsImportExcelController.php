<?php

namespace App\Http\Controllers\API;

use App\Models\Cheque;
use App\Models\Visit;
use App\Models\VisitCountmax;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\StoreIntegrationLog;
use App\Models\StoreIntegrationType;
use Maatwebsite\Excel\Facades\Excel;
use App\Integration\Store\ExcelChequeTransformer;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class VisitsImportExcelController extends Controller
{

    /**
     * @var array
     */
    protected $rules = [
        'file' => 'required|file',
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
            'success' => [],
        ];

        Excel::load($request->file('file')->getRealPath(), function ($reader) use (&$output) {
            $reader->setHeaderRow(2);

            $items = $reader->get()->toArray();

            if ( ! isset($items[0]) || ! count($items[0])) {
                return $this->responseError();
            }

            $indexToKey = ['date', 'empty', 'time', 'number', 'name', 'count'];

            foreach ($items as $item) {
                $formattedItem = [];
                $iteration = 0;

                foreach ($item as $value) {
                    $formattedItem[$indexToKey[$iteration]] = $value;

                    if ($iteration == 5) {
                        break;
                    }

                    $iteration++;
                }

                /** @var \Illuminate\Validation\Validator $validator */
                $validator = $this->getValidationFactory()->make($formattedItem, [
                    'date' => 'required|regex:/^(\d{2})\.(\d{2})\.(\d{4})$/i',
                    'time' => 'required|regex:/^(\d{1,2})\:(\d{2})$/i',
                    'number' => 'required|numeric',
                    'count' => 'required|numeric|min:1',
                ]);

                if ($validator->fails()) {
                    $output['error'][] = [
                        'data' => $formattedItem,
                        'validation' => $validator->errors(),
                    ];
                } else {
                    /** @var \App\Models\VisitCountmax $countmax */
                    if ( ! $countmax = VisitCountmax::query()->where('number', $formattedItem['number'])->first()) {
                        $countmax = VisitCountmax::query()->create([
                            'mall_id' => \request()->get('mall_id', 1),
                            'store_id' => -1,
                            'number' => $formattedItem['number'],
                        ]);
                    }

                    /** @var \App\Models\Visit $visit */
                    $visit = Visit::query()->create([
                        'mall_id' => $countmax->mall_id,
                        'store_id' => $countmax->store_id,
                        'fixed_at' => $this->formatDate("{$formattedItem['date']} {$formattedItem['time']}"),
                        'countmax_id' => $countmax->id,
                        'count' => $formattedItem['count'],
                    ]);

                    $output['success'][] = [
                        'data' => $visit->toArray(),
                    ];
                }
            }
        });

        return $this->response($output);
    }


    /**
     * @param string $date
     *
     * @return string
     */
    protected function formatDate(string $date): string
    {
        list($date, $time) = explode(' ', $date);

        list($hours, $minutes) = explode(':', $time);
        list($day, $month, $year) = explode('.', $date);

        if ($hours < 10) {
            $hours = "0{$hours}";
        }

        return date('Y-m-d H:i:s', strtotime("{$year}-{$month}-{$day} {$hours}:{$minutes}"));
    }

}
