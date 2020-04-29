<?php
namespace Gist\Walgreens;

use Gist\Walgreens\WalgreensClient;
use Gist\Walgreens\StoreLookupInterface;
use Gist\Walgreens\Exception\InvalidRequestException;

class StoreLookup implements StoreLookupInterface
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

    public function deviceInfo($client)
    {

      $deviceInfo = $client->getConfig('device_info');

      if ($deviceInfo !== null) {
        $this->request['devInf'] = $deviceInfo;
      }

    }

    public function latitude($params)
    {

      if (isset($params['latitude'])) {
        $this->request['latitude'] = $params['latitude'];
      }

    }

    public function longitude($params)
    {

      if (isset($params['longitude'])) {
        $this->request['longitude'] = $params['longitude'];
      }

    }

    public function radius($params)
    {

      if (isset($params['radius'])) {
        $this->request['r'] = $params['radius'];
      }

    }

    public function action($params)
    {

      $this->request['act'] = "photoStores";

    }

    public function productId($params)
    {

      if (isset($params['product_id']) && is_string($params['product_id'])) {
        $this->request['productId'] = $params['product_id'];
      } else {
        throw new InvalidRequestException("You must specify a product id");
      }

    }

    public function productDetails($params)
    {

      if (isset($params['product_details']) && is_array($params['product_details'])) {
        $this->request['productDetails'] = $params['product_details'];
      } else {
        throw new InvalidRequestException("You must specify product details in the request");
      }

    }

    public function quantity($params)
    {

      if (isset($params['quantity']) && is_string($params['quantity'])) {
        $this->request['qty'] = $params['quantity'];
      } else {
        throw new InvalidRequestException("You must specify product quantity in the request");
      }

    }




    public function zip($params)
    {

      if (isset($params['zip']) && is_string($params['zip'])) {
        $this->request['zip'] = $params['zip'];
      }

    }

    public function address($params)
    {

      if (isset($params['address']) && is_string($params['address'])) {
        $this->request['address'] = $params['address'];
      }

    }

    public function isValidRequest()
    {

      if (isset($this->request['zip'])) {
        return true;
      }

      if (isset($this->request['address'])) {
        return true;
      }

      if (isset($this->request['longitude']) && isset($this->request['longitude'])) {
        return true;
      }

      throw new InvalidRequestException("The request parameters must contain a zip, address, or latitude and longitude cordinates");

    }

    /**
     * Build the store lookup request
     *
     * @param array $params
     */
    public function buildRequest(array $params, WalgreensClient $client): Array
    {

      $this->apiKey($client);
      $this->affiliateId($client);
      $this->appVersion($client);
      $this->deviceInfo($client);
      $this->latitude($params);
      $this->longitude($params);
      $this->radius($params);
      $this->zip($params);
      $this->address($params);
      $this->action($params);
      $this->productDetails($params);
      //$this->productId($params);
      //$this->quantity($params);

      //Validate the request before continueing
      $this->isValidRequest();

      $request = [
        'json' => $this->request,
      ];

    /*echo "<pre><code>";
      echo json_encode($request, JSON_PRETTY_PRINT);
      echo "</code></pre>";*/


      return $request;

    }
}
