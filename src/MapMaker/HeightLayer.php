<?php

namespace MapMaker;

use ArrayAccess;
use Exception;
use MapMaker\Base\Abstraction\IMap;
use MapMaker\Base\Abstraction\Layer;
use SplFixedArray;

class HeightLayer extends Layer
{

    private $unpreparedPoints;

    /**
     * @param IMap  $map
     * @param float[][] $heightsArray 2d array
     */
    public function __construct(IMap $map, ArrayAccess $heightsArray)
    {
        parent::__construct($map);
        if($this->checkInputArraySizes($heightsArray))
            $this->unpreparedPoints = $heightsArray;
    }

    private function checkInputArraySizes($array)
    {
        $x = $this->map->getGrid()->getSizeX();
        $y = $this->map->getGrid()->getSizeY();
        if (empty($array[0][0]) || count($array) < $x || count($array[0]) < $y)
            throw new Exception(printf('Heightmap sizes must be more than %dx%d', $x, $y));
        return true;
    }

    /**
     * Метод должен генерировать слой на основе заданных до этого параметров
     */
    public function generate()
    {
        if(count($this->unpreparedPoints) != $this->map->getGrid()->getSizeX()
            || count($this->unpreparedPoints[0]) != $this->map->getGrid()->getSizeY())
            $this->cut(0, 0);

        foreach ($this->map->getGrid() as $coordinates) {
            list($x, $y) = $coordinates;
            $height = $this->unpreparedPoints[$x][$y];
            $this->setCell($x, $y, new HeightCell($this, $height));
        }
    }

    public function cut($startX, $startY)
    {
        $unpreparedX = count($this->unpreparedPoints);
        $unpreparedY = count($this->unpreparedPoints[0]);

        $newUnpreparedPoints = new SplFixedArray($this->map->getGrid()->getSizeX());

        if($unpreparedX - $startX < $this->map->getGrid()->getSizeX() ||
            $unpreparedY - $startY < $this->map->getGrid()->getSizeY())
            throw new Exception("Small map");


        foreach ($this->map->getGrid() as $coordinates) {
            list($ix, $iy) = $coordinates;

            if(empty($newUnpreparedPoints[$ix]))
                $newUnpreparedPoints[$ix] = new SplFixedArray($this->map->getGrid()->getSizeY());

            $newUnpreparedPoints[$ix][$iy] = $this->unpreparedPoints[$startX + $ix][$startY + $iy];
        }

        $this->unpreparedPoints = $newUnpreparedPoints;
    }

}