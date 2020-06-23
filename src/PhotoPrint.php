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

    public function publisherId($client)
    {

      $this->request['publisherId'] = $client->getConfig('publisher_id');
    }

    public function appVersion($client)
    {

      $appVersion = $client->getConfig('app_version');

      if ($appVersion !== null) {
        $this->request['appVer'] =$appVersion;
      }

    }

    public function deviceInfo($client)
    {

      $deviceInfo = $client->getConfig('device_info');;

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

    public function action($action)
    {

      $this->request['act'] = $action;

    }

    public function productGroup($params)
    {

      if (isset($params['product_group']) && is_string($params['product_group'])) {
        $this->request['productGroupId'] = $params['product_group'];
      }

    }

    /*
    * An array of JSON ProductDetails objects. JSON ProductDetails objects will contain the two below parameters.
    */
    public function productDetails($params)
    {

      if (!isset($params['products'])) {
        throw new InvalidRequestException("You must specify products in order to submit an order");
      }

      $products = $params['products'];

      foreach($products as $product) {

        if (!isset($product['productId'])) {
          throw new InvalidRequestException("You must specify a product ID for each submitted product");
        }

        if (!isset($product['imageDetails']) || !is_array($product['imageDetails']) || count($product['imageDetails']) <= 0) {
          throw new InvalidRequestException("You must specify image details for each product");
        }

        foreach($product['imageDetails'] as $image) {

          if (!isset($image['qty'])) {
            throw new InvalidRequestException("You must specify a quantity for each image");
          }

          if (!isset($image['url'])) {
            throw new InvalidRequestException("You must specify a url for each image");
          }

        }

      }

      $this->request['productDetails'] = $params['products'];


    }

    public function promiseTime($params)
    {
      $this->request['promiseTime'] = $params['promise_time'];
    }

    public function firstName(Array $params)
    {

      if (!isset($params['first_name'])) {
        throw new InvalidRequestException("You must specify a first_name");
      }

      $this->request['firstName'] = $params['first_name'];
    }

    public function lastName(Array $params)
    {

      if (!isset($params['last_name'])) {
        throw new InvalidRequestException("You must specify a last_name");
      }

      $this->request['lastName'] = $params['last_name'];
    }


    public function phone(Array $params)
    {

      if (!isset($params['phone'])) {
        throw new InvalidRequestException("You must specify a email");
      }

      $this->request['phone'] = $params['phone'];
    }

    public function email(Array $params)
    {

      if (!isset($params['email'])) {
        throw new InvalidRequestException("You must specify a email");
      }

      $this->request['email'] = $params['email'];
    }

    public function storeNum(Array $params)
    {

      if (!isset($params['store_number'])) {
        throw new InvalidRequestException("You must specify a store number");
      }

      $this->request['storeNum'] = $params['store_number'];
    }

    public function affNotes(Array $params)
    {

      if (!isset($params['note'])) {
        throw new InvalidRequestException("You must specify a note");
      }

      $this->request['affNotes'] = $params['note'];
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
      $this->action("getphotoprods");
      $this->productGroup($params);

      $request = [
        'json' => $this->request,
      ];

      return $request;

    }

    public function submitOrder(array $params, WalgreensClient $client): Array
    {

      $this->apiKey($client);
      $this->publisherId($client);
      $this->affiliateId($client);
      $this->appVersion($client);
      $this->deviceInfo($client);
      $this->action("submitphotoorder");
      $this->productDetails($params);
      $this->promiseTime($params);

      $this->firstName($params);
      $this->lastName($params);

      $this->phone($params);
      $this->email($params);
      $this->storeNum($params);
      $this->affNotes($params);

      $request = [
        'json' => $this->request,
      ];

      return $request;

    }

}
