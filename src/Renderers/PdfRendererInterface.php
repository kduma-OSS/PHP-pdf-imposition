<?php


namespace Kduma\PdfImposition\Renderers;


use Kduma\PdfImposition\DTO\DuplexPdfPage;
use Kduma\PdfImposition\DTO\PdfPage;
use Kduma\PdfImposition\PageLayoutConfiguration;

interface PdfRendererInterface
{
    public function start();
    public function render(PageLayoutConfiguration $page);
    public function finish(): string;

    /**
     * @param array|PdfPage[]|DuplexPdfPage[] $sources
     *
     * @return mixed
     */
    public function preload(array $sources);
}
