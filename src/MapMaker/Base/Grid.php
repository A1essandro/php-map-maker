<?php

namespace MapMaker\Base;

use MapMaker\Base\Abstraction\IGrid;

class Grid implements IGrid
{

    protected $sizeX = 0;
    protected $sizeY = 0;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct($x, $y)
    {
        $this->sizeX = $x;
        $this->sizeY = $y;
    }

    #region Iterator

    private $ix = 0;
    private $iy = 0;

    /**
     * @return int[]
     */
    public function current()
    {
        return array($this->ix, $this->iy);
    }

    public function key()
    {
        //В более поздних версиях PHP можно сделать так:
        //    return array($this->ix, $this->iy);
        //Но т.к. нужно поддерживать PHP v5.3, сделаем так:
        return $this->ix . '~' . $this->iy;
    }

    /**
     * Для единообразия парсинга координат, которые вернулись из $this->key()
     *
     * @param array $coordinates
     *
     * @return string
     */
    public function parseCoordinates($coordinates)
    {
        return explode('~', $coordinates);
    }

    public function next()
    {
        if ($this->ix < $this->sizeX - 1) {
            $this->ix++;
        } else {
            $this->ix = 0;
            $this->iy++;
        }
    }

    public function rewind()
    {
        $this->ix = $this->iy = 0;
    }

    public function valid()
    {
        return $this->ix < $this->sizeX && $this->iy < $this->sizeY;
    }

    /**
     * @return int
     */
    public function getSizeX()
    {
        return $this->sizeX;
    }

    /**
     * @return int
     */
    public function getSizeY()
    {
        return $this->sizeY;
    }

    #endregion

}