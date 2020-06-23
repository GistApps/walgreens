<?php
namespace Gist\Walgreens;

use Gist\Walgreens\WalgreensClient;
/**
 * Object interface for building checkout requests
 * https://developer.walgreens.com/api/photoprints/rest
 *
 * {
 *  "apiKey": "YOUR API KEY",
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
 */
interface CheckoutInterface
{

    /**
     * Add the api key to the lookup request
     * "apiKey"
     *
     * @param String $apiKey
     *
     * @return Checkout
     */
    public function apiKey(WalgreensClient $client);


    /**
     * Add the affiliate ID to the lookup request
     * "affId"
     * The AffiliateID that you was given in your application email.
     *
     * @param String $affiliateId
     *
     * @return Checkout
     */
    public function affiliateId(WalgreensClient $client);


    /**
     * Used to differentiate product groups, for example: Standard Prints (STDPR) vs Square Prints (SQR01)
     * $request['productGroupId'] = "STDPR" || "SQR01"
     * @return Checkout
     */
    public function productGroup(Array $params);

    /**
     * Used to associate the order to your publisher account.
     * Signup for this here: https://signup.cj.com/member/signup/publisher/?cid=3655651#/branded?_k=0dijjx
     *
     * @return Checkout
     */
    public function publisherId(WalgreensClient $client);

    /**
    * OPTIONAL:
    * Adds a custom app version to the request. "appVer"
    * This would be used only by Walgreens for debugging
    * eg) "1.0"
     *
     * @param String $appVersion
     *
     * @return Checkout
     */
    public function appVersion(WalgreensClient $client);


    /**
     * Add the image urls uploaded previously
     *
     * @return Checkout
     */
    public function images(Array $params);

    /**
     * The channelInfo is used to pass the type of integration. Only set this as "web" if your integration is a website. If you integration is a mobile application pass an empty string.
     *
     * @return Checkout
     */
    public function channelInfo(WalgreensClient $client);

    /**
     * The callBackLink is used to pass URL we callback to after order submission/cancellation. Only set this if your integration is a website. DO NOT PASS IF YOUR APPLICATION IS A MOBILE APP.
     *
     * @return Checkout
     */
    public function callBackLink(Array $params);

    /**
     * The expiryTime is obtained from the expiryTime used to generate your uploaded photos, or, 36 hours from right now.
     *
     * @return Checkout
     */
    public function expiryTime();

    /**
     * The lat is the latitude of the customer as a string. Example: "42.138199"
     *
     * @return Checkout
     */
    public function latitude(Array $params);

    /**
     * The lng is the longitude of the customer as a string. Example: "-87.945799"
     *
     * @return Checkout
     */
    public function longitude(Array $params);

    /**
     * The customer object contains (the next 4 parameters) all the info needed to pre-populate the order submission pages.
     * The firstName value is the first name of the customer.
     * lastName	required	The lastName value is the last name of the customer.
     * lastName	required	The lastName value is the last name of the customer.
     * email	required	The email value is the email address of the customer. Must be a valid email. Format: email@domain.tld
     *
     * @return Checkout
     */
    public function customer(Array $params);


    /**
     * The Transaction is a value that invokes our internal service. The value must be "photoCheckoutv2".
     * $request['transaction'] = "photoCheckoutv2"
     *
     * @return Checkout
     */
    public function transaction();

    /**
     * Required for product lookup
     * $request['act'] = "mweb5UrlV2"
     *
     * @param String $appVersion
     *
     * @return Checkout
     */
    public function action();

    /**
     * Required for product lookup
     * $request['view'] = "mweb5UrlV2JSON"
     *
     *
     * @return Checkout
     */
    public function view();

    /**
     * OPTIONAL:
     * Adds a custom app version to the request. "devInf"
     * This would be used only by Walgreens for debugging
     *
     * $request['devinfo'] = "iPhone,13.0"
     *
     * @param String $appVersion
     *
     * @return Checkout
     */
    public function deviceInfo();

    /**
     * optional
     * Used for order tracking on your side. For staging order we recommend setting this as "Test Order, Do not print!"
     * $request['affNotes'] = "Test Order, Do not print!"
     *
     * @return Checkout
     */
    public function notes(Array $params, WalgreensClient $client);


    /**
     * Build the store lookup request
     *
     * @param array $params
     */
    public function landingUrlRequest(array $params, WalgreensClient $client);

}
