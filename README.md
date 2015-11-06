# MapMaker

##Usage example:
```php

use MapGenerator\DiamondAndSquare;
use MapMaker\Base\Grid;
use MapMaker\Base\Map;
use MapMaker\GDVisualiser;
use MapMaker\HeightLayer;
use MapMaker\WaterlineGDVisualiser;
use MapMaker\WaterlineLayer;

require __DIR__ . '/vendor/autoload.php';

//init map
$map = new Map(new Grid(100, 100));
$map->setVisualiser(new GDVisualiser(GDVisualiser::TYPE_PNG));

// See https://github.com/A1essandro/Diamond-And-Square
$heightMap = DiamondAndSquare::generateAndGetMap(7, 100);

//HeightLayer
$heightLayer = new HeightLayer($map, $heightMap);
$heightLayer->cut(2, 2);
$heightLayer->generate();
$map->attachLayer($heightLayer);

//Waterline layer
$waterLayer = new WaterlineLayer($map, $heightLayer);
$waterLayer->setWaterRatio(1 / rand(2, 5));
$waterLayer->generate();
$map->attachLayer($waterLayer);

//add visualiser for WaterlineLayer
$waterlineVisualiser = new WaterlineGDVisualiser();
$waterlineVisualiser->setLayer($waterLayer);
$map->addLayerVisualiser($waterlineVisualiser);

echo $map->render();

```