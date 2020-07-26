<?php


namespace Kduma\PdfImposition\LayoutGenerators;


use Kduma\PdfImposition\DTO\PageMargins;
use Kduma\PdfImposition\DTO\PageSize;
use Kduma\PdfImposition\DTO\Size;

class AutoGridPageLayoutGenerator extends GridPageLayoutGenerator
{
    public function __construct(Size $boxSize, float $horizontalSpacing = 0, float $verticalSpacing = 0, PageSize $size = null, PageMargins $margins = null)
    {
        $size ??= PageSize::fromName('A4');
        $margins ??= PageMargins::make();

        $width = $size->isLandscape() ? $size->getSize()->getHeight() : $size->getSize()->getWidth();
        $height = $size->isLandscape() ? $size->getSize()->getWidth() : $size->getSize()->getHeight();

        parent::__construct(
            $this->fit($height - $margins->getTop() - $margins->getBottom(), $boxSize->getHeight(), $verticalSpacing),
            $this->fit($width - $margins->getLeft() - $margins->getRight(), $boxSize->getWidth(), $horizontalSpacing),
            $boxSize, $horizontalSpacing, $verticalSpacing, $size, $margins
        );
    }

    public function center()
    {
        $width = $this->pageSize->isLandscape() ? $this->pageSize->getSize()->getHeight() : $this->pageSize->getSize()->getWidth();
        $height = $this->pageSize->isLandscape() ? $this->pageSize->getSize()->getWidth() : $this->pageSize->getSize()->getHeight();

        $horizontalFreeSpace = $width - $this->columns * ($this->boxSize->getWidth() + $this->horizontalSpacing) + $this->horizontalSpacing - $this->margins->getLeft() - $this->margins->getRight();
        $verticalFreeSpace = $height - $this->rows * ($this->boxSize->getHeight() + $this->verticalSpacing) + $this->verticalSpacing - $this->margins->getTop() - $this->margins->getBottom();

        $this->margins = new PageMargins(
            $this->margins->getLeft() + $horizontalFreeSpace/2,
            $this->margins->getRight() + $horizontalFreeSpace/2,
            $this->margins->getTop() + $verticalFreeSpace/2,
            $this->margins->getBottom() + $verticalFreeSpace/2,
            $this->margins->getHeader(),
            $this->margins->getFooter()
        );

        return $this;
    }

    private function fit(float $max, float $single, float $spacing): int
    {
        $i = 0;

        while(($i + 1)*($single + $spacing) <= $max + $spacing)
            $i++;

        return $i;
    }

}
