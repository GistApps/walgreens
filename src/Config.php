<?php
namespace Gist\Walgreens;

use Gist\Walgreens\ConfigInterface;
use Gist\Walgreens\Exception\ConfigurationException;


class Config implements ConfigInterface
{
    /** @var array Default request options */
    private $config;

    const appVersion = "1.0.0";

    /**
     * Add the api key to the lookup request
     * "apiKey"
     *
     * @param Array $params
     *
     * @return Config
     */
    public function apiKey(Array $params)
    {
      // Make sure an endpoint is set up prior to creating the base uri
      if (isset($params['api_key']) && is_string($params['api_key'])) {
        $this->config['api_key'] = $params['api_key'];
      } else {
        throw new ConfigurationException("An API key must be included with the request to use the Walgreens API");
      }

    }


    /**
     * Add the affiliate ID to the lookup request
     * "affId"
     *
     * @param Array $params
     *
     * @return Config
     */
    public function affiliateId(Array $params)
    {
      // Make sure an endpoint is set up prior to creating the base uri
      if (isset($params['affiliate_id']) && is_string($params['affiliate_id'])) {
        $this->config['affiliate_id'] = $params['affiliate_id'];
      } else {
        throw new ConfigurationException("An Affliiate ID must be included with the request to use the Walgreens API");
      }

    }


    /**
    * OPTIONAL:
    * Adds a custom app version to the request. "appVer"
    * This would be used only by Walgreens for debugging
    * eg) "1.0"
     *
     * @param Array $params
     *
     * @return Config
     */
    public function baseUrl(Array $params) {

      if (isset($params['test']) && $params['test'] === true) {
        $this->config['base_uri'] = "https://services-qa.walgreens.com";
      }

    }


    /**
    * OPTIONAL:
    * Adds a custom app version to the request. "appVer"
    * This would be used only by Walgreens for debugging
    * eg) "1.0"
     *
     * @param Array $params
     *
     * @return Config
     */
    public function appVersion() {

      // Version of this app
      $this->config['app_version']  = Self::appVersion;

    }


    /**
     * OPTIONAL:
     * Adds a custom app version to the request. "devInf"
     * This would be used only by Walgreens for debugging
     * eg) "iPhone,13.0"
     *
     * @param Array $params
     *
     * @return Config
     */
    public function deviceInfo(Array $params) {

      if (isset($params['device_info'])) {
        $this->config['device_info'] = $params['device_info'];
      } else {
        $this->config['device_info'] = "iPhone,13.0";
      }

    }

    /**
     * OPTIONAL: Used to add the revenue share partner ID.
     *
     * @param Array $params
     *
     * @return Config
     */
    public function publisherId(Array $params) {

      if (isset($params['publisher_id'])) {
        $this->config['publisher_id'] = $params['publisher_id'];
      }

    }

    /**
     * Set to "web" if a web application
     *
     * @param Array $params
     *
     * @return Config
     */
    public function channelInfo(Array $params) {

      if (isset($params['channel_info'])) {
        $this->config['channel_info'] = $params['channel_info'];
      }

    }


    public function setup(Array $params)
    {

      // Sandbox example Url: https://services-qa.walgreens.com/api/stores/search/v1
      // Production  example Url: https://services.walgreens.com/api/stores/search/v1

      // Setup the default base url
      $this->config['base_uri'] = "https://services.walgreens.com";

      $this->apiKey($params);
      $this->affiliateId($params);
      $this->baseUrl($params);
      $this->appVersion($params);
      $this->deviceInfo($params);
      $this->publisherId($params);
      $this->channelInfo($params);

      return $this->config;
    }
}
