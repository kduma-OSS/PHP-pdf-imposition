<?php


namespace Kduma\PdfImposition\DTO;


class PageMargins
{
    /**
     * @var float
     */
    private float $left;

    /**
     * @var float
     */
    private float $right;

    /**
     * @var float
     */
    private float $top;

    /**
     * @var float
     */
    private float $bottom;

    /**
     * @var float
     */
    private float $header;

    /**
     * @var float
     */
    private float $footer;

    /**
     * PageMargins constructor.
     *
     * @param float $left
     * @param float $right
     * @param float $top
     * @param float $bottom
     * @param float $header
     * @param float $footer
     */
    public function __construct(float $left = 15, float $right = 15, float $top = 16, float $bottom = 16, float $header = 9, float $footer = 9)
    {
        $this->left = $left;
        $this->right = $right;
        $this->top = $top;
        $this->bottom = $bottom;
        $this->header = $header;
        $this->footer = $footer;
    }

    /**
     * @param float $left
     * @param float $right
     * @param float $top
     * @param float $bottom
     * @param float $header
     * @param float $footer
     *
     * @return PageMargins
     */
    public static function make(float $left = 15, float $right = 15, float $top = 16, float $bottom = 16, float $header = 9, float $footer = 9): PageMargins
    {
        return new PageMargins($left, $right, $top, $bottom, $header, $footer);
    }

    /**
     * @param float $horizontal
     * @param float $vertical
     * @param float $header
     * @param float $footer
     *
     * @return PageMargins
     */
    public static function makeByAxis(float $horizontal = 15, float $vertical = 16, float $header = 9, float $footer = 9): PageMargins
    {
        return new PageMargins($horizontal, $horizontal, $vertical, $vertical, $header, $footer);
    }

    /**
     * @return PageMargins
     */
    public function flip(): PageMargins
    {
        return new PageMargins($this->right, $this->left, $this->top, $this->bottom, $this->header, $this->footer);
    }

    /**
     * @return float
     */
    public function getLeft(): float
    {
        return $this->left;
    }

    /**
     * @return float
     */
    public function getRight(): float
    {
        return $this->right;
    }

    /**
     * @return float
     */
    public function getTop(): float
    {
        return $this->top;
    }

    /**
     * @return float
     */
    public function getBottom(): float
    {
        return $this->bottom;
    }

    /**
     * @return float
     */
    public function getHeader(): float
    {
        return $this->header;
    }

    /**
     * @return float
     */
    public function getFooter(): float
    {
        return $this->footer;
    }
}
