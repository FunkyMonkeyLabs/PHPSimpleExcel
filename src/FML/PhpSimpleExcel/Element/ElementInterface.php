<?php

namespace FML\PHPSimpleExcel\Element;

/**
 * @author Patryk Szlagowski [FunkyMonkeyLabs] <szlagowskipatryk@gmail.com>
 */
interface ElementInterface
{
    /**
     * @return string
     */
    public function render();
}