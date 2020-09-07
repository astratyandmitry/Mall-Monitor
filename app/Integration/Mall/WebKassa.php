<?php

namespace App\Integration\Mall;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Models\MallIntegration;
use App\Models\MallIntegrationLog;

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
     * @var \App\Integration\Mall\WebKassa
     */
    private static $instance;

    /**
     * @var \App\Models\MallIntegration
     */
    protected $integration;

    /**
     * @var array
     */
    protected $cashboxes = [];

    /**
     * @var array
     */
    protected $shifts = [];

    /**
     * @var array
     */
    protected $cheques = [];

    /**
     * @param \App\Models\MallIntegration $integration
     *
     * @return void
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
     * @return \App\Integration\Mall\WebKassa
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
     * @throws \GuzzleHttp\Exception\GuzzleException
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

        if (isset($response->Errors) && count($response->Errors)) {
            $this->log('Authorize', $response->Errors[0]->Code, $response->Errors[0]->Text, $params);

            return false;
        }

        $this->log('Authorize', 0, null, [
            'token' => $this->token = $response->Data->Token,
        ]);

        return true;
    }

    /**
     * @return array
     */
    public function availableForReadHistory(): array
    {
        $params = [
            'Token' => $this->token,
        ];

        $request = new Request('POST', '/api/cashboxes/availableForReadHistory', [
            'Content-Type' => 'application/json',
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (isset($response->Errors) && count($response->Errors)) {
            $this->log('availableForReadHistory', $response->Errors[0]->Code, $response->Errors[0]->Text, $params);

            return [];
        }

        $this->log('availableForReadHistory', 0, null, $params);

        return $response->Data;
    }

    /**
     * @param string $cashboxNumber
     * @param string $dateFrom
     * @param int $skip
     * @param int $take
     *
     * @return array
     */
    public function shiftHistory(string $cashboxNumber, string $dateFrom, int $skip = 0, int $take = 50): array
    {
        $params = [
            'Token' => $this->token,
            'CashboxUniqueNumber' => $cashboxNumber,
            'FromDate' => date('d.m.Y H:i:s', strtotime($dateFrom)),
            'ToDate' => date('d.m.Y H:i:s'),
            'Skip' => $skip,
            'Take' => $take,
        ];

        $request = new Request('POST', '/api/Shift/ExternalHistory', [
            'Content-Type' => 'application/json',
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (isset($response->Errors) && count($response->Errors)) {
            $this->log('ShiftHistory', $response->Errors[0]->Code, $response->Errors[0]->Text, $params);

            return [];
        }

        $this->log('ShiftHistory', 0, null, $params);

        if (! count($response->Data->Shifts)) {
            return [];
        }

        return $response->Data->Shifts;
    }

    /**
     * @param string $cashboxNumber
     * @param string $shiftNumber
     * @param int $skip
     * @param int $take
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function history(string $cashboxNumber, string $shiftNumber, int $skip = 0, int $take = 50): array
    {
        $params = [
            'Token' => $this->token,
            'CashboxUniqueNumber' => $cashboxNumber,
            'ShiftNumber' => $shiftNumber,
            'Skip' => $skip,
            'Take' => $take,
        ];

        $request = new Request('POST', '/api/Ticket/ExternalHistory', [
            'Content-Type' => 'application/json',
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (isset($response->Errors) && count($response->Errors)) {
            $this->log('History', $response->Errors[0]->Code, $response->Errors[0]->Text, $params);

            return [];
        }

        $this->log('History', 0, null, $params);

        if (! count($response->Data->Items)) {
            return [];
        }

        return $response->Data->Items;
    }

    /**
     * @param string $operation
     * @param int $code
     * @param string $message
     * @param array $data
     *
     * @return void
     */
    protected function log(string $operation, int $code = 0, ?string $message = null, array $data = []): void
    {
        MallIntegrationLog::store(
            $this->integration->system_id, $this->integration->mall_id, $operation, $code, $message, $data
        );
    }
}
