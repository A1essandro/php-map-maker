<?php

namespace MapMaker;

use MapMaker\Base\Abstraction\IMap;
use MapMaker\Base\Abstraction\Layer;
use LogicException;

/**
 * Алгоритм имеет ступенчатый прирост времени обработки,
 * в зависимости от размеров карты. Например, при карте 126х126,
 * генерируется исходный слой 128х128, а при увеличении хотя бы
 * одной из сторон до 127 - алгоритм будет уже генерировать слой
 * размером 256х256, увеличивая время расчета при этом ~ в 4 раза
 *
 * @author Alexander Yermakov
 */
class DiamondAndSquare extends Layer
{

    /**
     * Размер сторон генерируемого слоя. Зависит от размера карты,
     * и будет всегда больше чем ее бОльшая сторона (до 2 в степени)
     *
     * @var int
     */
    private $size;

    /**
     * Максимальный размер карты (по X или Y)
     *
     * @var int
     */
    private $mapMaxSize;

    /**
     * разница между самой высокой и самой низкой точкой на карте
     *
     * @var float
     */
    private $maxOffset = 100;

    const KEY = __CLASS__;

    /**
     * @param IMap $map       объект карты
     * @param int  $maxOffset максимальная разница между высотами
     */
    public function __construct(IMap $map, $maxOffset = 100)
    {
        parent::__construct($map);

        $this->maxOffset = $maxOffset;
        $this->mapMaxSize = max($this->map->getGrid()->getSizeX(), $this->map->getGrid()->getSizeY());
        $this->size = $this->getFullSize();

        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                $this->setCell($x, $y, new HeightCell($this));
            }
        }

        $this->getCell(0, 0)->setHeight($this->getOffset($this->size));
        $this->getCell(0, $this->size-1)->setHeight($this->getOffset($this->size));
        $this->getCell($this->size-1, 0)->setHeight($this->getOffset($this->size));
        $this->getCell($this->size-1, $this->size-1)->setHeight($this->getOffset($this->size));
    }

    public function generate()
    {
        //основная часть алгоритма тут:
        $this->divide($this->size / 2);

        //обрезаем лишние клетки:
        $this->cut();
    }

    /**
     * Получение полного размера генерируемой карты
     *
     * @return number
     */
    private function getFullSize()
    {
        $max = max($this->map->getGrid()->getSizeX(), $this->map->getGrid()->getSizeY());

        for ($i = 2; ; $i++) {
            $res = pow(2, $i);
            if ($res >= ($max + 1)) { //+1 для скрытия крайних точек в this::cut()
                return $res + 1;
            }
        }

        //сюда мы не должны попасть, но PHPStorm требует или return или Exception
        throw new LogicException('Очень странная ошибка!');
    }

    /**
     * Рекурсивное деление карты
     *
     * @param $stepSize
     */
    private function divide($stepSize)
    {
        $half = floor($stepSize / 2);

        if ($half < 1) {
            return;
        }

        for ($x = $half; $x < $this->size; $x += $stepSize) {
            for ($y = $half; $y < $this->size; $y += $stepSize) {
                $this->square($x, $y, $half, $this->getOffset($stepSize));
            }
        }

        $this->divide($half);
    }

    /**
     * Определение высоты клетки в центре квадрата
     *
     * @param $x      int
     * @param $y      int
     * @param $size   int
     * @param $offset float
     */
    private function square($x, $y, $size, $offset)
    {
        $a = $this->getCellHeight($x - $size, $y - $size, $size);
        $b = $this->getCellHeight($x + $size, $y + $size, $size);
        $c = $this->getCellHeight($x - $size, $y + $size, $size);
        $d = $this->getCellHeight($x + $size, $y - $size, $size);

        $average = ($a + $b + $c + $d) / 4;
        $this->getCell($x, $y)->setHeight($average + $offset);

        $this->diamond($x, $y - $size, $size, $this->getOffset($size));
        $this->diamond($x - $size, $y, $size, $this->getOffset($size));
        $this->diamond($x, $y + $size, $size, $this->getOffset($size));
        $this->diamond($x + $size, $y, $size, $this->getOffset($size));
    }

    /**
     * Определение высоты клетки в центре ромба
     *
     * @param $x      int
     * @param $y      int
     * @param $size   int
     * @param $offset float
     */
    private function diamond($x, $y, $size, $offset)
    {
        $a = $this->getCellHeight($x, $y - $size, $size);
        $b = $this->getCellHeight($x, $y + $size, $size);
        $c = $this->getCellHeight($x - $size, $y, $size);
        $d = $this->getCellHeight($x + $size, $y, $size);

        $average = ($a + $b + $c + $d) / 4;

        if (!$this->getCell($x, $y)) {
            $this->setCell($x, $y, new HeightCell($this));
        }

        $this->getCell($x, $y)->setHeight($average + $offset);
    }

    /**
     * Получение случайного изменения высоты для точки
     *
     * @param float $stepSize
     *
     * @return float
     */
    private function getOffset($stepSize)
    {
        return $stepSize / $this->size *
        rand(-$this->maxOffset / 2, $this->maxOffset / 2);
    }

    /**
     * Обрезка лишних клеток
     */
    private function cut()
    {
        $startX = rand(1, $this->size - $this->map->getGrid()->getSizeX());
        $startY = rand(1, $this->size - $this->map->getGrid()->getSizeY());
        //от 1, чтобы скрыть крайние точки, т.к. в углах карты могут образовываться резкие ямы или возвышенности
        //крайние точки справа и снизу отсекаются, т.к. размер сетки карты всегда меньше как минимум на 2

        $resultMap = array(array());
        foreach ($this->map->getGrid() as $coordinates) {
            list($ix, $iy) = $coordinates;
            $resultMap[$ix][$iy] = $this->getCell($startX + $ix, $startY + $iy);
        }
        $this->setCells($resultMap);
    }

    /**
     * Получить максимальную разницу между самой выскокой и самой низкой точкой
     * изначально генерируемой карты высот. Полученный в итоге слой может
     * не включать в себя эти точки
     *
     * @return float
     */
    function getMaxOffset()
    {
        return $this->maxOffset;
    }

    /**
     * Задать максимальную разницу между самой выскокой и самой низкой точкой
     * изначально генерируемой карты высот. Полученный в итоге слой может
     * не включать в себя эти точки
     *
     * @param float $maxOffset
     */
    function setMaxOffset($maxOffset)
    {
        $this->maxOffset = $maxOffset;
    }

    /**
     * Возвращает высоту клетки, если ее нет, случайную высоту в зависимости от размера текущего шага
     *
     * @param $x
     * @param $y
     * @param $stepSize
     *
     * @return double
     */
    private function getCellHeight($x, $y, $stepSize = 0)
    {
        return $this->getCell($x, $y)
            ? $this->getCell($x, $y)->getHeight()
            : rand(-$stepSize, $stepSize);
    }

    /**
     * Уточнение для данного класса, что Cell instanceof HeightCell (для IDE)
     *
     * @param $x
     * @param $y
     *
     * @return HeightCell
     */
    public function getCell($x, $y)
    {
        return parent::getCell($x, $y);
    }

}
