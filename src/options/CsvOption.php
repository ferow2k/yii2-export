<?php

namespace Da\export\options;

use Yii;
use OpenSpout\Common\Type;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use Da\export\ExportMenu;

class CsvOption extends OptionAbstract
{
    public $extension = '.csv';

    /**
     * @inheritdoc
     */
    public function process()
    {
        //CSV object initialization
        $spoutObject = WriterEntityFactory::createCSVWriter();
        switch ($this->target) {
            case ExportMenu::TARGET_SELF:
            case ExportMenu::TARGET_BLANK:
                Yii::$app->controller->layout = false;
                $spoutObject->openToBrowser($this->filename . $this->extension);
                break;
            case ExportMenu::TARGET_QUEUE:
            default:
                Yii::$app->controller->layout = false;
                $spoutObject->openToBrowser($this->filename . $this->extension);
                break;
        }

        //header
        $headerRow = $this->generateHeader();
        if (!empty($headerRow)) {
            $spoutObject->addRow(WriterEntityFactory::createRow($headerRow));
        }

        //body
        $bodyRows = $this->generateBody();
        foreach ($bodyRows as $row) {
            $spoutObject->addRow(WriterEntityFactory::createRow($row));
        }

        //footer
        $footerRow = $this->generateFooter();
        if (!empty($footerRow)) {
            $spoutObject->addRow(WriterEntityFactory::createRow($footerRow));
        }

        $spoutObject->close();
    }
}
