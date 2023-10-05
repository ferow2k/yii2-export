<?php

namespace Da\export\options;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\ODS\Writer;
use OpenSpout\Writer\ODS\Options;
use OpenSpout\Common\Entity\Cell;

class OdsOption extends SpoutOption
{
    protected Options $options;
    protected string $extension = '.ods';

    protected function createWriter()
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

    protected function addRow(array $row)
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
