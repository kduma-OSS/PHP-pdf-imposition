<?php


namespace Kduma\PdfImposition;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\DTO\PageSize;
use Kduma\PdfImposition\DTO\PageMargins;
use Kduma\PdfImposition\DTO\PdfPage;
use Kduma\PdfImposition\DTO\Text;

class PageLayoutConfiguration
{
    /**
     * @var array|Box[]
     */
    protected array $boxes = [];

    /**
     * @var array|PdfPage[]
     */
    protected array $sources = [];

    /**
     * @var array|Line[]
     */
    protected array $lines = [];

    /**
     * @var array|Text[]
     */
    protected array $texts = [];

    /**
     * @var PageSize
     */
    protected PageSize $pageSize;

    /**
     * @var PageMargins
     */
    protected PageMargins $margins;

    /**
     * @var string
     */
    protected string $header = '';

    /**
     * @var string
     */
    protected string $footer = '';

    /**
     * PageLayoutConfiguration constructor.
     *
     * @param PageSize    $size
     * @param PageMargins $margins
     */
    public function __construct(PageSize $size, PageMargins $margins)
    {
        $this->pageSize = $size;
        $this->margins = $margins;
    }

    /**
     * @param Box $box
     *
     * @return int
     */
    public function addBox(Box $box): int
    {
        $this->boxes[] = $box;
        return count($this->boxes) - 1;
    }

    /**
     * @param int $index
     *
     * @return Box|null
     */
    public function getBox(int $index): ?Box
    {
        return $this->boxes[$index] ?? null;
    }

    /**
     * @return array|Box[]
     */
    public function getBoxes(): array
    {
        return $this->boxes;
    }

    /**
     * @param array $sources
     *
     * @return PageLayoutConfiguration
     */
    public function setSources(array $sources): PageLayoutConfiguration
    {
        $this->sources = array_values($sources);

        return $this;
    }

    /**
     * @param PdfPage $source
     *
     * @return int
     */
    public function addSource(PdfPage $source): int
    {
        $this->sources[] = $source;
        return count($this->sources) - 1;
    }

    /**
     * @param int $index
     *
     * @return PdfPage|null
     */
    public function getSource(int $index): ?PdfPage
    {
        return $this->sources[$index] ?? null;
    }

    /**
     * @return array|PdfPage[]
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @param Line $line
     *
     * @return int
     */
    public function addLine(Line $line): int
    {
        $this->lines[] = $line;
        return count($this->lines) - 1;
    }

    /**
     * @param int $index
     *
     * @return Line|null
     */
    public function getLine(int $index): ?Line
    {
        return $this->lines[$index] ?? null;
    }

    /**
     * @return array|Line[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }


    /**
     * @param Text $text
     *
     * @return int
     */
    public function addText(Text $text): int
    {
        $this->texts[] = $text;
        return count($this->texts) - 1;
    }

    /**
     * @param int $index
     *
     * @return Text|null
     */
    public function getText(int $index): ?Text
    {
        return $this->texts[$index] ?? null;
    }

    /**
     * @return array|Text[]
     */
    public function getTexts(): array
    {
        return $this->texts;
    }

    /**
     * @return PageSize
     */
    public function getPageSize(): PageSize
    {
        return $this->pageSize;
    }

    /**
     * @return PageMargins
     */
    public function getMargins(): PageMargins
    {
        return $this->margins;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header)
    {
        $this->header = $header;
    }

    /**
     * @param string $footer
     */
    public function setFooter(string $footer)
    {
        $this->footer = $footer;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getFooter(): string
    {
        return $this->footer;
    }
}
