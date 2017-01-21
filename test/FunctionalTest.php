<?php
namespace PHPCurl\CurlHttp;

use Symfony\Component\Process\Process;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var Process
     */
    private static $server;

    public static function setUpBeforeClass()
    {
        if (strpos(phpversion(), 'hhvm') !== false) {
            self::markTestSkipped('No hhvm support');
        }

        self::$server = new Process("php -S localhost:8080 -a " . escapeshellarg(__DIR__  . "/helper/server.php"));
        self::$server->start();
        sleep(1);
    }

    public static function tearDownAfterClass()
    {
        if (isset(self::$server)) {
            self::$server->stop();
        }
    }

    protected function setUp()
    {
        $this->client =  new HttpClient();
    }

    public function testGet()
    {
        $response = $this->client->get('http://localhost:8080/yo', ['Foo: Bar']);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(
            [
                'uri' => '/yo',
                'headers' => [
                    'Foo' => 'Bar',
                    'Host' => 'localhost:8080',
                    'Accept' => '*/*',
                ],
                'method' => 'GET',
                'post' => [],
                'raw_post' => null,
            ],
            $this->getRequestData($response)
        );
    }

    public function testHead()
    {
        $response = $this->client->head('http://localhost:8080/yo', ['Foo: Bar']);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(
            [
                'uri' => '/yo',
                'headers' => [
                    'Foo' => 'Bar',
                    'Host' => 'localhost:8080',
                    'Accept' => '*/*',
                ],
                'method' => 'HEAD',
                'post' => [],
                'raw_post' => null,
            ],
            $this->getRequestData($response)
        );
    }

    public function testPost()
    {
        $response = $this->client->post('http://localhost:8080/yo', 'foo=bar', ['Foo: Bar']);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(
            [
                'uri' => '/yo',
                'headers' => [
                    'Foo' => 'Bar',
                    'Host' => 'localhost:8080',
                    'Accept' => '*/*',
                    'Content-Length' => '7',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'method' => 'POST',
                'post' => [
                    'foo' => 'bar',
                ],
                'raw_post' => 'foo=bar',
            ],
            $this->getRequestData($response)
        );
    }

    public function testPut()
    {
        $response = $this->client->put('http://localhost:8080/yo', 'boo', ['Foo: Bar']);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(
            [
                'uri' => '/yo',
                'headers' => [
                    'Foo' => 'Bar',
                    'Host' => 'localhost:8080',
                    'Accept' => '*/*',
                    'Content-Length' => '3',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'method' => 'PUT',
                'post' => [],
                'raw_post' => 'boo',
            ],
            $this->getRequestData($response)
        );
    }

    public function testDelete()
    {
        $response = $this->client->delete('http://localhost:8080/yo', ['Foo: Bar']);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(
            [
                'uri' => '/yo',
                'headers' => [
                    'Foo' => 'Bar',
                    'Host' => 'localhost:8080',
                    'Accept' => '*/*',
                ],
                'method' => 'DELETE',
                'post' => [],
                'raw_post' => null,
            ],
            $this->getRequestData($response)
        );
    }

    /**
     * @expectedException \PHPCurl\CurlHttp\NoResponse
     */
    public function testException()
    {
        $response = $this->client->delete('http://localhost:0', ['Foo: Bar']);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(
            [
                'uri' => '/yo',
                'headers' => [
                    'Foo' => 'Bar',
                    'Host' => 'localhost:8080',
                    'Accept' => '*/*',
                ],
                'method' => 'DELETE',
                'post' => [],
                'raw_post' => null,
            ],
            $this->getRequestData($response)
        );
    }

    /**
     * @param HttpResponse $response
     * @return array
     */
    private function getRequestData(HttpResponse $response)
    {
        $prefix = 'Request-Data: ';
        foreach ($response->getHeaders() as $header) {
            if (0 === strpos($header, $prefix)) {
                return unserialize(substr($header, strlen($prefix)));
            }
        }
        throw new \LogicException();
    }
}
