# MapMaker

##Usage example:
```php

require __DIR__ . '/vendor/autoload.php';

use MapGenerator\DiamondAndSquare;
use MapMaker\Base\Grid;
use MapMaker\Base\Map;
use MapMaker\HeightLayer;
use MapMaker\HtmlVisualiser;
use MapMaker\WaterlineHtmlVisualiser;
use MapMaker\WaterlineLayer;

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

```