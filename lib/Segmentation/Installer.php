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
 * Installs, upgrades, and uninstalls the Segmentation module.
 */
class Segmentation_Installer extends Zikula_AbstractInstaller
{

    /**
     * Install the module.
     *
     * @return bool true if successful, false otherwise
     */
    function install()
    {
		// create tables
        try {
            DoctrineHelper::createSchema($this->entityManager, array('Segmentation_Entity_Email'));
        } catch (Exception $e) {
            LogUtil::registerError($this->__f('Error! Could not create tables (%s).', $e->getMessage()));
            return false;
        }

		//Variables
		$this->setVar('perpage', '20');

		//Hooks
		HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
		
		// Initialization successful
        return true;
    }

    /**
     * Upgrade the module from a prior version.
     *
     * This function must consider all the released versions of the module!
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param string $oldVersion The version number string from which the upgrade starting.
     *
     * @return boolean|string True if the module is successfully upgraded to the current version; last valid version string or false if the upgrade fails.
     */
    function upgrade($oldVersion)
    {
        // Upgrade dependent on old version number
        switch ($oldVersion)
        {
            case '1.0.0':
                // Upgrade 1.0.0 -> 1.1.0
				HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());

            case '1.1.0':
				try {
					DoctrineHelper::updateSchema($this->entityManager, array('Segmentation_Entity_Email'));
				} catch (\Exception $e) {
					if (System::isDevelopmentMode()) {
						return LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
					}

					LogUtil::registerStatus($e->getMessage());
                    return false;
				}
				// The following break should be the only one in the switch, and should appear immediately prior to the default case.
                break;

			default:
                $this->registerError($this->__f('Upgrading the Segmentation module from version %1$s to %2$s is not supported.', array($oldVersion, $this->version->getVersion())));
                return $oldVersion;
        }

        // Update successful
        return true;
    }

    /**
     * Delete the Segmentation module.
     *
     * @return bool True if successful; otherwise false.
     */
    function uninstall()
    {
        DoctrineHelper::dropSchema($this->entityManager, array('Segmentation_Entity_Email'));
		$this->delVars();
		HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());

        // Deletion successful
        return true;
    }
}
