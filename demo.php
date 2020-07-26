<?php

use Kduma\PdfImposition\LayoutGenerators\Markers\HeadersMarkings;
use Kduma\PdfImposition\LayoutGenerators\Markers\PrinterBoxCutMarkings;
use Kduma\PdfImposition\LayoutGenerators\AutoGridPageLayoutGenerator;
use Kduma\PdfImposition\DTO\PageMargins;
use Kduma\PdfImposition\DTO\PageSize;
use Kduma\PdfImposition\PdfImposer;
use Kduma\PdfImposition\PdfSource;
use Kduma\PdfImposition\DTO\Size;

require __DIR__.'/vendor/autoload.php';

$cardSize = Size::make(90, 50);
$pageSize = PageSize::fromName('A4');
$pageMargins = PageMargins::make();

$layoutGenerator = new AutoGridPageLayoutGenerator($cardSize, 0, 0, $pageSize, $pageMargins);
$layoutGenerator->center();

$layoutGenerator = new PrinterBoxCutMarkings($layoutGenerator);
$layoutGenerator->setPrintEvery(5);

$layoutGenerator = new HeadersMarkings($layoutGenerator, 'Page {PAGENO}, Sheet {SHEET}');

$PdfImposer = new PdfImposer($layoutGenerator);

$cards = (new PdfSource)->read(__DIR__ . '/tests/test_input.pdf', true);
$PdfImposer->export($cards, 'output.pdf');
