<?php

namespace Da\export\options;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\ODS\Writer;
use OpenSpout\Writer\ODS\Options;
use Yii;
use Da\export\ExportMenu;
use OpenSpout\Common\Entity\Cell;

class OdsOption extends SpoutOption
{
    public Writer $spout;
    public Options $options;
    public string $extension = '.ods';

    public function createWriter()
    {
        $this->options = new Options();
        $this->spout = new Writer($this->options);
    }

    protected function setOptions()
    {
        foreach ($this->maxColumnLengths as $key => $value) {
            $this->options->setColumnWidth($value, $key + 1);
        }
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
        $cells = [];
        foreach ($row as $key => $value) {
            $cellStyle = null;
            if (is_string($value)) {
                $this->maxColumnLengths[$key] = min(max($this->maxColumnLengths[$key] ?? 0, (!empty($value) ? strlen($value) : 0) * 8), 800);
            }
            if ($value instanceof \DateTime) {
                $cellStyle = (new Style())->setFormat('dd/mm/yyyy');
            }
            $cells[] = Cell::fromValue($value, $cellStyle);
        }
        $this->spout->addRow(new Row($cells));
    }
}
