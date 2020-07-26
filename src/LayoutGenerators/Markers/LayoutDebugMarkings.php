<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\DTO\Text;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\DelegatesToParent;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\SelectsSides;
use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;
use Kduma\PdfImposition\PageLayoutConfiguration;

class LayoutDebugMarkings implements PageLayoutGeneratorInterface
{
    use DelegatesToParent, SelectsSides;

    /**
     * BoxCrossesMarkings constructor.
     *
     * @param PageLayoutGeneratorInterface $parent
     */
    public function __construct(PageLayoutGeneratorInterface $parent)
    {
        $this->parent = $parent;
    }

    protected float $lengthFactor = 1;
    protected float $skewFactor   = 0.1;
    protected float $width        = 0.2;

    public function getLayout(bool $back = false, ?int $page_number = null, ?int $limit = null): PageLayoutConfiguration
    {
        $layout = $this->parent->getLayout($back, $page_number, $limit);

        if(!$this->printOn($back, $page_number))
            return $layout;

        /** @var Box $box */
        foreach ($layout->getBoxes() as $index => $box) {
            $point = $box->getPoint();
            $size = $box->getSize();

            $verticalVector = $size->getHeight() / 2;
            $horizontalVector = $size->getWidth() / 2;

            $layout->addText(
                Text::make(
                    $point->add($verticalVector * $this->skewFactor + 3, $horizontalVector * $this->skewFactor + 3 + 3),
                    "{$index}: {$point}",
                    10
                )
            );

            $layout->addLine(
                Line::make(
                    $point->add(0, 0),
                    $point->add($horizontalVector * $this->lengthFactor, $verticalVector * $this->skewFactor),
                    $this->width
                )
            );
            $layout->addLine(
                Line::make(
                    $point->add(0, 0),
                    $point->add($horizontalVector * $this->skewFactor, $verticalVector * $this->lengthFactor),
                    $this->width
                )
            );

            $layout->addLine(
                Line::make(
                    $point->add($size->getWidth(), 0),
                    $point->add($size->getWidth() - $horizontalVector * $this->lengthFactor, $verticalVector * $this->skewFactor),
                    $this->width
                )
            );
            $layout->addLine(
                Line::make(
                    $point->add($size->getWidth(),  0),
                    $point->add($size->getWidth() - $horizontalVector * $this->skewFactor, $verticalVector * $this->lengthFactor),
                    $this->width
                )
            );

            $layout->addLine(
                Line::make(
                    $point->add(0, $size->getHeight()),
                    $point->add($horizontalVector * $this->lengthFactor, $size->getHeight() - $verticalVector * $this->skewFactor),
                    $this->width
                )
            );
            $layout->addLine(
                Line::make(
                    $point->add(0, $size->getHeight()),
                    $point->add($horizontalVector * $this->skewFactor, $size->getHeight() - $verticalVector * $this->lengthFactor),
                    $this->width
                )
            );

            $layout->addLine(
                Line::make(
                    $point->add($size->getWidth(), $size->getHeight()),
                    $point->add($size->getWidth() - $horizontalVector * $this->lengthFactor, $size->getHeight() - $verticalVector * $this->skewFactor),
                    $this->width
                )
            );
            $layout->addLine(
                Line::make(
                    $point->add($size->getWidth(),  $size->getHeight()),
                    $point->add($size->getWidth() - $horizontalVector * $this->skewFactor,$size->getHeight() - $verticalVector * $this->lengthFactor),
                    $this->width
                )
            );

        }

        return $layout;
    }
}
