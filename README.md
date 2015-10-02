# MapMaker

##Usage example:
```php

//autoload third-party packages
require __DIR__ . '/vendor/autoload.php';

use MapGenerator\DiamondAndSquare;
use MapMaker\Base\Grid;
use MapMaker\Base\Map;
use MapMaker\HeightLayer;
use MapMaker\HtmlVisualiser;
use MapMaker\WaterlineHtmlVisualiser;
use MapMaker\WaterlineLayer;

//create Map 75x75 and set to visualiser
$map = new Map(new Grid(75, 75));
$map->setVisualiser(new HtmlVisualiser());

//create "crude" heightmap (you can use another method)
// See https://github.com/A1essandro/Diamond-And-Square
$heightMap = DiamondAndSquare::generateAndGetMap(7, 100);

//generate height layer based heightmap
$heightLayer = new HeightLayer($map, $heightMap);
$heightLayer->cut(2, 2);
$heightLayer->generate();
$map->attachLayer($heightLayer);

//generate waterline layer based heightLayer
$waterLayer = new WaterlineLayer($map, $heightLayer);
$waterLayer->setWaterRatio(1 / rand(2, 5));
$waterLayer->generate();
$map->attachLayer($waterLayer);

//create visualiser for waterline layer
$waterlineVisualiser = new WaterlineHtmlVisualiser();
$waterlineVisualiser->setLayer($waterLayer);
$map->addLayerVisualiser($waterlineVisualiser);

//rendering all visualisers
echo $map->render();

```