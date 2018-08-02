<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2017, ArmenianBros. <i@armenianbros.com>
 */
class Controller extends \Illuminate\Routing\Controller
{

    use \Illuminate\Foundation\Bus\DispatchesJobs,
        \Illuminate\Foundation\Validation\ValidatesRequests,
        \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    /**
     * @var Request
     */
    protected $request;


    /**
     * @param Request $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * @param array $rules
     *
     * @return bool
     */
    protected function perfomeValidation(array $rules = []): bool
    {
        if ( ! $rules) {
            return $this->validate($this->request, $this->model->getRules());
        }

        return $this->validate($this->request, $rules);
    }


    /**
     * @param Request $request
     * @param array   $rules
     * @param array   $messages
     * @param array   $customAttributes
     *
     * @return bool
     */
    public function validate(
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ): bool {
        $rules = $this->uniqueRules($rules);
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, []);

        if ($validator->fails()) {
            $this->response->setCode(self::CODE_VALIDATION_ERRORS);
            $this->response->setData($validator->errors()->getMessages());

            return false;
        }

        return true;
    }


    /**
     * @param array $rules
     *
     * @return array
     */
    protected function uniqueRules(array $rules): array
    {
        if (isset($rules['_unique'])) {
            $requestIsPut = $this->request->server()['REQUEST_METHOD'] == 'PUT';

            $uniqueAttributes = explode(',', $rules['_unique']);
            unset($rules['_unique']);

            $uniqueRule = "unique:{$this->model->getTable()}";
            $uniqueId = ($requestIsPut) ? $this->request->route()->parameters()['id'] : null;

            foreach ($uniqueAttributes as $attribute) {
                $iUniqueRule = $uniqueRule;

                if ($requestIsPut) {
                    $iUniqueRule .= ",{$attribute},{$uniqueId}";
                }

                if (isset($rules[$attribute])) {
                    $rules[$attribute] .= '|' . $iUniqueRule;
                } else {
                    $rules[$attribute] = $iUniqueRule;
                }
            }
        }

        return $rules;
    }

}
