<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="blog_posts")
 * @ORM\HasLifecycleCallbacks
 *
 * @UniqueEntity(fields={"title"})
 * @UniqueEntity(fields={"slug"})
 */
class Post
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 120
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1000, unique=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 120
     * )
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 60
     * )
     */
    private $metaTitle;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 160
     * )
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 100,
     *      max = 500
     * )
     */
    private $introductionContent;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 500000
     * )
     */
    private $content;

    /**
     * @Assert\Image(
     *      minWidth = 500,
     *      minHeight = 300,
     *      maxWidth = 1920,
     *      maxHeight = 1080,
     *      maxSize = "1M"
     * )
     */
    private $thumbnail;

    /**
     * @ORM\ManyToOne(
     *      targetEntity = "Category",
     *      inversedBy = "posts"
     * )
     *
     * @ORM\JoinColumn(
     *      name = "category_id",
     *      referencedColumnName = "id",
     *      onDelete = "SET NULL"
     * )
     *
     * @Assert\NotBlank
     */
    private $category;

    /**
     * @ORM\ManyToOne(
     *      targetEntity = "PostStatus",
     *      inversedBy = "posts"
     * )
     *
     * @ORM\JoinColumn(
     *      name = "status_id",
     *      referencedColumnName = "id",
     *      onDelete = "SET NULL"
     * )
     *
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @ORM\ManyToMany(
     *      targetEntity = "Tag",
     *      inversedBy = "posts"
     * )
     *
     * @ORM\JoinTable(
     *      name = "blog_tags"
     * )
     *
     * @Assert\Count(
     *      min=1,
     *      max=5
     * )
     */
    private $tags;

    /**
     * @ORM\ManyToOne(
     *      targetEntity = "App\Entity\User"
     * )
     *
     * @ORM\JoinColumn(
     *      name = "author_id",
     *      referencedColumnName = "id"
     * )
     */
    private $author;

    /**
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(name="published_date", type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $publishedDate = null;

    /**
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publishedDate = new \DateTime();
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
     * Set title.
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set metaTitle.
     *
     * @param string $metaTitle
     *
     * @return Post
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

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

    /**
     * Get $metaTitle.
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription.
     *
     * @param string $metaDescription
     *
     * @return Post
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get $metaDescription.
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = \App\Libs\Utils::sluggify($slug);

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
     * Set content.
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set introductionContent.
     *
     * @param string $content
     *
     * @return Post
     */
    public function setIntroductionContent($introductionContent)
    {
        $this->introductionContent = $introductionContent;

        return $this;
    }

    /**
     * Get introductionContent.
     *
     * @return string
     */
    public function getIntroductionContent()
    {
        return $this->introductionContent;
    }

    /**
     * Set thumbnail.
     *
     * @param string $thumbnail
     *
     * @return Post
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail.
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }


    /**
     * Set author.
     *
     * @param App\Entity\User $author
     *
     * @return Post
     */
    public function setAuthor(User  $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return App\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return Post
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $updateDate
     *
     * @return Post
     */
    public function setUpdateDate($createDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate.
     *
     * @return \DateTime
     */
    public function getupdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set publishedDate.
     *
     * @param \DateTime $publishedDate
     *
     * @return Post
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate.
     *
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Set category.
     *
     * @param App\Entity\Category $category
     *
     * @return Post
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return App\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set status.
     *
     * @param App\Entity\PostStatus $status
     *
     * @return Post
     */
    public function setStatus(PostStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return App\Entity\PostStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add tags.
     *
     * @param App\Entity\Tag $tags
     *
     * @return Post
     */
    public function addTag(App\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags.
     *
     * @param App\Entity\Tag $tags
     */
    public function removeTag(App\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
