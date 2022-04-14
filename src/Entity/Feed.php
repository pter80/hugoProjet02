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
     * @ORM\Column(name="id", type="integer", nullable=false, unique=false)
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
}
