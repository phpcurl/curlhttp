<?php
namespace PHPCurl\CurlHttp;

/**
 * A simple HTTP response
 */
class HttpResponse
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $info;

    /**
     * HttpResponse constructor.
     * @param string $response
     * @param array $curlInfo
     */
    public function __construct($response, array $curlInfo)
    {
        $this->info = $curlInfo;
        $this->code = $curlInfo['http_code'];
        $headerSize = $curlInfo['header_size'];
        $headers = substr($response, 0, $headerSize);
        $this->headers = preg_split("/\r\n/", $headers, -1, PREG_SPLIT_NO_EMPTY);
        $this->body = substr($response, $headerSize);
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get curl_info array
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }
}
