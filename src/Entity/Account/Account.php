<?php

namespace App\Entity\Account;

use App\Entity\Category\Category;
use App\Entity\Account\FieldValue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\AccountRepository")
 * @ORM\Table(name="accounts")
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category\Category", inversedBy="accounts", fetch="LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(name="login")
     */
    private $login;

    /**
     * @ORM\Column(name="password")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account\FieldValue", mappedBy="account", fetch="LAZY")
     * @ORM\JoinTable(
     *     name="account_fields_values",
     *     joinColumns={
     *          @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     *     }
     * )
     */
    private $fieldValues;

    public function __construct(Category $category)
    {
        $this->category = $category;
        $this->fieldValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addFieldValue(FieldValue $value)
    {
        if (!$this->fieldValues->contains($value)) {
            $this->fieldValues[] = $value;
            $value->setAccount($this);
        }

        return $this;
    }

    public function getFieldValue($fieldId)
    {
        /** @var FieldValue $field */
        foreach ($this->fieldValues as $field) {
            if($field->getId() === $fieldId) {
                return $field->getValue();
            }
        }

        return null;
    }

    /**
     * @return Collection|FieldValue[]
     */
    public function getFieldsValues()
    {
        return $this->fieldValues;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

}
