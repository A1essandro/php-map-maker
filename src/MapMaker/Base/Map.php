<?php

namespace MapMaker\Base;

use Exception;
use MapMaker\Base\Abstraction\ICell;
use MapMaker\Base\Abstraction\IGrid;
use MapMaker\Base\Abstraction\IMap;
use MapMaker\Base\Abstraction\IMapVisualiser;
use MapMaker\Base\Abstraction\Layer;
use MapMaker\Base\Abstraction\LayerVisualiser;

/**
 * Description of MapMakerMap
 *
 * @author Alexander Yermakov
 */
class Map implements IMap
{

    #region Properties

    protected $layersKeys = array();

    /**
     * @var LayerVisualiser[]
     */
    protected $layerVisualisers = array();

    /**
     * @var IMapVisualiser
     */
    protected $visualiser = null;

    /**
     * Массив объектов на карте
     *
     * @var Layer[]
     */
    public $layers = array();

    /**
     * @var IGrid
     */
    protected $grid;

    #endregion

    /**
     * @param $grid IGrid
     */
    public function __construct($grid)
    {
        $this->grid = $grid;
    }

    /**
     * Назвачение визуализатора для карты
     *
     * @param IMapVisualiser $visualiser
     */
    public function setVisualiser(IMapVisualiser $visualiser)
    {
        $this->visualiser = $visualiser;
    }

    public function render()
    {
        return $this->visualiser->render($this);
    }

    public function attachLayer(Layer $layer, $layerName = null)
    {
        $orderKey = count($this->layers);
        $this->layers[$orderKey] = $layer;
        $nameKey = $layerName ? $layerName : $layer::KEY;
        $this->layersKeys[$nameKey] = $orderKey;
    }

    /**
     * @param LayerVisualiser $visualiser
     */
    public function addLayerVisualiser(LayerVisualiser $visualiser)
    {
        $this->layerVisualisers[] = $visualiser;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return ICell[]
     */
    public function getLayersCells($x, $y)
    {
        //начиная с PHP v5.5 появились генераторы и ключевое слово yield
        //это классно, но нужно поддерживать PHP v5.3
        $layers = array();
        foreach ($this->layers as $layer) {
            if ($layer->getCell($x, $y)) {
                $layers[] = $layer->getCell($x, $y);
            }
        }

        return $layers;
    }

    public function getLayerCell($x, $y, $layerName)
    {
        $layers = $this->getLayersCells($x, $y);

        return $layers[$this->getLayerOrderKey($layerName)];
    }

    public function getLayerOrderKey($layerName)
    {
        if (isset($this->layersKeys[$layerName])) {
            return $this->layersKeys[$layerName];
        } else {
            throw new Exception(sprintf('Не найден ключ %s списке слоев карты', $layerName));
        }
    }

    #region Getters

    /**
     * @return Abstraction\LayerVisualiser[]
     */
    public function getLayerVisualisers()
    {
        return $this->layerVisualisers;
    }

    /**
     * @return IGrid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    #endregion

}
