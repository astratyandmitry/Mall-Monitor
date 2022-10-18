<?php

namespace App\Integration\Mall;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Models\MallIntegration;
use App\Models\MallIntegrationLog;

class Trinity
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
     * @var \App\Integration\Mall\Trinity
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
     * @return \App\Integration\Mall\Trinity
     */
    public static function init(MallIntegration $integration): Trinity
    {
        if (is_null(self::$instance)) {
            self::$instance = new Trinity($integration);
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

        $request = new Request('POST', '/api/External/Authenticate', [
            'Content-Type' => 'application/json',
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (isset($response->error)) {
            $this->log('Authenticate', $response->error->cody, $response->error->text, $params);

            return false;
        }

        if (! isset($response->data) || ! isset($response->data->accessToken)) {
            $this->log('Authenticate', -1, 'Can not authenticate', $params);

            return false;
        }

        $this->log('Authenticate', 0, null, [
            'token' => $this->token = $response->data->accessToken,
        ]);

        return true;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function availableForReadHistory(): array
    {
        $request = new Request('POST', '/api/External/ExportCashboxes', [
            'Content-Type' => 'application/json',
            'authorization' => "bearer {$this->token}",
        ]);

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (isset($response->error)) {
            $this->log('ExportCashboxes', $response->error->cody, $response->error->text);

            return false;
        }

        $this->log('ExportCashboxes');

        return $response->data;
    }

    /**
     * @param string $cashboxNumber
     * @param string $dateFrom
     * @param int $skip
     * @param int $take
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function shiftHistory(string $cashboxNumber, string $dateFrom, int $skip = 0, int $take = 50): array
    {
        $params = [
            'CashboxUniqueNumber' => $cashboxNumber,
            'FromDate' => date('d.m.Y H:i:s', strtotime($dateFrom)),
            'ToDate' => date('d.m.Y H:i:s'),
            'Skip' => $skip,
            'Take' => $take,
        ];

        $request = new Request('POST', '/api/External/ExportShifts', [
            'Content-Type' => 'application/json',
            'authorization' => "bearer {$this->token}",
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (isset($response->error)) {
            $this->log('ExportShifts', $response->error->cody, $response->error->text, $params);

            return false;
        }

        $this->log('ShiftHistory', 0, null, $params);

        return $response->data->shifts ?? [];
    }

    /**
     * @param string $cashboxNumber
     * @param string $shiftNumber
     * @param int $skip
     * @param int $take
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function history(string $cashboxNumber, string $shiftNumber, int $skip = 0, int $take = 50): array
    {
        $params = [
            'CashboxUniqueNumber' => $cashboxNumber,
            'ShiftNumber' => $shiftNumber,
            'Skip' => $skip,
            'Take' => $take,
        ];

        $request = new Request('POST', '/api/External/ExportTickets', [
            'Content-Type' => 'application/json',
            'authorization' => "bearer {$this->token}",
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (isset($response->error)) {
            $this->log('ExportTickets', $response->error->cody, $response->error->text, $params);

            return false;
        }

        $this->log('ExportTickets', 0, null, $params);

        return $response->data->items;
    }

    /**
     * @param string $operation
     * @param int $code
     * @param string|null $message
     * @param array $data
     * @return void
     */
    protected function log(string $operation, int $code = 0, ?string $message = null, array $data = []): void
    {
        MallIntegrationLog::store(
            $this->integration->system_id, $this->integration->mall_id, $operation, $code, $message, $data
        );
    }
}
