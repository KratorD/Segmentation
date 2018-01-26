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

/**
 * Administrative API functions.
 */
class Segmentation_Api_Admin extends Zikula_AbstractApi
{

    /**
     * Get available admin panel links.
     *
     * @return array Array of adminpanel links.
     */
    public function getLinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('Segmentation::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('Segmentation', 'admin', 'edit'),
                'text' => $this->__('New segmentation'),
                'class' => 'z-icon-es-new');
        }

        return $links;
    }
}