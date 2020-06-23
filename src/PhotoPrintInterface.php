<?php
namespace Gist\Walgreens;

/**
 * Object interface for building store lookup requests
 */
interface PhotoPrintInterface
{


    /**
     * Adds the transaction reason to the request
     *
     * @return PhotoPrint
     */
    public function transaction();

    /**
     * Add the platform to the request eg "ios" || "android"
     *
     * @return PhotoPrint
     */
    public function platform();

    /**
     * Add the api key to the lookup request
     * "apiKey"
     *
     * @param String $apiKey
     *
     * @return PhotoPrint
     */
    public function apiKey(WalgreensClient $client);


    /**
     * Add the affiliate ID to the lookup request
     * "affId"
     *
     * @param String $affiliateId
     *
     * @return PhotoPrint
     */
    public function affiliateId(WalgreensClient $client);


    /**
    * OPTIONAL:
    * Adds a custom app version to the request. "appVer"
    * This would be used only by Walgreens for debugging
    * eg) "1.0"
     *
     * @param String $appVersion
     *
     * @return PhotoPrint
     */
    public function appVersion(WalgreensClient $client);


    /**
     * OPTIONAL:
     * Adds a custom app version to the request. "devInf"
     * This would be used only by Walgreens for debugging
     * eg) "iPhone,13.0"
     *
     * @param String $appVersion
     *
     * @return PhotoPrint
     */
    public function deviceInfo();

    /**
     * Required for product lookup
     * $request['act'] = "getphotoprods"
     *
     * @param String $appVersion
     *
     * @return PhotoPrint
     */
    public function action(string $action);

    /**
     * optional
     * $request['productGroupId'] = "STDPR" || "SQR01"
     *
     * @param Array $params
     *
     * @return PhotoPrint
     */
    public function productGroup(Array $params);

    /**
     * required
     * An array of JSON ProductDetails objects. JSON ProductDetails objects will contain the two below parameters.
     * $request['productDetails'] = [{ "productId":"0000001", "imageDetails":SEE BELOW }, { "productId":"0000002", "imageDetails":SEE BELOW }];
     *
     * @param Array $params
     *
     * @return PhotoPrint
     */
    public function productDetails(Array $params);



}
