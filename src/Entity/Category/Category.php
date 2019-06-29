<?php

namespace App\Entity\Category;

use App\Entity\Account\Account;
use App\Entity\Account\FieldValue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Category\CategoryRepository")
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="Category")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account\Account", mappedBy="category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $accounts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category\Field")
     * @ORM\JoinTable(
     *     name="categories_fields",
     *     joinColumns={
     *          @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     *     }
     * )
     */
    private $categoryFields;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->categoryFields = new ArrayCollection();
        $this->categoryFieldsValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getAllFields()
    {
        return new ArrayCollection(array_merge(
            $this->categoryFields->toArray(),
            $this->getParentFields()->toArray()
        ));
    }

    /**
     * @return Collection|Field[]
     */
    public function getParentFields()
    {
        return $this->parent ? $this->getParent()->getAllFields() : new ArrayCollection();
    }

    /**
     * @return Collection|Field[]
     */
    public function getOwnFields(): Collection
    {
        return $this->categoryFields;
    }

    public function addCategoryField(Field $categoryField): self
    {
        if (!$this->categoryFields->contains($categoryField)) {
            $this->categoryFields[] = $categoryField;
        }

        return $this;
    }

    public function getCategoryFields()
    {
        return $this->categoryFields;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setCategory($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->contains($account)) {
            $this->accounts->removeElement($account);
            // set the owning side to null (unless already changed)
            if ($account->getCategory() === $this) {
                $account->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
