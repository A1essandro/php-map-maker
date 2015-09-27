<?php

namespace MapMaker;

use MapMaker\Base\Abstraction\IMap;
use MapMaker\Base\Abstraction\IMapVisualiser;

/**
 * Description of Render
 *
 * @author Alexander Yermakov
 */
class HtmlVisualiser implements IMapVisualiser
{

    /**
     * Визуализация всей карты
     *
     * @param IMap $map
     *
     * @return string
     */
    public function render(IMap $map)
    {
        ob_start();
        include './map.php';
        $result = ob_get_clean();

        return $this->compress($result);
    }

    /**
     * Этот метод сжирает очень много памяти при больших размерах карты
     *
     * @param string $html
     *
     * @link http://habrahabr.ru/post/30525/
     * @return string
     */
    protected function compress($html)
    {
        preg_match_all('!(<(?:code|pre|script).*>[^<]+</(?:code|pre|script)>)!', $html, $pre);
        $html = preg_replace('!<(?:code|pre).*>[^<]+</(?:code|pre)>!', '#pre#', $html);
        $html = preg_replace('#<!–[^\[].+–>#', '', $html);
        $html = preg_replace('/[\r\n\t]+/', ' ', $html);
        $html = preg_replace('/>[\s]+</', '><', $html);
        $html = preg_replace('/[\s]+/', ' ', $html);
        if (!empty($pre[0])) {
            foreach ($pre[0] as $tag) {
                $html = preg_replace('!#pre#!', $tag, $html, 1);
            }
        }

        return $html;
    }

}
