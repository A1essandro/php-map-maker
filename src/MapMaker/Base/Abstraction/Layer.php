<?php

namespace MapMaker\Base\Abstraction;

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
     * @var ICell[][]
     */
    private $cells = array(array());

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

    protected function setCells(array $cells)
    {
        $this->cells = $cells;
    }

    protected function setCell($x, $y, ICell $cell)
    {
        $this->cells[$x][$y] = $cell;
    }

}
