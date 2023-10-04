<?php

namespace Da\export\options;

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\WriterMultiSheetsAbstract;
use Yii;
use Da\export\ExportMenu;

class OdsOption extends SpoutOption
{
    public WriterMultiSheetsAbstract $spout;
    public string $extension = '.ods';

    public function createWriter()
    {
        $this->spout = WriterEntityFactory::createODSWriter();
    }

    protected function setOptions()
    {
        foreach($this->maxColumnLengths as $key => $value) {
            $this->spout->setColumnWidth($key, $value);
        }
    }

    public function openToBrowser()
    {
        $this->spout->openToBrowser($this->filename . $this->extension);
    }

    public function process()
    {
        $this->createWriter();
        switch ($this->target) {
            case ExportMenu::TARGET_SELF:
            case ExportMenu::TARGET_BLANK:
                Yii::$app->controller->layout = false;
                $this->openToBrowser();
                break;
            case ExportMenu::TARGET_QUEUE:
            default:
                Yii::$app->controller->layout = false;
                $this->openToBrowser();
                break;
        }

        $this->writeFile();

        $this->setOptions();

        $this->spout->close();
    }

    public function addRow(array $row)
    {
        foreach($row as $key => $value) {
            $this->maxColumnLengths[$key] = max($this->maxColumnLengths[$key] ?? 0, strlen($value));
        }
        $this->spout->addRow(WriterEntityFactory::createRowFromArray($row));
    }
}
