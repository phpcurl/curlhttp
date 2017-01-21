<?php
namespace PHPCurl\CurlHttp;

interface ExecutorInterface
{
    /**
     * Init curl with $url, set $options, execute, return response
     * @param string $url     Goes to curl_init()
     * @param array  $options Goes to curl_setopt()
     * @return HttpResponse
     * @throws NoResponse If no response was received
     */
    public function execute($url, array $options);
}
