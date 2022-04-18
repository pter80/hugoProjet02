<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Entity\UserGroup;

/**
 * Feed
 *
 * @ORM\Table(name="feed")
 * @ORM\Entity
 */
class Feed
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="feedRSS", type="text", length=65535,nullable=true)
     */
    private $feedRSS;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false, unique=false)
     */
    private $title;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserGroup", inversedBy="feeds")
     */
    private $group;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set feedRSS.
     *
     * @param string|null $feedRSS
     *
     * @return Feed
     */
    public function setFeedRSS($feedRSS = null)
    {
        $this->feedRSS = $feedRSS;

        return $this;
    }

    /**
     * Get feedRSS.
     *
     * @return string|null
     */
    public function getFeedRSS()
    {
        return $this->feedRSS;
    }

    /**
     * Set group.
     *
     * @param \Entity\UserGroup|null $group
     *
     * @return Feed
     */
    public function setGroup(\Entity\UserGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group.
     *
     * @return \Entity\UserGroup|null
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Feed
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
