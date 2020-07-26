<?php


namespace Kduma\PdfImposition\LayoutGenerators\Markers;


use Kduma\PdfImposition\DTO\Box;
use Kduma\PdfImposition\DTO\Line;
use Kduma\PdfImposition\DTO\Point;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\DelegatesToParent;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\ExtractsCrossPoints;
use Kduma\PdfImposition\LayoutGenerators\Markers\Traits\SelectsSides;
use Kduma\PdfImposition\LayoutGenerators\PageLayoutGeneratorInterface;
use Kduma\PdfImposition\PageLayoutConfiguration;

class PrinterBoxCutMarkings implements PageLayoutGeneratorInterface
{
    use ExtractsCrossPoints, DelegatesToParent, SelectsSides;

    /**
     * BoxCrossesMarkings constructor.
     *
     * @param PageLayoutGeneratorInterface $parent
     * @param float                        $margin
     * @param float                        $inside
     * @param float                        $length
     * @param float                        $bleeds
     * @param float                        $width
     */
    public function __construct(PageLayoutGeneratorInterface $parent, float $margin = 5, float $inside = 2, float $length = 25, float $bleeds = 0, float $width = 0.05)
    {
        $this->parent = $parent;
        $this->bleeds = $bleeds;
        $this->margin = $margin;
        $this->length = $length;
        $this->inside = $inside;
        $this->width = $width;
    }

    protected float $bleeds;
    protected float $margin;
    protected float $inside;
    protected float $width;
    protected float $length;

    public function getLayout(bool $back = false, ?int $page_number = null, ?int $limit = null): PageLayoutConfiguration
    {
        $layout = $this->parent->getLayout($back, $page_number, $limit);

        if(!$this->printOn($back, $page_number))
            return $layout;

        [$horizontal, $vertical] = $this->extractCrossPoints($layout, $this->bleeds);

        $verticalFirst = count($horizontal) > count($vertical);

        if($verticalFirst) {
            foreach ($vertical as $position => $values) {
                $layout->addLine(
                    Line::vertical(
                        Point::make((float)$position, min($values))->add(0, -$this->margin),
                        -$this->length,
                        $this->width
                    )
                );
                $layout->addLine(
                    Line::vertical(
                        Point::make((float)$position, max($values))->add(0, $this->margin),
                        $this->length,
                        $this->width
                    )
                );
            }
        } else {
            foreach ($horizontal as $position => $values) {
                $layout->addLine(
                    Line::horizontal(
                        Point::make(min($values), (float)$position)->add(-$this->margin, 0),
                        -$this->length,
                        $this->width
                    )
                );
                $layout->addLine(
                    Line::horizontal(
                        Point::make(max($values), (float)$position)->add($this->margin, 0),
                        $this->length,
                        $this->width
                    )
                );
            }
        }


        if($verticalFirst) {
            foreach ($horizontal as $position => $values) {
                $layout->addLine(
                    Line::horizontal(
                        Point::make(min($values), (float)$position)->add($this->inside, 0),
                        -$this->bleeds - $this->inside * 2,
                        $this->width
                    )
                );
                $layout->addLine(
                    Line::horizontal(
                        Point::make(max($values), (float)$position)->add(-$this->inside, 0),
                        $this->bleeds + $this->inside * 2,
                        $this->width
                    )
                );

                collect($values)
                    ->unique()
                    ->reject(fn($v) => $v == min($values) || $v == max($values))
                    ->each(function ($v) use ($position, $layout) {
                        $layout->addLine(
                            Line::horizontal(
                                Point::make($v, (float)$position)->add(-$this->inside, 0),
                                $this->inside * 2,
                                $this->width
                            )
                        );
                    });
            }
        } else {
            foreach ($vertical as $position => $values) {
                $layout->addLine(
                    Line::vertical(
                        Point::make((float)$position, min($values))->add(0, $this->inside),
                        -$this->bleeds - $this->inside * 2,
                        $this->width
                    )
                );
                $layout->addLine(
                    Line::vertical(
                        Point::make((float)$position, max($values))->add(0, -$this->inside),
                         $this->bleeds + $this->inside * 2,
                        $this->width
                    )
                );

                collect($values)
                    ->unique()
                    ->reject(fn($v) => $v == min($values) || $v == max($values))
                    ->each(function ($v) use ($position, $layout) {
                        $layout->addLine(
                            Line::vertical(
                                Point::make((float)$position, $v)->add(-$this->inside, 0),
                                $this->inside * 2,
                                $this->width
                            )
                        );
                    });
            }
        }

        return $layout;
    }
}
