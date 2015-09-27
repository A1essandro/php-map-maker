<?php

namespace MapMaker\Base\Abstraction;

/**
 *
 * @author Alexander Yermakov
 */
interface IMap
{

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
     * Прикрепление слоя к карте
     *
     * @param Layer $layer
     */
    public function attachLayer(Layer $layer);

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
