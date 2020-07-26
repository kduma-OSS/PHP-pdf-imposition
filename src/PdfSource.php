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

        $pages = collect(range(1, $count))
            ->map(fn($page_number) => new PdfPage($file, $page_number));

        if($duplex) {
            $pages = $pages->chunk(2)->map(fn(Collection $row) => new DuplexPdfPage($row->first(), $row->last()));
        }

        return $pages->toArray();
    }
}
