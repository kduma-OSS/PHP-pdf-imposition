<?php

namespace Kduma\PdfImposition;

use Kduma\PdfImposition\DTO\DuplexPdfPage;
use Kduma\PdfImposition\DTO\PageMargins;
use Kduma\PdfImposition\DTO\PageSize;
use Kduma\PdfImposition\DTO\Size;
use Kduma\PdfImposition\LayoutGenerators\AutoGridPageLayoutGenerator;
use Kduma\PdfImposition\LayoutGenerators\Markers\PrinterBoxCutMarkings;
use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;
use Kduma\PdfImposition\Renderers\MpdfRenderer;
use Kduma\PdfImposition\Renderers\PdfRendererInterface;
use Tightenco\Collect\Support\Collection;

class PdfImposer
{
    /**
     * @var PageLayoutGeneratorInterface
     */
    protected PageLayoutGeneratorInterface $layoutGenerator;

    /**
     * @var PdfRendererInterface
     */
    protected PdfRendererInterface $renderer;

    public function __construct(PageLayoutGeneratorInterface $layoutGenerator, PdfRendererInterface $renderer = null)
    {
        $this->renderer = $renderer ?? new MpdfRenderer();
        $this->layoutGenerator = $layoutGenerator;
    }

    public function export(array $cards, string $output_file): void
    {
        $is_duplex = count(array_filter($cards, fn($source) => $source instanceof DuplexPdfPage)) != 0;

        $this->renderer->start();
        $this->renderer->preload($cards);

        $page_number = 1;
        $cards = array_chunk($cards, $this->layoutGenerator->getBoxesCount());
        $cards = array_map(function ($boxes) use (&$page_number, $is_duplex) {
            if ($is_duplex) {
                return [
                    $this->layoutGenerator->getLayout(false, $page_number, count($boxes))->setSources(
                        array_map(fn($page) => $page instanceof DuplexPdfPage ? $page->getFront() : $page, $boxes)
                    ),
                    $this->layoutGenerator->getLayout(true, $page_number++, count($boxes))->setSources(
                        array_map(fn($page) => $page instanceof DuplexPdfPage ? $page->getBack() : $page, $boxes)
                    )
                ];
            } else {
                return [
                    $this->layoutGenerator->getLayout(false, $page_number++, count($boxes))->setSources(
                        $boxes
                    )
                ];
            }
        }, $cards);
        $cards = array_merge([], ...$cards);

        /** @var PageLayoutConfiguration $layout */
        foreach ($cards as $layout) {
            $this->renderer->render($layout);
        }

        file_put_contents($output_file, $this->renderer->finish());
    }
}
