<?php

use MapGenerator\DiamondAndSquare;
use MapMaker\Base\Grid;
use MapMaker\Base\Map;
use MapMaker\HeightLayer;
use MapMaker\HtmlVisualiser;
use MapMaker\WaterlineHtmlVisualiser;
use MapMaker\WaterlineLayer;

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
header('Content-Type: text/html; charset=utf-8');

require __DIR__ . '/vendor/autoload.php';

function __autoload($class)
{
    $ds = DIRECTORY_SEPARATOR;
    $classPath = str_replace('\\', $ds, $class);
    require __DIR__ . $ds . 'src' . $ds . $classPath . '.php';
}

$map = new Map(new Grid(75, 75));
$map->setVisualiser(new HtmlVisualiser());

$ds = new DiamondAndSquare(7, 100);
$ds->generate();
$heightMap = $ds->getMap();

$heightLayer = new HeightLayer($map, $heightMap);
$heightLayer->cut(2, 2);
$heightLayer->generate();

$waterLayer = new WaterlineLayer($map, $heightLayer);
$waterLayer->setWaterRatio(1 / rand(2, 5));
$waterLayer->generate();
$map->attachLayer($waterLayer);

$waterlineVisualiser = new WaterlineHtmlVisualiser();
$waterlineVisualiser->setLayer($waterLayer);
$map->addLayerVisualiser($waterlineVisualiser);

echo $map->render();
