<?php

namespace MapMaker;

use MapMaker\Base\Abstraction\ICell;
use MapMaker\Base\Abstraction\LayerVisualiser;
use InvalidArgumentException;

class WaterlineHtmlVisualiser extends LayerVisualiser
{

    /**
     * Внутренняя реализация рендера ячейки
     *
     * @param ICell $cell
     *
     * @return string
     */
    protected function render(ICell $cell)
    {
        if (!$cell instanceof WaterlineCell) {
            throw new InvalidArgumentException('First argument must be WaterlineCell');
        }

        $color = self::getColor($cell);

        return '<div class="layer" title="' . (int)round($cell->getHeight())
        . '" style="width:100%; background: #' . $color . ';"></div>';
    }

    /**
     * Определение цвета точки, в зависимости от высоты над уровнем воды
     *
     * @param WaterlineCell $cell
     *
     * @return string
     */
    private static function getColor(WaterlineCell $cell)
    {
        if (!$cell->isWater()) {
            $r = dechex(round($cell->percentFromMax * 12));
            $g = dechex(12 - round($cell->percentFromMax * 5));
            $color = $r . $g . '2';
        } else {
            $ratio = $cell->percentFromMax > 1 ? 1 : $cell->percentFromMax;
            $c = dechex((int)round(14 - $ratio * 14));
            $b = 'f';
            $color = $c . $c . $b;
        }

        return $color;
    }

}