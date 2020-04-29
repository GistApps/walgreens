<?php
namespace Gist\Walgreens;

use Gist\Walgreens\WalgreensClient;
use Gist\Walgreens\PhotoPrintInterface;
use Gist\Walgreens\Exception\InvalidRequestException;
use Gist\Walgreens\Utils\UUID;

/**
 * Object interface for building store lookup requests
 */
class Checkout implements CheckoutInterface
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
        $this->request['appver'] =$appVersion;
      }

    }

    public function channelInfo($client)
    {

      $channelInfo = $client->getConfig('channel_info'); // Web or blank

      if ($channelInfo !== null) {
        $this->request['channelInfo'] = $channelInfo;
      } else {
        $this->request['channelInfo'] = "";
      }

    }

    public function publisherId($params)
    {

      $publisherId = $client->getConfig('publisher_id');

      if ($publisherId !== null) {
        $this->request['publisherId'] = $publisherId;
      } else {
        throw new InvalidRequestException("Publisher ID is required to make this request. Please add the publisher_id paramter to your client config.");
      }

    }

    public function deviceInfo()
    {

      $deviceInfo = null;

      if ($deviceInfo !== null) {
        $this->request['devinf'] = $deviceInfo;
      }

    }

    public function transaction()
    {

      $this->request['transaction'] = "photoCheckoutv2";

    }


    public function action()
    {

      $this->request['act'] = "mweb5UrlV2";

    }

    public function view()
    {

      $this->request['view'] = "mweb5UrlV2JSON";

    }

    public function productGroup($params)
    {

      if (isset($params['product_group']) && is_string($params['product_group'])) {
        $this->request['product_group'] = $params['product_group'];
      }

    }

    public function callbackLink($params)
    {

      if (isset($params['callback_url']) && is_string($params['callback_url'])) {
        $this->request['callBackLink'] = $params['callback_url'];
      }

    }

    public function images($params)
    {

      if (isset($params['images']) && is_array($params['images'])) {
        $this->request['images'] = $params['images'];
      } else {
        throw new InvalidRequestException("Please include an array of image urls assigned to the 'image' parameter in order to create the checkout");
      }

    }

    public function latitude($params)
    {

      if (isset($params['latitude']) && is_string($params['latitude'])) {
        $this->request['lat'] = $params['latitude'];
      } else {
        throw new InvalidRequestException("Please include the latitude of the customer ");
      }

    }

    public function longitude($params)
    {

      if (isset($params['longitude']) && is_string($params['longitude'])) {
        $this->request['lng'] = $params['longitude'];
      } else {
        throw new InvalidRequestException("Please include the longitude of the customer ");
      }

    }

    public function expiryTime($params)
    {

      $expiry = new \DateTime("Plus 36 hours");

      // Ruturn ISO 8601 Formatted date
      $this->request['expiryTime'] = $expiry->format('c');

    }

    public function notes($params, $client)
    {

      $test = $client->getConfig('test'); // Web or blank

      if ($test !== null) {

        $this->request['affNotes'] = "Test Order, Do not print!";

      } else if (isset($params['notes']) && is_string($params['notes'])) {

        $this->request['affNotes'] = $params['notes'];

      }

    }



    public function customer($params)
    {

      if (!isset($params['customer']) || !is_array($params['customer'])) {

        throw new InvalidRequestException("Please include customer details in the request.");

      } else if (!isset($params['customer']['first_name'])) {

        throw new InvalidRequestException("Please include customer details in the request.");

      } else if (!isset($params['customer']['last_name'])) {

        throw new InvalidRequestException("Please include customer details in the request.");

      } else if (!isset($params['customer']['email'])) {

        throw new InvalidRequestException("Please include customer details in the request.");

      } else if (!isset($params['customer']['phone'])) {

        throw new InvalidRequestException("Please include customer details in the request.");

      } else {

        $this->request['customer'] = array(
          "firstName" => $params['customer']['first_name'],
          "lastName"  => $params['customer']['last_name'],
          "phone"     => $params['customer']['phone'],
          "email"     => $params['customer']['email'],
        );

      }


    }





    /**
     * Build the store lookup request
     * *  "apiKey": "YOUR API KEY",
      *  "affId": "YOUR AFFILIATE ID",
      *  "productGroupId": "PRODUCT GROUP ID",
      *  "publisherId": "YOUR PUBLISHER ID",
      *  "channelInfo": "CHANNEL INFO: web or ''",
      *  "callBackLink": "IF CHANNEL INFO IS web: YOUR CALLBACK URL",
      *  "expiryTime": "IMAGE EXPIRATION TIME",
      *  "images": [
      *      "URL1",
      *      "URL2",
      *      "URL3",
      *      "etc..."
      *  ],
      *  "lat": "CUSTOMER LATITUDE",
      *  "lng": "CUSTOMER LONGITUDE",
      *  "customer": {
      *      "firstName": "CUSTOMER FIRST NAME",
      *      "lastName": "CUSTOMER LAST NAME",
      *      "email": "CUSTOMER EMAIL ADDRESS",
      *      "phone": "CUSTOMER PHONE NUMBER"
      *  },
      *  "transaction": "photoCheckoutv2",
      *  "act": "mweb5UrlV2",
      *  "view": "mweb5UrlV2JSON",
      *  "devinf": "THE DEVICE INFO",
      *  "appver": "THE APP VERSION",
      *  "affNotes": "TRACKING ID / NOTES"
      * }
     * @param array $params
     */
    public function landingUrlRequest(array $params, WalgreensClient $client): Array
    {

      // Get the configuration details
      $this->apiKey($client);
      $this->affiliateId($client);
      $this->publisherId($client);
      $this->channelInfo($client);
      $this->appVersion($client);
      $this->deviceInfo($client);

      // Set up Static properties for request
      $this->transaction();
      $this->action();
      $this->view();
      $this->platform();
      $this->expiryTime();

      // Add dynamic properties for request
      $this->productGroup($params);
      $this->callbackLink($params);
      $this->images($params);
      $this->latitude($params);
      $this->longitude($params);
      $this->customer($params);
      $this->notes($params);

      $request = [
        'json' => $this->request,
      ];

      return $request;

    }


}
