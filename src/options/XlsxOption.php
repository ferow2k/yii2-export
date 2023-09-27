<?php

namespace Da\export\options;

use Yii;
use OpenSpout\Common\Type;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
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

        $this->spoutObject = $spoutObject;
        $this->writeFile();

        $spoutObject->close();
    }
}
