<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StageRepository")
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
     * @ORM\Column(type="boolean")
     * @Assert\Type("boolean")
     */
    private $status;  

    /**
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="date")
     * @Assert\Date(message = "Data musi mieć format YYYY-MM-DD")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Goal", inversedBy="stages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $goal;

    public function  __construct()
    {
        if(isset($this->goal)) {
            $this->autoSetNumber();
        } else {
            $this->number = 1;
        }
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
}
     