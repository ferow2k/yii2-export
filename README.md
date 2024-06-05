# Just another Yii2 Export Widget for Yii2

[![Latest Version](https://img.shields.io/github/tag/ericmaicon/yii2-export.svg?style=flat-square&label=release)](https://github.com/ericmaicon/yii2-export/tags)
[![Build Status](https://img.shields.io/travis/ericmaicon/yii2-export/master.svg?style=flat-square)](https://travis-ci.org/ericmaicon/yii2-export)

The main purpose of this library is not replace [kartik-v/yii2-export](https://github.com/kartik-v/yii2-export). Kartik's one has a lot of features not implemented on this one.

This is a new yii2-export widget wrote from the scratch to improve performance.

The idea of this one appeared when PHPExcel doesn't fit to generate large excel files in a fast way. The first feature was dispatch reports to be generated in queues.

Not enough, another feature was replace PHPExcel with [spout](https://github.com/box/spout), successfully reducing the time consuming.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require ericmaicon/yii2-export:*
```

or add

```
"ericmaicon/yii2-export": "*"
```

to the `require` section of your `composer.json` file.

## Usage

Using with the Grid:

```
<?= \Da\export\GridView::widget([
    'dataProvider' => $dataProvider,
]); ?>
```

To use Kartik's grid you will need to override renderExport method:

```
<?php

use Da\export\ExportMenu;

class GridView extends \kartik\grid\GridView
{
    public function renderExport()
    {
        return ExportMenu::widget([
            'dataProvider' => $this->dataProvider,
            'columns' => $this->columns,
        ]);
    }
}
```

Stand-alone use:

```

```

Another configurations:

*Queue*

```
[
    'target' => \Da\export\ExportMenu::TARGET_QUEUE,
    'queueConfig' => [
        'queueName' => \common\models\ReportModel::REPORT_TUBE,
        'queueAdapter' => \Da\export\queue\rabbitmq\RabbitMqQueueStoreAdapter::className(),
        'queueMessage' => function () {

        }
    ]
]
```

*Target*

```
[
    'target' => \Da\export\ExportMenu::TARGET_SELF,
]
```

*Filename*

```
[
    'filename' => 'test',
]
```

*Export Footer*

```
[
    'exportFooter' => true,
]
```

*Options*

```
[
    'class' => 'btn-group',
]
```

*Dropdown Options*

```
[
    'class' => 'btn btn-secondary',
    'label' => 'Export',
    'menuOptions' => [
        'class' => 'dropdown-menu dropdown-menu-right'
    ]
]
```

*Dropdown Items*

```
[
    ExportMenu::FORMAT_CSV => [
        'label' => 'CSV',
        'options' => [
            'title' => 'Comma Separated Values',
            'data-id' => ExportMenu::FORMAT_CSV,
        ],
        'url' => 'javascript:;',
        'className' => CsvOption::className(),
    ]
]
```

*Selected Option*

```
[
    'selectedOption' => ExportMenu::FORMAT_CSV,
]
```

## Testing

```bash
$ ./vendor/bin/phpunit
```

## What is missing?

1. PDF, HTML and TXT export options
2. Confirm Alert
3. Column selectors
4. Store file
5. Events
6. Internationalization