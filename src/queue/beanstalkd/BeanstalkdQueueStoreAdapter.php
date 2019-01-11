<?php
namespace Da\export\queue\beanstalkd;

use Da\export\queue\JobInterface;
use Da\export\queue\QueueStoreAdapterInterface;
use Pheanstalk\Pheanstalk;
use yii\base\BaseObject;

class BeanstalkdQueueStoreAdapter extends BaseObject implements QueueStoreAdapterInterface
{
    /**
     * @var string the queue name
     */
    public $queueName;

    /**
     * @var int delay
     */
    public $delay = 0;

    /**
     * @var int
     */
    public $timeToRun;

    /**
     * @var array
     */
    public $configuration = [];

    /**
     * @var BeanstalkdQueueStoreConnection
     */
    protected $connection;

    /**
     * @return BeanstalkdQueueStoreAdapter
     */
    public function init()
    {
        parent::init();

        $this->getConnection()->connect();
    }

    /**
     * @return BeanstalkdQueueStoreConnection
     */
    public function getConnection()
    {
        if (empty($this->connection)) {
            $this->connection = new BeanstalkdQueueStoreConnection($this->configuration);
        }

        return $this->connection;
    }

    /**
     * @param string $message
     *
     * @return int
     */
    public function enqueue($message)
    {
        return $this->getConnection()
            ->getInstance()
            ->useTube($this->queueName)
            ->put($message, Pheanstalk::DEFAULT_PRIORITY, $this->delay, $this->timeToRun);
    }
}
