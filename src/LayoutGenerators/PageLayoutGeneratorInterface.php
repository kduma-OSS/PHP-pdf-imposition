<?php


namespace Kduma\PdfImposition\LayoutGenerators;


use Kduma\PdfImposition\PageLayoutConfiguration;

interface PageLayoutGeneratorInterface
{
    public function getLayout(bool $back = false, ?int $page_number = null, ?int $limit = null): PageLayoutConfiguration;
    public function getBoxesCount(): int;
}
