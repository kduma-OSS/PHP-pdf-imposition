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
        $cards = collect($cards);

        $is_duplex = !! $cards->filter(fn($source) => $source instanceof DuplexPdfPage)->count();

        $this->renderer->start();
        $this->renderer->preload($cards->toArray());

        $page_number = 1;
        $cards
            ->chunk($this->layoutGenerator->getBoxesCount())
            ->flatMap(function (Collection $boxes) use (&$page_number, $is_duplex) {
                if ($is_duplex) {
                    return [
                        $this->layoutGenerator->getLayout(false, $page_number, $boxes->count())->setSources(
                            $boxes
                                ->map(fn($page) => $page instanceof DuplexPdfPage ? $page->getFront() : $page)
                                ->toArray()
                        ),
                        $this->layoutGenerator->getLayout(true, $page_number++, $boxes->count())->setSources(
                            $boxes
                                ->map(fn($page) => $page instanceof DuplexPdfPage ? $page->getBack() : $page)
                                ->toArray()
                        )
                    ];
                } else {
                    return [
                        $this->layoutGenerator->getLayout(false, $page_number++, $boxes->count())->setSources(
                            $boxes->toArray()
                        )
                    ];
                }
            })
            ->each(fn(PageLayoutConfiguration $layout) => $this->renderer->render($layout));

        file_put_contents($output_file, $this->renderer->finish());
    }
}
