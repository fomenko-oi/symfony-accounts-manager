<?php

namespace App\UseCase\Category;

use App\Services\Category\Exporter\CSVExporter;
use App\Services\Category\Exporter\SimpleExporter;
use Symfony\Bridge\Twig\TwigEngine;
use Twig\Environment;

class ExporterFactory
{
    const EXPORT_SIMPLE_TYPE = 'simple';
    const EXPORT_CSV_TYPE = 'csv';

    /**
     * @var TwigEngine
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getRenderer($type)
    {
        if(!in_array($type, self::getExportTypes())) {
            throw new \InvalidArgumentException("Unable to recognize {$type}.");
        }

        if($type === self::EXPORT_SIMPLE_TYPE) {
            return new SimpleExporter($this->twig);
        }

        if($type === self::EXPORT_CSV_TYPE) {
            return new CSVExporter($this->twig);
        }

        throw new \LogicException("Undefined type {$type}.");
    }

    public static function getExportTypes()
    {
        return [self::EXPORT_CSV_TYPE, self::EXPORT_SIMPLE_TYPE];
    }
}
