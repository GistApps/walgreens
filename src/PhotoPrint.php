<?php
namespace Gist\Walgreens;

use Gist\Walgreens\WalgreensClient;
use Gist\Walgreens\PhotoPrintInterface;
use Gist\Walgreens\Exception\InvalidRequestException;
use Gist\Walgreens\Utils\UUID;

/**
 * Object interface for building store lookup requests
 */
class PhotoPrint implements PhotoPrintInterface
{


    /** @var array Default request options */
    private $request;

    public function apiKey($client)
    {
      $this->request['apiKey'] = $client->getConfig('api_key');
    }

    public function affiliateId($client)
    {
      $this->request['affId'] = $client->getConfig('affiliate_id');
    }

    public function appVersion($client)
    {

      $appVersion = $client->getConfig('app_version');

      if ($appVersion !== null) {
        $this->request['appVer'] =$appVersion;
      }

    }

    public function deviceInfo()
    {

      $deviceInfo = null;

      if ($deviceInfo !== null) {
        $this->request['devInf'] = $deviceInfo;
      }

    }

    public function transaction()
    {

      $this->request['transaction'] = "photocheckoutv2";

    }

    public function platform()
    {

      $this->request['platform'] = "desktop";

    }

    public function action()
    {

      $this->request['act'] = "getphotoprods";

    }

    public function productGroup($params)
    {

      if (isset($params['product_group']) && is_string($params['product_group'])) {
        $this->request['product_group'] = $params['product_group'];
      }

    }

    /**
     * Build the store lookup request
     *
     * @param array $params
     */
    public function credentialsRequest(array $params, WalgreensClient $client): Array
    {

      $this->apiKey($client);
      $this->affiliateId($client);
      $this->appVersion($client);
      $this->deviceInfo($client);
      $this->transaction();
      $this->platform();

      $request = [
        'json' => $this->request,
      ];

      return $request;

    }

    public function uploadRequest(array $params, WalgreensClient $client): Array
    {

      $request = [
        'body' => $params['image'],
        'headers' => [
          "Content-Type"           => $params['content_type'],
          "Content-Length"         => strlen($params['image']),
          "x-ms-client-request-id" => UUID::v4(),
          "x-ms-blob-type"         => "BlockBlob",
        ]
      ];

      return $request;

    }

    public function productRequest(array $params, WalgreensClient $client): Array
    {

      $this->apiKey($client);
      $this->affiliateId($client);
      $this->appVersion($client);
      $this->deviceInfo($client);
      $this->action();
      $this->productGroup($params);

      $request = [
        'json' => $this->request,
      ];

      return $request;

    }

}
