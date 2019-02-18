<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="blog_categories")
 */
class Category extends AbstractTaxonomy
{
    /**
     * @ORM\OneToMany(
     *      targetEntity = "Post",
     *      mappedBy = "category"
     * )
     */
    protected $posts;
}
