<?php

use MapMaker\Base\Grid;
use MapMaker\Base\Map;
use MapMaker\DiamondAndSquare;
use MapMaker\HtmlVisualiser;
use MapMaker\WaterlineHtmlVisualiser;
use MapMaker\WaterlineLayer;

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
header('Content-Type: text/html; charset=utf-8');

function __autoload($class)
{
    $ds = DIRECTORY_SEPARATOR;
    $classPath = str_replace('\\', $ds, $class);
    require __DIR__ . $ds . 'src' . $ds . $classPath . '.php';
}

$map = new Map(new Grid(75, 75));
$map->setVisualiser(new HtmlVisualiser());

$heightLayer = new DiamondAndSquare($map);
$heightLayer->setMaxOffset(rand(100, 500));
$heightLayer->generate();
$map->attachLayer($heightLayer);

$waterLayer = new WaterlineLayer($map);
$waterLayer->setHeightLayerKey(DiamondAndSquare::KEY);
$waterLayer->setWaterRatio(1 / rand(4, 10));
$waterLayer->generate();
$map->attachLayer($waterLayer);

$waterlineVisualiser = new WaterlineHtmlVisualiser();
$waterlineVisualiser->setLayer($waterLayer);
$map->addLayerVisualiser($waterlineVisualiser);

echo $map->render();
