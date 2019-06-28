<?php

namespace App\Entity\Account;

use App\Entity\Category\Field;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\FieldValueRepository")
 * @ORM\Table(name="account_fields_values")
 * @UniqueEntity(fields={"field_id", "account_id"})
 */
class FieldValue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Category\Field", cascade={"ALL"})
     */
    private $field;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", cascade={"ALL"})
     */
    private $account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getField(): ?Field
    {
        return $this->field;
    }

    public function setField($field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
