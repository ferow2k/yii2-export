<?php

namespace Da\export\options;

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\WriterMultiSheetsAbstract;
use Yii;
use Da\export\ExportMenu;

class XlsxOption extends SpoutOption
{
    public WriterMultiSheetsAbstract $spout;
    public string $extension = '.xlsx';

    public $firstRowWritten = false;

    public function createWriter()
    {
        $this->spout = WriterEntityFactory::createXLSXWriter();
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
        /*if (!$this->firstRowWritten) {
            $this->firstRowWritten = true;
            foreach ($row as $key => $value) {
                $length = max(10, $value ? strlen($value) : 0) * 1.2;
                $this->spout->setColumnWidth($length, $key);
            }
        }*/
        $this->spout->addRow(WriterEntityFactory::createRowFromArray($row));
    }
}
