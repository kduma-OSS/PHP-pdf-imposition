<?php


namespace Kduma\PdfImposition\DTO;


class Box
{
    /**
     * @var Point
     */
    protected Point $point;

    /**
     * @var Size
     */
    protected Size $size;

    /**
     * Box constructor.
     *
     * @param Point $point
     * @param Size  $size
     */
    public function __construct(Point $point, Size $size)
    {
        $this->point = $point;
        $this->size = $size;
    }

    /**
     * @param Point $point
     * @param Size  $size
     *
     * @return Box
     */
    public static function make(Point $point, Size $size): Box
    {
        return new Box($point, $size);
    }

    /**
     * @return Point
     */
    public function getPoint(): Point
    {
        return $this->point;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }
}
