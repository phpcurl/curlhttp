<?php
namespace PHPCurl\CurlHttp;

use Weew\HttpServer\HttpServer;

class HttpClientFunctionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpServer
     */
    private $server;

    /**
     * @var HttpClient
     */
    private $client;

    public function setUp()
    {
        $this->server = new HttpServer('localhost', 8080, __DIR__.'/helper/server.php');
        $this->server->disableOutput();
        $this->server->start();

        $this->client =  new HttpClient();
    }

    public function tearDown()
    {
        $this->server->stop();
    }

    public function testGet()
    {
        $response = $this->client->get('http://localhost:8080/yo', ['Foo: Bar']);
        $server = $this->getRequestData($response);
        $this->assertEquals('/yo', $server['uri']);
        $this->assertEquals('Bar', $server['headers']['Foo']);
        $this->assertEquals('GET', $server['method']);
        $this->assertEquals(null, $server['raw_post']);
    }

    public function testHead()
    {
        $response = $this->client->head('http://localhost:8080/yo', ['Foo: Bar']);
        $server = $this->getRequestData($response);
        $this->assertEquals('/yo', $server['uri']);
        $this->assertEquals('Bar', $server['headers']['Foo']);
        $this->assertEquals('HEAD', $server['method']);
        $this->assertEquals(null, $server['raw_post']);
    }

    public function testPost()
    {
        $response = $this->client->post('http://localhost:8080/yo', 'boo', ['Foo: Bar']);
        $server = $this->getRequestData($response);
        $this->assertEquals('/yo', $server['uri']);
        $this->assertEquals('Bar', $server['headers']['Foo']);
        $this->assertEquals('POST', $server['method']);
        $this->assertEquals('boo', $server['raw_post']);
    }

    public function testPut()
    {
        $response = $this->client->put('http://localhost:8080/yo', 'boo', ['Foo: Bar']);
        $server = $this->getRequestData($response);
        $this->assertEquals('/yo', $server['uri']);
        $this->assertEquals('Bar', $server['headers']['Foo']);
        $this->assertEquals('PUT', $server['method']);
        $this->assertEquals('boo', $server['raw_post']);
    }

    public function testDelete()
    {
        $response = $this->client->delete('http://localhost:8080/yo', ['Foo: Bar']);
        $server = $this->getRequestData($response);
        $this->assertEquals('/yo', $server['uri']);
        $this->assertEquals('Bar', $server['headers']['Foo']);
        $this->assertEquals('DELETE', $server['method']);
        $this->assertEquals(null, $server['raw_post']);
    }

    public function testCustom()
    {
        $response = $this->client->request('OPTIONS', 'http://localhost:8080/yo', 'boo', ['Foo: Bar']);
        $server = $this->getRequestData($response);
        $this->assertEquals('/yo', $server['uri']);
        $this->assertEquals('Bar', $server['headers']['Foo']);
        $this->assertEquals('OPTIONS', $server['method']);
        $this->assertEquals('boo', $server['raw_post']);
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
    }

}
