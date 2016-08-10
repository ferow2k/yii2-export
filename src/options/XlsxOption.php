<?php

namespace Da\export\options;

use Yii;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
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
        $spoutObject = WriterFactory::create(Type::XLSX);
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
            $spoutObject->addRow($headerRow);
        }

        //body
        $bodyRows = $this->generateBody();
        // $bodyRows = $this->dataProvider->query->asArray()->all();
        // foreach ($bodyRows as $row) {
        //     $spoutObject->addRow($row);
        // }

        $spoutObject->addRows($bodyRows); // add multiple rows at a time

        //footer
        $footerRow = $this->generateFooter();
        if (!empty($footerRow)) {
            $spoutObject->addRow($footerRow);
        }

        $spoutObject->close();
    }
}
