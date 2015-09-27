<?php

namespace MapMaker\Base\Abstraction;

abstract class LayerVisualiser
{

    /**
     * @var Layer
     */
    protected $layer;

    /**
     * @var IMap
     */
    protected $map;

    /**
     * Внутренняя реализация рендера ячейки
     *
     * @param ICell $cell
     *
     * @return mixed
     */
    abstract protected function render(ICell $cell);

    public function renderCell($x, $y)
    {
        $cell = $this->layer->getCell($x, $y);
        return $this->render($cell);
    }

    /**
     * @param Layer $layer
     *
     * @return void
     */
    public function setLayer(Layer $layer)
    {
        $this->layer = $layer;
    }

    /**
     * @return Layer
     */
    public function getLayer()
    {
        $this->layer;
    }

}