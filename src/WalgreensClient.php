<?php
namespace Gist\Walgreens;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Gist\Walgreens\Config;
use Gist\Walgreens\StoreLookup;
use Gist\Walgreens\PhotoPrint;
use Gist\Walgreens\Utils\UUID;
use Gist\Walgreens\WalgreensClientInterface;
use Gist\Walgreens\Exception\ResponseException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class WalgreensClient implements WalgreensClientInterface
{
    /** @var array Default request options */
    private $config;

    /** @var Client Set up guzzle http client */
    public $guzzle;

    const PRINT_REQUEST = false;

    /**
     * Clients accept an array of constructor parameters.
     *
     * Here's an example of creating a client using a base_uri and an array of
     * default request options to apply to each request:
     *
     *     $client = new Gist\Walgreens\Client([
     *         'endpoint'     => 'stores/search'
     *         'version'      => 'v1',
     *         'api_key'      => "123456789",
     *         'affiliate_id' => "AAAAAAA",
     *         'test'         => false,         //optional
     *         'app_version'  => "1.0",         //optional
     *         'device_info'  => "iPhone,13.0", //optional
     *     ]);
     *
     * @param array $config Client configuration settings.
     *
     */
    public function __construct(array $params = [])
    {
       $this->setupClient($params);
       $this->setupGuzzle();
    }

    public function getConfig(string $configOption)
    {

      if (isset($this->config[$configOption])) {
        return $this->config[$configOption];
      }

      return null;

    }

    public function request(array $params = [], string $endpoint)
    {


      // Get the request according the the endpoint.
      $query = $this->getRequest($params, $endpoint);

      try {
        echo $query['url'] . "<br>";
        // Create a PSR-7 request object and send
        $response = $this->guzzle->request($query['type'], $query['url'], $query['params']);

        if (self::PRINT_REQUEST === true) {
          echo $endpoint . " status code: " . $response->getStatusCode() . "<br/>";

          echo "<br/>";
        }


        $response = json_decode($response->getBody(), true);

      } catch(ClientException $e) {

        $response = [
          'error' => true,
          'message' => $e->getMessage(),
        ];

      } catch(RequestException $e) {

        $response = [
          'error' => true,
          'message' => $e->getMessage(),
        ];

      }

      if (self::PRINT_REQUEST === true) {
        echo "<pre><code>";
        echo json_encode($response, JSON_PRETTY_PRINT);
        echo "</code></pre>";
        echo "<br/>";
      }
      // Return the json
      return $response;
    }

    /**
     * Generate an upload url in the correct format
     * to use for the imagte upload endpoint.
     * Format: https://pstrgqp01.blob.core.windows.net/qpcontainerin/Image-YOUR_AFFILIATE_ID-a12b3cd-e4f5-6789-ghi0-1234567j89kl.jpg?sig=BLAHBLAHBLAH&se=YYYY-MM-DDT22%3A53%3A57Z&sv=YYYY-MM-DD&sp=w&sr=c
     */
    private function getUploadUrl()
    {

      $response = $this->request([], "photoprint_credentials");

      if (!isset($response['cloud']) || !isset($response['cloud'][0]) || !isset($response['cloud'][0]['sasKeyToken'])) {
        echo "<pre><code>";
        echo json_encode($response, JSON_PRETTY_PRINT);
        echo "</code></pre>";
        die();
        throw new ResponseException("There was an error generating your upload credentials");
      }

      $affiliateId   = $this->getConfig('affiliate_id');

      $uuId          = UUID::v4();

      $sasToken      = $response['cloud'][0]['sasKeyToken'];

      $blobContainer = explode("?", $sasToken, 2)[0];

      $signature     = explode("?", $sasToken, 2)[1];

      $imageName     = "Image-{$affiliateId}-{$uuId}.jpg";

      $uploadUrl     = "{$blobContainer}/{$imageName}?{$signature}";

      return $uploadUrl;

    }

    private function getRequest(array $params, string $endpoint)
    {

      $headers = null;

      switch($endpoint) {

        case "store_locator":
          $storeLookup = new StoreLookup();
          // Pass the request parameters and the class intance to the storelookup function.
          $params  = $storeLookup->buildRequest($params, $this);
          $type    = "POST";
          $url     = "/api/photo/store/v3";
        break;

        case "photoprint_credentials":

          $photoPrint = new PhotoPrint();
          // Pass the request parameters and the class intance to the storelookup function.
          $params  = $photoPrint->credentialsRequest($params, $this);
          echo "<pre><code>";
          echo json_encode($params, JSON_PRETTY_PRINT);
          echo "</code></pre>";
          $type    = "POST";
          $url     = "/api/photo/creds/v3";

        break;

        case "photoprint_upload":

          // Generate the upload url needed by Walgreens
          $uploadUrl  = $this->getUploadUrl();

          $photoPrint = new PhotoPrint();
          // Get the upload request
          $params  = $photoPrint->uploadRequest($params, $this);
          $type    = "PUT";
          $url     = $uploadUrl;

        break;

        case "products":

          $photoPrint = new PhotoPrint();
          // Get the upload request
          $params  = $photoPrint->productRequest($params, $this);
          $type    = "POST";
          $url     = "/api/photo/products/v3";

        break;


        case "submit_order":

          $photoPrint = new PhotoPrint();
          // Get the upload request
          $params  = $photoPrint->submitOrder($params, $this);
          $type    = "POST";
          $url     = "/api/photo/order/submit/v3";

        break;

        case "checkout":

          $checkout = new Checkout();

          $params     = $checkout->landingUrlRequest($params, $this);
          $type       = "POST";
          $url        = "/api/util/v3.0/mweb5url";

        break;


      }

      $request = [
        'params'  => $params,
        'type'    => $type,
        'url'     => $url,
      ];

      if (self::PRINT_REQUEST === true) {
        echo "<pre><code>";
        echo json_encode($request, JSON_PRETTY_PRINT);
        echo "</code></pre>";
      }

      return $request;

    }

    /**
     * Set up the Guzzle Http Client
     *
     * @return Client The guzzle http client used to make the requests
     */
    private function setupGuzzle()
    {
      // http://docs.guzzlephp.org/en/stable/overview.html

      $this->guzzle = new Client([
          'base_uri' => $this->config['base_uri'],
      ]);

    }

    /**
     * Configures the default options for a client.
     *
     * @param array $params
     */
    private function setupClient(array $params)
    {

      $configClass = new Config();
      $this->config = $configClass->setup($params);

    }
}
