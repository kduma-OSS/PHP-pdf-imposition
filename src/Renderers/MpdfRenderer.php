<?php


namespace Kduma\PdfImposition\Renderers;


use Kduma\PdfImposition\DTO\DuplexPdfPage;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\DTO\PdfPage;
use Kduma\PdfImposition\DTO\Text;
use Kduma\PdfImposition\PageLayoutConfiguration;
use Tightenco\Collect\Support\Collection;

class MpdfRenderer implements PdfRendererInterface
{
    private \Mpdf\Mpdf $mpdf;
    private array $templates = [];

    public function start()
    {
        $this->mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
        ]);

        $this->templates = [];
    }

    public function preload(array $sources)
    {
        $sources = array_map(fn($p) => $p instanceof DuplexPdfPage ? [$p->getFront(), $p->getBack()] : [$p], $sources);
        $sources = array_merge([], ...$sources);

        $files = [];

        /** @var PdfPage $p */
        foreach ($sources as $p) {
            $files[$p->getFile()][] = $p;
        }

        foreach ($files as $file => $pages) {
            $this->mpdf->SetSourceFile($file);
            $pages = array_map(fn(PdfPage $p) => $p->getPage(), $pages);
            $pages = array_unique($pages);
            foreach ($pages as $page_number) {
                $key = "{$file}:{$page_number}";

                if(!isset($this->templates[$key])) {
                    $this->templates[$key] = $this->mpdf->ImportPage($page_number);
                }
            }
        }
    }

    public function render(PageLayoutConfiguration $page)
    {
        $this->mpdf->defaultheaderfontsize=8;
        $this->mpdf->defaultheaderfontstyle='';
        $this->mpdf->defaultheaderline=0;

        $this->mpdf->defaultfooterfontsize=8;
        $this->mpdf->defaultfooterfontstyle='';
        $this->mpdf->defaultfooterline=0;

        $this->mpdf->SetHeader($page->getHeader());
        $this->mpdf->SetFooter($page->getFooter());


        $this->mpdf->AddPageByArray([
            'format' => [$page->getPageSize()->getSize()->getWidth(), $page->getPageSize()->getSize()->getHeight()],
            'orientation' => $page->getPageSize()->isLandscape() ? 'L' : 'P',

            'margin_left' => $page->getMargins()->getLeft(),
            'margin_right' => $page->getMargins()->getRight(),
            'margin_top' => $page->getMargins()->getTop(),
            'margin_bottom' => $page->getMargins()->getBottom(),
            'margin_header' => $page->getMargins()->getHeader(),
            'margin_footer' => $page->getMargins()->getFooter(),
        ]);

        foreach ($page->getBoxes() as $index => $box) {
            $source = $page->getSource($index);

            if(null !== $source) {
                $key = "{$source->getFile()}:{$source->getPage()}";

                if (!isset($this->templates[$key])) {
                    $this->mpdf->SetSourceFile($source->getFile());
                    $this->templates[$key] = $this->mpdf->ImportPage($source->getPage());
                }

                $this->mpdf->UseTemplate(
                    $this->templates[$key],
                    $box->getPoint()->getX(),
                    $box->getPoint()->getY(),
                    $box->getSize()->getWidth(),
                    $box->getSize()->getHeight()
                );
            }
        }

        foreach ($page->getLines() as $line) {
            if($line->getWidth() != $this->mpdf->LineWidth)
                $this->mpdf->SetLineWidth($line->getWidth());

            $this->mpdf->line(
                $line->getStart()->getX(),
                $line->getStart()->getY(),
                $line->getEnd()->getX(),
                $line->getEnd()->getY()
            );
        }

        /** @var Text $text */
        foreach ($page->getTexts() as $text) {
            if($text->getSize() != $this->mpdf->FontSize)
                $this->mpdf->SetFontSize($text->getSize());

            $this->mpdf->WriteText(
                $text->getPoint()->getX(),
                $text->getPoint()->getY(),
                $text->getText()
            );
        }
    }

    public function finish(): string
    {
        return $this->mpdf->Output('', 'S');
    }
}

