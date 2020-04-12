<?php
namespace Gist\Walgreens;

use Gist\Walgreens\WalgreensClient;
use Gist\Walgreens\StoreLookupInterface;

class StoreLookup implements StoreLookupInterface
{
    /** @var array Default request options */
    private $request;

    private function apiKey($client)
    {
      $this->request['apiKey'] = $client->getConfig('api_key');
    }

    private function affiliateId($client)
    {
      $this->request['affId'] = $client->getConfig('affiliate_id');
    }

    private function appVersion($client)
    {

      $appVersion = $client->getConfig('app_version');

      if ($appVersion !== null) {
        $this->request['appVer'] =$appVersion;
      }

    }

    private function deviceInfo($client)
    {

      $deviceInfo = $client->getConfig('device_info');

      if ($deviceInfo !== null) {
        $this->request['devInf'] = $deviceInfo;
      }

    }

    private function lattitude($params)
    {

      if (isset($params['lattitude'])) {
        $this->request['lat'] = $params['lattitude'];
      }

    }

    private function longitude($params)
    {

      if (isset($params['longitude'])) {
        $this->request['lng'] = $params['longitude'];
      }

    }

    private function radius($params)
    {

      if (isset($params['radius'])) {
        $this->request['r'] = $params['radius'];
      }

    }

    private function requestType($params)
    {

      $this->request['requestType'] = "location";

    }

    private function filterOptions($params)
    {

      if (isset($params['filter']) && is_array($params['filter'])) {
        $this->request['filterOptions'] = $params['filter'];
      }

    }

    private function pageIndex($params)
    {

      if (isset($params['page_index']) && is_integer($params['page_index'])) {
        $this->request['nxtPrev'] = $params['page_index'];
      }

    }

    private function zip($params)
    {

      if (isset($params['zip']) && is_integer($params['zip'])) {
        $this->request['zip'] = $params['zip'];
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

      if (isset($this->request['lat']) && isset($this->request['lng'])) {
        return true;
      }

      throw new InvalidRequestException("The request parameters must contain a zip, address, or lattitude and longitude cordinates");

    }

    /**
     * Build the store lookup request
     *
     * @param array $params
     */
    public function buildRequest(array $params, WalgreensClient $client): StoreLookup
    {

      $this->apiKey($client);
      $this->affiliateId($client);
      $this->appVersion($client);
      $this->deviceInfo($client);
      $this->lattitude($params);
      $this->longitude($params);
      $this->radius($params);
      $this->requestType($params);
      $this->filterOptions($params);
      $this->pageIndex($params);

      //Validate the request before continueing
      $this->isValidRequest();

      return $this->request;

    }
}
