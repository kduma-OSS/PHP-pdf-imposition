<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\DelegatesToParent;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\SelectsSides;
use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;
use Kduma\PdfImposition\PageLayoutConfiguration;

class BoxCrossesMarkings implements PageLayoutGeneratorInterface
{
    use DelegatesToParent, SelectsSides;

    /**
     * BoxCrossesMarkings constructor.
     *
     * @param PageLayoutGeneratorInterface $parent
     * @param float                        $insideLength
     * @param float                        $outsideLength
     * @param float                        $width
     * @param float                        $bleeds
     */
    public function __construct(PageLayoutGeneratorInterface $parent, float $insideLength = 5, float $outsideLength = 5, float $bleeds = 0, float $width = 0.05)
    {
        $this->parent = $parent;
        $this->bleeds = $bleeds;
        $this->insideLength = $insideLength;
        $this->outsideLength = $outsideLength;
        $this->width = $width;
    }

    protected float $bleeds;
    protected float $insideLength;
    protected float $outsideLength;
    protected float $width;

    public function getLayout(bool $back = false, ?int $page_number = null, ?int $limit = null): PageLayoutConfiguration
    {
        $layout = $this->parent->getLayout($back, $page_number, $limit);

        if(!$this->printOn($back, $page_number))
            return $layout;

        /** @var Box $box */
        foreach ($layout->getBoxes() as $box) {
            $point = $box->getPoint();
            $size = $box->getSize();

            $layout->addLine(
                Line::horizontal(
                    $point->add(-$this->outsideLength, 0)->add($this->bleeds, $this->bleeds),
                    $this->insideLength + $this->outsideLength,
                    $this->width
                )
            );
            $layout->addLine(
                Line::vertical(
                    $point->add(0, -$this->outsideLength)->add($this->bleeds, $this->bleeds),
                    $this->insideLength + $this->outsideLength,
                    $this->width
                )
            );

            $layout->addLine(
                Line::horizontal(
                    $point->add($size->getWidth() - $this->insideLength, 0)->add(-$this->bleeds, $this->bleeds),
                    $this->insideLength + $this->outsideLength,
                    $this->width
                )
            );
            $layout->addLine(
                Line::vertical(
                    $point->add($size->getWidth(), -$this->outsideLength)->add(-$this->bleeds, $this->bleeds),
                    $this->insideLength + $this->outsideLength,
                    $this->width
                )
            );

            $layout->addLine(
                Line::horizontal(
                    $point->add(-$this->outsideLength, $size->getHeight())->add($this->bleeds, -$this->bleeds),
                    $this->insideLength + $this->outsideLength,
                    $this->width
                )
            );
            $layout->addLine(
                Line::vertical(
                    $point->add(0, $size->getHeight() - $this->insideLength)->add($this->bleeds, -$this->bleeds),
                    $this->insideLength + $this->outsideLength,
                    $this->width
                )
            );

            $layout->addLine(
                Line::horizontal(
                    $point->add($size->getWidth() - $this->insideLength, $size->getHeight())->add(-$this->bleeds, -$this->bleeds),
                    $this->insideLength + $this->outsideLength,
                    $this->width
                )
            );
            $layout->addLine(
                Line::vertical(
                    $point->add($size->getWidth(), $size->getHeight() - $this->insideLength)->add(-$this->bleeds, -$this->bleeds),
                    $this->insideLength + $this->outsideLength,
                    $this->width
                )
            );
        }

        return $layout;
    }
}
