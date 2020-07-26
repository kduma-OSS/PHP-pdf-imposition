<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\DTO\Point;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\DelegatesToParent;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\ExtractsCrossPoints;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\SelectsSides;
use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;
use Kduma\PdfImposition\PageLayoutConfiguration;

class FullLengthLinesMarkings implements PageLayoutGeneratorInterface
{
    use ExtractsCrossPoints, DelegatesToParent, SelectsSides;

    /**
     * FullLengthLinesMarkings constructor.
     *
     * @param PageLayoutGeneratorInterface $parent
     * @param float                        $margin
     * @param float                        $bleeds
     * @param float                        $width
     */
    public function __construct(PageLayoutGeneratorInterface $parent, float $margin = 5, float $bleeds = 0, float $width = 0.05)
    {
        $this->parent = $parent;
        $this->bleeds = $bleeds;
        $this->margin = $margin;
        $this->width = $width;
    }

    protected float $bleeds;
    protected float $margin;
    protected float $width;

    public function getLayout(bool $back = false, ?int $page_number = null, ?int $limit = null): PageLayoutConfiguration
    {
        $layout = $this->parent->getLayout($back, $page_number, $limit);

        if(!$this->printOn($back, $page_number))
            return $layout;

        [$horizontal, $vertical] = $this->extractCrossPoints($layout, $this->bleeds);

        foreach ($horizontal as $position => $values) {
            $layout->addLine(
                Line::horizontal(
                    Point::make($this->margin, (float)$position),
                    $layout->getPageSize()->getSize()->getWidth() - 2 * $this->margin,
                    $this->width
                )
            );
        }


        foreach ($vertical as $position => $values) {
            $layout->addLine(
                Line::vertical(
                    Point::make((float)$position, $this->margin),
                    $layout->getPageSize()->getSize()->getHeight() - 2 * $this->margin,
                    $this->width
                )
            );
        }


        return $layout;
    }
}
