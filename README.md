# MapMaker

##Example:
```php

use MapMaker\Base\Grid;
use MapMaker\Base\Map;
use MapMaker\DiamondAndSquare;
use MapMaker\HtmlVisualiser;
use MapMaker\WaterlineHtmlVisualiser;
use MapMaker\WaterlineLayer;

$map = new Map(new MapMaker\Base\Grid(75, 75));
$map->setVisualiser(new MapMaker\HtmlVisualiser());

$heightLayer = new MapMaker\DiamondAndSquare($map);
$heightLayer->setMaxOffset(rand(100, 500));
$heightLayer->generate();
$map->attachLayer($heightLayer);

$waterLayer = new MapMaker\WaterlineLayer($map);
$waterLayer->setHeightLayerKey(\MapMaker\DiamondAndSquare::KEY);
$waterLayer->setWaterRatio(1 / rand(4, 10));
$waterLayer->generate();
$map->attachLayer($waterLayer);

$waterlineVisualiser = new \MapMaker\WaterlineHtmlVisualiser();
$waterlineVisualiser->setLayer($waterLayer);
$map->addLayerVisualiser($waterlineVisualiser);

echo $map->render();

```