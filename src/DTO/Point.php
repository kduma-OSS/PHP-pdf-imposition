<?php


namespace Kduma\PdfImposition\DTO;


class Point
{
    /**
     * @var float
     */
    protected float $x;

    /**
     * @var float
     */
    protected float $y;

    /**
     * Point constructor.
     *
     * @param float $x
     * @param float $y
     */
    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param float $x
     * @param float $y
     *
     * @return Point
     */
    public static function make(float $x, float $y): Point
    {
        return new Point($x, $y);
    }

    /**
     * @param float $x
     * @param float $y
     *
     * @return Point
     */
    public function add(float $x, float $y): Point
    {
        return new Point($this->x + $x, $this->y + $y);
    }

    /**
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }

    /**
     * @return float
     */
    public function getY(): float
    {
        return $this->y;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("(%s,%s)", round($this->x, 2), round($this->y, 2));
    }
}
