<?php


namespace Kduma\PdfImposition\DTO;


class PdfPage
{
    /**
     * @var string
     */
    protected string $file;

    /**
     * @var int
     */
    protected int $page;

    /**
     * PdfPage constructor.
     *
     * @param string $file
     * @param int    $page
     */
    public function __construct(string $file, int $page)
    {
        $this->file = $file;
        $this->page = $page;
    }

    /**
     * @param string $file
     * @param int    $page
     *
     * @return PdfPage
     */
    public static function make(string $file, int $page): PdfPage
    {
        return new PdfPage($file, $page);
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
