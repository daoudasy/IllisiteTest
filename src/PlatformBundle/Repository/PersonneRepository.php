<?php

namespace PlatformBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * PersonneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonneRepository extends \Doctrine\ORM\EntityRepository
{
	public function findAllAndPagine($page, $max = 15){
		$query = $this->createQueryBuilder('p')->getQuery();
		$query->setFirstResult(($page-1)*$max)
			  ->setMaxResults($max);
		return new Paginator($query);
	}
}