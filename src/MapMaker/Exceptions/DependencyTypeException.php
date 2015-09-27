<?php

namespace MapMaker\Exceptions;

/**
 * Исключение зависимости. Выбрасывается в случае, если, например, слой зависит
 * от другого, но зависимость не указана
 *
 * @author Yermakov Alexander
 */
class DependencyTypeException extends DependencyException
{

    public function __construct($type)
    {
        $str = sprintf('Некорректный тип зависимости %s', $type);
        parent::__construct($str);
    }

}
