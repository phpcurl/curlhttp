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
     * HttpResponse constructor.
     * @param int $status
     * @param array $headers
     * @param string $body
     */
    public function __construct($status, array $headers, $body)
    {
        $this->status = $status;
        $this->body = $body;
        $this->headers = $headers;
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
