<?php

namespace MapMaker\Base\Abstraction;

/**
 * Интерфейс для визуализатора карты
 *
 * @author Alexander Yermakov
 */
interface IMapVisualiser
{

    /**
     * Визуализация всей карты
     *
     * @param IMap $map
     */
    public function render(IMap $map);
}
