<?php

use MapMaker\Base\Grid;
use MapMaker\Base\Map;
use MapMaker\GDVisualiser;
use MapMaker\HeightLayer;
use MapMaker\WaterlineGDVisualiser;
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

$map = new Map(new Grid(100, 100));
$map->setVisualiser(new GDVisualiser(GDVisualiser::TYPE_PNG));

// See https://github.com/A1essandro/Diamond-And-Square
$generator = new MapGenerator\PerlinNoiseGenerator();
$generator->setSize(100);
$generator->setPersistence(mt_rand(500, 800) / 1000);
$heightMap = $generator->generate();

$heightLayer = new HeightLayer($map, $heightMap);
$heightLayer->generate();
$map->attachLayer($heightLayer);

$waterLayer = new WaterlineLayer($map, $heightLayer);
$waterLayer->setWaterRatio(mt_rand(300, 700) / 1000);
$waterLayer->generate();
$map->attachLayer($waterLayer);

$waterlineVisualiser = new WaterlineGDVisualiser();
$waterlineVisualiser->setLayer($waterLayer);
$map->addLayerVisualiser($waterlineVisualiser);

echo $map->render();
