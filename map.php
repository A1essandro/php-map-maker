<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8"/>
    <style type="text/css">
        #map {
            background: #0B6138;
            position: absolute;
        }

        #map div {
            width: 7px;
            height: 7px;
            float: left;
        }

        #map div:nth-child(<?php echo $map->getGrid()->getSizeX(); ?>n+<?php echo $map->getGrid()->getSizeY() + 1; ?>) {
            clear: left
        }

        .layers {
            position: relative;
        }

        .layer {
            border: 1px solid #484;
            position: absolute;
        }
    </style>
</head>
<body>
<div id="map">
    <?php foreach ($map->getGrid() as $coordinates): ?>
        <?php list($x, $y) = $coordinates; ?>
        <div>
            <div class="layers">
                <?php foreach ($map->getLayerVisualisers() as $visualiser): ?>
                    <?php echo $visualiser->renderCell($x, $y); ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>