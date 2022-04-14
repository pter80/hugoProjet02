<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Entity\UserGroup;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
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
     * @ORM\Column(name="username", type="string", nullable=false, unique=false)
     */
    private $username;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=false, unique=false)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", nullable=false, unique=false)
     */
    private $genre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=false, unique=false)
     */
    private $password;
    
    /**
     * @ORM\OneToMany(targetEntity="UserGroup", mappedBy="user")
     *
     */
    private $userGroups;
    

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
     * Set username.
     *
     * @param string $username
     *
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set genre.
     *
     * @param string $genre
     *
     * @return User
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre.
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userGroups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userGroup.
     *
     * @param \Entity\UserGroup $userGroup
     *
     * @return User
     */
    public function addUserGroup(\Entity\UserGroup $userGroup)
    {
        $this->userGroups[] = $userGroup;

        return $this;
    }

    /**
     * Remove userGroup.
     *
     * @param \Entity\UserGroup $userGroup
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUserGroup(\Entity\UserGroup $userGroup)
    {
        return $this->userGroups->removeElement($userGroup);
    }

    /**
     * Get userGroups.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }
}
