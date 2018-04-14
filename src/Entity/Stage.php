<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StageRepository")
 * @ORM\Table(name="stage", uniqueConstraints={@ORM\UniqueConstraint(name="search_user_stage", columns={"name", "userId"})})
 * @Serializer\ExclusionPolicy("none")
 * @UniqueEntity(
 *     fields={"name", "userId"},
 *     errorPath="name",
 *     message="Istnieje już poziom o takiej nazwie"
 * )
 */
class Stage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type("integer")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type("string")
     * @Assert\NotBlank(message = "Proszę wprowadzić nazwę")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Poziom musi zawierać conajmniej 3 znaki",
     *      maxMessage = "Poziom może zawierać maksymalnie 100 znaków"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     *      minMessage = "Nazwa nagrody musi zawierać conajmniej 3 znaki",
     *      maxMessage = "Nazwa nagrody może zawierać maksymalnie 100 znaków"
     * )
     */
    private $award;

     /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date(message = "Data musi mieć format YYYY-mm-dd")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Goal", inversedBy="stages")
     * @ORM\JoinColumn(nullable=true)
     * @Serializer\Exclude
     */
    private $goal;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User")
    * @ORM\JoinColumn(name="userId", referencedColumnName="id")
    * @Serializer\Exclude
    */
    private $userId;

    public function  __construct()
    {
        $this->status = true;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    public function autoSetNumber()
    {
        $this->number = count($this->goal->getStages()) + 1;
    }

    public function getGoal(): Goal
    {
        return $this->goal;
    }

    public function setGoal(Goal $goal)
    {
        $this->goal = $goal;

        $this->autoSetNumber();
    }

     /**
     * Get the value of number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set the value of number
     *
     * @return  self
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
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
        switch ($status) {
            case "true":
                $status = true;
            break;
            case "false":
                $status = false;
            break;
        }

        $this->status = $status;

        return $this;
    }

    /**
     * Get min = 3,
     */
    public function getAward()
    {
        return $this->award;
    }

    /**
     * Set min = 3,
     *
     * @return  self
     */
    public function setAward($award)
    {
        $this->award = $award;

        return $this;
    }

    /**
     * Get the value of endDate
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     *
     * @return  self
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */
    public function setUserId(User $user)
    {
        $this->userId = $user;

        return $this;
    }
}
