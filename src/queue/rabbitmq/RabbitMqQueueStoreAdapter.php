<?php
namespace Da\export\queue\rabbitmq;

use Da\export\queue\QueueStoreAdapterInterface;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\BaseObject;

class RabbitMqQueueStoreAdapter extends BaseObject implements QueueStoreAdapterInterface
{
    /**
     * @var string the queue name
     */
    public $queueName;

    /**
     * @var string
     */
    public $routingKey;
    /**
     * @var RabbitMqQueueStoreConnection
     */
    public $connection;

    /** @var bool */
    public $passive = false;

    /** @var bool */
    public $durable = false;

    /** @var bool */
    public $exclusive = false;

    /** @var bool */
    public $auto_delete = true;

    /** @var bool */
    public $nowait = false;

    /** @var bool */
    public $arguments = null;

    /** @var bool */
    public $ticket = null;

    /** @var bool */
    public $persistent = false;

    /**
     * @var array
     */
    public $configuration = [];

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
            $this->connection = new RabbitMqQueueStoreConnection($this->configuration);
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
        $this->getConnection()->getInstance()->channel()->queue_declare(
            $this->queueName,
            $this->passive,
            $this->durable,
            $this->exclusive,
            $this->auto_delete,
            $this->nowait,
            $this->arguments,
            $this->ticket
        );

        $properties = [];
        if ($this->persistent) {
            $properties = [
                'delivery_mode' => 2
            ];
        }

        $msg = new AMQPMessage($message, $properties);

        $this->getConnection()->getInstance()->channel()->basic_publish($msg, '', $this->queueName, $this->routingKey);
    }
}
