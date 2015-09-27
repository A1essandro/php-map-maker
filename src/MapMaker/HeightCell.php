<?php

namespace MapMaker;

use MapMaker\Base\Abstraction\Layer;
use MapMaker\Base\Cell;
use InvalidArgumentException;

/**
 * Description of MapMakerMapCell
 *
 * @author Alexander Yermakov
 */
class HeightCell extends Cell
{

    protected $height = 0;

    function __construct(Layer $layer, $height = 0)
    {
        $this->height = $height;
        parent::__construct($layer);
    }

    function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $round
     *
     * @return float
     * @throws InvalidArgumentException
     */
    function getRoundHeight($round = 0)
    {
        if (!is_int($round)) {
            throw new InvalidArgumentException('Must be int');
        }

        return round($this->height, $round);
    }

    function setHeight($height)
    {
        $this->height = $height;
    }

}
