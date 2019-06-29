<?php

namespace App\UseCase\Category;

use App\Entity\Category\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    /**
     * @var ExporterFactory
     */
    private $exporterFactory;

    public function __construct(ExporterFactory $exporterFactory)
    {
        $this->exporterFactory = $exporterFactory;
    }

    public function export(Category $category, string $type): Response
    {
        $data = $category->getAccounts()->toArray();

        return $this->exporterFactory->getRenderer($type)->render($data);
    }
}
