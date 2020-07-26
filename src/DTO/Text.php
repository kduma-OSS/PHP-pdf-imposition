<?php


namespace Kduma\PdfImposition\DTO;


class Text
{
    /**
     * @var Point
     */
    protected Point $point;

    /**
     * @var float
     */
    protected float $size;

    /**
     * @var string
     */
    protected string $text;

    /**
     * Text constructor.
     *
     * @param Point  $point
     * @param string $text
     * @param float  $size
     */
    public function __construct(Point $point, string $text, float $size)
    {
        $this->point = $point;
        $this->size = $size;
        $this->text = $text;
    }


    /**
     * @param Point  $point
     * @param string $text
     * @param float  $size
     *
     * @return Text
     */
    public static function make(Point $point, string $text, float $size): Text
    {
        return new Text($point, $text, $size);
    }

    /**
     * @return Point
     */
    public function getPoint(): Point
    {
        return $this->point;
    }

    /**
     * @return float
     */
    public function getSize(): float
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}
