<?php

namespace MapMaker\Base\Abstraction;

/**
 *
 * @author Alexander Yermakov
 */
interface IMap
{

    const EXISTS_KEY_EVENT_EXCEPTION = 0;
    const EXISTS_KEY_EVENT_OVERWRITE = 1;
    const EXISTS_KEY_EVENT_DISPLACE = 2;

    /**
     * Установка визуализатора карты
     *
     * @param IMapVisualiser $visualiser
     */
    public function setVisualiser(IMapVisualiser $visualiser);

    /**
     * Визуализация
     *
     * @return mixed
     */
    public function render();

    /**
     * Attaching layer to this map
     *
     * @param Layer $layer
     * @param int $orderKey
     * @param int $existsKeyEvent event if orderKey is exists
     */
    public function attachLayer(Layer $layer, $orderKey = null, $existsKeyEvent = self::EXISTS_KEY_EVENT_EXCEPTION);

    /**
     * @param LayerVisualiser $visualiser
     *
     * @return void
     */
    public function addLayerVisualiser(LayerVisualiser $visualiser);

    /**
     * @return IGrid
     */
    public function getGrid();

}
