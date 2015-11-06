<?php

namespace MapMaker\Base\Abstraction;
use SplFixedArray;

/**
 * Общие методы, свойства и интерфейс для слоев
 *
 * @author Alexander Yermakov
 */
abstract class Layer
{

    /**
     * Объект карты
     *
     * @var IMap
     */
    protected $map;

    /**
     * Массив точек слоя
     *
     * @var SplFixedArray
     */
    private $cells;

    /**
     * Ключ по умолчанию
     *
     * в более поздних версиях PHP доступна константа ::class
     */
    const KEY = __CLASS__;

    /**
     * Метод должен генерировать слой на основе заданных до этого параметров
     */
    abstract public function generate();

    function __construct(IMap $map)
    {
        $this->map = $map;
    }

    /**
     * @param $x
     * @param $y
     *
     * @return ICell
     */
    public function getCell($x, $y)
    {
        if (!empty($this->cells[$x][$y])) {
            return $this->cells[$x][$y];
        }

        return null;
    }

    protected function setCells(SplFixedArray $cells)
    {
        $this->cells = $cells;
    }

    protected function setCell($x, $y, ICell $cell)
    {
        $this->cells[$x][$y] = $cell;
    }

    /**
     * Set your handler for each cell!
     *
     * @param callable $handler Callable handler
     * @param array    $additionalParams Additional params array to your handler
     */
    public function cellsHandler(callable $handler, array $additionalParams = array())
    {
        foreach ($this->map->getGrid() as $coordinates) {
            list($x, $y) = $coordinates;
            $cell = $this->getCell($x, $y);
            $handler($cell, $additionalParams);
        }
    }

}
