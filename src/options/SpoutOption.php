<?php

namespace Da\export\options;

use CurlMultiHandle;
use Yii;
use Da\export\options\OptionAbstract;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use Da\export\ExportMenu;
use OpenSpout\Writer\Common\Entity\Options;
use OpenSpout\Writer\WriterInterface;
use OpenSpout\Writer\WriterMultiSheetsAbstract;

abstract class SpoutOption extends OptionAbstract
{
    public string $extension = '';

    protected $maxColumnLengths = [];

    protected function setOptions() {}
}
