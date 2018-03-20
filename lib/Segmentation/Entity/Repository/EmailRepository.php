<?php

/**
 * Mail Repository
 * 
 * @license MIT
 * @copyright   Copyright (c) 2012, Craig Heydenburg, Sound Web Development
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Segmentation_Entity_Email as Email;

class Segmentation_Entity_Repository_EmailRepository extends EntityRepository
{

    /**
     * Get a collection of items for display
     * 
     * @param string $orderBy
     * @param string $orderDir
     * @param integer $startNum
     * @param integer $limit
     * @return array of objects 
     */
    public function getCollection($orderBy, $orderDir, $startNum, $limit, $args)
    {
        $dql = "SELECT a FROM Segmentation_Entity_Email a";
        //$where = array();

        if (!empty($args['codEmail'])) {
            $codEmail = $args['codEmail'];
			$where[] = "a.codEmail = '$codEmail'";
        }

		if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }

        $dql .= " ORDER BY a.$orderBy $orderDir";

        // generate query
        $query = $this->_em->createQuery($dql);

        if ($startNum > 0) {
            $query->setFirstResult($startNum);
        }
        if ($limit > 0) {
            $query->setMaxResults($limit);
        }

        try {
            $result = $query->getResult();
        } catch (Exception $e) {
            echo "<pre>";
            var_dump($e->getMessage());
            var_dump($query->getDQL());
            var_dump($query->getParameters());
            var_dump($query->getSQL());
            die;
        }
        return $result;
    }

}