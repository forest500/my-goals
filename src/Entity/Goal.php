<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GoalRepository")
 */
class Goal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "Proszę wprowadzić kategorie")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Nazwa kategorii musi zawierać conajmniej 3 znaki",
     *      maxMessage = "Nazwa kategorii może zawierać maksymalnie 100 znaków"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="goals")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stage", mappedBy="goal")
     */
    private $stages; 

    public function __construct()
    {
        $this->stages = new ArrayCollection();
    }    

    /**
     * @return Collection|Stage[]
     */
    public function getStages()
    {
        return $this->stages;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get min = 3,
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set min = 3,
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of categoryId
     */ 
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set the value of categoryId
     *
     * @return  self
     */ 
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }
}
