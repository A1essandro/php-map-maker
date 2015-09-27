<?php

namespace MapMaker\Exceptions;

/**
 * Исключение зависимости. Выбрасывается в случае, если, например, слой зависит
 * от другого, но зависимость не указана
 *
 * @author Yermakov Alexander
 */
class DependencyException extends \Exception
{

    public function __construct($dependency)
    {
        $str = sprintf('Не задан ключ к зависимости %s', $dependency);
        parent::__construct($str);
    }

}
