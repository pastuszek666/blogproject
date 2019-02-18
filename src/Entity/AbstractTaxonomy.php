<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Util\Slugger;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractTaxonomy
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=120, unique=true)
     */
    private $slug;

    protected $posts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add posts.
     *
     * @param \App\Entity\Post $posts
     *
     * @return Category
     */
    public function addPost(App\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts.
     *
     * @param \App\Entity\Post $posts
     */
    public function removePost(App\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

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
     * @return AbstractTaxonomy
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return AbstractTaxonomy
     */
    public function setSlug($slug)
    {
        $this->slug = Slugger::sluggify($slug);

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave()
    {
        if (null === $this->slug) {
            $this->setSlug($this->getName());
        }
    }

    /**
     * Generates the magic method.
     */
    public function __toString()
    {
        return $this->name;
    }
}
