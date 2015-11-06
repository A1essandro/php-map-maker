<?php

namespace MapMaker\Base;

use InvalidArgumentException;
use LogicException;
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

    public function attachLayer(Layer $layer, $orderKey = null, $existsKeyEvent = self::EXISTS_KEY_EVENT_EXCEPTION)
    {
        if ($orderKey === null) {
            $orderKey = count($this->layers);
        } elseif (!is_int($orderKey)) {
            throw new InvalidArgumentException('Second argument must be int');
        }

        if (!empty($this->layers[$orderKey])) {
            switch ($existsKeyEvent) {
                case self::EXISTS_KEY_EVENT_EXCEPTION:
                    throw new LogicException('Overwrite error');
                    break;

                case self::EXISTS_KEY_EVENT_OVERWRITE:
                    $this->layers[$orderKey] = $layer;
                    break;

                case self::EXISTS_KEY_EVENT_DISPLACE:
                    for ($i = count($this->layers); $i > $orderKey; $i--) {
                        $this->layers[$i] = $this->layers[$i - 1];
                    }
                    $this->layers[$orderKey] = $layer;
                    break;

                default:
                    throw new InvalidArgumentException(
                        printf('Undefined overwriting event %s', $existsKeyEvent)
                    );
            }
        }

        $this->layers[$orderKey] = $layer;
    }

    /**
     * @param LayerVisualiser $visualiser
     */
    public function addLayerVisualiser(LayerVisualiser $visualiser)
    {
        $this->layerVisualisers[] = $visualiser;
        $visualiser->setMap($this);
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

    /**
     * @return IMapVisualiser
     */
    public function getVisualiser()
    {
        return $this->visualiser;
    }

    #endregion

}
