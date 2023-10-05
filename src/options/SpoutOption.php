<?php

namespace Da\export\options;

use Da\export\options\OptionAbstract;
use Da\export\ExportMenu;
use OpenSpout\Writer\AbstractWriter;
use Yii;

abstract class SpoutOption extends OptionAbstract
{
    protected string $extension = '';

    protected $maxColumnLengths = [];

    protected AbstractWriter $spout;

    public function process()
    {
        $this->createWriter();
        $this->openWriter();
        $this->writeFile();
        $this->setOptions();
        $this->closeWriter();
    }

    public function openWriter()
    {
        switch ($this->target) {
            case ExportMenu::TARGET_SELF:
            case ExportMenu::TARGET_BLANK:
                Yii::$app->controller->layout = false;
                $this->spout->openToBrowser($this->filename . $this->extension);
                break;
            case ExportMenu::TARGET_QUEUE:
            default:
                Yii::$app->controller->layout = false;
                $this->spout->openToBrowser($this->filename . $this->extension);
                break;
        }
    }

    public function closeWriter()
    {
        $this->spout->close();
    }

    abstract protected function createWriter();

    abstract protected function setOptions();
}
