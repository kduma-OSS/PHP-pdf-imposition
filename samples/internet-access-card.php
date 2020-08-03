<?php

use Kduma\PdfImposition\LayoutGenerators\Markers\HeadersMarkings;
use Kduma\PdfImposition\LayoutGenerators\Markers\PrinterBoxCutMarkings;
use Kduma\PdfImposition\LayoutGenerators\AutoGridPageLayoutGenerator;
use Kduma\PdfImposition\DTO\PageMargins;
use Kduma\PdfImposition\DTO\PageSize;
use Kduma\PdfImposition\PdfImposer;
use Kduma\PdfImposition\PdfSource;
use Kduma\PdfImposition\DTO\Size;

require __DIR__.'/../vendor/autoload.php';

$cardSize = Size::make(95, 55);
$pageSize = PageSize::fromName('A4');
$pageMargins = PageMargins::make(0,0,0,0, 5, 5);
$cut_every_pages = 5;

$layoutGenerator = new AutoGridPageLayoutGenerator($cardSize, 0, 0, $pageSize, $pageMargins);
$layoutGenerator->center();

$layoutGenerator = new \Kduma\PdfImposition\LayoutGenerators\Markers\OutsideBoxCutMarkings($layoutGenerator, 2.5, 50, 1.5);

$PdfImposer = new PdfImposer($layoutGenerator);

$cards = (new PdfSource)->readAsCutStacks(__DIR__ . '/internet-access-card.pdf', $layoutGenerator->getBoxesCount(), $cut_every_pages, true);
$PdfImposer->export($cards, __DIR__ . '/internet-access-card.output.pdf');
