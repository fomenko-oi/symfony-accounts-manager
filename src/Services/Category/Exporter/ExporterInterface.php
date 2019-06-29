<?php

namespace App\Services\Category\Exporter;

use Symfony\Component\HttpFoundation\Response;

interface ExporterInterface
{
    public function render(array $accounts): Response;
}
