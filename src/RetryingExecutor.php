<?php
namespace PHPCurl\CurlHttp;

class RetryingExecutor implements ExecutorInterface
{
    /**
     * @var int
     */
    private $retries;

    /**
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * @param int               $retries
     * @param ExecutorInterface $executor
     */
    public function __construct($retries, ExecutorInterface $executor)
    {
        $retries = (int) $retries;
        if ($retries < 1) {
            throw new \InvalidArgumentException('Number of retries must be positive');
        }
        $this->retries = $retries;
        $this->executor = $executor;
    }

    /**
     * @inheritdoc
     */
    public function execute($url, array $options)
    {
        for ($attempt = 0; $attempt <= $this->retries; $attempt++) {
            try {
                return $this->executor->execute($url, $options);
            } catch (NoResponse $exception) {
            }
        }
        throw new NoResponse("No response after $attempt attempts", 0, $exception);
    }
}
