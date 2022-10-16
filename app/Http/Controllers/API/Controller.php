<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\JsonResponse;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class Controller extends \App\Http\Controllers\Controller
{
    const CODE_ERROR_PARAMETERS = 991;

    const CODE_ERROR_VALIDATION = 992;

    const CODE_ERROR_UNNAMED = 999;

    /**
     * @var array
     */
    protected $errorCode = self::CODE_ERROR_UNNAMED;

    /**
     * @var array
     */
    protected $errorData = [];

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return bool
     */
    public function validate(
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ): bool {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, []);

        if ($validator->fails()) {
            $this->errorCode = self::CODE_ERROR_VALIDATION;
            $this->errorData = $validator->errors()->getMessages();

            return false;
        }

        return true;
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response(array $data = []): JsonResponse
    {
        return $this->responseSuccess($data);
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSuccess(array $data = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * @param null|int $code
     * @param null|string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseError(?int $code = null, ?string $message = null): JsonResponse
    {
        $error = [
            'code' => (! is_null($code)) ? $code : $this->errorCode,
        ];

        if (count($this->errorData)) {
            $error['data'] = $this->errorData;
        }

        if (! is_null($message)) {
            $error['message'] = $message;
        }

        return response()->json([
            'success' => false,
            'error' => $error,
        ]);
    }

    /**
     * @param \Illuminate\Http\UploadedFile $fileInstance
     *
     * @return string
     */
    protected function getUploadedFile(UploadedFile $fileInstance): string
    {
        $filename = implode('_', [time(), str_random(16)]);
        $filename = "{$filename}.{$fileInstance->getClientOriginalExtension()}";

        $fileInstance->move(storage_path("app/public/files"), $filename);

        return "/storage/files/{$filename}";
    }
}
