<?php

namespace MapMaker;


use InvalidArgumentException;
use MapMaker\Base\Abstraction\ICell;
use MapMaker\Base\Abstraction\LayerVisualiser;

class WaterlineGDVisualiser extends LayerVisualiser
{

    /**
     * Внутренняя реализация рендера ячейки
     *
     * @param ICell $cell
     *
     * @return mixed
     */
    protected function render(ICell $cell)
    {
        if (!$cell instanceof WaterlineCell) {
            throw new InvalidArgumentException('First argument must be WaterlineCell');
        }

        return $this->getColor($cell);
    }

    private function getColor(WaterlineCell $cell)
    {
        if (!$cell->isWater()) {
            $r = (int)round($cell->percentFromMax * 200);
            $g = 200 - (int)round($cell->percentFromMax * 75);
            return imagecolorallocate($this->map->getVisualiser()->getImageResource(), $r, $g, 30);
        } else {
            $ratio = $cell->percentFromMax > 1 ? 1 : $cell->percentFromMax;
            $c = (int)round(240 - $ratio * 240);
            $b = 255;
            return imagecolorallocate($this->map->getVisualiser()->getImageResource(), $c, $c, $b);
        }
    }
}