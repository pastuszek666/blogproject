<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function getPublishedPost($slug)
    {
        $qb = $this->getQueryBuilder([
//            'status' => 'published'
        ]);

        $qb->andWhere('p.slug = :slug')
                ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getQueryBuilder(array $params = [])
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
        $datetime = new \DateTime();
        $year = $datetime->format('Y');
        $month = $datetime->format('m');
        $day = $datetime->format('d');

        $qb = $this->createQueryBuilder('post')
                ->select('post, category, tags, author, status')
                ->leftJoin('post.category', 'category')
                ->leftJoin('post.tags', 'tags')
                ->leftJoin('post.author', 'author')
                ->leftJoin('post.status', 'status');

        if (!empty($params['category'])) {
            $qb->andWhere('category = :category')
                    ->setParameter('category', $params['category']);
        }

        if (!empty($params['tags'])) {
            $qb->andWhere('tags = :tags')
                    ->setParameter('tags', $params['tags']);
        }

        if (!empty($params['status'])) {
            $qb->andWhere('status = :status')
                    ->setParameter('status', $params['status']);
        }

        if (!empty($params['today'])) {
            $qb->andWhere('YEAR(post.updateDate) = :currDateYear')
                    ->andWhere('MONTH(post.updateDate) = :currDateMonth')
                    ->andWhere('DAY(post.updateDate) = :currDateDay')
                    ->setParameter('currDateYear', $year)
                    ->setParameter('currDateMonth', $month)
                    ->setParameter('currDateDay', $day);
        }

        if (!empty($params['search'])) {
            $searchParam = '%'.$params['search'].'%';
            $qb->andWhere('post.title LIKE :searchParam OR post.content LIKE :searchParam')
                    ->setParameter('searchParam', $searchParam);
        }

        if (!empty($params['orderBy'])) {
            $orderDir = !empty($params['orderDir']) ? $params['orderDir'] : null;
            $qb->orderBy($params['orderBy'], $orderDir);
        }

        if (!empty($params['limit'])) {
            $qb->setMaxResults($params['limit']);
        }

        return $qb;
    }

    public function getStatistics()
    {
        $qb = $this->createQueryBuilder('p')
                ->select('COUNT(p)');

        $all = (int) $qb->getQuery()->getSingleScalarResult();

        $published = (int) $qb->andWhere('p.publishedDate <= :currDate AND p.publishedDate IS NOT NULL')
                        ->setParameter('currDate', new \DateTime())
                        ->getQuery()
                        ->getSingleScalarResult();

        return [
            'all' => $all,
            'published' => $published,
            'unpublished' => ($all - $published),
        ];
    }

    public function moveToCategory($oldCategoryId, $newCategoryId)
    {
        return $this->createQueryBuilder('post')
                        ->update()
                        ->set('post.category', ':newCategoryId')
                        ->where('post.category = :oldCategoryId')
                        ->setParameters([
                            'newCategoryId' => $newCategoryId,
                            'oldCategoryId' => $oldCategoryId,
                        ])
                        ->getQuery()
                        ->execute();
    }
}
