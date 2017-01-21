<?php
namespace PHPCurl\CurlHttp;

use PHPCurl\CurlWrapper\CurlInterface;

class NoResponse extends \RuntimeException
{
    public static function fromCurl(CurlInterface $curl)
    {
        return new self($curl->error(), $curl->errno());
    }
}
