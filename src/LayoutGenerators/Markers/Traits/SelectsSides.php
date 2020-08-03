<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers\Traits;


use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;

trait SelectsSides
{
    /**
     * @var bool|null
     */
    protected ?bool $printOnFront = true;
    /**
     * @var int
     */
    protected int $printEvery = 1;

    /**
     * @return bool|null
     */
    public function getPrintOnFront(): ?bool
    {
        return $this->printOnFront;
    }

    /**
     * @param bool|null $printOnFront
     *
     * @return static
     */
    public function setPrintOnFront(?bool $printOnFront): self
    {
        $this->printOnFront = $printOnFront;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrintEvery(): int
    {
        return $this->printEvery;
    }

    /**
     * @param int $printEvery
     *
     * @return static
     */
    public function setPrintEvery(int $printEvery): self
    {
        $this->printEvery = $printEvery;
        return $this;
    }

    /**
     * @param bool $back
     *
     * @param int|null $page_number
     *
     * @return bool
     */
    protected function printOn(bool $back, ?int $page_number): bool
    {
        if($this->printOnFront !== null && (!$this->printOnFront || $back) && ($this->printOnFront || !$back))
            return false;

        if(($page_number - 1) % $this->printEvery) // to fix
            return false;

        return true;
    }
}
