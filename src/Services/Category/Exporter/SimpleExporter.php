<?php

namespace App\Services\Category\Exporter;

use App\Entity\Category\Category;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class SimpleExporter implements ExporterInterface
{
    /**
     * @var TwigEngine
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(array $accounts): Response
    {
        return new Response(
            $this->twig->render('system/exporters/simple_exporter.html.twig', [
                'accounts' => $accounts
            ])
        );
    }
}
