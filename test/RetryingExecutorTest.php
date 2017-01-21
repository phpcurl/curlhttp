<?php
namespace PHPCurl\CurlHttp;

class RetryingExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Number of retries must be positive
     */
    public function testConstructorChecksArgs()
    {
        new RetryingExecutor(0, $this->getMockForAbstractClass(ExecutorInterface::class));
    }

    public function successfulCases()
    {
        return [
            [1, 1],
            [1, 0],
            [10, 0],
            [10, 1],
            [10, 9],
        ];
    }

    public function unsuccessfulCases()
    {
        return [
            [1],
            [10],
        ];
    }

    /**
     * @dataProvider successfulCases
     * @param $retries
     * @param $fails
     */
    public function testSuccessfulResponse($retries, $fails)
    {
        $url = 'https://example.com';
        $options = [1 => true];
        $executor = $this->getMockForAbstractClass(ExecutorInterface::class);
        for ($i = 0; $i < $fails; $i++) {
            $executor->expects($this->at($i))
                ->method('execute')
                ->with($url, $options)
                ->willThrowException(new NoResponse());
        }
        $response = new HttpResponse(200, [], 'ok');
        $executor->expects($this->at($fails))
            ->method('execute')
            ->with($url, $options)
            ->willReturn($response);
        $re = new RetryingExecutor($retries, $executor);
        $this->assertEquals($response, $re->execute($url, $options));
    }

    /**
     * @dataProvider unsuccessfulCases
     * @param $retries
     * @expectedException \PHPCurl\CurlHttp\NoResponse
     * @expectedExceptionMessageRegExp /No response after \d+ attempts/
     */
    public function testNoResponse($retries)
    {
        $url = 'https://example.com';
        $options = [1 => true];
        $executor = $this->getMockForAbstractClass(ExecutorInterface::class);
        $executor->method('execute')
            ->with($url, $options)
            ->willThrowException(new NoResponse());
        $re = new RetryingExecutor($retries, $executor);
        $re->execute($url, $options);
    }
}
