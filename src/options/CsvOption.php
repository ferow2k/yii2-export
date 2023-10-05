<?php

namespace Da\export\options;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\CSV\Writer;
use OpenSpout\Writer\CSV\Options;
use Yii;
use Da\export\ExportMenu;
use OpenSpout\Common\Entity\Cell;

class CsvOption extends SpoutOption
{
    public Writer $spout;
    public Options $options;
    public string $extension = '.csv';

    public function createWriter()
    {
        $this->options = new Options();
        $this->spout = new Writer($this->options);
    }
    
    protected function setOptions() {}

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
    
    public function process()
    {
        $this->createWriter();
        
        $this->openWriter();

        $this->writeFile();

        $this->setOptions();

        $this->spout->close();
    }

    public function addRow(array $row)
    {
        $row = array_map(function ($value) {
            if ($value instanceof \DateTime) {
                return $value->format('Y-m-d H:i:s');
            } else {
                return $value;
            }
        }, $row);
        $this->spout->addRow(Row::fromValues($row));
    }
}
