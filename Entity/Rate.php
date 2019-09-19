<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Rate
 *
 * @ORM\Table(name="rate")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RateRepository")
 */
class Rate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="suggest_id", type="integer")
     */
    private $suggestId;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float")
     */
    private $rate;

   /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var Suggestion $suggestion
     * @Assert\NotBlank(message="rate.suggestion.not_blank", groups={"Validate"})
     * @ORM\ManyToOne(targetEntity="Suggestion")
     * @ORM\JoinColumn(name="suggest_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $suggestion;


    public function __construct()
    {
        $this->createdAt= new \DateTime();
        $this->updatedAt= new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getSuggestion()
    {
        return $this->suggestion;
    }

    /**
     * @param mixed $suggestion
     */
    public function setSuggestion($suggestion)
    {
        $this->suggestion = $suggestion;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt= new \DateTime();
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Rate
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set suggestId
     *
     * @param integer $suggestId
     *
     * @return Rate
     */
    public function setSuggestId($suggestId)
    {
        $this->suggestId = $suggestId;

        return $this;
    }

    /**
     * Get suggestId
     *
     * @return int
     */
    public function getSuggestId()
    {
        return $this->suggestId;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     *
     * @return Rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }
}

