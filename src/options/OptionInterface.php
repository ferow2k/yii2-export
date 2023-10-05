<?php

namespace Da\export\options;

interface OptionInterface
{
    /**
     * @return bool
     */
    public function process();
}