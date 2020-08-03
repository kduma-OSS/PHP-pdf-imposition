<?php


namespace Kduma\PdfImposition;


use Kduma\PdfImposition\DTO\DuplexPdfPage;
use Kduma\PdfImposition\DTO\PdfPage;
use Tightenco\Collect\Support\Collection;

class PdfSource
{
    /**
     * @param string $file
     * @param bool   $duplex
     *
     * @return array|PdfPage[]|DuplexPdfPage[]
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     */
    public function read(string $file, bool $duplex = false): array
    {
        $mpdf = new \Mpdf\Mpdf();
        $count = $mpdf->SetSourceFile($file);

        $pages = array_map(fn($page_number) => new PdfPage($file, $page_number), range(1, $count));

        if($duplex) {
            $pages = array_map(fn($page) => new DuplexPdfPage($page[0], $page[1]), array_chunk($pages, 2));
        }

        return $pages;
    }

    public function readAsCutStacks(string $file, int $boxes_count, int $reset_every = null, bool $duplex = false)
    {
        $reset_every ??= 10000;

        $pages = $this->read($file, $duplex);
        $pages = array_chunk($pages, $reset_every * $boxes_count);
        $pages = array_map(function ($group) use ($boxes_count) {
            $positions = [];

            foreach ($group as $key => $item) {
                $positions[$key % $boxes_count][] = $item;
            }

            return array_merge([], ...$positions);
        }, $pages);
        $pages = array_merge([], ...$pages);

        return $pages;
    }
}
