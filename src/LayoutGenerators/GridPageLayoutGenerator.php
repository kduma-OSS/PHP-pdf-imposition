<?php


namespace Kduma\PdfImposition\LayoutGenerators;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\DTO\PageMargins;
use Kduma\PdfImposition\DTO\PageSize;
use Kduma\PdfImposition\DTO\Point;
use Kduma\PdfImposition\DTO\Size;
use Kduma\PdfImposition\PageLayoutConfiguration;

class GridPageLayoutGenerator implements PageLayoutGeneratorInterface
{
    /**
     * @var PageSize
     */
    protected PageSize $pageSize;

    /**
     * @var Size
     */
    protected Size $boxSize;

    /**
     * @var PageMargins
     */
    protected PageMargins $margins;

    /**
     * @var int
     */
    protected int $rows;

    /**
     * @var int
     */
    protected int $columns;

    /**
     * @var float
     */
    protected float $horizontalSpacing = 0;

    /**
     * @var float
     */
    protected float $verticalSpacing = 0;

    /**
     * GridPageLayoutGenerator constructor.
     *
     * @param int              $rows
     * @param int              $columns
     * @param Size             $boxSize
     * @param float            $horizontalSpacing
     * @param float            $verticalSpacing
     * @param PageSize|null    $size
     * @param PageMargins|null $margins
     *
     * @throws \Mpdf\MpdfException
     */
    public function __construct(int $rows, int $columns, Size $boxSize, float $horizontalSpacing = 0, float $verticalSpacing = 0, PageSize $size = null, PageMargins $margins = null)
    {
        $this->pageSize = $size ?? PageSize::fromName('A4');
        $this->margins = $margins ?? PageMargins::make();
        $this->rows = $rows;
        $this->columns = $columns;
        $this->boxSize = $boxSize;
        $this->horizontalSpacing = $horizontalSpacing;
        $this->verticalSpacing = $verticalSpacing;
    }

    /**
     * @return int
     */
    public function getBoxesCount(): int
    {
        return $this->rows * $this->columns;
    }

    /**
     * @param bool     $back
     *
     * @param int|null $page_number
     * @param int|null $limit
     *
     * @return PageLayoutConfiguration
     */
    public function getLayout(bool $back = false, ?int $page_number = null, ?int $limit = null): PageLayoutConfiguration
    {
        $limit ??= $this->getBoxesCount();

        $layout = new PageLayoutConfiguration($this->pageSize, $back ? $this->margins->flip() : $this->margins);

        for ($row = 0; $row < $this->rows; $row++){
            for ($column = 0; $column < $this->columns; $column++){
                $x = $this->margins->getLeft() + $column * ($this->boxSize->getWidth() + $this->horizontalSpacing);

                $width = $this->pageSize->isLandscape()
                    ? $this->pageSize->getSize()->getHeight()
                    : $this->pageSize->getSize()->getWidth();

                $point = Point::make(
                    $back ? $width - $this->boxSize->getWidth() - $x : $x,
                    $this->margins->getTop() + $row * ($this->boxSize->getHeight() + $this->verticalSpacing)
                );

                $box = Box::make($point, $this->boxSize);
                $layout->addBox($box);

                if(!--$limit) {
                    return $layout;
                }
            }
        }

        return $layout;
    }

    /**
     * @return PageSize
     */
    public function getPageSize(): PageSize
    {
        return $this->pageSize;
    }

    /**
     * @return Size
     */
    public function getBoxSize(): Size
    {
        return $this->boxSize;
    }

    /**
     * @return PageMargins
     */
    public function getMargins(): PageMargins
    {
        return $this->margins;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @return int
     */
    public function getColumns(): int
    {
        return $this->columns;
    }

    /**
     * @return float
     */
    public function getHorizontalSpacing(): float
    {
        return $this->horizontalSpacing;
    }

    /**
     * @return float
     */
    public function getVerticalSpacing(): float
    {
        return $this->verticalSpacing;
    }
}
