<?php

/**
 * Segmentation
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */
class Segmentation_Form_Handler_Admin_EditMail extends Zikula_Form_AbstractHandler
{

    /**
     * mail id.
     *
     * When set this handler is in edit mode.
     *
     * @var integer
     */
    private $id;

    /**
     * Setup form.
     *
     * @param Zikula_Form_View $view Current Zikula_Form_View instance.
     *
     * @return boolean
     */
    public function initialize(Zikula_Form_View $view)
    {
        $id = FormUtil::getPassedValue('id', null, 'GET', FILTER_SANITIZE_NUMBER_INT);
        if ($id) {
            // load record with id
            $mail = $this->entityManager->getRepository('Segmentation_Entity_Email')->find($id);

            if ($mail) {
                // switch to edit mode
                $this->id = $id;
                // assign current values to form fields
                $view->assign($mail->toArray());
            } else {
                return LogUtil::registerError($this->__f('Mail with id %s not found', $id));
            }
        }

        $view->setStateData('returnurl', ModUtil::url('Segmentation', 'admin', 'editMail'));
		
        return true;
    }

    /**
     * Handle form submission.
     *
     * @param Zikula_Form_View $view  Current Zikula_Form_View instance.
     * @param array     &$args Args.
     *
     * @return boolean
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        $returnurl = $view->getStateData('returnurl');

        // process the cancel action
        if ($args['commandName'] == 'cancel') {
            return $view->redirect($returnurl);
        }

        if ($args['commandName'] == 'delete') {
            $mail = $this->entityManager->getRepository('Segmentation_Entity_Email')->find($this->id);

            $this->entityManager->remove($mail);
            $this->entityManager->flush();
            ModUtil::apiFunc('Segmentation', 'user', 'clearItemCache', $mail);
            LogUtil::registerStatus($this->__f('Item [id# %s] deleted!', $this->id));
            return $view->redirect($returnurl);
        }

        // check for valid form
        if (!$view->isValid()) {
            return false;
        }

        // load form values
        $data = $view->getValues();

        // switch between edit and create mode
        if ($this->id) {
            $mail = $this->entityManager->getRepository('Segmentation_Entity_Email')->find($this->id);
        } else {
            $mail = new Segmentation_Entity_Email();
        }

		try {
            $mail->merge($data);
            $this->entityManager->persist($mail);
            $this->entityManager->flush();
        } catch (Zikula_Exception $e) {
            echo "<pre>";
            var_dump($e->getDebug());
            echo "</pre>";
            die;
        }

        ModUtil::apiFunc('Segmentation', 'user', 'clearItemCache', $mail);

        return $view->redirect($returnurl);
    }

}