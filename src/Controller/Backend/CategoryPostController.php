<?php

namespace App\Controller\Backend;

use App\Form\CategoryDeleteType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Category;
use App\Form\TaxonomyType;

/**
 * @Route("/panel/blog/categories")
 */
class CategoryPostController extends AbstractController
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
     * List of Categories.
     *
     * @Route(
     *      "/{page}",
     *      name="panel_categories",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     *
     * @param PaginatorInterface $paginator
     * @param int                $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categories(PaginatorInterface $paginator, int $page)
    {
        $categoryRepository = $this->getDoctrine()->getRepository('App\Entity\Category');
        $queryParams = [
                'orderBy' => 't.name',
                'orderDir' => 'ASC',
            ];
        $qb = $categoryRepository->getQueryBuilder($queryParams);
        $categories = $paginator->paginate($qb, $page, $this->defaultItemPage);

        return $this->render('backend/blog/categories.html.twig', [
            'categories' => $categories,
         ]);
    }

    /**
     * Add and Edit page Category.
     *
     * @Route(
     *      "/category/{slug}",
     *      name="panel_category",
     *      defaults={"slug"=NULL}
     * )
     *
     * @param Request             $request
     * @param string|null         $slug
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function category(Request $request, string $slug = null, TranslatorInterface $translator)
    {
        if (null === $slug) {
            $category = new Category();
            $newCategoryForm = true;
        } else {
            $category = $this->getDoctrine()->getRepository('App\Entity\Category')->findOneBy(['slug' => $slug]);
        }

        $form = $this->createForm(TaxonomyType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $message = (isset($newCategoryForm)) ? $translator->trans('Category has been added') : $translator->trans('Category has been corrected');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('panel_category', ['slug' => $category->getSlug()]);
        } elseif ($form->isSubmitted() && false === $form->isValid()) {
            $this->addFlash('error', $translator->trans('Corrects form'));
        }

        return $this->render('backend/blog/category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    /**
     * Delete category.
     *
     * @Route(
     *      "/delete/{id}",
     *      name="panel_category_delete",
     *      requirements={"id"="\d+"}
     * )
     *
     * @param int                 $id
     * @param Request             $request
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(int $id, Request $request, TranslatorInterface $translator, Category $category)
    {
        try {
            $form = $this->createForm(CategoryDeleteType::class, $category);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $chosen = false;
                if (true === $form->get('setNull')->getData()) {
                    $newCategoryId = null;
                    $chosen = true;
                } elseif (null !== ($newCategory = $form->get('newCategory')->getData())) {
                    $newCategoryId = $newCategory->getId();
                    $chosen = true;
                }
                if ($chosen) {
                    $postRepo = $this->getDoctrine()->getRepository('App\Entity\Post');
                    $modifiedPosts = $postRepo->moveToCategory($category->getId(), $newCategoryId);
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($category);
                    $em->flush();
                    $this->addFlash('success', sprintf('Kategoria została usunięta. %d postów zostało zmodyfikowanych.', $modifiedPosts));

                    return $this->redirect($this->generateUrl('admin_categoriesList'));
                } else {
                    $this->addFlash('danger', 'Musisz wybrać nową kategorię lub ustawić News bez kategorii!');
                }
            }
//
//            if (!$this->isCsrfTokenValid('delete-item', $token)) {
//                throw new Exception($translator->trans('Incorrect share token'));
//            } else {
//                $category = $this->getDoctrine()->getRepository('App\Entity\Category')->find($id);
//                $em = $this->getDoctrine()->getManager();
//                $em->remove($category);
//                $em->flush();
//                $category = $this->getDoctrine()->getRepository('App\Entity\Category')->find($id);
//
//                if ($category) {
//                    throw new Exception($translator->trans('There was an error we could not delete the article'));
//                }
//                $this->addFlash('success', $translator->trans('The article has been successfully removed'));
//
//                return $this->redirectToRoute('panel_categories');
//            }
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render('backend/blog/category_delete.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
