<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Entity\User;

/**
 * UserGroup
 *
 * @ORM\Table(name="userGroup")
 * @ORM\Entity
 */
class UserGroup
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
     * @ORM\Column(name="name", type="string", nullable=false, unique=true)
     */
    private $name;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="feeds")
     */
    private $user;
    
    /**
     * @ORM\OneToMany(targetEntity="Feed", mappedBy="group")
     *
     */
    private $feeds;


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
     * Set name.
     *
     * @param string $name
     *
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user.
     *
     * @param \Entity\User|null $user
     *
     * @return Group
     */
    public function setUser(\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->feeds = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add feed.
     *
     * @param \Entity\Feed $feed
     *
     * @return UserGroup
     */
    public function addFeed(\Entity\Feed $feed)
    {
        $this->feeds[] = $feed;

        return $this;
    }

    /**
     * Remove feed.
     *
     * @param \Entity\Feed $feed
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFeed(\Entity\Feed $feed)
    {
        return $this->feeds->removeElement($feed);
    }

    /**
     * Get feeds.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeeds()
    {
        return $this->feeds;
    }
}
