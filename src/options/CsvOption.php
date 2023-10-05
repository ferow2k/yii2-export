<?php

namespace Da\export\options;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\CSV\Writer;
use OpenSpout\Writer\CSV\Options;

class CsvOption extends SpoutOption
{
    protected Options $options;
    protected string $extension = '.csv';

    protected function createWriter()
    {
        $this->options = new Options();
        $this->spout = new Writer($this->options);
    }
    
    protected function setOptions() {}

    protected function addRow(array $row)
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
