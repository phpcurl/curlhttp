<?php
namespace PHPCurl\CurlHttp;

class HttpResponse
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $headers;

    /**
     * @param int $status
     * @param array $headers
     * @param string $body
     */
    public function __construct($status, array $headers, $body)
    {
        $this->status = (int) $status;
        $this->body = (string) $body;
        $this->headers = $headers;
    }

    /**
     * @param string $response
     * @param array $info
     * @return HttpResponse
     */
    public static function fromCurl($response, array $info)
    {
        $headerSize = $info['header_size'];
        $headers = substr($response, 0, $headerSize);
        return new self(
            $info['http_code'],
            preg_split("/\r\n/", $headers, -1, PREG_SPLIT_NO_EMPTY),
            substr($response, $headerSize)
        );
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
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
}
