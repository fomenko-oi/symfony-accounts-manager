<?php

namespace App\Entity\Category;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Category\FieldRepository")
 * @ORM\Table(name="fields")
 */
class Field
{
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_SELECT = 'select';

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\Column(type="json", nullable=true, length=1024)
     */
    private $variables;

    /**
     * @ORM\Column(type="boolean")
     */
    private $required = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category\Category", mappedBy="categoryFields")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->variables = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isTextarea()
    {
        return $this->type === self::TYPE_TEXTAREA;
    }

    public function isText()
    {
        return $this->type === self::TYPE_TEXT;
    }

    public function isSelect()
    {
        return $this->type === self::TYPE_SELECT && count($this->variables) > 0;
    }

    public function isRequired()
    {
        return $this->required === true;
    }

    public function setRequired(bool $isRequired)
    {
        $this->required = $isRequired;

        return $this;
    }

    public function getVariables(): ArrayCollection
    {
        return is_array($this->variables) ? new ArrayCollection($this->variables) : $this->variables;
    }

    public function setVariables(array $variables)
    {
        $this->variables = $variables;

        return $this;
    }

    public function addCategoryField(Category $category)
    {
        if(!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }
        return $this;
    }

    public static function getTypesList(): array
    {
        return [
            self::TYPE_TEXT => 'text',
            self::TYPE_SELECT => 'select',
            self::TYPE_TEXTAREA => 'textarea'
        ];
    }
}
