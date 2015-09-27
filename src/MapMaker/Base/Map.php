<?php

namespace MapMaker\Base;

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
