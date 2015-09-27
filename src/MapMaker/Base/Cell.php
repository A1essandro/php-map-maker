<?php

namespace MapMaker\Base;

use MapMaker\Base\Abstraction\Layer;

/**
 * Ячейка
 */
class Cell implements Abstraction\ICell
{

    /**
     * @var Layer
     */
    public $layer = null;

    function __construct(Layer $layer)
    {
        $this->layer = $layer;
    }

}
