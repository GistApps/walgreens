<?php
namespace Gist\Walgreens;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Gist\Walgreens\ConfigurationException;
use Gist\Walgreens\InvalidRequestException;
use Gist\Walgreens\StoreLookup;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class WalgreensClient implements ClientInterface
{
    /** @var array Default request options */
    private $config;

    /** @var Client Set up guzzle http client */
    private $guzzle;

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
       $this->configureDefaults($params);
       $this->setupGuzzle();
    }

    public function getConfig(string $configOption)
    {
      return $this->config[$configOption];
    }

    public function request(array $params = [], string $endpoint)
    {

      // Create a PSR-7 request object to send

      // Set any headers that are applicable
      // $headers = ['X-Foo' => 'Bar'];
      $request = $this->getRequest($params, $endpoint);
      // Set the request body json encoded;
      $body    =   [
          'json' => $request['params']
      ];

      // Send an async request.
      $promise = $this->guzzle->requestAsync($request['type'], $request['url'], $body);

      // Handle the response.
      $promise->then(
          function (ResponseInterface $res) {
              echo $res->getStatusCode() . "\n";
          },
          function (RequestException $e) {
              echo $e->getMessage() . "\n";
              echo $e->getRequest()->getMethod();
          }
      );

    }

    private function getRequest(array $params, string $endpoint)
    {

      $version = $this->config['version'];

      switch($endpoint) {

        case "store_locator":
          // Pass the request parameters and the class intance to the storelookup function.
          $params  = StoreLookup::buildRequest($params, $this);
          $type    = "POST";
          $url     = "/stores/search/{$version}";
        break;

        /*case "store_locator":
          // Pass the request parameters and the class intance to the storelookup function.
          $params  = StoreLookup::buildRequest($params, $this);
          $type    = "POST";
          $url     = "/stores/search/{$version}";
        break; */
      }

      return [
        'params' => $params,
        'type'   => $type,
        'url'    => $url
      ];

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
          'base_uri' => $this->config['baseUri'],
      ]);

    }

    /**
     * Configures the default options for a client.
     *
     * @param array $params
     */
    private function configureDefaults(array $params)
    {

      // Sandbox example Url: https://services-qa.walgreens.com/api/stores/search/v1
      // Production  example Url: https://services.walgreens.com/api/stores/search/v1

      $options = [
          'base_url' => "https://services.walgreens.com/api",
          'version'  => "v1",
      ];

      if (isset($params['test']) && $params['test'] === true) {
        $options['base_url'] = "https://services-qa.walgreens.com/api";
      }

      if (isset($params['version'])) {
        $options['version'] = $params['version'];
      }


      // Make sure an endpoint is set up prior to creating the base uri
      if (isset($params['api_key']) && $params['api_key'] === true) {
        $options['api_key'] = $params['api_key'];
      } else {
        throw new InvalidRequestException("An API key must be included with the request to use the Walgreens API");
      }

      // Make sure an endpoint is set up prior to creating the base uri
      if (isset($params['affiliate_id']) && $params['affiliate_id'] === true) {
        $options['affiliate_id'] = $params['affiliate_id'];
      } else {
        throw new InvalidRequestException("An Affliiate ID must be included with the request to use the Walgreens API");
      }

      $this->config['base_uri']     = $options['base_url'];

      $this->config['version']      = $options['version'];

      $this->config['api_key']      = $options['api_key'];

      $this->config['affiliate_id'] = $options['affiliate_id'];


    }
}
