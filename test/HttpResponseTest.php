<?php
namespace PHPCurl\CurlHttp;

class HttpResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $response = new HttpResponse(200, array('Age: 42'), 'Hey');
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(array('Age: 42'), $response->getHeaders());
        $this->assertEquals('Hey', $response->getBody());
    }
}
