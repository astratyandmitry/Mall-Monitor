<?php

namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Models\IntegrationLog;
use App\Models\MallIntegration;

class WebKassa
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var mixed
     */
    protected $data;

    /**
     *
     * @var Singleton
     */
    private static $instance;

    /**
     * @var \App\Models\MallIntegration
     */
    protected $integration;


    /**
     * WebKassa constructor.
     *
     * @param \App\Models\MallIntegration $integration
     */
    public function __construct(MallIntegration $integration)
    {
        $this->integration = $integration;

        $this->client = new Client([
            'base_uri' => $this->integration->host,
        ]);
    }


    /**
     * @param \App\Models\MallIntegration $integration
     *
     * @return \App\Integration\WebKassa
     */
    public static function init(MallIntegration $integration): WebKassa
    {
        if (is_null(self::$instance)) {
            self::$instance = new WebKassa($integration);
        }

        return self::$instance;
    }


    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $params = [
            'Login' => $this->integration->username,
            'Password' => $this->integration->password,
        ];

        $request = new Request('POST', '/api/Authorize', [
            'Content-Type' => 'application/json',
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if ( ! is_null($response->Errors)) {
            $this->log('Authorize', $response->Errors[0]->Code, $response->Errors[0]->Text, $params);

            return false;
        }

        $this->log('Authorize', 0, null, [
            'token' => $this->token = $response->Data->Token,
        ]);

        return true;
    }


    /**
     * @return bool
     */
    public function provoidData(): bool
    {
        $params = [
            'Token' => $this->token,
        ];

        $request = new Request('POST', '/api/cashboxes/availableForReadHistory', [
            'Content-Type' => 'application/json',
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        dd($response);

        if ( ! is_null($response->Errors)) {
            $this->log('Authorize', $response->Errors[0]->Code, $response->Errors[0]->Text, $params);

            return false;
        }

        if (! is_null($response->Errors)) {
            $this->log('Authorize', $response->Errors[0]->Code, $response->Errors[0]->Text, $params);

            return false;
        }

            $packet = $response->ProvideDataResult->ResultObject->enc_value->Packet;

            $data['packetGuid'] = $this->packageGUID = $packet->Guid;

            $this->data = (is_array($packet->Content->Operations->BaseOperation)) ? $packet->Content->Operations->BaseOperation : $packet->Content->Operations;

        $this->log('ProvideData', $response->ProvideDataResult, $data);

        return true;
    }


    /**
     * @return bool
     */
    public function confirmData(): bool
    {
        $success = false;

        $params = [
            'token' => $this->token,
            'packetGuid' => $this->packageGUID,
        ];

        $response = $this->client->ConfirmData($params);

        if ($response->ConfirmDataResult->Code == '000') {
            $success = true;
        }

        $this->log('ConfirmData', $response->ConfirmDataResult, $params);

        return $success;
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * @param string $operation
     * @param int    $code
     * @param string $message
     * @param array  $data
     *
     * @return void
     */
    protected function log(string $operation, int $code = 0, ?string $message = null, array $data = []): void
    {
        IntegrationLog::store(
            $this->integration->system_id, $this->integration->mall_id, $operation, $code, $message, $data
        );
    }

}
