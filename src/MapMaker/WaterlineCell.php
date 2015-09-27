<?php

namespace MapMaker;

use MapMaker\Base\Abstraction\Layer;

/**
 *
 */
class WaterlineCell extends HeightCell
{

    /**
     * Процент от максимальной высоты (для воды может быть больше 1)
     *
     * @var float
     */
    public $percentFromMax = 0;

    function __construct(Layer $layer, $height = 0, $percentOfMax = 0)
    {
        parent::__construct($layer, $height);
        $this->percentFromMax = $percentOfMax;
    }

    /**
     * Проверка, является ли клетка водой
     *
     * @return bool
     */
    public function isWater()
    {
        return $this->height < 0;
    }

}
