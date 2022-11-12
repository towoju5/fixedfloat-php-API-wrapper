<?php

namespace Towoju5\Fixedfloat;

class Fixedfloat
{
    protected $baseUrl = "https://fixedfloat.com/api/v1/";


    /**
     * Constructor for FixedFloatAPI
     */
    function __construct($key = NULL, $secret = NULL)
    {
        $this->key        = $key ?? getenv('FIXEDFLOAT_API_KEY');
        $this->secret     = $secret ?? getenv('FIXEDFLOAT_SECRET_KEY');

        $this->curl       = curl_init();
        $curl_options     = [
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT => 300
        ];

        curl_setopt_array($this->curl, $curl_options);
    }

    /**
     * Close CURL
     */
    function __destruct()
    {
        curl_close($this->curl);
    }


    /**
     * @return response JSON
     * @param string fromCurrency required
     * @param string toCurrency required
     * @param number fromQty optional
     * @param number toQty optional
     * @param string toAddress required
     * @param enum type ['fixed', 'float'] required
     * @response Object
     */
    public function createOrder()
    {
        $data = $data ?? [];
        $endpoint = 'createOrder';
        $response = $this->process($data, $endpoint, "POST");
        return $response;
    }

    /**
     * @return JSON
     * @method POST
     * @param string fromCurrency required
     * @param string toCurrency required
     * @param number fromQty optional 
     * @param number toQty optional 
     * @param enum type ['fixed', 'float'] required
     */
    public function getPrice()
    {
        $endpoint = 'getCurrencies';
        $response = $this->process([], $endpoint, "GET");
        return $response;
    }

    /**
     * @return JSON
     * @method GET
     * @param NULL
     */
    public function getCurrencies()
    {
        $endpoint = 'getCurrencies';
        $response = $this->process([], $endpoint, "GET");
        return $response;
    }

    /**
     * @return JSON
     * @method GET
     * @param string id : orderID required
     * @param string token: Security token of Order required
     */
    public function getOrder()
    {
        $endpoint = 'getOrder';
        $response = $this->process([], $endpoint, "GET");
        return $response;
    }

    /**
     * @return JSON
     * @method GET
     * @param string id : orderID required
     * @param string token: Security token of Order required
     */
    public function setEmergency(array $data)
    {
        $data = $data ?? [];
        $endpoint = 'setEmergency';
        $response = $this->process($data, $endpoint, "GET");
        return $response;
    }


    private function process(array $data, string $endpoint, string $method)
    {
        $method = strtoupper($method);
        $curl_url = $this->baseUrl . $endpoint;
        // Set URL & Header
        $query   = http_build_query($data, '', '&');

        // set API key and sign the message
        $sign    = hash_hmac('sha256', $query, $this->secret);

        $headers = array(
            'X-API-KEY: ' . $this->key,
            'X-API-SIGN: ' . $sign
        );

        // make request
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

        // build the POST data string
        $postdata = $data;

        // Set URL & Header
        curl_setopt($this->curl, CURLOPT_URL, $curl_url . "?{$query}");

        //Add post vars
        if ($method == "POST") {
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, array());
        }

        //Get result
        $result = curl_exec($this->curl);
        // var_dump($result); exit;
        if ($result === false)
            throw new \Exception('CURL error: ' . curl_error($this->curl));

        // decode results
        $result = json_decode($result, true);
        if (!is_array($result) || json_last_error())
            throw new \Exception('JSON decode error');

        return $result;
    }
}
