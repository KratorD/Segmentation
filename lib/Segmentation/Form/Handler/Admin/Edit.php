<?php

/**
 * Segmentation
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */
class Segmentation_Form_Handler_Admin_Edit extends Zikula_Form_AbstractHandler
{

    /**
     * segmentation id.
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

		$properties = ModUtil::apiFunc('Profile', 'user', 'getall');

		// get group items
		$groups = ModUtil::apiFunc('Groups', 'user', 'getAll');
		$ugroup[0]['text'] = 'None';
		$ugroup[0]['value'] = 0;
		$cont = 1;
		foreach ($groups as $group){
			$ugroup[$cont]['text'] = $group['name'];
			$ugroup[$cont]['value'] = $group['gid'];
			$cont++;
		}

		$this->view->assign('properties', $properties);
		$this->view->assign('groups', $ugroup);

        $view->setStateData('returnurl', ModUtil::url('Segmentation', 'admin', 'main'));
		
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

        // check for valid form
        if (!$view->isValid()) {
            return false;
        }

        // load form values
        $data = $view->getValues();

		$searchCond=array_filter($data['properties'], "strlen"); //delete empties

		if (empty($data['newName']) && $data['oldName'] == '0'){
			LogUtil::registerError($this->__('Error! Select an existing group, or enter a new one'));
			$view->redirect(ModUtil::url('Segmentation', 'admin', 'edit'));
		}
		if (!empty($data['newName']) && $data['oldName'] > 0){
			LogUtil::registerError($this->__('Error! Select an existing group, OR enter a new one'));
			$view->redirect(ModUtil::url('Segmentation', 'admin', 'edit'));
		}

		//Prepare SQL
		// Get database setup
        $dbtable = DBUtil::getTables();
        
        $userscolumn = $dbtable['users_column'];
        $datacolumn  = $dbtable['objectdata_attributes_column'];
        $propcolumn  = $dbtable['user_property_column'];
		
		$whereList = array();
		//Discart user number 1
		$whereList[] = "tbl.uid != 1";
		//Property conditions
		$cont = 1;
        foreach ($searchCond as $prop_id => $value) {
            $prop_id = DataUtil::formatForStore($prop_id);
            $value   = DataUtil::formatForStore($value);
			$joinsList[] = "LEFT JOIN objectdata_attributes a{$cont} ON a{$cont}.object_id = tbl.uid";
			$joinsList[] = "LEFT JOIN user_property b{$cont} ON b{$cont}.attributename = a{$cont}.attribute_name";
            $whereList[] = "(b{$cont}.{$propcolumn['prop_id']} = '{$prop_id}' AND a{$cont}.{$datacolumn['value']} LIKE '%{$value}%')";
			$cont++;
        }
		//Registration date
		if (!empty($data['regDateFrom'])){
			if (!empty($data['regDateTo'])){
				$whereList[] = "tbl.user_regdate BETWEEN '{$data['regDateFrom']} 00:00:00' AND '{$data['regDateTo']} 23:59:59'";
			}else{
				$whereList[] = "tbl.user_regdate BETWEEN '{$data['regDateFrom']} 00:00:00' AND '{$data['regDateFrom']} 23:59:59'";
			}
		}
		//Last Login date
		if (!empty($data['lastLoginFrom'])){
			if (!empty($data['lastLoginTo'])){
				$whereList[] = "tbl.lastlogin BETWEEN '{$data['lastLoginFrom']} 00:00:00' AND '{$data['lastLoginTo']} 23:59:59'";
			}else{
				$whereList[] = "tbl.lastlogin BETWEEN '{$data['lastLoginFrom']} 00:00:00' AND '{$data['lastLoginFrom']} 23:59:59'";
			}
		}
		//Active users
		$whereList[] = "tbl.activated = 1";
        // check if there where contitionals
        if (!empty($whereList)) {
            $where .= implode(' AND ', $whereList) . ' ';
        }
		if (!empty($joinsList)) {
            $join .= implode(' ', $joinsList) . ' ';
        }

		//Generate SQL
		$sql = "SELECT DISTINCT(tbl.uid) FROM users AS tbl ".$join." WHERE ". $where. " ORDER BY tbl.uname ASC";
		$connection = $this->entityManager->getConnection();
		$results = $connection->fetchAll($sql);
		foreach($results as $result){
			$users[] = $result['uid'];
		}

		if (!empty($data['newName'])){
			//Create group
			$check = ModUtil::apiFunc('Groups', 'admin', 'getgidbyname',
					array('name' => $data['newName']));
			if ($check != false) {
				// Group already exists
				LogUtil::registerError($this->__('Error! There is already a group with that name.'));
				$view->redirect(ModUtil::url('Segmentation', 'admin', 'view'));
			} else {
				$gid = ModUtil::apiFunc('Groups', 'admin', 'create',
						array('name'  => $data['newName'],
						'gtype'       => 2, //Private $gtype,
						'state'       => 1, //Open $state,
						'nbumax'      => 0, //No limit $nbumax,
						'description' => $data['newName']));

				// The return value of the function is checked here
				if ($gid != false) {
					// Success
					LogUtil::registerStatus($this->__('Done! Created the group.'));
				}
			}
		} elseif ($data['oldName'] > 0) {
			$gid = $data['oldName'];
		}

		// Add user/s to the group.
        if (is_array($users)) {
            foreach ($users as $id) {
                if (!ModUtil::apiFunc('Groups', 'admin', 'adduser', 
					array('gid' => $gid,
						'uid' => $id))) {
                    // Failure
                    return LogUtil::registerError($this->__('Error! A problem occurred and the user was not added to the group.'));
                }
            }
			// Success
			LogUtil::registerStatus($this->__('Done! The user was added to the group.'));
        } else {
            if (ModUtil::apiFunc('Groups', 'admin', 'adduser',
            array('gid' => $gid,
            'uid' => $users))) {
                // Success
                LogUtil::registerStatus($this->__('Done! The user was added to the group.'));
            }
        }

        return $view->redirect($returnurl);
    }

}