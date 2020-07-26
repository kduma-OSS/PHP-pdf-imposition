<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\DelegatesToParent;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\SelectsSides;
use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;
use Kduma\PdfImposition\PageLayoutConfiguration;

class BoxBordersMarkings implements PageLayoutGeneratorInterface
{
    use DelegatesToParent, SelectsSides;

    /**
     * BoxBordersMarkings constructor.
     *
     * @param PageLayoutGeneratorInterface $parent
     * @param float                        $width
     * @param float                        $bleeds
     */
    public function __construct(PageLayoutGeneratorInterface $parent, float $bleeds = 0, float $width = 0.05)
    {
        $this->parent = $parent;
        $this->bleeds = $bleeds;
        $this->width = $width;
    }

    protected float $bleeds;
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
                    $point->add(0, 0)->add($this->bleeds, $this->bleeds),
                    $size->getWidth() - 2 * $this->bleeds,
                    $this->width
                )
            );

            $layout->addLine(
                Line::horizontal(
                    $point->add(0, $size->getHeight())->add($this->bleeds, -$this->bleeds),
                    $size->getWidth() - 2 * $this->bleeds,
                    $this->width
                )
            );

            $layout->addLine(
                Line::vertical(
                    $point->add(0, 0)->add($this->bleeds, $this->bleeds),
                    $size->getHeight() - 2 * $this->bleeds,
                    $this->width
                )
            );

            $layout->addLine(
                Line::vertical(
                    $point->add($size->getWidth(), 0)->add(-$this->bleeds, $this->bleeds),
                    $size->getHeight() - 2 * $this->bleeds,
                    $this->width
                )
            );
        }

        return $layout;
    }
}
