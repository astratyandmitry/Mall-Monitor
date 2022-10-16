<?php

namespace App\Integration\Mall;

use App\Models\MallIntegration;
use App\Models\MallIntegrationLog;

class Prosystems
{
    /**
     * @var \SoapClient
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
     * @var string
     */
    protected $packageGUID;

    /**
     *
     * @var \App\Integration\Mall\Prosystems
     */
    private static $instance;

    /**
     * @var \App\Models\MallIntegration
     */
    protected $integration;

    /**
     * @param \App\Models\MallIntegration $integration
     *
     * @return void
     * @throws \SoapFault
     *
     */
    public function __construct(MallIntegration $integration)
    {
        $this->integration = $integration;

        $this->client = new \SoapClient($this->integration->host, [
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                    'cafile' => base_path('prosystems.cer'),
                ],
            ]),
        ]);
    }

    /**
     * @param \App\Models\MallIntegration $integration
     *
     * @return \App\Integration\Mall\Prosystems
     * @throws \SoapFault
     */
    public static function init(MallIntegration $integration): Prosystems
    {
        if (is_null(self::$instance)) {
            self::$instance = new Prosystems($integration);
        }

        return self::$instance;
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $success = true;
        $data = [];

        $params = [
            'login' => $this->integration->username,
            'password' => $this->integration->password,
        ];

        $response = $this->client->Authorize($params);

        if ($response->AuthorizeResult->Code != '000') {
            $success = false;

            $data = $params;
        } else {
            $data['token'] = $this->token = $response->AuthorizeResult->ResultObject->enc_value->Token;
        }

        $this->log('Authorize', $response->AuthorizeResult, $data);

        return $success;
    }

    /**
     * @return bool
     */
    public function provoidData(): bool
    {
        $success = true;
        $data = [];

        $params = [
            'token' => $this->token,
        ];

        $response = $this->client->ProvideData($params);

        if ($response->ProvideDataResult->Code != '000') {
            $success = false;

            $data = $params;
        } else {
            $packet = $response->ProvideDataResult->ResultObject->enc_value->Packet;

            $data['packetGuid'] = $this->packageGUID = $packet->Guid;

            $this->data = (is_array($packet->Content->Operations->BaseOperation)) ? $packet->Content->Operations->BaseOperation : $packet->Content->Operations;
        }

        $this->log('ProvideData', $response->ProvideDataResult, $data);

        return $success;
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
     * @param \stdClass $response
     * @param array $data
     *
     * @return void
     */
    protected function log(string $operation, \stdClass $response, array $data = []): void
    {
        MallIntegrationLog::store(
            $this->integration->system_id, $this->integration->mall_id, $operation, $response->Code ?? 0, $response->Message ?? null, $data
        );
    }
}
