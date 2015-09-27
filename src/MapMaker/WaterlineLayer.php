<?php

namespace MapMaker;

use Exception;
use MapMaker\Base\Abstraction\IMap;
use MapMaker\Base\Abstraction\Layer;
use MapMaker\Exceptions\DependencyTypeException;
use InvalidArgumentException;

/**
 * Description of PhysicalLayer
 *
 * @author Alexander Yermakov
 */
class WaterlineLayer extends Layer
{

    protected $maxHeight = null;
    protected $minHeight = null;
    protected $AVGHeight = null;
    protected $heightLayerKey = null;
    protected $waterlineHeight = 0;
    protected $waterRatio = 0;

    /**
     * @var Layer Height layer
     */
    protected $heightLayer;

    const KEY = __CLASS__;

    function __construct(IMap $map, Layer $heightLayer, $waterRatio = 0)
    {
        parent::__construct($map);
        $this->heightLayer = $heightLayer;
        $this->setWaterRatio($waterRatio);
    }

    private function analysis()
    {
        $heightSum = 0;

        foreach ($this->map->getGrid() as $coordinates) {
            list($x, $y) = $coordinates;

            $heightCell = $this->heightLayer->getCell($x, $y);
            if (!$heightCell instanceof HeightCell) {
                throw new DependencyTypeException('heightLayerKey');
            }

            $height = $heightCell->getHeight();
            $heightSum += $height;

            $this->checkMaxHeight($height);
            $this->checkMinHeight($height);
        }

        $this->AVGHeight = $heightSum / ($this->map->getGrid()->getSizeX() * $this->map->getGrid()->getSizeX());
        $this->waterlineHeight = $this->minHeight + ($this->waterRatio * ($this->maxHeight - $this->minHeight));
    }

    private final function checkMinHeight($height)
    {
        if ($this->minHeight === null || $this->minHeight > $height) {
            $this->minHeight = $height;
        }
    }

    private final function checkMaxHeight($height)
    {
        if ($this->maxHeight === null || $this->maxHeight < $height) {
            $this->maxHeight = $height;
        }
    }

    public function generate()
    {
        $this->analysis();
        $currentMaxHeight = $this->maxHeight - $this->waterlineHeight;
        foreach ($this->map->getGrid() as $coordinates) {
            list($x, $y) = $coordinates;

            $heightCell = $this->heightLayer->getCell($x, $y);

            if (!$heightCell instanceof HeightCell) {
                throw new Exception('$heightCell must be instance of HeightCell');
            }

            $h = $heightCell->getHeight() - $this->waterlineHeight;
            $percentOfMax = $currentMaxHeight == 0 ? 1 : abs($h) / $currentMaxHeight;
            $this->setCell($x, $y, new WaterlineCell($this, $h, $percentOfMax));
        }
    }

    /**
     * Указываем ключ слоя высот, от которого зависит данный слой
     *
     * @param string $heightLayerKey ключ слоя в объекте Map
     */
    public function setHeightLayerKey($heightLayerKey)
    {
        $this->heightLayerKey = $heightLayerKey;
    }

    /**
     * Примерный процент количества воды на карте
     *
     * @return float от 0 до 1
     */
    function getWaterRatio()
    {
        return $this->waterRatio;
    }

    /**
     * Коэффициент примерного количества воды на карте
     *
     * @param float $waterRatio от 0 до 1
     */
    function setWaterRatio($waterRatio)
    {
        if ($waterRatio > 1 || $waterRatio < 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Коэффициент количества воды должен быть в пределах [0,1], задан %d',
                    $waterRatio
                )
            );
        }
        $this->waterRatio = $waterRatio;
    }

    /**
     * Уточнение для данного класса, что Cell instanceof HeightCell
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
