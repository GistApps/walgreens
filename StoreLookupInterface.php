<?php
namespace Gist\Wallgreens;

/**
 * Object interface for building store lookup requests
 */
interface StoreLookupInterface
{


    /**
     * Add the api key to the lookup request
     * "apiKey"
     *
     * @param String $apiKey
     *
     * @return StoreLookup
     */
    public function apiKey(String $apiKey);


    /**
     * Add the affiliate ID to the lookup request
     * "affId"
     *
     * @param String $affiliateId
     *
     * @return StoreLookup
     */
    public function affiliateId(String $affiliateId);


    /**
     * Add the latitude to the store lookup request
     * "lat"
     *
     * @param String $latitude
     *
     * @return StoreLookup
     */
    public function latitude(String $latitude);


    /**
     * Adds a longitude
     * "lng"
     *
     * @param String $latitude Add the latitud to the store lookup request
     *
     * @return StoreLookup
     */
    public function longitude(String $longitude);


    /**
     * Adds a radius in miles for the lookup request
     * "r"
     *
     * @param String $latitude Add a radius in miles
     *
     * @return StoreLookup
     */
    public function radius(String $radius);


    /**
     * Adds a request type - always returns "locator"
     * "requestType"
     *
     * @param String $latitude Add a radius in miles
     *
     * @return StoreLookup
     */
    public function requestType(String $latitude);


    /**
     * OPTIONAL:
     * Adds filter options to the lookup request
     * "filterOptions"
     *
     * @param Array $filterOptions Add a radius in miles
     *
     * @return StoreLookup
     */
    public function filterOptions(Array $options);


    /**
     * OPTIONAL:
     * Adds the "nxtPrev" member to the request
     * This will show the next page of results, starting at the number
     * defined here
     *
     * @param Integer $pageIndex
     *
     * @return StoreLookup
     */
    public function pageIndex(Integer $pageIndex);


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

    /**
     * Checks if request is valid
     *
     * @param String $appVersion
     *
     * @return Boolean
     */
    public function isValidRequest();


}
