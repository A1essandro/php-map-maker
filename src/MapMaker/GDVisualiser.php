<?php

namespace MapMaker;

use InvalidArgumentException;
use MapMaker\Base\Abstraction\IMap;
use MapMaker\Base\Abstraction\IMapVisualiser;

/**
 * For visualisation via GD
 *
 * @package MapMaker
 */
class GDVisualiser implements IMapVisualiser
{

    const TYPE_JPEG = 'jpeg';
    const TYPE_GIF = 'gif';
    const TYPE_PNG = 'png';

    protected $validTypes = array(self::TYPE_JPEG, self::TYPE_GIF, self::TYPE_PNG);
    protected $type = 'jpeg';
    protected $tileSize = 10;
    protected $borderSize = 1;

    public function __construct($type = self::TYPE_JPEG)
    {
        $this->setType($type);
    }

    /**
     * @var resource
     */
    protected $imageResource;

    /**
     * Визуализация всей карты
     *
     * @param IMap $map
     */
    public function render(IMap $map)
    {
        $type = $this->type;
        header(sprintf('Content-Type: image/%s', $type));

        $step = $this->tileSize + $this->borderSize;
        $sizeX = $map->getGrid()->getSizeX() * $step + $this->borderSize;
        $sizeY = $map->getGrid()->getSizeY() * $step + $this->borderSize;

        $this->imageResource = imagecreatetruecolor($sizeX, $sizeY);

        $borderColor = imagecolorallocate($this->imageResource, 0, 100, 50);
        imagefill($this->imageResource, 0, 0, $borderColor);

        foreach ($map->getGrid() as $coordinates) {
            list($x, $y) = array($coordinates[0], $coordinates[1]);
            foreach ($map->getLayerVisualisers() as $visualiser) {
                $color = $visualiser->renderCell($x, $y);
                imagefilledrectangle(
                    $this->imageResource, $x * $step + 1, $y * $step + 1, $x * $step + $this->tileSize,
                    $y * $step + $this->tileSize, $color
                );
            }
        }

        $imageFunc = 'image'.$type;
        $imageFunc($this->imageResource);
        imagedestroy($this->imageResource);

    }

    /**
     * @param string $type
     */
    protected function setType($type)
    {
        if (!in_array($type, $this->validTypes)) {
            throw new InvalidArgumentException('Not valid type');
        }

        $this->type = $type;
    }


    /**
     * @param int $tileSize
     */
    public function setTileSize($tileSize)
    {
        if (!is_int($tileSize)) {
            throw new InvalidArgumentException("tileSize must be int");
        }
        $this->tileSize = $tileSize;
    }

    /**
     * @param int $borderSize
     */
    public function setBorderSize($borderSize)
    {
        if (!is_int($borderSize)) {
            throw new InvalidArgumentException("borderSize must be int");
        }
        $this->borderSize = $borderSize;
    }

    /**
     * @return resource
     */
    public function getImageResource()
    {
        return $this->imageResource;
    }

}