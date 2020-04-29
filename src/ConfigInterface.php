<?php
namespace Gist\Walgreens;

/**
 * Object interface to configure the Walgreens Client
 * [
 *         'api_key'      => "123456789",
 *         'affiliate_id' => "AAAAAAA",
 *         'platform'     => "desktop",
 *         'test'         => false,         //optional
 *         'device_info'  => "iPhone,13.0", //optional
 * ]
 */
interface ConfigInterface
{

    /**
     * Add the api key to the lookup request
     * "apiKey"
     *
     * @param String $apiKey
     *
     * @return Config
     */
    public function apiKey(Array $params);


    /**
     * Add the affiliate ID to the lookup request
     * "affId"
     *
     * @param String $affiliateId
     *
     * @return Config
     */
    public function affiliateId(Array $params);


    /**
     * OPTIONAL:
     * Adds the base url to the configuration object
     *
     * @param String $appVersion
     *
     * @return Config
     */
    public function baseUrl(Array $params);



    /**
    * OPTIONAL:
    * Adds a custom app version to the request. "appVer"
    * This would be used only by Walgreens for debugging
    * eg) "1.0"
     *
     * @param String $appVersion
     *
     * @return Config
     */
    public function appVersion();


    /**
     * OPTIONAL:
     * Adds a custom app version to the request. "devInf"
     * This would be used only by Walgreens for debugging
     * eg) "iPhone,13.0"
     *
     * @param String $appVersion
     *
     * @return Config
     */
    public function deviceInfo(Array $params);

    /**
     * OPTIONAL:
     * Adds a custom app version to the request. "publisherId"
     * Used to add the revenue share for Walgreens
     *
     * @param String $appVersion
     *
     * @return Config
     */
    public function publisherId(Array $params);

    /**
     * OPTIONAL:
     * Adds a custom app version to the request. "publisherId"
     * Used to add the revenue share for Walgreens
     *
     * @param String $appVersion
     *
     * @return Config
     */
    public function channelInfo(Array $params);


}
