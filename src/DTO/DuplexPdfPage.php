<?php


namespace Kduma\PdfImposition\DTO;


class DuplexPdfPage
{
    protected PdfPage $front;
    protected PdfPage $back;

    public function __construct(PdfPage $front, PdfPage $back)
    {
        $this->front = $front;
        $this->back = $back;
    }

    public static function make(PdfPage $front, PdfPage $back): DuplexPdfPage
    {
        return new DuplexPdfPage($front, $back);
    }

    /**
     * @return PdfPage
     */
    public function getFront(): PdfPage
    {
        return $this->front;
    }

    /**
     * @return PdfPage
     */
    public function getBack(): PdfPage
    {
        return $this->back;
    }
}
