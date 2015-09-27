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
     * Получение определенной ячейки определенного слоя
     *
     * @param $x
     * @param $y
     * @param $layerName
     *
     * @return ICell
     */
    public function getLayerCell($x, $y, $layerName);

    /**
     * Получение ячеек слоев в координатах
     *
     * @param int $x
     * @param int $y
     *
     * @return ICell[]
     */
    public function getLayersCells($x, $y);

    /**
     * Получение ключа сортировки по названию слоя
     *
     * @param $layerName
     *
     * @return int
     */
    public function getLayerOrderKey($layerName);

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
