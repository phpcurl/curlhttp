<?php
namespace PHPCurl\CurlHttp;

interface HttpClientInterface
{
    /**
     * HTTP GET
     * @param string $url     Goes to curl_init()
     * @param array  $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function get($url, array $headers = []);

    /**
     * HTTP HEAD (implemented using CURLOPT_NOBODY)
     * @param string $url     Goes to curl_init()
     * @param array  $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function head($url, array $headers = []);

    /**
     * HTTP POST
     * @param string       $url     Goes to curl_init()
     * @param string|array $data    Same as CURLOPT_POSTFIELDS
     * @param array        $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function post($url, $data = '', array $headers = []);

    /**
     * HTTP PUT
     * @param string       $url     Goes to curl_init()
     * @param string|array $data    Same as CURLOPT_POSTFIELDS
     * @param array        $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function put($url, $data = '', array $headers = []);

    /**
     * HTTP DELETE
     * @param string $url     Goes to curl_init()
     * @param array  $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function delete($url, array $headers = []);
}
