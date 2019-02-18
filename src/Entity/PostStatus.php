<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
/**
 * @ORM\Entity(repositoryClass="App\Repository\PostStatusRepository")
 * @ORM\Table(name="blog_status")
 */
class PostStatus extends AbstractTaxonomy {
    
    /**
     * @ORM\OneToMany(
     *      targetEntity = "Post",
     *      mappedBy = "status"
     * )
     */
    protected $posts;
    
    
}
