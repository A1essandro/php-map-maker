<?php

namespace MapMaker\Base\Abstraction;


use Iterator;

interface IGrid extends Iterator
{

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct($x, $y);

    /**
     * @return int
     */
    public function getSizeX();

    /**
     * @return int
     */
    public function getSizeY();

}