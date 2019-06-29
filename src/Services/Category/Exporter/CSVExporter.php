<?php

namespace App\Services\Category\Exporter;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Category\Field;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class CSVExporter implements ExporterInterface
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
        /** @var Account $account */
        $account = $accounts[0];

        $allFieldsList = $account->getCategory()->getAllFields();

        $fields = [
            array_merge(
                ['ID', 'Login', 'Password'],
                $allFieldsList->map(function(Field $field) {
                    return $field->getName();
                })->toArray()
            )
        ];

        foreach($accounts as $account) {
            $additionalValues = $allFieldsList->map(function (Field $field) use($account) {
                return $account->getFieldValue($field->getId());
            })->toArray();

            $fields[] = array_merge(
                [$account->getId(), $account->getLogin(), $account->getPassword()],
                $additionalValues
            );
        }

        return new Response(
            $this->twig->render('system/exporters/csv_exporter.html.twig', [
                'fields' => $fields
            ])
        );
    }
}
