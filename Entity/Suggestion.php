<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Suggestion
 *
 * @ORM\Table(name="suggestion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SuggestionRepository")
 */
class Suggestion
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
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plan_complete", type="datetime", nullable=true)
     */
    private $planComplete;

    /**
     * @var int
     *
     * @ORM\Column(name="suggest_status", type="smallint")
     */
    private $suggestStatus;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $user_id;

   /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     *
     * @ORM\OneToMany(targetEntity="Rate", mappedBy="suggestion", cascade={"persist", "remove"})
     * @var ArrayCollection $rates
     */
    private $rates;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->createdAt= new \DateTime();
        $this->updatedAt= new \DateTime();
        $this->rates = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * @param ArrayCollection $rates
     */
    public function setRates($rates)
    {
        /* @var Rate $rate */
        foreach($rates as $rate)  {
            $rate->setSuggestion($this);
        }
        $this->rates = $rates;
    }
    /**
     * @param Rate $rate
     */
    public function addRates(Rate $rate)
    {
        $this->rates[] = $rate;
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
     * Set category
     *
     * @param string $category
     *
     * @return Suggestion
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Suggestion
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Suggestion
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set planComplete
     *
     * @param \DateTime $planComplete
     *
     * @return Suggestion
     */
    public function setPlanComplete($planComplete)
    {
        $this->planComplete = $planComplete;

        return $this;
    }

    /**
     * Get planComplete
     *
     * @return \DateTime
     */
    public function getPlanComplete()
    {
        return $this->planComplete;
    }

    /**
     * Set suggestStatus
     *
     * @param integer $suggestStatus
     *
     * @return Suggestion
     */
    public function setSuggestStatus($suggestStatus)
    {
        $this->suggestStatus = $suggestStatus;

        return $this;
    }

    /**
     * Get suggestStatus
     *
     * @return int
     */
    public function getSuggestStatus()
    {
        return $this->suggestStatus;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return self
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

