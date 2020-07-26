<?php


namespace Kduma\PdfImposition\DTO;


class Size
{
    /**
     * @var float
     */
    protected float $width;

    /**
     * @var float
     */
    protected float $height;

    /**
     * Size constructor.
     *
     * @param float $width
     * @param float $height
     */
    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param float $width
     * @param float $height
     *
     * @return Size
     */
    public static function make(float $width, float $height): Size
    {
        return new Size($width, $height);
    }

    /**
     * @param float $width
     * @param float $height
     *
     * @return Size
     */
    public function add(float $width, float $height): Size
    {
        return new Size($this->width + $width, $this->height + $height);
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }
}
