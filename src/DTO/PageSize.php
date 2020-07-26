<?php


namespace Kduma\PdfImposition\DTO;


use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\PageFormat;

class PageSize
{
    /**
     * @var Size
     */
    protected Size $size;

    /**
     * @var bool
     */
    private bool  $landscape;

    /**
     * PageSize constructor.
     *
     * @param Size $size
     * @param bool $landscape
     */
    public function __construct(Size $size, bool $landscape = false)
    {
        $this->landscape = $landscape;
        $this->size = $size;
    }

    /**
     * @param string $format
     * @param bool   $landscape
     *
     * @return PageSize
     * @throws MpdfException
     */
    public static function fromName(string $format, bool $landscape = false): PageSize
    {
        $size = PageFormat::getSizeFromName($format);

        return new PageSize(Size::make($size[0]/Mpdf::SCALE, $size[1]/Mpdf::SCALE), $landscape);
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * @return bool
     */
    public function isLandscape(): bool
    {
        return $this->landscape;
    }
}
