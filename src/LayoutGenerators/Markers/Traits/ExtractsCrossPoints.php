<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers\Traits;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\PageLayoutConfiguration;
use Tightenco\Collect\Support\Collection;

trait ExtractsCrossPoints
{

    /**
     * @param PageLayoutConfiguration $layout
     *
     * @param float                   $bleeds
     *
     * @return array[]
     */
    protected function extractCrossPoints(PageLayoutConfiguration $layout, float $bleeds): array
    {
        $horizontal = [];
        $vertical = [];


        /** @var Box $box */
        foreach ($layout->getBoxes() as $box) {
            $point = $box->getPoint();
            $size = $box->getSize();

            $key = number_format($point->getY() + $bleeds, 8, '.', '');
            if (!isset($horizontal[$key]))
                $horizontal[$key] = [];

            $horizontal[$key][] = $point->getX() + $bleeds;
            $horizontal[$key][] = $point->getX() + $size->getWidth() - $bleeds;


            $key = number_format($point->getY() + $size->getHeight() - $bleeds, 8, '.', '');
            if (!isset($horizontal[$key]))
                $horizontal[$key] = [];

            $horizontal[$key][] = $point->getX() + $bleeds;
            $horizontal[$key][] = $point->getX() + $size->getWidth() - $bleeds;


            $key = number_format($point->getX() + $bleeds, 8, '.', '');
            if (!isset($vertical[$key]))
                $vertical[$key] = [];

            $vertical[$key][] = $point->getY() + $bleeds;
            $vertical[$key][] = $point->getY() + $size->getHeight() - $bleeds;


            $key = number_format($point->getX() + $size->getWidth() - $bleeds, 8, '.', '');
            if (!isset($vertical[$key]))
                $vertical[$key] = [];

            $vertical[$key][] = $point->getY() + $bleeds;
            $vertical[$key][] = $point->getY() + $size->getHeight() - $bleeds;


        }
        return collect([collect($horizontal), collect($vertical)])
            ->map(function (Collection $collection) {
                return $collection->map(fn(array $points) => collect($points))
                    ->map(fn(Collection $collection) => $collection->unique());
            })
            ->toArray();
    }
}
