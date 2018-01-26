<?php
/**
 * Copyright (c) 2001-2012 Zikula Foundation
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license http://www.gnu.org/licenses/lgpl-3.0.html GNU/LGPLv3 (or at your option any later version).
 * @package Royal Bounty HD
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Provides version information for the Segmentation module.
 */
class Segmentation_Version extends Zikula_AbstractVersion
{
    /**
     * Retrieve version and other metadata for the Segmentation module.
     *
     * @return array Metadata for the Segmentation module, as specified by Zikula core.
     */
    public function getMetaData()
    {
        return array(
            'displayname' => $this->__('Segmentation'),
            'description' => $this->__('Create user groups using attributes'),
            //! module name that appears in URL
            'url' => 'Segmentation', //$this->__(/*!used in URL - nospaces, no special chars, lcase*/),
            'version' => '1.0.0',
            'core_min' => '1.3.0', // Fixed to 1.3.x range
            'core_max' => '1.4.99', // Fixed to 1.3.x range
			'dependencies' => array(
				array('modname'    => 'Profile',
					'minversion' => '1.5.0',
					'maxversion' => '1.6.2',
					'status'     => ModUtil::DEPENDENCY_REQUIRED),
				array('modname'    => 'Groups',
					'minversion' => '',
					'maxversion' => '',
					'status'     => ModUtil::DEPENDENCY_REQUIRED)
			),
            'securityschema' => array(
                $this->name . '::' => '::',
            ),
        );
    }
}
