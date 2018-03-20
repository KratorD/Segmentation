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
        $meta = array();
        $meta['displayname'] = $this->__('Segmentation');
        $meta['url'] = $this->__('Segmentation');
        $meta['description'] = $this->__('Create user groups for attributes and send emails');
        $meta['version'] = '1.2.0';
		$meta['author'] = 'KratorD';
		$meta['contact'] = 'http://www.torredemarfil.es';

        $meta['securityschema'] = array(
            'Segmentation::' => '::');
		$meta['capabilities'] = array(HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true));
		$meta['core_min'] = '1.3.0'; // requires minimum 1.3.3 or later
        $meta['core_max'] = '1.4.99';
		// Module depedencies
        $meta['dependencies'] = array(
            array(  'modname'    => 'Profile',
                    'minversion' => '1.5.0',
					'maxversion' => '1.6.2',
					'status'     => ModUtil::DEPENDENCY_REQUIRED),
			array(  'modname'    => 'Groups',
                    'minversion' => '',
					'maxversion' => '',
					'status'     => ModUtil::DEPENDENCY_REQUIRED),
		);

        return $meta;
		
    }

	protected function setupHookBundles()
    {
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.segmentation.ui_hooks.segmentation', 'ui_hooks', $this->__('Segmentation Hooks'));
        $bundle->addEvent('display_view', 'segmentation.ui_hooks.segmentation.display_view');
		$bundle->addEvent('form_edit', 'segmentation.ui_hooks.segmentation.form_edit');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.segmentation.filter_hooks.segmentation', 'filter_hooks', $this->__('Segmentation filter Hooks'));
        $bundle->addEvent('filter', 'segmentation.filter_hooks.segmentation.filter');
        $this->registerHookSubscriberBundle($bundle);
    }
}
