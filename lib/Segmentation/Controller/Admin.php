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
 * Administrator-initiated actions for the Segmentation module.
 */
class Segmentation_Controller_Admin extends Zikula_AbstractController
{
    /**
     * The main administration entry point.
     *
     * Redirects to the {@link modifyConfig()} function.
     *
     * @return void
     */
    public function main()
    {
        $this->redirect(ModUtil::url($this->name, 'admin', 'edit'));
    }

	/**
     * Create or edit record.
     *
     * @return string|boolean Output.
     */
    public function edit()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Segmentation::', '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $form = FormUtil::newForm('Segmentation', $this);
        return $form->execute('admin/edit.tpl', new Segmentation_Form_Handler_Admin_Edit());
    }

}