<?php


namespace Kduma\PdfImposition\DTO;


class Line
{
    /**
     * @var Point
     */
    protected Point $start;

    /**
     * @var Point
     */
    protected Point $end;

    /**
     * @var float
     */
    protected float $width;

    /**
     * Line constructor.
     *
     * @param Point $start
     * @param Point $end
     * @param float $width
     */
    public function __construct(Point $start, Point $end, float $width)
    {
        $this->start = $start;
        $this->end = $end;
        $this->width = $width;
    }

    /**
     * @param Point $start
     * @param Point $end
     * @param float $width
     *
     * @return Line
     */
    public static function make(Point $start, Point $end, float $width): Line
    {
        return new Line($start, $end, $width);
    }

    /**
     * @param Point $start
     * @param float $length
     * @param float $width
     *
     * @return Line
     */
    public static function horizontal(Point $start, float $length, float $width): Line
    {
        return new Line($start, $start->add($length, 0), $width);
    }

    /**
     * @param Point $start
     * @param float $length
     * @param float $width
     *
     * @return Line
     */
    public static function vertical(Point $start, float $length, float $width): Line
    {
        return new Line($start, $start->add(0, $length), $width);
    }

    /**
     * @param Point $point
     * @param float $width
     *
     * @return Line
     */
    public static function dot(Point $point, float $width): Line
    {
        return new Line($point, $point, $width);
    }

    /**
     * @return Point
     */
    public function getStart(): Point
    {
        return $this->start;
    }

    /**
     * @return Point
     */
    public function getEnd(): Point
    {
        return $this->end;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }
}
