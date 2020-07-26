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

class OutsideBoxCutMarkings implements PageLayoutGeneratorInterface
{
    use ExtractsCrossPoints, DelegatesToParent, SelectsSides;

    /**
     * BoxCrossesMarkings constructor.
     *
     * @param PageLayoutGeneratorInterface $parent
     * @param float                        $margin
     * @param float                        $length
     * @param float                        $bleeds
     * @param float                        $width
     */
    public function __construct(PageLayoutGeneratorInterface $parent, float $margin = 5, float $length = 50, float $bleeds = 0, float $width = 0.05)
    {
        $this->parent = $parent;
        $this->bleeds = $bleeds;
        $this->margin = $margin;
        $this->width = $width;
        $this->length = $length;
    }

    protected float $bleeds;
    protected float $margin;
    protected float $width;
    protected float $length;

    public function getLayout(bool $back = false, ?int $page_number = null, ?int $limit = null): PageLayoutConfiguration
    {
        $layout = $this->parent->getLayout($back, $page_number, $limit);

        if(!$this->printOn($back, $page_number))
            return $layout;

        [$horizontal, $vertical] = $this->extractCrossPoints($layout, $this->bleeds);

        foreach ($horizontal as $position => $values) {
            $layout->addLine(
                Line::horizontal(
                    Point::make(min($values) - $this->bleeds, (float)$position)->add(-$this->margin, 0),
                    -$this->length,
                    $this->width
                )
            );
            $layout->addLine(
                Line::horizontal(
                    Point::make(max($values) + $this->bleeds, (float)$position)->add($this->margin, 0),
                    $this->length,
                    $this->width
                )
            );
        }


        foreach ($vertical as $position => $values) {
            $layout->addLine(
                Line::vertical(
                    Point::make((float)$position, min($values) - $this->bleeds)->add(0, -$this->margin),
                    -$this->length,
                    $this->width
                )
            );
            $layout->addLine(
                Line::vertical(
                    Point::make((float)$position, max($values) + $this->bleeds)->add(0, $this->margin),
                    $this->length,
                    $this->width
                )
            );
        }


        return $layout;
    }
}
