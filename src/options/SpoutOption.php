<?php

namespace Da\export\options;

use Da\export\options\OptionAbstract;

abstract class SpoutOption extends OptionAbstract
{
    public string $extension = '';

    protected $maxColumnLengths = [];

    protected function setOptions() {}
}
