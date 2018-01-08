<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "Proszę wprowadzić kategorię")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Nazwa kategorii musi zawierać conajmniej 3 znaki",
     *      maxMessage = "Nazwa kategorii może zawierać maksymalnie 100 znaków"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Opis kategorii musi zawierać conajmniej 10 znaków",
     *      maxMessage = "Opis kategorii może zawierać maksymalnie 255 znaków"
     * )
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Goal", mappedBy="category")
     */
    private $goals;

    public function __construct()
    {
        $this->goals = new ArrayCollection();
    }    

    /**
     * @return Collection|Goal[]
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
