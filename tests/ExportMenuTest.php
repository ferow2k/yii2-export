<?php

namespace tests;

use Da\export\ExportMenu;
use Da\export\queue\rabbitmq\RabbitMqQueueStoreAdapter;
use Yii;

use PHPUnit\Framework\TestCase;

class ExportMenuTest extends TestCase
{
    /**
     *
     */
    public function testWidget()
    {
        Yii::$app = new \yii\web\Application([
            'id' => 'test',
            'basePath' => __DIR__,
            'vendorPath' => __DIR__ . '/../vendor',
            'components' => [
                'request' => \yii\web\Request::class,
            ],
        ]);
        Yii::$app->controller = new \yii\base\Controller('test', Yii::$app);

        $actual = ExportMenu::widget([]);
        $expected = file_get_contents(__DIR__ . '/data/test-form.bin');
        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenQueueNameIsNotSet()
    {
        ExportMenu::widget([
            'target' => ExportMenu::TARGET_QUEUE,
            'queueConfig' => [

            ]
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenQueueAdapterIsNotSet()
    {
        ExportMenu::widget([
            'target' => ExportMenu::TARGET_QUEUE,
            'queueConfig' => [
                'queueName' => 'test'
            ]
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenQueueMessageIsNotSet()
    {
        ExportMenu::widget([
            'target' => ExportMenu::TARGET_QUEUE,
            'queueConfig' => [
                'queueName' => 'test',
                'queueAdapter' => RabbitMqQueueStoreAdapter::class,
            ]
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenQueueAdapterIsSetWrong()
    {
        ExportMenu::widget([
            'target' => ExportMenu::TARGET_QUEUE,
            'queueConfig' => [
                'queueName' => 'test',
                'queueAdapter' => 'test',
            ]
        ]);
    }
}
