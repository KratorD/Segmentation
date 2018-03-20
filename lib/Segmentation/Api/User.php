<?php
/**
 * Copyright (c) 2001-2012 Zikula Foundation
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html GNU/LGPLv3 (or at your option any later version).
 * @package Segmentation
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Segmentation_Api_User extends Zikula_AbstractApi
{
	/**
     * get mails filtered as requested
     * @param type $args
     * @return array of objects
     */
    public function getall($args)
    {
		// declare args
        $startnum = isset($args['startnum']) ? $args['startnum'] : 0;
        $orderby = isset($args['orderby']) ? $args['orderby'] : 'codEmail';
        $orderdir = isset($args['orderdir']) ? $args['orderdir'] : 'ASC';
        $limit = isset($args['limit']) ? $args['limit'] : '20';

        $mails = $this->entityManager->getRepository('Segmentation_Entity_Email')
                ->getCollection($orderby, $orderdir, $startnum, $limit, $args);

        $result = array();
        foreach ($mails as $key => $mail) {
            if (!SecurityUtil::checkPermission('Segmentation::Item', $mail->getId() . '::', ACCESS_READ) ) {
                continue;
            } else {
                $result[$key] = $mail;
            }
        }
        return $result;
    }

    /**
     * cound the number of results in the query
     * @param array $args
     * @return integer
     */
    public function countQuery($args)
    {
        $args['limit'] = -1;
        $items = $this->getall($args);
        return count($items);
    }
}
