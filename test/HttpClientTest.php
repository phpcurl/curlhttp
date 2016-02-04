<?php
namespace PHPCurl\CurlHttp;


class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $curl = $this->getMockBuilder('PHPCurl\\CurlWrapper\\Curl')
            ->disableOriginalConstructor()
            ->setMethods(array('init', 'exec', 'setOptArray', 'getInfo', '__destruct'))
            ->getMock();

        $curl->expects($this->once())
            ->method('init')
            ->with('http://example.com');

        $curl->expects($this->once())
            ->method('exec')
            ->willReturn("Age: 42\r\n\r\nHey");

        $curlInfo = array(
            'http_code' => 200,
            'header_size' => 11
        );

        $curl->expects($this->once())
            ->method('getInfo')
            ->willReturn($curlInfo);

        $curl->expects($this->once())
            ->method('setOptArray')
            ->with(array(
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_NOBODY => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
            ));

        $client = new HttpClient();
        $client->setOptions(array(
            CURLOPT_BINARYTRANSFER => true,
        ));

        $response = $client->exec('http://example.com', array(CURLOPT_NOBODY => true), $curl);

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(array('Age: 42'), $response->getHeaders());
        $this->assertEquals('Hey', $response->getBody());
        $this->assertEquals($curlInfo, $response->getInfo());
    }

}
