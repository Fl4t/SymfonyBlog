<?php

namespace Sdz\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
  public function myFindAll()
  {
    return $this->createQueryBuilder('a')
      ->getQuery()
      ->getResult();
  }

  public function getAvecTags(array $nom_tags)
  {
      $qb = $this->createQueryBuilder('a');

      // On fait une jointure sur la table des tags, avec pour alias « t ».
      $qb ->join('a.tags', 't')
          ->where($qb->expr()->in('t.nom', $nom_tags)); // Puis on filtre sur le nom des tags.

      // Enfin, on retourne le résultat.
      return $qb->getQuery()
                  ->getResult();
  }
}