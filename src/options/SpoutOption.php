<?php

namespace Da\export\options;

use Yii;
use Da\export\options\OptionAbstract;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use Da\export\ExportMenu;
use OpenSpout\Writer\WriterInterface;

class SpoutOption extends OptionAbstract
{
    public string $extension = '';
    public WriterInterface $spout;

    protected function createSpoutWriter(): WriterInterface
    {
        switch ($this->extension) {
            case '.csv':
                return WriterEntityFactory::createCSVWriter();
            case '.xlsx':
                return WriterEntityFactory::createXLSXWriter();
            case '.ods':
                return WriterEntityFactory::createODSWriter();
            default:
                throw new \Exception('Invalid type');
        }
    }

    public function process()
    {
        $this->spout = $this->createSpoutWriter();
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

        $this->writeFile();
        $this->spout->close();
    }

    public function addRow(array $row)
    {
        $this->spout->addRow(WriterEntityFactory::createRowFromArray($row));
    }
}
