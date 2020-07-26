<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\DelegatesToParent;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\SelectsSides;
use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;
use Kduma\PdfImposition\PageLayoutConfiguration;

class HeadersMarkings implements PageLayoutGeneratorInterface
{
    use DelegatesToParent;

    private ?string $header;
    private ?string $footer;

    public function __construct(PageLayoutGeneratorInterface $parent, string $header = null, string $footer = null)
    {
        $this->parent = $parent;
        $this->header = $header;
        $this->footer = $footer;
    }

    public function getLayout(bool $back = false, ?int $page_number = null, ?int $limit = null): PageLayoutConfiguration
    {
        $layout = $this->parent->getLayout($back, $page_number, $limit);

        if ($this->header !== null)
            $layout->setHeader(str_replace(['{SHEET}', '{PARENT}'], [$page_number ?? '#', $layout->getHeader()], $this->header));

        if ($this->footer !== null)
            $layout->setFooter(str_replace(['{SHEET}', '{PARENT}'], [$page_number ?? '#', $layout->getFooter()], $this->footer));

        return $layout;
    }
}
