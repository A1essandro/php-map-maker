# MapMaker

##Example:
```php

use MapMaker\Base\Grid;
use MapMaker\Base\Map;
use MapMaker\DiamondAndSquare;
use MapMaker\HtmlVisualiser;
use MapMaker\WaterlineHtmlVisualiser;
use MapMaker\WaterlineLayer;

$map = new Map(new Grid(75, 75));
$map->setVisualiser(new HtmlVisualiser());

$heightLayer = new DiamondAndSquare($map);
$heightLayer->setMaxOffset(rand(100, 500));
$heightLayer->generate();
$map->attachLayer($heightLayer);

$waterLayer = new WaterlineLayer($map, $heightLayer);
$waterLayer->setWaterRatio(1 / rand(2, 5));
$waterLayer->generate();
$map->attachLayer($waterLayer);

$waterlineVisualiser = new WaterlineHtmlVisualiser();
$waterlineVisualiser->setLayer($waterLayer);
$map->addLayerVisualiser($waterlineVisualiser);

echo $map->render();

```