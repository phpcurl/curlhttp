<?php
namespace PHPCurl\CurlHttp;

/**
 * Minimalistic HTTP client
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * HttpClient constructor.
     * @param ExecutorInterface $executor
     */
    public function __construct(ExecutorInterface $executor = null)
    {
        $this->executor = $executor ?: new Executor();
    }

    /**
     * HTTP GET
     * @param string $url     Goes to curl_init()
     * @param array  $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function get($url, array $headers = [])
    {
        return $this->executor->execute(
            $url,
            [
                CURLOPT_HTTPHEADER => $headers,
            ]
        );
    }

    /**
     * HTTP HEAD (implemented using CURLOPT_NOBODY)
     * @param string $url     Goes to curl_init()
     * @param array  $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function head($url, array $headers = [])
    {
        return $this->executor->execute(
            $url,
            [
                CURLOPT_NOBODY => true,
                CURLOPT_HTTPHEADER => $headers,
            ]
        );
    }

    /**
     * HTTP POST
     * @param string       $url     Goes to curl_init()
     * @param string|array $data    Same as CURLOPT_POSTFIELDS
     * @param array        $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function post($url, $data = '', array $headers = [])
    {
        return $this->executor->execute(
            $url,
            [
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $data,
            ]
        );
    }

    /**
     * HTTP PUT
     * @param string       $url     Goes to curl_init()
     * @param string|array $data    Same as CURLOPT_POSTFIELDS
     * @param array        $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function put($url, $data = '', array $headers = [])
    {
        return $this->executor->execute(
            $url,
            [
                CURLOPT_CUSTOMREQUEST  => 'PUT',
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $data,
            ]
        );
    }

    /**
     * HTTP DELETE
     * @param string $url     Goes to curl_init()
     * @param array  $headers Same as CURLOPT_HEADER
     * @return HttpResponse
     */
    public function delete($url, array $headers = [])
    {
        return $this->executor->execute(
            $url,
            [
                CURLOPT_CUSTOMREQUEST  => 'DELETE',
                CURLOPT_HTTPHEADER => $headers,
            ]
        );
    }
}
