<?php
namespace PHPCurl\CurlHttp;

use \PHPCurl\CurlWrapper\Curl;

/**
 * Minimalistic HTTP client
 */
class HttpClient
{
    const USE_EXCEPTIONS = true;

    /**
     * Numer of attempts to make per each call
     * @var int
     */
    protected $attempts = 1;

    /**
     * @var array
     */
    private $userOptions = array();

    /**
     * HTTP GET
     * @param string $url Goes to curl_init()
     * @param array $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function get($url, array $headers = null)
    {
        $opt = array();
        if ($headers) {
            $opt[CURLOPT_HTTPHEADER] = $headers;
        }
        return $this->exec($url, $opt);
    }

    /**
     * HTTP HEAD (implemented using CURLOPT_NOBODY)
     * @param string $url Goes to curl_init()
     * @param array $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function head($url, array $headers = null)
    {
        $opt[CURLOPT_NOBODY] = true;
        if ($headers !== null) {
            $opt[CURLOPT_HTTPHEADER] = $headers;
        }
        return $this->exec($url, $opt);
    }

    /**
     * HTTP POST
     * @param string $url Goes to curl_init()
     * @param string|array $data Same as CURLOPT_POSTFIELDS
     * @param array $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function post($url, $data = null, array $headers = null)
    {
        $opt[CURLOPT_POST] = true;
        if ($data !== null) {
            $opt[CURLOPT_POSTFIELDS] = $data;
        }
        if ($headers !== null) {
            $opt[CURLOPT_HTTPHEADER] = $headers;
        }
        return $this->exec($url, $opt);
    }

    /**
     * HTTP PUT
     * @param string $url Goes to curl_init()
     * @param string|array $data Same as CURLOPT_POSTFIELDS
     * @param array $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function put($url, $data = null, array $headers = null)
    {
        return $this->request('PUT', $url, $data, $headers);
    }

    /**
     * HTTP DELETE
     * @param string $url Goes to curl_init()
     * @param array $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function delete($url, array $headers = null)
    {
        return $this->request('DELETE', $url, null, $headers);
    }

    /**
     * Custom HTTP method
     * @param string $method Goes to CURLOPT_CUSTOMREQUEST
     * @param string $url Goes to curl_init()
     * @param string|array $data Goes to CURLOPT_POSTFIELDS
     * @param array $headers Goes to CURLOPT_HEADER
     * @return HttpResponse
     */
    public function request($method, $url, $data = null, array $headers = null)
    {
        $opt[CURLOPT_CUSTOMREQUEST] = $method;
        if ($headers !== null) {
            $opt[CURLOPT_HTTPHEADER] = $headers;
        }
        if ($data !== null) {
            $opt[CURLOPT_POSTFIELDS] = $data;
        }
        return $this->exec($url, $opt);
    }

    /**
     * Set additional CURL options to pass with each request
     * @param array $userOptions Format is the same as curl_setopt_array().
     *                          Pass an empty array to clear.
     */
    public function setOptions(array $userOptions)
    {
        $this->userOptions = $userOptions;
    }

    /**
     * Init curl with $url, set $options, execute, return response
     * @param string $url Goes to curl_init()
     * @param array $options Goes to curl_setopt()
     * @param Curl $curl
     * @return HttpResponse
     */
    public function exec($url, array $options, Curl $curl = null)
    {
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_HEADER] = true;

        $curl = $curl ?: new Curl();
        $curl->init($url);
        $curl->setOptArray(array_replace_recursive(
            $this->userOptions,
            $options
        ));

        $response = $curl->exec($this->attempts, self::USE_EXCEPTIONS);

        $info = $curl->getInfo();
        $code = $info['http_code'];
        $headerSize = $info['header_size'];
        $headers = substr($response, 0, $headerSize);
        $headersArray = preg_split("/\r\n/", $headers, -1, PREG_SPLIT_NO_EMPTY);
        $body = substr($response, $headerSize);
        return new HttpResponse($code, $headersArray, $body);
    }
}
