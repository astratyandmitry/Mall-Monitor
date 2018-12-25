<?php

namespace App\WSDL;

use App\Models\IntegrationLog;
use App\Models\IntegrationSystem;

class ProsystemsWSDL
{

    /**
     * @var string
     */
    protected $url = 'https://88.204.142.178:8014/FSCDataProvider/KERUENBONUS/STREAMING/INSTANCE-A.asmx?wsdl';

    /**
     * @var string
     */
    protected $username = 'keruen';

    /**
     * @var string
     */
    protected $password = 'h^M1IpW3ovxq1$5I';

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
     * @var Singleton
     */
    private static $instance;


    /**
     * ProsystemsWSDL constructor.
     */
    public function __construct()
    {
        $this->client = new \SoapClient($this->url, ['local_cert' => base_path('prosystems.cer')]);
    }


    /**
     * @return \App\WSDL\ProsystemsWSDL
     */
    public static function init(): ProsystemsWSDL
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProsystemsWSDL;
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
            'login' => $this->username,
            'password' => $this->password,
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

            $this->data = $packet->Content->Operations->BaseOperation;
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
     * @param string    $operation
     * @param \stdClass $response
     * @param array     $data
     *
     * @return void
     */
    protected function log(string $operation, \stdClass $response, array $data = []): void
    {
        IntegrationLog::store(
            IntegrationSystem::PROSYSTEMS, $operation, $response, $data
        );
    }

}
