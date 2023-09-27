<?php

namespace Da\export\options;

use Yii;
use OpenSpout\Common\Type;
use OpenSpout\Common\Creator\WriterEntityFactory;
use Da\export\ExportMenu;

class XlsxOption extends OptionAbstract
{
    public $extension = '.xlsx';

    /**
     * @inheritdoc
     */
    public function process()
    {
        //XLSX object initialization
        $spoutObject = WriterEntityFactory::createXLSXWriter();
        switch ($this->target) {
            case ExportMenu::TARGET_SELF:
            case ExportMenu::TARGET_BLANK:
                Yii::$app->controller->layout = false;
                $spoutObject->openToBrowser($this->filename . $this->extension);
                // $spoutObject->openToFile('/tmp/testexcel2.xlsx'); // write data to a file or to a PHP stream
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
        $bodyRows = $this->dataProvider->query->asArray()->all();
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
