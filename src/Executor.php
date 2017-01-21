<?php
namespace PHPCurl\CurlHttp;

use PHPCurl\CurlWrapper\Curl;
use PHPCurl\CurlWrapper\CurlInterface;

class Executor implements ExecutorInterface
{
    /**
     * @var CurlInterface
     */
    private $curl;

    /**
     * @param CurlInterface $curl
     */
    public function __construct(CurlInterface $curl = null)
    {
        $this->curl = $curl ?: new Curl();
    }

    /**
     * @inheritdoc
     */
    public function execute($url, array $options)
    {
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_HEADER] = true;

        $this->curl->init($url);
        $this->curl->setOptArray($options);
        $response = $this->curl->exec();
        if (false === $response) {
            throw NoResponse::fromCurl($this->curl);
        }
        $info = $this->curl->getInfo();
        return HttpResponse::fromCurl($response, $info);
    }
}
