<?php

namespace Da\export\options;

interface OptionInterface
{
    public function createWriter();

    /**
     * @return bool
     */
    public function process();
}