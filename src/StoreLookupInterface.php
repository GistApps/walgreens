<?php
namespace Gist\Walgreens;

/**
 * Object interface for building store lookup requests
 */
interface StoreLookupInterface
{


    /**
     * Add the api key to the lookup request
     * "apiKey"
     *
     * @param WalgreensClient $client
     *
     * @return StoreLookup
     */
    public function apiKey(WalgreensClient $client);


    /**
     * Add the affiliate ID to the lookup request
     * "affId"
     *
     * @param String $affiliateId
     *
     * @return StoreLookup
     */
    public function affiliateId(WalgreensClient $client);


    /**
     * Add the latitude to the store lookup request
     * "lat"
     *
     * @param String $latitude
     *
     * @return StoreLookup
     */
    public function latitude(Array $params);


    /**
     * Adds a longitude
     * "lng"
     *
     * @param Array $params
     *
     * @return StoreLookup
     */
    public function longitude(Array $params);


    /**
     * Adds a radius in miles for the lookup request
     * "r"
     *
     * @param Array $params
     *
     * @return StoreLookup
     */
    public function radius(Array $params);

    /**
     * Adds a zip code for the lookup request
     * "zip"
     *
     * @param Array $params
     *
     * @return StoreLookup
     */
    public function zip(Array $params);

    /**
     * Adds an address for the lookup request
     * "address"
     *
     * @param Array $params
     *
     * @return StoreLookup
     */
    public function address(Array $params);

    /**
     * Adds a request type - always returns "locator"
     * "requestType"
     *
     * @param Array $params
     *
     * @return StoreLookup
     */
    public function action(Array $params);


    /**
     * Add the product details to the lookup request
     *
     * @param Array $params
     *
     * @return StoreLookup
     */
    public function productDetails(Array $params);


    /**
     * add the product quantity
     *
     * @param Array $params
     *
     * @return StoreLookup
     */
    public function quantity(Array $params);

    /**
     * Adds a request type - always returns "locator"
     * "requestType"
     *
     * @param Array $params
     *
     * @return StoreLookup
     */
    public function productId(Array $params);


    /**
    * OPTIONAL:
    * Adds a custom app version to the request. "appVer"
    * This would be used only by Walgreens for debugging
    * eg) "1.0"
     *
     * @param String $appVersion
     *
     * @return StoreLookup
     */
    public function appVersion(String $appVersion);


    /**
     * OPTIONAL:
     * Adds a custom app version to the request. "devInf"
     * This would be used only by Walgreens for debugging
     * eg) "iPhone,13.0"
     *
     * @param String $appVersion
     *
     * @return StoreLookup
     */
    public function deviceInfo(String $appVersion);


}
