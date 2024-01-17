<?php

namespace App\Integration\Mall;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Models\MallIntegration;
use App\Models\MallIntegrationLog;

class Prosklad
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
     * @var \App\Integration\Mall\Prosklad
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
            'verify' => false,
        ]);
    }

    /**
     * @param \App\Models\MallIntegration $integration
     * @return \App\Integration\Mall\Trinity
     */
    public static function init(MallIntegration $integration): Prosklad
    {
        if (is_null(self::$instance)) {
            self::$instance = new Prosklad($integration);
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
            'username' => $this->integration->username,
            'password' => $this->integration->password,
        ];

        $request = new Request('POST', '/oauth/token', [
            'Content-Type' => 'application/json',
        ], json_encode($params));

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (isset($response->error_code)) {
            $this->log('Authenticate', $response->error_code, $response->message ?? 'Unknown', $params);

            return false;
        }

        if (! isset($response->access_token)) {
            $this->log('Authenticate', -1, 'Can not authenticate', $params);

            return false;
        }

        $this->log('Authenticate', 0, null, [
            'token' => $this->token = $response->access_token,
        ]);

        return true;
    }

    public function getCashboxes(): array
    {
        $request = new Request('POST', '/v1/employee/30977/company-group/cashbox', [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (! isset($response->data)) {
            $this->log('getCashboxes', $response->error_code ?? -1, $response->message ?? 'Unknown', $params);

            return false;
        }

        return $response->data;
    }

    /**
     * @param int $latestId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCheques(int $latestId = 0): array
    {
        $request = new Request('POST', "/v1/employee/30977/company-group/sale?latest_id={$latestId}", [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response = json_decode($this->client->send($request)->getBody()->getContents());

        if (! isset($response->data)) {
            $this->log('getCashboxes', $response->error_code ?? -1, $response->message ?? 'Unknown', $params);

            return false;
        }

        return [
            $response->data,
            $response->next_page_url !== null,
        ];
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
