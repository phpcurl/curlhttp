<?php
namespace PHPCurl\CurlHttp;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClient
     */
    private $http;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $executor;

    protected function setUp()
    {
        $this->executor = $this->getMockForAbstractClass(ExecutorInterface::class);
        $this->http = new HttpClient($this->executor);
    }

    public function testGet()
    {
        $response = new HttpResponse(200, [], 'ok');
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(
                'http://example.com',
                [
                    CURLOPT_HTTPHEADER => ['Foo: Bar']
                ]
            )
            ->willReturn($response);
        $this->assertEquals(
            $response,
            $this->http->get('http://example.com', ['Foo: Bar'])
        );
    }

    public function testHead()
    {
        $response = new HttpResponse(200, [], 'ok');
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(
                'http://example.com',
                [
                    CURLOPT_HTTPHEADER => ['Foo: Bar'],
                    CURLOPT_NOBODY => true,
                ]
            )
            ->willReturn($response);
        $this->assertEquals(
            $response,
            $this->http->head('http://example.com', ['Foo: Bar'])
        );
    }

    public function testPost()
    {
        $response = new HttpResponse(200, [], 'ok');
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(
                'http://example.com',
                [
                    CURLOPT_HTTPHEADER => ['Foo: Bar'],
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => 'foo',
                ]
            )
            ->willReturn($response);
        $this->assertEquals(
            $response,
            $this->http->post('http://example.com', 'foo', ['Foo: Bar'])
        );
    }

    public function testPut()
    {
        $response = new HttpResponse(200, [], 'ok');
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(
                'http://example.com',
                [
                    CURLOPT_HTTPHEADER => ['Foo: Bar'],
                    CURLOPT_CUSTOMREQUEST => 'PUT',
                    CURLOPT_POSTFIELDS => 'foo',
                ]
            )
            ->willReturn($response);
        $this->assertEquals(
            $response,
            $this->http->put('http://example.com', 'foo', ['Foo: Bar'])
        );
    }

    public function testDelete()
    {
        $response = new HttpResponse(200, [], 'ok');
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(
                'http://example.com',
                [
                    CURLOPT_HTTPHEADER => ['Foo: Bar'],
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                ]
            )
            ->willReturn($response);
        $this->assertEquals(
            $response,
            $this->http->delete('http://example.com', ['Foo: Bar'])
        );
    }
}
