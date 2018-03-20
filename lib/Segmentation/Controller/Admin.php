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
     * Redirects to the {@link edit()} function.
     *
     * @return void
     */
    public function main()
    {
        $this->redirect(ModUtil::url($this->name, 'admin', 'edit'));
    }

	public function view()
	{
		// Security check
        if (!SecurityUtil::checkPermission('Segmentation::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

		// initialize sort array - used to display sort classes and urls
        $sort = array();
        $fields = array('codEmail', 'description'); // possible sort fields
        foreach ($fields as $field) {
            $sort['class'][$field] = 'z-order-unsorted'; // default values
        }

        // Get parameters from whatever input we need.
        $startnum = (int)$this->request->query->get('startnum', $this->request->request->get('startnum', isset($args['startnum']) ? $args['startnum'] : null));
        $orderby = $this->request->query->get('orderby', $this->request->request->get('orderby', isset($args['orderby']) ? $args['orderby'] : 'codEmail'));
        $original_sdir = $this->request->query->get('sdir', $this->request->request->get('sdir', isset($args['sdir']) ? $args['sdir'] : 0));

		$this->view->assign('startnum', $startnum);
        $this->view->assign('orderby', $orderby);
        $this->view->assign('sdir', $original_sdir);
        $this->view->assign('rowcount', ModUtil::apiFunc('Segmentation', 'user', 'countQuery'));

		$sdir = $original_sdir ? 0 : 1; //if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort['class'][$orderby] = 'z-order-desc';
            $orderdir = 'DESC';
        }
        if ($sdir == 1) {
            $sort['class'][$orderby] = 'z-order-asc';
            $orderdir = 'ASC';
        }
        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort['url'][$field] = ModUtil::url('Segmentation', 'admin', 'view', array(
                        'orderby' => $field,
                        'sdir' => $sdir));
        }
        $this->view->assign('sort', $sort);

		$mails = ModUtil::apiFunc('Segmentation', 'user', 'getall', array(
                    'startnum' => $startnum,
                    'orderby' => $orderby,
                    'orderdir' => $orderdir
                ));

        return $this->view->assign('mails', $mails)
                          ->fetch('admin/view.tpl');

	}

	/**
     * Create or edit segmentation.
     *
     * @return string|boolean Output.
     */
    public function edit()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Segmentation::', '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $form = FormUtil::newForm('Segmentation', $this);
        return $form->execute('admin/edit.tpl', new Segmentation_Form_Handler_Admin_Edit());
    }

	/**
     * Create or edit email.
     *
     * @return string|boolean Output.
     */
    public function editMail()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Segmentation::', '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $form = FormUtil::newForm('Segmentation', $this);
        return $form->execute('admin/editMail.tpl', new Segmentation_Form_Handler_Admin_EditMail());
    }

	/**
     * Create an email and send it.
     *
     * @return string|boolean Output.
     */
    public function sendEmailGroup()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Segmentation::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

		if ($this->request->isPost()) {
			//List of users for id
			$value = $this->request->request->get('ugroup', null);
			$uidList = UserUtil::getUsersForGroup($value);

			//Email data (subject, email body...)
			$sendmail = $this->request->request->get('sendmail', null);

			//Email templates
			$cmbTemplate = $this->request->request->get('cmbTemplate', null);
			if ($cmbTemplate <> 0){
				$mailTpl = $this->entityManager->getRepository('Segmentation_Entity_Email')->find($cmbTemplate);
				$sendmail['message'] = $mailTpl['body'];
			}

			//Get data about users
			$findUsersArgs = array('ugroup' => $this->request->request->get('ugroup', null));
			$userList = ModUtil::apiFunc('Users', 'admin', 'findUsers', $findUsersArgs);
			foreach($userList as $user){
				$recipientsname[$user['uid']] = $user['uname'];
				$recipientsemail[$user['uid']] = $user['email'];
			}
			$sendmail['recipientsname'] = $recipientsname;
			$sendmail['recipientsemail'] = $recipientsemail;

			$mailSent = ModUtil::apiFunc('Users', 'admin', 'sendmail', array(
                'uid'       => $uidList,
                'sendmail'  => $sendmail,
            ));

			$this->redirect(ModUtil::url($this->name, 'admin', 'main'));
		} elseif (!$this->request->isGet()) {
            throw new Zikula_Exception_Forbidden();
        }

		if ($this->request->isGet()){
			// get group items
			$groups = ModUtil::apiFunc('Groups', 'user', 'getAll');

			// get email templates items
			$templates = ModUtil::apiFunc('Segmentation', 'user', 'getall');

			return $this->view->assign('groups', $groups)
				->assign('templates', $templates)
				->fetch('admin/send_email_group.tpl');
		}

    }

}