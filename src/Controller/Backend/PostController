<?php

namespace App\Controller\Backend;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\SearchPostType;
use App\DTO\SearchPost;
use App\Service\FileUploader;

/**
 * @Route("/panel/blog")
 */
class PostController extends AbstractController
{
    /**
     * @var int
     */
    private $defaultItemPage;

    public function __construct(int $defaultItemPage)
    {
        $this->defaultItemPage = $defaultItemPage;
    }

    /**
     * List of articles and a simple search engine after the category status and search field.
     *
     * @Route(
     *      "/{page}",
     *      name="panel_posts",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     *
     * @param Request            $request
     * @param PaginatorInterface $paginator
     * @param int                $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function posts(Request $request, PaginatorInterface $paginator, int $page)
    {
        $postRepository = $this->getDoctrine()->getRepository('App\Entity\Post');
        $searchPost = new SearchPost();

        $form = $this->createForm(SearchPostType::class, $searchPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $queryParams = [
                'search' => $searchPost->search,
                'category' => $searchPost->category,
                'status' => $searchPost->status,
                'tags' => $searchPost->tags,
                'orderBy' => 'post.publishedDate',
                'orderDir' => 'DESC',
            ];
            $qb = $postRepository->getQueryBuilder($queryParams);
            $posts = $paginator->paginate($qb, $page, $this->defaultItemPage);
        } else {
            $qb = $postRepository->getQueryBuilder(['orderBy' => 'post.publishedDate', 'orderDir' => 'DESC']);
            $posts = $paginator->paginate($qb, $page, $this->defaultItemPage);
        }

        return $this->render('backend/blog/posts.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
         ]);
    }

    /**
     * Add and Edit page Post.
     *
     * @Route(
     *      "/post/{slug}",
     *      name="panel_post",
     *      defaults={"slug"=NULL}
     * )
     *
     * @param Request             $request
     * @param string|null         $slug
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function post(Request $request, string $slug = null, TranslatorInterface $translator, FileUploader $fileUploader)
    {
        if (null === $slug) {
            $post = new Post();
//            $post->setAuthor($this->getUser());
            $newPostForm = true;
        } else {
            $post = $this->getDoctrine()->getRepository('App\Entity\Post')->findOneBy(['slug' => $slug]);
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getThumbnail()) {
                $file = $post->getThumbnail();
                $fileName = $fileUploader->upload($file);
                $post->setThumbnail($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $message = (isset($newPostForm)) ? $translator->trans('Post has been added') : $translator->trans('Post has been corrected');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('panel_post', ['slug' => $post->getSlug()]);
        } elseif ($form->isSubmitted() && false === $form->isValid()) {
            $this->addFlash('danger', $translator->trans('Corrects form'));
        }

        return $this->render('backend/blog/post.html.twig', [
            'currPage' => 'posts',
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * Delete post.
     *
     * @Route(
     *      "/delete/{id}/{token}",
     *      name="panel_post_delete",
     *      requirements={"id"="\d+"}
     * )
     *
     * @param int    $id
     * @param string $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(int $id, string $token, TranslatorInterface $translator)
    {
        try {
            if (!$this->isCsrfTokenValid('delete-item', $token)) {
                throw new Exception($translator->trans('Incorrect share token'));
            } else {
                $post = $this->getDoctrine()->getRepository('App\Entity\Post')->find($id);
                $em = $this->getDoctrine()->getManager();
                $em->remove($post);
                $em->flush();
                $post = $this->getDoctrine()->getRepository('App\Entity\Post')->find($id);

                if ($post) {
                    throw new Exception($translator->trans('There was an error we could not delete the article'));
                }
                $this->addFlash('success', $translator->trans('The article has been successfully removed'));

                return $this->redirectToRoute('panel_posts');
            }
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('panel_posts');
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
