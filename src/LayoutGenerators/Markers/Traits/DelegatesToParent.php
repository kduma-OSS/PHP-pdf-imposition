<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers\Traits;


use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;

trait DelegatesToParent
{
    /**
     * @var PageLayoutGeneratorInterface
     */
    protected PageLayoutGeneratorInterface $parent;

    /**
     * @return int
     */
    public function getBoxesCount(): int
    {
        return $this->parent->getBoxesCount();
    }
}
