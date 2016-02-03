#CurlHttp 
The simplest cURL HTTP client for PHP

##Install
Via [composer](https://getcomposer.org):
`$ composer require "phpcurl/curlhttp"`

##Usage

It is really that easy.

```php
<?php
require_once 'vendor/autoload.php';
use PHPCurl\CurlHttp\HttpClient;

$http = new HttpClient();

$http->setOptions(array(
    CURLOPT_FOLLOWLOCATION => false, // Any arbitrary curl options you want
));

$response = $http->post('http://example.com/?a=b', 'my post data', ['User-Agent: My php crawler']);
// Supported: get(), post(), head(), post(), put(), delete(), custom methods

$body = $response->getBody(); // Response body, string

/*

<!doctype html>
<html>
...
</html>

 */

$statusCode = $response->getStatus(); // HTTP status, int

/*

200

 */

$headers = $response->getHeaders(); // HTTP response headers, array
/*

array(
  0 => 'HTTP/1.1 200 OK',
  1 => 'Accept-Ranges: bytes',
  2 => 'Cache-Control: max-age=604800',
  3 => 'Content-Type: text/html',
  4 => 'Date: Wed, 03 Feb 2016 07:01:58 GMT',
  5 => 'Etag: "359670651"',
  6 => 'Expires: Wed, 10 Feb 2016 07:01:58 GMT',
  7 => 'Last-Modified: Fri, 09 Aug 2013 23:54:35 GMT',
  8 => 'Server: ECS (rhv/818F)',
  9 => 'Vary: Accept-Encoding',
  10 => 'X-Cache: HIT',
  11 => 'x-ec-custom-error: 1',
  12 => 'Content-Length: 1270',
);

 */
```
