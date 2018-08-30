<?php
header("Content-type: text/html; charset=gb2312");

/**
 * @author Administrator
 * @Date 2011��5��9�� 15:19:33
 * @version 1.0
 * @description:������ Model��
 */
class model_projectmanagent_borrow_borrow extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_borrow_borrow";
        $this->sql_map = "projectmanagent/borrow/borrowSql.php";
        parent:: __construct();
        parent:: setObjAss();
        $this->statusDao = new model_common_status();
        $this->statusDao->status = array(
            0 => array(
                'statusEName' => 'shipmentsno1',
                'statusCName' => 'δ����',
                'key' => '0'
            ),
            1 => array(
                'statusEName' => 'shipmentsyes1',
                'statusCName' => '�ѷ���',
                'key' => '1'
            ),
            2 => array(
                'statusEname' => 'part1',
                'statusCname' => '���ַ���',
                'key' => '2'
            ),
            3 => array(
                'statusEname' => 'Stop1',
                'statusCname' => 'ֹͣ����',
                'key' => '3'
            )
        );
    }

    //��˾Ȩ�޴���
    protected $_isSetCompany = 1;

    /**
     * ����ID ��ȡȫ����Ϣ
     * $borrowId : ������ID
     * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('equ','product') Ĭ��Ϊ�� ȡȫ��
     *      prodcut-��Ʒ  equ-����
     */
    function getBorrowInfo($borrowId, $getInfoArr = null) {
        if (empty ($getInfoArr)) {
            $getInfoArr = array(
                'product',
                'equ'
            );
        }
        $daoArr = array(
            "product" => "model_projectmanagent_borrow_product",
            "equ" => "model_projectmanagent_borrow_borrowequ",
        );
        $borrowInfo = $this->get_d($borrowId);
        foreach ($getInfoArr as $key => $val) {
            $daoName = $daoArr[$val];
            $dao = new $daoName ();
            $borrowInfo[$val] = $dao->getDetail_d($borrowId);
        }
        return $borrowInfo;
    }

    /**
     * ����ID ��ȡȫ����Ϣ
     * $borrowId : ������ID
     * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('equ','product') Ĭ��Ϊ�� ȡȫ��
     *      prodcut-��Ʒ  equ-����
     */
    function getBorrowInfoWithTemp($borrowId, $getInfoArr = null) {
        if (empty ($getInfoArr)) {
            $getInfoArr = array(
                'product',
                'equ',
            );
        }
        $daoArr = array(
            "product" => "model_projectmanagent_borrow_product",
            "equ" => "model_projectmanagent_borrow_borrowequ",
        );
        $borrowInfo = $this->get_d($borrowId);
        foreach ($getInfoArr as $key => $val) {
            $daoName = $daoArr[$val];
            $dao = new $daoName ();
            if ($val == 'product') {
                $borrowInfo[$val] = $dao->getDetailWithTemp_d($borrowId);
            } else {
                $borrowInfo[$val] = $dao->getDetail_d($borrowId);
            }
        }
        return $borrowInfo;
    }

    /**
     * ����ID ��ȡȫ����Ϣ(����ɾ���ļ�¼)
     * $borrowId : ������ID
     * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('equ','product') Ĭ��Ϊ�� ȡȫ��
     *      prodcut-��Ʒ  equ-����
     */
    function getBorrowInfoAll($borrowId, $getInfoArr = null) {
    	if (empty ($getInfoArr)) {
    		$getInfoArr = array(
    			'product',
    			'borrowequ'
    		);
    	}
    	$daoArr = array(
    		"product" => "model_projectmanagent_borrow_product",
    		"borrowequ" => "model_projectmanagent_borrow_borrowequ",
    	);
    	$borrowInfo = $this->get_d($borrowId);
    	foreach ($getInfoArr as $key => $val) {
    		$daoName = $daoArr[$val];
    		$dao = new $daoName ();
    		$dao->searchArr ['borrowId'] = $borrowId;
    		$borrowInfo[$val] = $dao->list_d();
    	}
    	return $borrowInfo;
    }

    /**
     * ��дadd_d����
     */
    function add_d($object,$act = '') {
        try {
            $this->start_d();
            //����ҵ�����
            $codeDao = new model_common_codeRule();
            if ($object['limits'] == "�ͻ�") {
                $salesNameId = $object['salesNameId'];
            } else {
                $salesNameId = $object['createId'];
            }

            $deptDao = new model_deptuser_dept_dept();
            $dept = $deptDao->getDeptByUserIdHas($salesNameId);

            $object['objCode'] = $codeDao->getObjCode($this->tbl_name . "_objCode", $dept['Code']);
            //�ж�Ա���������Ƿ���Ҫ�߷�������
            if ($object['isShipTip'] == "��" && $object['limits'] == 'Ա��' && $object['tostorage'] != '1') {
                $object['isproShipcondition'] = 1;
            } else {
                $object['isproShipcondition'] = 0;
            }
            // ��Ʒ������
            $newProLineStr = "";
            if (!empty ($object['product'])) {
            	foreach ($object['product'] as $v){
            		if ($v['isDelTag'] != "1") {
            			$newProLineStr .= $v['newProLineCode'] . ",";
            		}
            	}
            }
            $object['newProLineStr'] = rtrim($newProLineStr,',');
            //����������Ϣ
            $newId = parent:: add_d($object);
            //����ӱ���Ϣ
            //�豸
            if (!empty ($object['borrowequ'])) {
                $borrowequDao = new model_projectmanagent_borrow_borrowequ();
                $borrowequDao->createBatch($object['borrowequ'], array(
                    'borrowId' => $newId,
                    'borrowCode' => $object['Code']
                ));

                $licenseDao = new model_yxlicense_license_tempKey();
                $licenseDao->updateLicenseBacth_d(array(
                    'objId' => $newId,
                    'objType' => $this->tbl_name,
                    'extType' => $borrowequDao->tbl_name
                ), 'borrowId', 'license');
            }
            //��Ʒ
            if (!empty ($object['product'])) {
                $orderequDao = new model_projectmanagent_borrow_product();
                $orderequDao->createBatch($object['product'], array(
                    'borrowId' => $newId
                ), 'conProductName');
            }
            //��ѵ�ƻ�
            $TrainingplanDao = new model_projectmanagent_borrow_trainingplan();
            if (!empty ($object['trainingplan'])) {
                $TrainingplanDao->createBatch($object['trainingplan'], array(
                    'borrowId' => $newId
                ), 'beginDT');
            }
            //�ֹ�ȷ�� �����ʼ�
//            if ($object['tostorage'] == "1") {
//                $addmsg = $object['affirmRemark'];
//                $affirmName = $object['affirmName'];
//                $affirmNameId = $object['affirmNameId'];
//                $emailDao = new model_common_mail();
//                $emailInfo = $emailDao->toStorageEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "storageAffirmInfo", $object['Code'], "ͨ��", $affirmNameId, $addmsg);
//            }

            // ���˱���,�ύ���ݺ�ŷ��ʼ�
            if($act == "app" || $act == "con"){
                //�����������ʼ�
                $rows = $this->get_d($newId);
                $infoArr = array(
                    'code' => $rows['Code'],
                    'type' => '����'
                );
                //ͨ���ʼ������ݲ�֧��ҵ����������д���ʼ������� ���ݶ���
                $otherdatas = new model_common_otherdatas ();
                $objdeptName = $otherdatas->getUserDatas($rows['createId'], 'DEPT_NAME');
                $toUser=$rows['createId'];
                // ����ķ��͸�������,������ͳһ���͸���Ȩ��
                if($objdeptName == '����ҵ��' || $objdeptName == '����֧���Ŷ�'){
                    $toUser = ($toUser == "")? "" : $toUser.",xianzhen.yang";
                    $this->mailDeal_d("borrowToShip_HY", $toUser, $infoArr);
                }else if($rows['limits'] == 'Ա��'){
                    $toUser = ($toUser == "")? "" : $toUser.",quanzhou.luo";
                    $this->mailDeal_d("borrowToShip_YG", $toUser, $infoArr);
                }else if($rows['limits'] == '�ͻ�'){
                    $toUser = ($toUser == "")? "" : $toUser.",quanzhou.luo";
                    $this->mailDeal_d("borrowToShip_KH", $toUser, $infoArr);
                }
            }

            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��дget_d
     */
    function get_d($id, $selection = null) {
        //��ȡ������Ϣ
        $rows = parent:: get_d($id);
        if (!empty ($rows)) {
            if (empty ($selection)) {
                $equDao = new model_projectmanagent_borrow_borrowequ();
                $rows['borrowequ'] = $equDao->getDetail_d($id);
                $trainingplanDao = new model_projectmanagent_borrow_trainingplan();
                $rows['trainingplan'] = $trainingplanDao->getDetail_d($id);
            } else
                if (is_array($selection)) {
                    if (in_array('borrowequ', $selection)) {
                        $equDao = new model_projectmanagent_borrow_borrowequ();
                        $rows['borrowequ'] = $equDao->getDetail_d($id);
                    }
                    if (in_array('trainingplan', $selection)) {
                        $trainingplanDao = new model_projectmanagent_borrow_trainingplan(); //��ѵ�ƻ�
                        $rows['trainingplan'] = $trainingplanDao->getDetail_d($id);
                    }
                }
        }
        return $rows;
    }

    /**
     * ��Ⱦ���� - �鿴
     */
    function initView($object) {

        if (!empty ($object['borrowequ'])) {
            $equDao = new model_projectmanagent_borrow_borrowequ();
            $object['borrowequ'] = $equDao->initTableView($object['borrowequ'], $object['id']);
        } else {
            $object['borrowequ'] = '<tr><td colspan="11">���������Ϣ</td></tr>';
        }

        if (!empty ($object['trainingplan'])) {
            $trainingplanDao = new model_projectmanagent_borrow_trainingplan(); //��ѵ�ƻ�
            $object['trainingplan'] = $trainingplanDao->initTableView($object['trainingplan']);
        } else {
            $object['trainingplan'] = '<tr><td colspan="7">���������Ϣ</td></tr>';
        }
        return $object;
    }

    /**
     * ��Ⱦ���� - �༭
     */
    function initEdit($object) {

        //�豸
        $tentalcontractequDao = new model_projectmanagent_borrow_borrowequ();
        $rows = $tentalcontractequDao->initTableEdit($object['borrowequ']);
        $object['productNumber'] = $rows[0];
        $object['borrowequ'] = $rows[1];
        //��ѵ�ƻ�
        $TrainingplanDao = new model_projectmanagent_borrow_trainingplan();
        $rows = $TrainingplanDao->initTableEdit($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];
        return $object;
    }

    /**
     * �����޸�����
     */
    function editProduct($object) {

        //�豸
        $tentalcontractequDao = new model_projectmanagent_borrow_borrowequ();
        $rows = $tentalcontractequDao->proTableEdit($object['borrowequ']);
        $object['productNumber'] = $rows[0];
        $object['borrowequ'] = $rows[1];

        return $object;
    }

    /**
     * ���������޸�
     */
    function proedit_d($object) {
        try {
            $this->start_d();
            $borrowId = $object['id'];
            $equDao = new model_projectmanagent_borrow_borrowequ();
            foreach ($object['borrowequ'] as $k => $v) {
                if ($v['proId'] && empty ($v['isEdit']) && empty ($v['isDel'])) {
                    $v['id'] = $v['proId'];
                    $equDao->edit_d($v);
                }
                if (isset($v['isDel']) && isset($v['proId'])) {
                    $sql = "update " . $equDao->tbl_name . " set isDel = 1 where id = " . $v['proId'] . " ";
                    $this->_db->query($sql);
                }
                if ($v['isEdit'] && empty ($v['isDel'])) {
                    $sql = "update " . $equDao->tbl_name . " set isDel = 1 where id = " . $v['proId'] . " ";
                    $this->_db->query($sql);
                    $v['borrowId'] = $borrowId;
                    $equDao->add_d($v);
                    $licenseDao = new model_yxlicense_license_tempKey();
                    $licenseDao->updateLicenseBacth_d(array(
                        'objId' => $borrowId,
                        'objType' => $this->tbl_name,
                        'extType' => $equDao->tbl_name
                    ), 'borrowId', 'license');
                }
                if ($v['isAdd']) {
                    $v['borrowId'] = $borrowId;
                    $equDao->add_d($v);
                    $licenseDao = new model_yxlicense_license_tempKey();
                    $licenseDao->updateLicenseBacth_d(array(
                        'objId' => $borrowId,
                        'objType' => $this->tbl_name,
                        'extType' => $equDao->tbl_name
                    ), 'borrowId', 'license');
                }
            }

            //�����������ʼ�
            $rows = $this->get_d($borrowId);
            $infoArr = array(
                'code' => $rows['Code'],
                'type' => '����'
            );
            //ͨ���ʼ������ݲ�֧��ҵ����������д���ʼ������� ���ݶ���
            $otherdatas = new model_common_otherdatas ();
            $objdeptName = $otherdatas->getUserDatas($rows['createId'], 'DEPT_NAME');
            $toUser=$rows['createId'];
            // ����ķ��͸�������,������ͳһ���͸���Ȩ��
            if($objdeptName == '����ҵ��' || $objdeptName == '����֧���Ŷ�'){
                $toUser = ($toUser == "")? "" : $toUser.",xianzhen.yang";
                $this->mailDeal_d("borrowToShip_HY", $toUser, $infoArr);
            }else if($rows['limits'] == 'Ա��'){
                $toUser = ($toUser == "")? "" : $toUser.",quanzhou.luo";
                $this->mailDeal_d("borrowToShip_YG", $toUser, $infoArr);
            }else if($rows['limits'] == '�ͻ�'){
                $toUser = ($toUser == "")? "" : $toUser.",quanzhou.luo";
                $this->mailDeal_d("borrowToShip_KH", $toUser, $infoArr);
            }

            $this->commit_d();
            return $borrowId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��д�༭����
     */
    function edit_d($object,$isEditInfo = false, $act = '') {
        try {
            $this->start_d();
            //�ж�Ա���������Ƿ���Ҫ�߷�������
            $isProShipcondition = isproShipcondition;
            $isProShipcondition = explode(",", $isProShipcondition);
            if (in_array($object['createSectionId'], $isProShipcondition) && $object['limits'] == 'Ա��' && $object['tostorage'] != '1') {
                $object['isproShipcondition'] = 1;
            } else {
                $object['isproShipcondition'] = 0;
            }
            // ��Ʒ������
            $newProLineStr = "";
            foreach ($object['product'] as $k => $v) {
            	if ($v['isDelTag'] == '1') {
            		unset ($object['product'][$k]);
            	}else{
            		$newProLineStr .= $v['newProLineCode'] . ",";
            	}
            }
            $object['newProLineStr'] = rtrim($newProLineStr,',');
            //�޸�������Ϣ
            parent:: edit_d($object, true);

            $borrowId = $object['id'];
            //����ӱ���Ϣ
            //��Ʒ
            $orderequDao = new model_projectmanagent_borrow_product();
            $orderequDao->delete(array(
                'borrowId' => $borrowId
            ));
            $orderequDao->createBatch($object['product'], array(
                'borrowId' => $borrowId
            ), 'conProductName');
            //�豸
            if ($object['borrowequ']) {
            	$equDao = new model_projectmanagent_borrow_borrowequ();
            	$equDao->delete(array(
            			'borrowId' => $borrowId
            	));
            	$equDao->createBatch($object['borrowequ'], array(
            			'borrowId' => $borrowId
            	), 'productName');
                $licenseDao = new model_yxlicense_license_tempKey();
                $licenseDao->updateLicenseBacth_d(array(
                    'objId' => $object,
                    'objType' => $this->tbl_name,
                    'extType' => $equDao->tbl_name
                ), 'borrowId', 'license');
            }
            //��ѵ�ƻ�
            $TrainingplanDao = new model_projectmanagent_borrow_trainingplan();
            $TrainingplanDao->delete(array(
                'borrowId' => $borrowId
            ));
            $TrainingplanDao->createBatch($object['trainingplan'], array(
                'borrowId' => $borrowId
            ));

            //�ֹ�ȷ�� �����ʼ�
//            if ($object['tostorage'] == "1") {
//                $addmsg = $object['affirmRemark'];
//                $affirmNameId = $object['affirmNameId'];
//                $emailDao = new model_common_mail();
//                $emailDao->toStorageEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "storageAffirmInfo", $object['Code'], "ͨ��", $affirmNameId, $addmsg);
//            }
//            if ($object['tostorage'] == "2") {
//                $addmsg = $object['affirmRemark'];
//                $affirmNameId = $object['affirmNameId'];
//                $emailDao = new model_common_mail();
//                $emailDao->toStorageBackEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "storageAffirmInfoBack", $object['Code'], "ͨ��", $affirmNameId, $addmsg);
//            }

            // ���˱���,�ύ���ݺ�ŷ��ʼ�
            if($act == "app" || $act == "con") {
                //�����������ʼ�
                $rows = $this->get_d($borrowId);
                $infoArr = array(
                    'code' => $rows['Code'],
                    'type' => '����'
                );
                //ͨ���ʼ������ݲ�֧��ҵ����������д���ʼ������� ���ݶ���
                $otherdatas = new model_common_otherdatas ();
                $objdeptName = $otherdatas->getUserDatas($rows['createId'], 'DEPT_NAME');
                $toUser = $rows['createId'];
                // ����ķ��͸�������,������ͳһ���͸���Ȩ��
                if ($objdeptName == '����ҵ��') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",xianzhen.yang";
                    $this->mailDeal_d("borrowToShip_HY", $toUser, $infoArr);
                } else if ($rows['limits'] == 'Ա��') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",quanzhou.luo";
                    $this->mailDeal_d("borrowToShip_YG", $toUser, $infoArr);
                } else if ($rows['limits'] == '�ͻ�') {
                    $toUser = ($toUser == "") ? "" : $toUser . ",quanzhou.luo";
                    $this->mailDeal_d("borrowToShip_KH", $toUser, $infoArr);
                }
            }
            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }
    /***********************************************************************************/

    /**
     * ����ɾ������
     */
    function deletesInfo_d($ids) {
        try {
            return $this->deletes($ids);
        } catch (Exception $e) {
            throw $e;
        }

    }
    /***********************************************************************************/
    /**
     * ����������豸��Ϣ
     */
    function showDetaiInfo($rows) {
        $orderequDao = new model_projectmanagent_borrow_borrowequ();
        $rows['orderequ'] = $orderequDao->showDetailByOrder($orderequDao->showEquListInByOrder($rows['id'], "oa_borrow_borrow"));
        return $rows;
    }

    /**
     * ��ѯ���½����ñ� �������� ����״̬ (��ʱֻ����Ա��������)
     */
    function ajaxUpdateDeliveryStatus_d() {
        $borrowIdSql = "select id from oa_borrow_borrow where limits = 'Ա��'";
        $proId = $this->_db->getArray($borrowIdSql);
        foreach ($proId as $k => $v) {
            $id = $v['id'];
            $orderRemainSql = " select (sum(number)-sum(executedNum)) as remainNum,sum(number) as allNum from oa_borrow_equ where borrowId=" . $id . " and isDel=0";
            $remainNum = $this->_db->getArray($orderRemainSql);
            if ($remainNum[0]['remainNum'] <= 0) {
                $DeliveryStatus = 'YFH';
                $statusInfo = array(
                    'id' => $id,
                    'DeliveryStatus' => $DeliveryStatus
                );
            } else
                if ($remainNum[0]['remainNum'] == $remainNum[0]['allNum']) {
                    $statusInfo = array(
                        'id' => $id,
                        'DeliveryStatus' => 'WFH'
                    );
                    $this->updateById($statusInfo);
                } else {
                    $DeliveryStatus = 'BFFH';
                    $statusInfo = array(
                        'id' => $id,
                        'DeliveryStatus' => $DeliveryStatus
                    );
                }
            $this->updateById($statusInfo);
        }
    }

    /**
     * Ա�������� ����֪ͨ �����ʼ�����
     */
    function toremindMail_d($userId, $code, $rows) {
        $emailDao = new model_common_mail();

        $borrowEqu = $rows['borrowequ']; //ת������
        //�黹������Ϣ
        $Item = "";
        if (!empty ($borrowEqu)) {
            $i = 0;
            $Item .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>���ϱ��</b></td><td><b>�����ͺ�</b></td><td><b>����</b></td></tr>";
            foreach ($borrowEqu as $key => $val) {
                $i++;
                $productNmae = $val['productName'];
                $productNo = $val['productNo'];
                $productModel = $val['productModel'];
                $number = $val['number'];
                $Item .= <<<EOT
                    <tr align="center" >
                        <td>$i</td>
                        <td>$productNmae</td>
                        <td>$productNo</td>
                        <td>$productModel</td>
                        <td>$number</td>
                    </tr>
EOT;
            }
            $Item .= "</table>";
        }
        $emailDao->pickingRemindMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "PickingRemind", $code, "ͨ��", $userId, $Item);
    }
    /******************************************************************************/
    /*�����������*/
    function c_configuration($proId, $Num, $trId, $isEdit) {
        $configArr = '';
        $configurationDao = new model_stock_productinfo_configuration();
        $sql = "select configId,configNum from " . $configurationDao->tbl_name . " where hardWareId = $proId and configId > 0";
        $configId = $this->_db->getArray($sql);
        if (!empty ($configId)) {

            foreach ($configId as $k => $v) {
                $configIdA[$k] = $v['configId'];
            }
            $configIdA = implode(",", $configIdA);
            $productInfoDao = new model_stock_productinfo_productinfo();
            $sql = "select * from " . $productInfoDao->tbl_name . " where id in($configIdA)";
            $infoArr = $this->_db->getArray($sql);
            foreach ($infoArr as $key => $val) {
                foreach ($configId as $keyo => $valo) {
                    if ($infoArr[$key]['id'] == $configId[$keyo]['configId']) {
                        $infoArr[$key]['configNum'] = $configId[$keyo]['configNum'];
                        $infoArr[$key]['isCon'] = $trId;
                    }
                }
            }
            if ($isEdit == "1") {
                $equDao = new model_projectmanagent_borrow_borrowequ();
                $configArr = $equDao->configTableEdit($infoArr, $Num);
            } else {
                $equDao = new model_projectmanagent_borrow_borrowequ();
                $configArr = $equDao->configTable($infoArr, $Num);
            }
        }

        return $configArr;
    }

    /**
     * ���ڽ����� -ֱ�Ӹ�����״̬Ϊ����ͬʱ�����ʼ�֪ͨ
     */
    function shortBorrowSub($id) {
        $date = date("Y-m-d");
        try {
            $sql = "update oa_borrow_borrow set ExaStatus = '����',ExaDT = '$date',isproShipcondition = 1,dealStatus = 1 where id = $id ";
            $this->_db->query($sql);
            $arr = $this->get_d($id);
            $linkDao = new model_projectmanagent_borrow_borrowequlink();
            $linkDao->update(
                array("borrowId" => $id),
                array("ExaStatus" => "���","ExaDTOne" => $date,"ExaDT" => $date,
                    "updateTime" => date("Y-m-d H:i:s"),"updateName" => $_SESSION['USERNAME'],"updateId" => $_SESSION['USER_ID'])
            );

            //���ʼ��������쵼
            $infoArr = array(
                'userName' => $arr['createName'],
                'code' => $arr['Code']
            );

            //��ȡ�����쵼
            $getDeptIdSql = "select DEPT_ID from user where USER_ID = '{$arr['createId']}';";
            $getDeptIdArr = $this->_db->get_one($getDeptIdSql);
            $deptId = ($getDeptIdArr)? $getDeptIdArr['DEPT_ID'] : '';
            $sql = "select MajorId from department where DEPT_ID='" . $deptId . "'";
            $deptArr = $this->_db->getArray($sql);
            $this->mailDeal_d("shortBorrowSub", $deptArr[0]['major'], $infoArr);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * �б�ҳ�ύ�ֹ�ȷ��
     */
    function ajaxCounSubS($id) {
        try {
            return $this->_db->query("update oa_borrow_borrow set tostorage = 1 where id = $id");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * �����õ�����ȷ�ϴ��
     * @param $id
     * @param string $tempId
     * @return mixed
     * @throws Exception
     */
    function ajaxBorrowBackR($id,$tempId = '') {
        try {
        	$rs = $this->get_d($id);
        	if($rs['limits'] == '�ͻ�'){
        		if($rs['dealStatus'] == '0'){//�����Ŀͻ������õ������Ҫ��������״̬
        			return $this->_db->query("update oa_borrow_borrow set status = 3,ExaStatus = 'δ����',ExaDT = NULL where id = $id");
        		}elseif($rs['dealStatus'] == '2'){//���δ����Ŀͻ������õ������Ҫ����Ϊ�Ѵ���
                    if($tempId != ''){// ���б����ʱ��¼ID,�������صı����¼��Ϣ
                        $updateSql = "update oa_borrow_changlog set ExaStatus = '����ȷ�ϴ��',ExaDT = now() where objType = 'borrow' and tempId=".$tempId;
                        $this->_db->query($updateSql);

                        $this->_db->query("update oa_borrow_borrow set isSubAppChange = 0,changeTips = 0,dealStatus = 1,ExaStatus = '����ȷ�ϴ��' where id = $tempId");
                    }

                    $result = $this->_db->query("update oa_borrow_borrow set isSubAppChange = 0,changeTips = 0,dealStatus = 1,ExaStatus = '���' where id = $id");
        			return $result;
        		}
        	}else{
                if($tempId != '' && $rs['dealStatus'] == '2'){// ���б����ʱ��¼ID,�������صı����¼��Ϣ
                    $updateSql = "update oa_borrow_changlog set ExaStatus = '����ȷ�ϴ��',ExaDT = now() where objType = 'borrow' and tempId=".$tempId;
                    $this->_db->query($updateSql);

                    $this->_db->query("update oa_borrow_borrow set isSubAppChange = 0,changeTips = 0,dealStatus = 1,ExaStatus = '����ȷ�ϴ��' where id = $tempId");
                    $result = $this->_db->query("update oa_borrow_borrow set isSubAppChange = 0,changeTips = 0,dealStatus = 1,ExaStatus = '���' where id = $id");
                    return $result;
                }else{//������Ա�������õ������Ҫ��������״̬
                    return $this->_db->query("update oa_borrow_borrow set ExaStatus = 'δ����',ExaDT = NULL where id = $id");
                }
        		// return $this->_db->query("update oa_borrow_borrow set status = 3 where id = $id");
        	}
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Ա�������� ת��ִ�в�
     */
    function ajaxBorrowShipR($id) {
        try {
            return $this->_db->query("update oa_borrow_borrow set isship = 1,status= 5 where id = $id");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ִ�в������ֿ� �ı�״̬ Ϊ δ�黹
     */
    function BackStorageDisR($id) {
        try {
            return $this->_db->query("update oa_borrow_borrow set isship = 0,status= 0 where id = $id");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Ա�������� ��״̬Ϊ����������
     */
    function becomeRenew($id) {
        try {
            return $this->_db->query("update oa_borrow_borrow set status = 4 where id = $id");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Ա�������� �������ʱ������
     */
    function updateAsOut($rows) {
        $sql = "update oa_borrow_equ set executedNum = executedNum + " . $rows['outNum'] . " where borrowId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
        $numSql = "select count(*) as allNum from oa_borrow_equ where borrowId = " . $rows['relDocId'] . " and isDel=0"; //���Դӱ�����
        $exeSql = "select count(*) as exeNum from oa_borrow_equ where borrowId = " . $rows['relDocId'] . " and number-executedNum <= 0 and isDel=0"; //����ɷ����ӱ�����
        $allNum = $this->_db->getArray($numSql);
        $exeNum = $this->_db->getArray($exeSql);
        if ($allNum[0]['allNum'] - $exeNum[0]['exeNum'] == 0) { //ȷ��Ϊ��ɷ���
            $this->updateField(array(
                "id" => $rows['relDocId']
            ), "DeliveryStatus", "YFH");
        } else {
            $this->updateField(array(
                "id" => $rows['relDocId']
            ), "DeliveryStatus", "BFFH");
        }
    }

    /**
     * Ա�������� ���ⷴ���ʱ������
     */
    function updateAsAutiAudit($rows) {
        $rows['outNum'] = $rows['outNum'] * (-1);
        $sql = "update oa_borrow_equ set executedNum = executedNum + " . $rows['outNum'] . " where borrowId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
        $this->_db->query($sql);
        $numSql = "select count(*) as allNum from oa_borrow_equ where borrowId = " . $rows['relDocId'] . " and isDel=0"; //���Դӱ�����
        $noexeSql = "select count(*) as noexeNum from oa_borrow_equ where borrowId = " . $rows['relDocId'] . " and executedNum = 0 and isDel=0"; //δ�����ӱ�����
        $allNum = $this->_db->getArray($numSql);
        $noexeNum = $this->_db->getArray($noexeSql);
        if ($allNum[0]['allNum'] - $noexeNum[0]['noexeNum'] == 0) { //ȷ��Ϊδ����
            $this->updateField(array(
                "id" => $rows['relDocId']
            ), "DeliveryStatus", "WFH");
        } else {
            $this->updateField(array(
                "id" => $rows['relDocId']
            ), "DeliveryStatus", "BFFH");
        }
    }

    /**
     * ���ݵ��������½������嵥���ѹ黹����
     * $borrow ����
     *        id:�����õ�id
     *        equId:�������嵥id
     *        productId:�������嵥����id
     * $backNum �黹����
     */
    function updateBorrowEquBackNum($relDocItemArr) {
        try {
            $outNum = $relDocItemArr['outNum'];
            $this->start_d();
            //if(!empty($relDocItemArr['relDocItemId'])){
            //�����Ƿ���Ҫ�жϹ黹�������嵥��������������ʱ�����ƣ����ܳ��ֹ黹�������嵥������
            //$equ=array("id"=>$borrow['equId'],"backNum"=>$backNum);
            $equDao = new model_projectmanagent_borrow_borrowequ();
            $equ = $equDao->get_d($relDocItemArr['relDocItemId']);
            if (empty ($equ['backNum'])) {
                $equ['backNum'] = 0;
            }
            $equ['backNum'] = $equ['backNum'] + $outNum;
            $equ['applyBackNum'] = $equ['applyBackNum'] + $outNum;
            $equDao->updateById($equ);
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ���ݵ���������˽������嵥���ѹ黹����
     * $borrow ����
     *        id:�����õ�id
     *        equId:�������嵥id
     *        productId:�������嵥����id
     * $backNum �黹����
     */
    function reUpdateBorrowEquBackNum($relDocItemArr) {
        try {
            $outNum = $relDocItemArr['outNum'] * -1;
            $this->start_d();
            //if(!empty($relDocItemArr['relDocItemId'])){
            //�����Ƿ���Ҫ�жϹ黹�������嵥��������������ʱ�����ƣ����ܳ��ֹ黹�������嵥������
            //$equ=array("id"=>$borrow['equId'],"backNum"=>$backNum);
            $equDao = new model_projectmanagent_borrow_borrowequ();
            $equ = $equDao->get_d($relDocItemArr['relDocItemId']);
            $equ['backNum'] = $equ['backNum'] + $outNum;
            $equ['applyBackNum'] = $equ['applyBackNum'] + $outNum;
            $equDao->updateById($equ);
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ���ݺ�ͬ״̬��id ���Һ�ͬ״̬
     */
    function getOrderExaType($contractId) {
        $Dao = new model_contract_contract_contract();
        $exaType = $Dao->find(array(
            'id' => $contractId
        ), null, 'ExaStatus');
        return $exaType['ExaStatus'];
    }

    /**
     * ��ȡ������¼
     */
    function findLockNum($planId, $stockId, $productId) {
        $lockDao = new model_stock_lock_lock();
        $planObj = $this->get_d($planId);
        $lockDao->searchArr = array(
            "productId" => $productId,
            "objId" => $planObj['id'],
            "objType" => "oa_borrow_borrow",
            "stockId" => $stockId
        );
        $sql = " select sum(c.lockNum) as lockNum from oa_stock_lock c where 1=1 ";
        $lockArr = $lockDao->listBySql($sql);
        return $lockArr[0]['lockNum'];
    }
    /*******************************���   ��ʼ***************************************************/
    /**
     * ��Ⱦ���� -���
     */
    function initChange($object) {
        //�豸
        $tentalcontractequDao = new model_projectmanagent_borrow_borrowequ();
        $rows = $tentalcontractequDao->changeTable($object['borrowequ']);
        $object['productNumber'] = $rows[0];
        $object['borrowequ'] = $rows[1];
        return $object;
    }

    /**
     * �������
     */
    function change_d($obj,$isAudit = '') {
        $originalId = $obj['oldId'];
        $borrowArr = $this->get_d($originalId);

        // ������ʱ��¼�Ĳ�Ʒ��
        if(isset($obj['product'])){
            foreach ($obj['product'] as $k => $v){
                $productArr = $this->_db->get_one("select * from oa_goods_base_info where id = '{$v['conProductId']}'");
                $obj['product'][$k]['newProLineName'] = isset($productArr['exeDeptName'])? $productArr['exeDeptName'] : $v['newProLineName'];
            }
        }

        try {
            $this->start_d();

            //�����¼,�õ��������ʱ������id
            $changeLogDao = new model_common_changeLog('borrow');
            $forArr = array(
                "product",
                "borrowequ"
            );
            if (!empty($obj['borrowequ'])) {
                foreach ($obj['borrowequ'] as $key => $val) {
                    if (empty($val['productId']) || empty($val['productName'])) {
                        unset($obj['borrowequ'][$key]);
                    }
                }
            }
            if (!empty($obj['product'])) {
            	// ��Ʒ������
            	$newProLineStr = "";
            	foreach ($obj['product'] as $key => $val) {
            		if (!isset($val['isDelTag'])) {
            			$newProLineStr .= $val['newProLineCode'] . ",";
            		}
            	}
            }
            $obj['newProLineStr'] = rtrim($newProLineStr,',');
            if(isset($obj['tempId']) && $obj['borrowId'] != $obj['oldId']){//���ڼ�������ʱ�����¼����
            	//�ϲ���ʱ�����¼ɾ����������
            	$tempObj = $this->getBorrowInfoAll($obj['tempId']);
            	foreach ($forArr as $key => $val) {
            		foreach ($tempObj[$val] as $v) {
            			if($v['isDel'] == '1'){
            				if(!isset($obj[$val])){
            					$obj[$val] = array();
            				}
            				array_push($obj[$val], $v);
            			}
            		}
            	}
            	foreach ($forArr as $key => $val) {
            		foreach ($obj[$val] as $k => $v) {
            			$obj[$val][$k]['oldId'] = empty($obj[$val][$k]['originalId']) ? '0' : $obj[$val][$k]['originalId'];//�ӱ��originalId��ӦԴ����id
            		}
            	}

                // ɾ��δ�ύ����ʱ��¼
                if(isset($obj['tempId']) && !empty($obj['tempId'])){
                    $this->deletes_d($obj['tempId']);
                }
            }else{
            	foreach ($forArr as $key => $val) {
            		foreach ($obj[$val] as $k => $v) {
            			$obj[$val][$k]['oldId'] = $obj[$val][$k]['id'];
            		}
            	}
            }

            // ����������ʱ��¼��Ϣ
            $tempObjId = $changeLogDao->addLog($obj);
            //ɾ�����μ��ص���ʱ�����¼(����)
//            if(!empty($obj['tempId'])){
//            	$sql = "select id,ExaStatus from oa_borrow_changlog where objType = 'borrow' and tempId=".$obj['tempId'];
//            	$rs = $this->_db->getArray($sql);
//            	if(!empty($rs)){
//            		//ȡ�����ر����¼�����������صı����¼��ɾ��
//            		if($rs[0]['ExaStatus'] != '���' || ($rs[0]['ExaStatus'] == '���' && $obj['oldId'] != $obj['borrowId'])){
//	            		$delSql = "delete from oa_borrow_changedetail where parentId=".$rs[0]['id'];
//	            		$this->_db->query($delSql);
//		            	$delSql = "delete from oa_borrow_changlog where objType = 'borrow' and tempId=".$obj['tempId'];
//		            	$this->_db->query($delSql);
//            		}
//            	}
//            }

            if(!empty($tempObjId)){
            	if($obj['isSub'] == '0'){//����ʱ������ʱ�����¼������״̬��Ϊ����
            		$updateSql = "update oa_borrow_changlog set ExaStatus = '����' where objType = 'borrow' and tempId=".$tempObjId;
            	}else{//�ύʱ���������¼������״̬��Ϊ������
            		$updateSql = "update oa_borrow_changlog set ExaStatus = '����ȷ��' where objType = 'borrow' and tempId=".$tempObjId;
                    $this->update(array("id"=>$tempObjId),array("changeTips"=>1,"isSubAppChange"=>1,"dealStatus"=>2,"ExaStatus"=>"����ȷ��"));
                    if(!empty($originalId)){
                        $this->update(array("id" => $originalId),array("changeTips"=>1,"isSubAppChange"=>1,"dealStatus"=>"2","ExaStatus"=>"����ȷ��"));
                    }
            	}
            	$this->_db->query($updateSql);

                // �������Ϲ�����ϵ
                $updateSql = "update oa_borrow_equ e1 left join oa_borrow_equ e2 on e1.originalId = e2.id left join oa_borrow_product p on (p.originalId = e2.conProductId and p.borrowId = {$tempObjId}) set e1.conProductId = p.id where e1.borrowId = {$tempObjId};";
                $this->_db->query($updateSql);
                $updateSql = "update oa_borrow_equ e1 left join oa_borrow_equ e2 on e1.parentEquId = e2.originalid set e1.parentEquId = e2.id where e1.borrowId = {$tempObjId} and e2.borrowId = {$tempObjId};";
                $this->_db->query($updateSql);

                sleep(0.2);// ���������Ľű�ִ�в�֪Ϊɶ��ʱ�������,��ʱ����������

                if($isAudit != '' && $isAudit == 'audit'){
                    //�����������ʼ�
                    $infoArr = array(
                        'code' => $borrowArr['Code'],
                        'type' => '���'
                    );

                    //ͨ���ʼ������ݲ�֧��ҵ����������д���ʼ������� ���ݶ���
                    $otherdatas = new model_common_otherdatas ();
                    $objdeptName = $otherdatas->getUserDatas($borrowArr['createId'], 'DEPT_NAME');
                    $toUser=$borrowArr['createId'];
                    // ����ķ��͸�������,������ͳһ���͸���Ȩ��
                    if($objdeptName == '����ҵ��' || $objdeptName == '����֧���Ŷ�'){
                        $toUser = ($toUser == "")? "" : $toUser.",xianzhen.yang";
                        $this->mailDeal_d("borrowToShip_HY", $toUser, $infoArr);
                    }else if($borrowArr['limits'] == 'Ա��'){
                        $toUser = ($toUser == "")? "" : $toUser.",quanzhou.luo";
                        $this->mailDeal_d("borrowToShip_YG", $toUser, $infoArr);
                    }else if($borrowArr['limits'] == '�ͻ�'){
                        $toUser = ($toUser == "")? "" : $toUser.",quanzhou.luo";
                        $this->mailDeal_d("borrowToShip_KH", $toUser, $infoArr);
                    }
                    // $emailInfo = $this->mailDeal_d("borrowToShip", "", $infoArr);
                }
            }

            $this->commit_d();
            return $tempObjId;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }

    }

    function changeNoApp($row){
        $sql = "update oa_borrow_borrow set remarkapp='".$row['remarkapp']."' where id = '".$row['oldId']."'";
        $this->_db->query($sql);
    }

    /*******************************���   end***************************************************/

    /**
     * ��ȡĳ���ͻ����������Ѿ����ý��
     */
    function getAreaMoneyByCustomerId($customerId) {
        $customerDao = new model_customer_customer_customer();
        $customer = $customerDao->get_d($customerId);
        $areaId = $customer['AreaId'];
        $areaDao = new model_system_region_region();
        $area = $areaDao->get_d($areaId);
        $sql = "select r.areaName ,sum(c.price*(c.executedNum-c.backNum)) as areaMoney from oa_borrow_equ c" .
            " left join oa_borrow_borrow b on b.id=c.borrowId " .
            " left join customer cr on cr.id=b.customerId" .
            " left join oa_system_region r on r.id=cr.AreaId " .
            "where b.customerId is not null and r.id=$areaId " .
            "and b.ExaStatus='���' group by r.id,r.areaName ";
        //echo $sql;
        $arr = $this->_db->getArray($sql);

        $moneyDao = new model_projectmanagent_borrow_money();
        $moneyConfig = $moneyDao->getMoneyByAreaId($areaId);
        $maxMoney = $moneyConfig['maxMoney'];
        if (isset ($arr[0])) {
            $arr[0]['maxMoney'] = $maxMoney;
            return $arr[0];
        }
        return array(
            "areaName" => $area['areaName'],
            "areaMoney" => 0,
            "maxMoney" => $maxMoney
        );
    }

    /**
     * ��ȡ�����˽��ý����Ϣ
     */
    function getUserMoney($userId) {
        //��ȡ�������ѽ��ý��
        $sql = "select b.createId as userId,sum(c.price*(ifnull(c.executedNum,0)-ifnull(c.backNum,0))) as borrowMoney from oa_borrow_equ c" .
            " left join oa_borrow_borrow b on b.id=c.borrowId " .
            " left join user u on u.USER_ID=b.createId where b.createId='$userId' and c.executedNum >= backNum" .
            " group by b.createId";
        $arrUser = $this->_db->getArray($sql);

        //��ȡ���������ڲ����ѽ��ý��
        $deptDao = new model_deptuser_dept_dept();
        $dept = $deptDao->getDeptByUserId($userId);
        $deptId = $dept['id'];
        $sql = "select b.createSectionId as deptId,sum(c.price*(ifnull(c.executedNum,0)-ifnull(c.backNum,0))) as borrowMoney from oa_borrow_equ c" .
            " left join oa_borrow_borrow b on b.id=c.borrowId " .
            " left join department d on d.DEPT_ID=b.createSectionId where b.createSectionId='$deptId' and c.executedNum >= backNum" .
            " group by b.createSectionId";
        $arrDept = $this->_db->getArray($sql);

        $moneyDao = new model_projectmanagent_borrow_money();
        $userMoneyConfig = $moneyDao->getMoneyByUserId($userId); //�˻��������
        $deptMoneyConfig = $moneyDao->getMoneyByDeptId($deptId); //���Ž������

        $arrUser[0]['maxMoney'] = $userMoneyConfig['maxMoney'];
        $arrDept[0]['maxMoney'] = $deptMoneyConfig['maxMoney'];
        $arrDept[0]['deptId'] = $deptId;

        $moneyArr = array(
            "userMoney" => $arrUser[0],
            "deptMoney" => $arrDept[0]
        );
        return $moneyArr;

    }
    /********************************************************************************************/
    /**
     * ѡ�����к�
     */
    function serialNum_d($serialArr) {
        $str = "";
        $i = 0;
        foreach ($serialArr as $key => $val) {
            $i++;
            $str .= <<<EOT
                <tr><td width="5%">$i</td>
                    <td><input type="text" name="" id="name$i" class="readOnlyTxtLong"  readonly="readonly" value="$val[name]"/>
                        <input type="hidden" id="id$i" name="" value="$val[id]"/></td>
                    <td><img src="images/closeDiv.gif" onclick="mydel(this,'list')" title="ɾ����" id="Del$i"/> </td>
                </tr>
EOT;
        }
        return $str;
    }

    /**
     * �鿴��ѡ���к�
     */
    function serialshow_d($serialArr) {
        $str = "";
        $i = 0;
        foreach ($serialArr as $key => $val) {
            $i++;
            $str .= <<<EOT
                <tr><td width="5%">$i</td>
                    <td><input type="text" name="" id="name$i" class="readOnlyTxtLong"  readonly="readonly" value="$val"/>
                </tr>
EOT;
        }
        return $str;
    }

    /**
     * �жϹ黹״̬
     */
    function backStatus($id) {
		// ״̬�ж�
		$exeSql = "select SUM(number) AS allNum, SUM(executedNum) AS exeNum, SUM(backNum) AS backNum
			from oa_borrow_equ where borrowId = " . $id . " and isDel = 0 and isTemp = 0"; //�ѹ黹�Ĵӱ�����
		$exeNum = $this->_db->getArray($exeSql);

        if ($exeNum[0]['allNum'] === null || $exeNum[0]['exeNum'] == 0 || $exeNum[0]['exeNum'] == $exeNum[0]['backNum']) {
            return 1;
        }

		if ($exeNum[0]['exeNum'] > 0 && $exeNum[0]['backNum'] <> 0) {
			return 2;
		}

		if ($exeNum[0]['exeNum'] > 0 && $exeNum[0]['backNum'] == 0) {
			return 0;
		}
    }

    /**
     * ת��ȷ�ϲ���
     */
    function updateSubAff($id) {
        try {
            return $this->_db->query("update oa_borrow_borrow set status= 0 where id = $id");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ת������ͨ�� �� ȷ�Ϻ� �����ʼ����ֹ�
     */
    function updateExaTomail($id) {
        $rows = $this->get_d($id);
        $subrows = $this->get_d($rows['subBorrowId']); //ת�赥Դ�� ����
        $subCode = $subrows['Code']; //ת��Դ�� ���
        $Code = $rows['Code']; //�������õ����
        $borrowEqu = $rows['borrowequ']; //ת������
        //�黹������Ϣ
        $Item = "";
        if (!empty ($borrowEqu)) {
            $i = 0;
            $Item .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>���ϱ��</b></td><td><b>�����ͺ�</b></td><td><b>ת������</b></td><td><b>���к�</b></td></tr>";
            foreach ($borrowEqu as $key => $val) {
                $i++;
                $productNmae = $val['productName'];
                $productNo = $val['productNo'];
                $productModel = $val['productModel'];
                $number = $val['number'];
                $serialName = $val['serialName'];
                $Item .= <<<EOT
                    <tr align="center" >
                        <td>$i</td>
                        <td>$productNmae</td>
                        <td>$productNo</td>
                        <td>$productModel</td>
                        <td>$number</td>
                        <td>$serialName</td>
                    </tr>
EOT;
            }
            $Item .= "</table>";
        }

        //��ȡĬ�Ϸ�����
        include(WEB_TOR . "model/common/mailConfig.php");
        $toMailId = isset($mailUser['tosubtenancyBorrow']['tosubtenancyNameId']) ?
            $mailUser['tosubtenancyBorrow']['tosubtenancyNameId'] : array(
                'tosubtenancyNameId' => '',
                'tosubtenancyName' => ''
            ); //�ʼ�������ID
        $emailDao = new model_common_mail();
        $emailDao->subtenancyEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "subTenancyInfo", $subCode, $Code, $toMailId, $Item);
    }

    /**
     * ������Աid  ��ȡ �����ý����
     */
    function moneyConfig($userId) {
        $deptment = new model_common_otherdatas();
        $deptArr = $deptment->getUserDatas($userId, array(
            "USER_ID",
            "jobs_id",
            "DEPT_ID"
        ));
        $jobsId = $deptArr['jobs_id']; //��ɫID
        $deptId = $deptArr['DEPT_ID']; //����ID
        $sql = "select maxMoney from oa_borrow_money_config where userId = '$userId'";
        $userMaxMoney = $this->_db->getArray($sql);
        $sql = "select maxMoney from oa_borrow_money_config where roleId = '$jobsId'";
        $jobsMaxMoney = $this->_db->getArray($sql);
        $sql = "select deptuserMoney from oa_borrow_money_config where deptId = '$deptId'";
        $deptMaxMoney = $this->_db->getArray($sql);
        if (!empty ($userMaxMoney)) {
            $maxMoney = $userMaxMoney[0]['maxMoney'];
        } else {
            if (!empty ($jobsMaxMoney)) {
                $maxMoney = $jobsMaxMoney[0]['maxMoney'];
            } else {
                if (!empty ($deptMaxMoney)) {
                    $maxMoney = $deptMaxMoney[0]['deptuserMoney'];
                } else {
                    $maxMoney = 0;
                }
            }
        }
        return $maxMoney;
    }

    /**
     * ��ȡ������ �����ñ���� ��ʼ������
     */
    function getInitializeInfo($rows) {
        $initializeDao = new model_projectmanagent_borrow_initialize();
        $initializeArr = $initializeDao->initializeReportInfo();
        foreach ($rows as $k => $v) {
            foreach ($initializeArr as $key => $val) {
                if ($v['userId'] == $val['applyUserId']) {
                    unset ($initializeArr[$key]);
                }
            }
        }
        $tempArrInfo = array();
        foreach ($initializeArr as $k => $v) {
            $keyIn = $v['applyUserName'];
            $tempArrInfo[$keyIn] = $v;
        }

        foreach ($tempArrInfo as $key => $val) {
            $temp['id'] = $val['applyUserId'];
            $temp['dept'] = $val['dept'];
            $temp['user'] = $val['applyUserName'];
            $temp['userId'] = $val['applyUserId'];
            $temp['allMoney'] = " ";
            $temp['moneyLimit'] = " ";
            $temp['isOverrun'] = " ";
            $temp['overrunMoeny'] = " ";
            $rows[] = $temp;
        }
        return $rows;
    }

    //�ӱ�
    function getInitializeInfoTable($rows, $searchArr) {

        $initializeDao = new model_projectmanagent_borrow_initialize();
        $initializeArr = $initializeDao->initializeReportInfoT($searchArr);
        $tempArr = array();
        foreach ($initializeArr as $key => $val) {
            $tempArr['id'] = $val['applyUserId'];
            $tempArr['createName'] = $val['applyUserName'];
            $tempArr['createId'] = $val['applyUserId'];
            $tempArr['renew'] = "0";
            $tempArr['borrowId'] = "";
            $tempArr['equid'] = "";
            $tempArr['productNo'] = $val['productCode'];
            $tempArr['productName'] = $val['productName'];
            $tempArr['number'] = $val['number'];
            $tempArr['price'] = "";
            $tempArr['money'] = "";
            $tempArr['beginTime'] = "";
            $tempArr['endTime'] = "";
            $tempArr['isOvertime'] = "";
            $tempArr['overtimeNum'] = "";
            $tempArr['renewNum'] = "";
            $tempArr['renewDate'] = "";
            $rows[] = $tempArr;
        }
        return $rows;
    }

    /**
     * ��ʼ������������
     */
    function initializeBorrowData_d() {

        $sql = "select * from oa_borrow_borrow where ExaStatus = '���' or ExaStatus = '����'";
        $rows = $this->_db->getArray($sql);
        $delSql = "delete from oa_borrow_borrow where ExaStatus = '���' or DeliveryStatus = 'BFFH' or status = '3'";
        $this->_db->query($delSql);
        $completeArr = array(); //��ɷ���������
        $exeArr = array(); //���ַ���������

        foreach ($rows as $k => $v) {
            $equDao = new model_projectmanagent_borrow_borrowequ();
            $flag = $equDao->borrowShipmentsStatus($v['id']);

            if ($flag == "0") {
                $completeArr[] = $v;
            } else
                if ($flag == "1") {
                    $equInfo = $this->get_d($v['id'], array(
                        "borrowequ"
                    ));
                    if ($equInfo['isTemp'] != '1') {
                        $exeArr[] = $equInfo;
                    }

                }
        }
        //ɾ��
        foreach ($completeArr as $k => $v) {
            $this->delete(array(
                "id" => $v['id']
            ));
        }
        //�������ַ���������
        $this->exportExeBorrowInfo($exeArr);
    }

    /**
     * �������ַ����Ľ���������
     */
    function exportExeBorrowInfo($exeArr) {
        $dataArr = array(); //��������
        foreach ($exeArr as $key => $val) {
            foreach ($val['borrowequ'] as $k => $v) {
                if (($v['executedNum'] != '0')) {
                    $temp['Code'] = $val['Code'];
                    $temp['limits'] = $val['limits'];
                    if ($val['limits'] == "�ͻ�") {
                        $temp['userNmae'] = $val['salesName'];
                    } else {
                        $temp['userNmae'] = $val['createName'];
                    }

                    $temp['productName'] = $v['productName'];
                    $temp['productNo'] = $v['productNo'];
                    $temp['productModel'] = $v['productModel'];
                    $temp['number'] = $v['number'];
                    $temp['executedNum'] = $v['executedNum'];
                    $temp['backNum'] = $v['backNum'];
                    if ($val['customerName'] == "NULL" || empty ($val['customerName'])) {
                        $temp['customerName'] = " ";
                    } else {
                        $temp['customerName'] = $val['customerName'];
                    }
                    $dataArr[] = $temp;
                }
            }
        }

        return model_contract_common_contExcelUtil:: exportExeBorrowInfoExcelUtil($dataArr);
    }

    /**
     * ������Ϣ
     */
    function findProductInfo() {
        return $this->_db->getArray("select id,productCode,productName,pattern,unitName,ext2 from oa_stock_product_info");
    }

    /**
     * ���³�ʼ���Ľ����õ������������
     * $allocatId:������id
     * $allocatCode:���������
     * $userId:������id
     * $productId:����id
     * $allocatNum:��������
     * $isCustomer:�Ƿ�ͻ�������
     */
    function updateInitialzeRelation($allocatId, $allocatCode, $userId, $productId, $allocatNum, $customerId) {
        try {
            $this->start_d();
            //���ҹ����û������ϵĳ�ʼ�������õ�
            $sqlPlus = "b.createId='$userId' and b.limits='Ա��'";
            if (!empty ($customerId)) {
                $sqlPlus = "b.salesNameId='$userId'  and customerId=$customerId";
            }
            $sql = "select e.borrowId,e.backNum,e.id,e.number,e.productId from oa_borrow_equ e " .
                "left join oa_borrow_borrow b on e.borrowId=b.id" .
                " where $sqlPlus and e.productId=$productId and initTip=1 and e.number>e.backNum" .
                " order by b.createTime asc";
            $arr = $this->findSql($sql);
            $productDao = new model_stock_productinfo_productinfo();
            $product = $productDao->get_d($productId);
            $allocatdao = new model_projectmanagent_borrow_borrowRefAllocat();
            if (!$arr || count($arr) == 0) {
                throw new Exception("û�и�Ա��������" . $product['productCode'] . "��Ӧ�Ľ��������뵥.");
            } else {
                //�жϹ黹�����Ƿ���ȷ
                $allCanBackNum = 0;
                foreach ($arr as $k => $v) {
                    $number = $v['number'];
                    $backNum = $v['backNum'];
                    $allCanBackNum += ($number - $backNum);
                }
                if ($allocatNum > $allCanBackNum) {
                    throw new Exception($product['productCode'] . "�黹�����������з������������õ��ɹ黹�������ܺ�.");
                }
                foreach ($arr as $key => $val) {
                    //�ȸ�����ϸ�ϵĹ黹����
                    $backNum = $val['backNum'];
                    $equId = $val['id'];
                    $borrowId = $val['borrowId'];
                    $number = $val['number'];
                    //�����������С�ڻ��ߵ���δ��������
                    if ($allocatNum <= ($number - $backNum)) {
                        if ($allocatNum < 0) {
                            throw new Exception("ϵͳ�쳣1,�黹��������Ϊ����.����ϵ�����");
                        }
                        $sql = "UPDATE oa_borrow_equ SET applyBackNum = applyBackNum + $allocatNum, " .
                            "backNum = backNum + $allocatNum WHERE id=$equId";
                        $this->query($sql);
                        $arr = array(
                            'borrowId' => $borrowId,
                            'borrowEquId' => $equId,
                            'allocatId' => $allocatId,
                            'allocatNum' => $allocatNum
                        );
                        $allocatdao->add_d($arr); //�����������
                        break;
                    }
                    //���������������δ��������
                    $curBackNum = $number - $backNum;
                    $allocatNum = $allocatNum - $curBackNum;
                    //echo $allocatNum;
                    if ($number < 0) {
                        throw new Exception("ϵͳ�쳣2,�黹��������Ϊ����.����ϵ�����");
                    }
                    $sql = "UPDATE oa_borrow_equ SET applyBackNum = $number, backNum = $number WHERE id=$equId";
                    $this->query($sql);
                    $arr = array(
                        'borrowId' => $borrowId,
                        'borrowEquId' => $equId,
                        'allocatId' => $allocatId,
                        'allocatNum' => $curBackNum
                    );
                    $allocatdao->add_d($arr); //�����������
                }
            }
            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ����˹黹���������¹黹������ɾ��������ϵ
     * $allocatId:������id
     */
    function autiAuditDelInitialzeRelation($allocatId) {
        try {
            $this->start_d();
            //�ȸ������뵥�黹����
            $dao = new model_projectmanagent_borrow_borrowRefAllocat();
            $dao->searchArr = array(
                "allocatId" => $allocatId
            );
            $rlist = $dao->list_d();
            foreach ($rlist as $k => $v) {
                $aNum = $v['allocatNum'];
                $equId = $v['borrowEquId'];
                $sql = "UPDATE oa_borrow_equ SET applyBackNum = applyBackNum - $aNum, " .
                    " backNum = backNum - $aNum WHERE id=$equId";
                $this->query($sql);
            }

            //�Ѹõ�������������ɾ��
            $dao->delete(array(
                "allocatId" => $allocatId
            ));
            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /*********************************������*************************************************/

    /**
     * ����id��ȡ �����������ˡ��ͻ�
     */
    function getBorinfoByborrow($id) {
        $sql = "select customerId,customerName,createName,limits from oa_borrow_borrow where id=$id";
        $borrowarr = $this->_db->getArray($sql);
        return $borrowarr[0];
    }
    /*********************************������*********end****************************************/


    /**
     * �������ۺ�ͬ��������
     */
    function updateEquInfo($docId, $productId = 0) {
        if ($productId == 0) {
            $condition = "";
        } else {
            $condition = " and productId not in('" . $productId . "') ";
        }
        if ($docId) {
            $isDelSql = "SELECT * FROM oa_borrow_equ WHERE borrowId='" . $docId . "' and isTemp=0 AND isDel=1" . $condition;
            $isDelArr = $this->_db->getArray($isDelSql);
            if (is_array($isDelArr) && count($isDelArr) > 0) {
                $oldEquIdArr = array();
                $equSql = "SELECT * FROM oa_borrow_equ WHERE borrowId='" . $docId . "' and isTemp=0 AND isDel=0 " . $condition . " order by id DESC";
                $equArr = $this->_db->getArray($equSql);
                if (is_array($equArr) && count($equArr)) {
                    foreach ($isDelArr as $key => $val) {
                        foreach ($equArr as $index => $row) {
                            if ($val['productId'] == $row['productId']) {
                                $this->updateEquNum($docId);
                                $oldEquIdArr[$key]['oldId'] = $val['id'];
                                $oldEquIdArr[$key]['newId'] = $row['id'];
                                break;
                            }
                        }
                    }
                    $this->updateRelInfo($oldEquIdArr);
                }
            }
            return 1;
        } else {
            return 0;
        }
    }

    function updateRelInfo($oldEquIdArr) {
        foreach ($oldEquIdArr as $key => $val) {
            $planSql = "update oa_stock_outplan_product set contEquId='" . $val['newId'] . "' where docType='oa_borrow_borrow' and contEquId='" . $val['oldId'] . "'";
            $this->_db->query($planSql);
            $planSql = "update oa_purch_plan_equ set applyEquId='" . $val['newId'] . "' where purchType='oa_borrow_borrow' and applyEquId='" . $val['oldId'] . "'";
            $this->_db->query($planSql);
        }
    }

    function updateEquNum($docId) {
        //��������
        $outStockSql = "update oa_borrow_equ op inner join
			       (
			        select sum(ai.allocatNum) as allocatNum,
			               ai.relDocId
			        from oa_stock_allocation a
			             RIGHT JOIN oa_stock_allocation_item ai on (ai.mainId = a.id)
			        where a.docStatus = 'YSH' and
			              a.relDocType = 'DBDYDLXJY' and
			              ai.relDocId > 0
			        group by ai.relDocId
			       ) sub on (sub.relDocId = op.id)
					set op.executedNum = sub.allocatNum
					where sub.allocatNum <> op.executedNum AND op.borrowId='" . $docId . "'";
        $this->_db->query($outStockSql);
        //�ƻ�����
        $outPlanSql = "UPDATE oa_borrow_equ e LEFT JOIN (
						SELECT e.id,e.borrowId,e.issuedShipNum,e.number,c.* FROM oa_borrow_equ e LEFT JOIN (
						SELECT
						IFNULL(sum(op.number),0) AS pNumber,
						op.contEquId,
						o.id AS oId
						FROM
						oa_stock_outplan_product op
						RIGHT JOIN oa_stock_outplan o ON (o.id = op.mainId)
						WHERE
						 op.contEquId is not NULL AND o.docType='oa_borrow_borrow' AND op.isDelete=0
						GROUP BY
						op.contEquId,o.docId HAVING op.contEquId<>0
						)c ON e.id=c.contEquId
						) p
						ON (e.id=p.contEquId AND e.borrowId=p.borrowId)
						SET e.issuedShipNum=p.pNumber
						WHERE p.pNumber is not NULL and e.borrowId='" . $docId . "'";
        $this->_db->query($outPlanSql);
        //�ɹ�����
        $purchSql = "UPDATE oa_borrow_equ e LEFT JOIN (
							SELECT e.id,e.borrowId,e.issuedPurNum,e.number,c.* FROM oa_borrow_equ e LEFT JOIN (
							SELECT
							IFNULL(sum(op.amountAll),0) AS pNumber,
							op.applyEquId,
							o.id AS oId
							FROM
							oa_purch_plan_equ op
							RIGHT JOIN oa_purch_plan_basic o ON (o.id = op.basicId)
							WHERE
							 op.applyEquId is not NULL AND o.purchType='oa_borrow_borrow'
							GROUP BY
							op.applyEquId,o.sourceID HAVING op.applyEquId<>0
							)c ON e.id=c.applyEquId
							) p
							ON (e.id=p.applyEquId AND e.borrowId=p.borrowId)
							SET e.issuedPurNum=p.pNumber
							WHERE p.pNumber is not NULL  and e.borrowId='" . $docId . "'";
        $this->_db->query($purchSql);
        //����״̬
        $shipStatusSql = "UPDATE oa_borrow_borrow c inner JOIN (
							SELECT c.borrowId,
									CASE WHEN ( c.countNum<=0 ) THEN '1'
											 WHEN ( c.countNum>0 AND c.executedNum=0 ) THEN '0'
											 WHEN ( c.countNum>0 AND c.executedNum>0 ) THEN '2'
									END AS DeliveryStatus
							FROM (select count(0) as equCount,sum(IF (c.remainNum>0,1,0)) AS countNum,c.borrowId,
											(select sum(o.executedNum) from oa_borrow_equ o where o.borrowId=c.borrowId and o.isTemp=0 and o.isDel=0) as executedNum
											from (select e.id,e.borrowId,(e.number-e.executedNum + e.backNum) as remainNum from oa_borrow_equ e
											where e.isTemp=0 and e.isDel=0) c where 1=1 GROUP BY borrowId HAVING borrowId is NOT NULL) c
							)e ON ( c.id=e.borrowId )
							SET c.DeliveryStatus=e.DeliveryStatus
							WHERE c.DeliveryStatus<>'3' and c.id='" . $docId . "'";
        $this->_db->query($shipStatusSql);
    }

    /********************************************************�������******************************************************************************/


    /**
     * ���ݺ�ͬid�޸ĺ�ͬ�������ƻ�״̬
     */
    function updateOutStatus_d($id) {

		// �黹״̬����
		$exeSql = "select SUM(number) AS number, SUM(executedNum) AS exeNum, SUM(backNum) AS backNum
			from oa_borrow_equ where borrowId = " . $id . " and isDel = 0"; //�ѹ黹�Ĵӱ�����
		$exeNum = $this->_db->getArray($exeSql);

		// ���ִ������Ϊ0���򽫹黹״̬����Ϊ�ѹ黹�����ڲ���
		if ($exeNum[0]['allNum'] === null || $exeNum[0]['exeNum'] == 0) {
			$this->updateField(array("id" => $id), "backStatus", "0");
            $hwBackStatus = 1;
		} else if ($exeNum[0]['exeNum'] == $exeNum[0]['backNum']) {
			$this->updateField(array("id" => $id), "backStatus", "1");
            $hwBackStatus = 3;
		} else {
			$this->updateField(array("id" => $id), "backStatus", "2");
            $hwBackStatus = 2;
		}

        $orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_borrow_equ o where o.borrowId=" . $id . " and o.isTemp=0 and o.isDel=0) as executeNum
						 from (select e.borrowId,(e.number-e.executedNum) as remainNum from oa_borrow_equ e
						where e.borrowId=" . $id . " and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
        $remainNum = $this->_db->getArray($orderRemainSql);
        if ($remainNum[0]['countNum'] <= 0) {//�ѷ���
            $statusInfo = array(
                'id' => $id,
                'DeliveryStatus' => 'YFH',
                'hwDeliveryStatus'=>2
            );
        } elseif ($remainNum[0]['countNum'] > 0 && $remainNum[0]['executeNum'] == 0) {//δ����
            $statusInfo = array(
                'id' => $id,
                'DeliveryStatus' => 'WFH',
                'hwDeliveryStatus'=>0
            );

        } else {//���ַ���
            $statusInfo = array(
                'id' => $id,
                'DeliveryStatus' => 'BFFH',
                'hwDeliveryStatus'=>1
            );
        }
        $this->updateById($statusInfo);

        //ͬ�����º���
        $borrowRow=$this->get_d($id);
        if ($borrowRow['customerId'] == '1058') {
            $updateSql = " update overseas_borrow_baseinfo set deliveryStatus='".$statusInfo['hwDeliveryStatus']."',backStatus='".$hwBackStatus."' where borrowNo='" . $borrowRow['Code'] . "'";
            $this->connectSql($updateSql);
        }
        return 0;
    }

    /**
     * �ı䷢��״̬ --- �ر�
     */
    function updateDeliveryStatus($id) {
		try {
			$this->start_d();

			// ����״̬����
			$this->updateField(array("id" => $id), "DeliveryStatus", "TZFH");

			// �黹״̬����
			$exeSql = "select SUM(executedNum) AS exeNum from oa_borrow_equ where borrowId = " . $id .
				" and isDel='0'"; //�ѹ黹�Ĵӱ�����
			$exeNum = $this->_db->getArray($exeSql);

			// ���ִ������Ϊ0���򽫹黹״̬����Ϊ�ѹ黹�����ڲ���
			if (!$exeNum || $exeNum[0]['exeNum'] == 0) {
				$this->updateField(array("id" => $id), "backStatus", "1");
			}

            //ͬ�����º���
            $borrowRow=$this->get_d($id);
            if ($borrowRow['customerId'] == '1058') {
                $updateSql = " update overseas_borrow_baseinfo set deliveryStatus='3' where borrowNo='" . $borrowRow['Code'] . "'";
                $this->connectSql($updateSql);
            }
			$this->commit_d();
			return 1;
		} catch (Exception $e) {
			$this->rollBack();
			return 0;
		}
    }

    /**
     * �������ݿ����Ӳ�ִ��sql���
     * @param string sql���
     * @return string id
     */
    function  connectSql($sql) {
        $con = mysql_connect(localhostOA, dbuserOA, dbpwOA);
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db(dbnameOA, $con);
        mysql_query("SET NAMES 'GBK'");

        if (!$result = mysql_query($sql, $con)) {
            echo $sql;
            die('Error: ' . mysql_error());
        }
        $id = mysql_insert_id();
        $rows = array();
        while ($rows[] = mysql_fetch_array($result, MYSQL_ASSOC)) {
        }
        mysql_free_result($result);
        array_pop($rows);
        mysql_close($con);
        return $rows;
    }


    /**
     * ���ݷ�������޸ĺ�ͬ�������ƻ�״̬
     */
    function updateShipStatus_d($id) {
        $orderRemainSql = "select count(0) as countNum,(select sum(o.issuedShipNum) from oa_borrow_equ o where o.borrowId=" . $id . " and o.isTemp=0 and o.isDel=0) as issuedShipNum
                from (select e.borrowId,(e.number-e.issuedShipNum) as remainNum from oa_borrow_equ e
            where e.borrowId=" . $id . " and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
        $remainNum = $this->_db->getArray($orderRemainSql);

        $orderRemainSql1 = " select (sum(number)-sum(executedNum)) as remainNum,sum(number) as allNum from oa_borrow_equ where isTemp=0 and borrowId=" . $id . " and isDel=0";
        $remainNum1 = $this->_db->getArray($orderRemainSql1);
        if ($remainNum1[0]['remainNum'] <= 0) {
            $DeliveryStatus = 'YFH';
        } else if ($remainNum1[0]['remainNum'] == $remainNum1[0]['allNum']) {
            $DeliveryStatus = 'WFH';
        } else {
            $DeliveryStatus = 'BFFH';
        }

        if ($remainNum[0]['countNum'] <= 0) {//�ѷ���
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'YXD',
                'DeliveryStatus' => $DeliveryStatus
            );
        } elseif ($remainNum[0]['countNum'] > 0 && $remainNum[0]['issuedShipNum'] == 0) {//δ����
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'WXD',
                'DeliveryStatus' => $DeliveryStatus
            );
        } else {//���ַ���
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'BFXD',
                'DeliveryStatus' => $DeliveryStatus
            );
        }
        $this->updateById($statusInfo);
        return 0;
    }

    /**************************************************��ͬ�豸ͳ�Ʋ��� start****************************************************/
    /**
     * �ɹ��豸-�ƻ�����
     */
    function pageEqu_d() {
        $this->_isSetCompany = 0;
        $stockDao = new model_stock_stockinfo_systeminfo();
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $searchArr = $this->__GET("searchArr");
        $this->__SET('searchArr', $searchArr);
        $this->groupBy = 'productId';
        $rows = $this->getPagePlan("select_equ");
        $equIdArr = array();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $key => $val) {
                $equIdArr[] = $val['productId'];
            }
            $equIdStr = implode(',', $equIdArr);
            $stockArr = $stockDao->get_d(1);
            $equInvInfo = $inventoryDao->getInventoryInfos($stockArr['salesStockId'], $equIdStr);
            foreach ($rows as $key => $val) {
                $rows[$key]['inventoryNum'] = 0;
                foreach ($equInvInfo as $k => $v) {
                    if ($val['productId'] == $v['productId']) {
                        $rows[$key]['inventoryNum'] = $v['exeNum'];
                    }
                }
            }
            $i = 0;
            foreach ($rows as $key => $val) {
                $searchArr = $this->__GET("searchArr");
                $searchArr['productIdEqu'] = $val['productId'];
                $this->groupBy = "id";
                $this->sort = "id";
                $this->searchArr = $searchArr;
                $chiRows = $this->listBySqlId("select_cont");
                $rows[$i]['childArr'] = $chiRows;
                ++$i;
            }
            return $rows;
        } else {
            return false;
        }
    }


    /**�ɹ�����-�ɹ��ƻ�-�豸�嵥��ʾģ��
     *author can
     *2011-3-23
     */
    function showEqulist_s($rows) {
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $i++;
                $addAllAmount = 0;
                $strTab = "";
                foreach ($val['childArr'] as $chdKey => $chdVal) {
                    switch ($chdVal['tablename']) {
                        case 'oa_sale_order':
                            $chdVal['tablename'] = '���۷���';
                            break;
                        case 'oa_sale_lease':
                            $chdVal['tablename'] = '���޷���';
                            break;
                        case 'oa_sale_rdproject':
                            $chdVal['tablename'] = '�з�����';
                            break;
                        case 'oa_sale_service':
                            $chdVal['tablename'] = '���񷢻�';
                            break;
                    }
                    $iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                    $addAllAmount += $chdVal['number'] - $chdVal['executedNum'];
                    $inventoryNum = $rows[$key]['inventoryNum'];
                    $strTab .= <<<EOT
						<tr align="center" height="28" class="$iClass">
			        		<td width="20%">
						    	$chdVal[code]
					        </td>
					        <td  width="8%">
					            $chdVal[number]
					        </td>
					        <td  width="8%">
					            $chdVal[onWayNum]
					        </td>
					        <td  width="8%">
					            $chdVal[executedNum]
					        </td>
		            	</tr>
EOT;
                }

                $str .= <<<EOT
					<tr class="$iClass">
				        <td    height="30" width="4%">
				        	<p class="childImg">
				            <image src="images/expanded.gif" />$i
				        	</p>
				        </td>
				        <td >
				            $val[productNo]<br>$val[productName]
				        </td>
				        <td   width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$addAllAmount</p>
				            </p>
				        </td>
				        <td width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$inventoryNum</p>
				            </p>
				        </td>
				        <td width="65%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
				        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
				        </td>
				    </tr>
EOT;
            }
        } else {
            $str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
        }
        return $str;
    }

    /**
     * ��ͬ�豸�ܻ� ��ҳ
     * 2011��10��19�� 16:24:57
     */
    function getPagePlan($sql) {
        $sql = $this->sql_arr [$sql];
        $countsql = "select count(0) as num " . substr($sql, strpos($sql, "FROM"));
        $countsql = $this->createQuery($countsql, $this->searchArr);
        $this->count = $this->queryCount($countsql);
        //ƴװ��������
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //����������Ϣ
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " group by productId order by " . $this->sort . " " . $asc;
        //������ȡ��¼��
        $sql .= " limit " . $this->start . "," . $this->pageSize;
        //		echo $sql;
        return $this->_db->getArray($sql);
    }
    /**************************************************��ͬ�豸ͳ�Ʋ��� end***************************************/


    /**
     * ��ȾԴ�����
     */
    function getSingleCodeURL($obj) {
        $singleArr = array();
        $SingleType = $obj['SingleType'];
        switch ($SingleType) {
            case "" :
                $singleArr['SingleType'] = "��";
                $singleArr['singleCode'] = "��";
                break;
            case "chance" :
                $chacneId = $obj['chanceId'];
                $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id=' . $chacneId . '&perm=view\')">' . $obj['chanceCode'] . '</span>';
                $singleArr['SingleType'] = "�̻�";
                $singleArr['singleCode'] = $code;
                break;
            case "order" :
                $orderId = $obj['contractId'];
                $orerType = $obj['contractType'];
                $orderCode = $obj['contractNum'];
                switch ($orerType) {
                    case "oa_sale_order" :
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=projectmanagent_order_order&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_service" :
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=engineering_serviceContract_serviceContract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_lease":
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=contract_rental_rentalcontract&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                    case "oa_sale_rdproject" :
                        $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=' . $orderId . '&perm=view\')">' . $orderCode . '</span>';
                        break;
                }
                $singleArr['SingleType'] = "��ͬ";
                $singleArr['singleCode'] = $code;
                break;
        }
        return $singleArr;
    }

    /**
     * ����Դ�����ӱ�ID ��ȡδִ������
     */
    function getDocNotExeNum($docId, $docItemId) {
        $sql = "select (number - executedNum) as nonexecutionNum from oa_borrow_equ where id=" . $docItemId . "";
        $numarr = $this->_db->getArray($sql);
        return $numarr[0]['nonexecutionNum'];
    }

    /**
     * ���¾�����
     */
    function updateBorrow_d() {
        try {
            $this->start_d();
            $objArr = $this->list_d();
            $linkdao = new model_projectmanagent_borrow_borrowequlink();
            if (is_array($objArr) && count($objArr) > 0) {
                $mainSql = "update oa_borrow_borrow set dealStatus='1'";
                $this->_db->query($mainSql);
                foreach ($objArr as $key => $val) {
                    $borrowId = $val['id'];
                    //����ȷ��������
                    $link = array(
                        "borrowId" => $borrowId,
                        "rObjCode" => $val['objCode'],
                        "borrowCode" => $val['Code'],
                        "borrowName" => '',
                        "borrowType" => "oa_borrow_borrow",
                        "ExaStatus" => '���',
                        "ExaDTOne" => $val['ExaDT'],
                        "ExaDT" => $val['ExaDT'],
                        "changeTips" => 0,
                        "isSubAppChange" => 0,
                        "updateTime" => $val['updateTime'],
                        "updateId" => $val['updateId'],
                        "updateName" => $val['updateName'],
                        "createTime" => $val['createTime'],
                        "createName" => $val['createName'],
                        "createId" => $val['createId']
                    );
                    $linkArr[$borrowId] = $linkdao->create($link); //����linkId
                }
                if (is_array($linkArr) && count($linkArr) > 0) {
                    foreach ($linkArr as $key => $val) {
                        $itemSql = "update oa_borrow_equ set linkId=" . $val . " where borrowId=" . $key;
                        $this->_db->query($itemSql);
                    }
                }
            }
            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return $e;
        }
    }

    /**
     * �ر�����ȷ��
     */
    function setEmailAfterCloseConfirm($id) {
        try {
            $this->start_d();
            $linkDao = new model_projectmanagent_borrow_borrowequlink();
            $linkDao->update(array('borrowId' => $id), array('ExaStatus' => '���', 'ExaDT' => day_date));
            $addMsg = '�ý�����������������ȷ�ϡ�';
            $mainObj = $this->get_d($id);
            $updateKey = array(
                'dealStatus' => '3'
            );
            $this->update(array('id' => $id), $updateKey);
            $outmailArr = array(
                $mainObj['salesNameId'],
                $mainObj['createId']
            );
            $outmailStr = implode(',', $outmailArr);
            $emailDao = new model_common_mail();
            $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL']
                , 'oa_borrow_equ', '�ر�', $mainObj['Code'], $outmailStr, $addMsg, '1');
            $this->commit_d();
            return 1;
        } catch (exception $e) {
            $this->rollBack();
            return 0;
        }
    }

    /**
     *
     * �ѳ�ʼ���Ľ��������ݲ�����Ӧ���������
     */
    function initExeBorrow() {
        set_time_limit(0);
        try {
            $this->start_d();
            $this->searchArr = array("initTip" => 1);
            $initBorrowArr = $this->listBySqlId();
            $allocationDao = new model_stock_allocation_allocation();
            $allocationItemDao = new model_stock_allocation_allocationitem();
            $borrowEquDao = new model_projectmanagent_borrow_borrowequ();
            $stockSystemDao = new model_stock_stockinfo_systeminfo();
            $otherdatasDao = new model_common_otherdatas ();
            $codeDao = new model_common_codeRule();
            $stockSysObj = $stockSystemDao->get_d(1);
            foreach ($initBorrowArr as $key => $value) {
                $tempAllocationObj = array(
                    "relDocId" => $value['id'],
                    "relDocType" => "DBDYDLXJY",
                    "relDocCode" => $value['Code'],
                    "auditDate" => day_date,
                    "toUse" => "CHUKUJY",
                    "outStartDate" => $value['beginTime'],
                    "customerName" => $value['customerName'],
                    "customerId" => $value['customerId'],
                    "contractId" => $value['id'],
                    "contractType" => "oa_borrow_borrow",
                    "contractCode" => $value['Code'],
                    "docStatus" => "YSH",
                    'createId' => $_SESSION ['USER_ID'],
                    'createName' => $_SESSION ['USERNAME'],
                    'createTime' => date("Y-m-d H:i:s"),
                    'updateId' => $_SESSION ['USER_ID'],
                    'updateName' => $_SESSION ['USERNAME'],
                    'updateTime' => date("Y-m-d H:i:s"),
                    'auditerCode' => $_SESSION ['USER_ID'],
                    'auditerName' => $_SESSION ['USERNAME'],
                    "remark" => $value['remark']
                );
                $tempAllocationObj['docCode'] = $codeDao->stockCode("oa_stock_allocation", "CHG");
                if ($value ['limits'] == "Ա��") {
                    $tempAllocationObj["pickName"] = $value ['createName'];
                    $tempAllocationObj["pickCode"] = $value ['createId'];
                    $userInfoObj = $otherdatasDao->getUserDatas($value ['createId'], array("DEPT_ID", "DEPT_NAME"));
                    $tempAllocationObj["deptName"] = $userInfoObj ['DEPT_NAME'];
                    $tempAllocationObj["deptCode"] = $userInfoObj ['DEPT_ID'];
                } else {
                    $tempAllocationObj["pickName"] = $value ['salesName'];
                    $tempAllocationObj["pickCode"] = $value ['salesNameId'];
                    $userInfoObj = $otherdatasDao->getUserDatas($value ['createId'], array("DEPT_ID", "DEPT_NAME"));
                    $tempAllocationObj["deptName"] = $userInfoObj ['DEPT_NAME'];
                    $tempAllocationObj["deptCode"] = $userInfoObj ['DEPT_ID'];
                }

                $allocationId = $allocationDao->create($tempAllocationObj);
                //echo "allocation:". $allocationId;
                $borrowEquArr = $borrowEquDao->findAll(array("borrowId" => $value['id']));
                $allocationItemArr = array();
                foreach ($borrowEquArr as $borrowEqObj) {
                    $tempAllocationItemObj = array(
                        "mainId" => $allocationId,
                        "productId" => $borrowEqObj['productId'],
                        "productCode" => $borrowEqObj['productNo'],
                        "productName" => $borrowEqObj['productName'],
                        "pattern" => $borrowEqObj['productModel'],
                        //"unitName"=>$borrowEqObj[''],
                        "allocatNum" => $borrowEqObj['number'],
                        "exportStockId" => $stockSysObj['salesStockId'],
                        "exportStockCode" => $stockSysObj['salesStockCode'],
                        "exportStockName" => $stockSysObj['salesStockName'],
                        "relDocId" => $borrowEqObj['id'],
                        "importStockName" => $stockSysObj['borrowStockName'],
                        "importStockId" => $stockSysObj['borrowStockId'],
                        "importStockCode" => $stockSysObj['borrowStockCode'],
                        "remark" => $borrowEqObj['remark']
                    );
                    array_push($allocationItemArr, $tempAllocationItemObj);
                }
                $allocationItemDao->addBatch_d($allocationItemArr);
            }
            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���������ۼƽ��
     */
    function getPersonalEquMoney($createName) {
        $sql = "
            SELECT
                SUM(
                    IF (
                        i.priCost IS NULL,
                        0,
                        (e.executedNum - e.backNum) * i.priCost
                    )
                ) AS equMoney
            FROM
                oa_borrow_equ e
            LEFT JOIN oa_borrow_borrow c ON e.borrowId = c.id
            LEFT JOIN oa_stock_product_info i ON e.productId = i.id
            WHERE
                c.createName = '{$createName}'
            AND	c.isTemp = 0
            AND c.backStatus <> 1
            AND e.executedNum > 0
            AND e.executedNum > backNum
            GROUP BY
                c.createName";
        return $this->findSql($sql);
    }


    /**
     * ��ȡת���ۺ�ͬ��Ϣ
     */
    function getTurnCon($bid) {
        $sql = "select contractId,contractCode from oa_contract_equ where toBorrowId='" . $bid . "' and isTemp=0 GROUP BY contractId";
        $arr = $this->_db->getArray($sql);
        $str = "";
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $v) {
                $cid = $v['contractId'];
                $code = $v['contractCode'];
                $str .= <<<EOT
                   <span style='color:blue;cursor:pointer;' onclick='contractView($cid);'>$code</span><br/>
EOT;
            }
            return $str;
        } else {
            return $str;
        }
    }

    /**
     * �Ƚϱ������
     */
    function getDeff($row,$rowb){

    	$product = $row['product'];
    	unset($row['product']);
    	$borrowequ = $row['borrowequ'];
    	unset($row['borrowequ']);

        $oldrow = array(
            "beginTime" => $rowb['beginTime'],
            "closeTime" => $rowb['closeTime'],
            "deliveryDate" => $rowb['deliveryDate'],
            "salesName" => $rowb['salesName'],
            "salesNameId" => $rowb['salesNameId'],
            "scienceName" => $rowb['scienceName'],
            "scienceNameId" => $rowb['scienceNameId'],
            "shipaddress" => $rowb['shipaddress'],
            "remark" => $rowb['remark'],
        	"remarkapp" => $rowb['remarkapp'],
            "oldId" => $rowb['id'],
        	"module" => $rowb['module'],
            "areaCode" => $rowb['areaCode'],
            "areaName" => $rowb['areaName']
        );

        unset($row['borrowId']);
        unset($row['isSub']);
        unset($row['tempId']);
		$diff = array_diff($row,$oldrow);
		if(count($diff) < 2){
			if(count($diff) == 1){
				if(isset($diff['remarkapp'])){// ֻ�����ע
					return 1;
				}else {
					return 0;
				}
			}
        	//�ж���ϸ�Ƿ��б��
        	$change = false;
            foreach($product as $key => $val){
    			if(empty($val['id']) || isset($val['isDelTag'])){//������ɾ���˲�Ʒ�嵥
    				$change = true;
    				break;
    			}
    		}
    		if($change){
    			return 0;
    		}else{
    			foreach($borrowequ as $key => $val){
    				if(empty($val['id']) || isset($val['isDelTag'])){//������ɾ���˷��������嵥
    					$change = true;
    					break;
    				}
    			}
    			if($change){
    				return 0;
    			}else{
    				foreach($product as $key => $val){
    					foreach($rowb['product'] as $k => $v){
							if($val['id'] == $v['id']){
								unset($val['rowNum_']);
								$diff = array_diff($val,$v);
								if(!empty($diff)){//�޸���ĳ����Ʒ�嵥
									$change = true;
									break 2;
								}
							}
    					}
    				}
    				if($change){
    					return 0;
    				}else{
    					foreach($borrowequ as $key => $val){
    						foreach($rowb['borrowequ'] as $k => $v){
    							if($val['id'] == $v['id']){
    								unset($val['rowNum_']);
	    							$diff = array_diff($val,$v);
									if(!empty($diff)){//�޸���ĳ�����������嵥
										$change = true;
										break 2;
									}
    							}
    						}
    					}
    					if($change){
    						return 0;
    					}else{
    						return 2;// ���κα��
    					}
    				}
    			}
    		}
        }else{
            return 0;
        }
    }

    /**
     * ��д����ɾ������
     */
    function deletes_d($ids) {
    	try {
    		// ��ȡ��Ʒ�嵥��Ϣ,��ɾ��
    		$productDao = new model_projectmanagent_borrow_product();
    		$rs = $productDao->findAll(array('borrowId' => $ids),null,'id');
    		if(!empty($rs)){
    			$productIds = '';
    			foreach ($rs as $v){
    				$productIds .= $v['id'].',';
    			}
    			$productIds = substr($productIds, 0, strlen($productIds)-1);
    			$productDao->deletes ( $productIds );
    		}
    		$this->deletes ( $ids );
    		return true;
    	} catch ( Exception $e ) {
    		throw $e;
    	}
    }

    /**
     * �ж��Ƿ��ܹر�
     * @param $id ���õ�id
     */
    function isCanClose_d($id){
    	// ��֤�Ƿ����δ�رյķ�������
    	$sql = "SELECT
					*
				FROM
					oa_borrow_borrow c
				LEFT JOIN oa_borrow_equ_link l ON (
					c.id = l.borrowId
					AND l.isTemp = 0
				)
				WHERE
					(
						SELECT
							count(*)
						FROM
							oa_borrow_equ e
						WHERE
							e.isTemp = 0
						AND e.isDel = 0
						AND e.borrowId = c.id
					) > 0
				AND c.isTemp = 0
				AND c.ExaStatus IN ('���', '���������')
				AND l.ExaStatus IN ('���', '���������')
				AND c.DeliveryStatus IN ('WFH', 'BFFH')
				AND c.limits = '�ͻ�'
				AND c.id = ".$id;
    	$rs = $this->_db->getArray($sql);
    	if(!empty($rs)){
    		return 1;
    	}else{
    		// ��֤�Ƿ����δ�رյķ����ƻ�
    		$outplan = new model_stock_outplan_outplan();
    		$rs = $outplan->find(array('docId' => $id,'docType' => 'oa_borrow_borrow'),null,'docStatus');
    		if(!empty($rs) && $rs['docStatus'] != 'YGB'){
    			return 2;
    		}else{
    			// ��֤�Ƿ���ڵ�����
    			$allocationDao = new model_stock_allocation_allocation();
    			$rs = $allocationDao->find(array('contractId' => $id,'contractType' => 'oa_borrow_borrow'));
    			if(!empty($rs)){
    				return 3;
    			}else{
    				return 0;
    			}
    		}
    	}
    }

    /**
     *  workflowCallBack
     */
    function workflowCallBack($spid){
    	$otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo ['objId'];
        if (!empty ($objId)) {
            $contract = $this->get_d($objId);
            $rows = $this->get_d($objId);
            if ($contract ['ExaStatus'] == "���") {

                $linkDao = new model_projectmanagent_borrow_borrowequlink();
                $linkDao->update(
                    array("borrowId" => $objId),
                    array(
                        "ExaStatus" => '���',
                        "ExaDT" => day_date,
                        "ExaDTOne" => day_date
                    )
                );

                $updateArr = ($rows['limits'] == 'Ա��')? array('status' => '0',"isproShipcondition" => 1,"dealStatus" => 1) : array('status' => '0',"dealStatus" => 1);
                $this->update(array('id' => $objId),$updateArr);
//            	//�������״̬Ϊ�˻أ���������ͨ��������Ϊ����
//            	if($contract['status'] == '3'){
//            		$this->update(array('id' => $objId),$updateArr);
//            	}
//                if ($rows['createId'] != $rows['salesNameId']) {
//                    $deptName = $otherdatas->getUserDatas($rows['salesNameId'], 'DEPT_NAME');
//                    if ($deptName != '����ҵ��') {
//                        $addmsg = "���Ϊ ��" . $rows['Code'] . " ���Ľ����õ��ݣ��ѽ�������Ϊ�����ˣ���ע����ٵ��ݡ�<br />   �����ˣ�" . $rows['createName'] . "";
//                        $emailDao = new model_common_mail();
//                        $emailInfo = $emailDao->batchEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_borrow_borrow", "�ύ��", "�Ѿ�ͨ��", $rows['salesNameId'], $addmsg);
//                    }
//                }
//                //�����������ʼ�
//                $infoArr = array(
//                    'code' => $rows['Code'],
//                    'type' => '����'
//                );
//                //ͨ���ʼ������ݲ�֧��ҵ����������д���ʼ������� ���ݶ���
//                $objdeptName = $otherdatas->getUserDatas($rows['createId'], 'DEPT_NAME');
//                $toUser="";
//                if($deptName == '����ҵ��'){
//                    $this->mailDeal_d("borrowToShip_HY", $toUser, $infoArr);
//                }else if($rows['limits'] == 'Ա��'){
//                    $this->mailDeal_d("borrowToShip_YG", $toUser, $infoArr);
//                }else if($rows['limits'] == '�ͻ�'){
//                    $this->mailDeal_d("borrowToShip_KH", $toUser, $infoArr);
//                }
            }
        }
    }
    function workflowCallBack_sub($spid){
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo ['objId'];
        $rows = $this->get_d($objId);
        $status = $rows['status']; //��ȡת�赥��״̬��
        if ($status != 6) {
            $this->updateExaTomail($objId);
        }
    }

    /**
     * ��������ص�����
     * @param $spid
     */
    function workflowCallBack_changeNew($spid){
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo ['objId'];
        $date = date("Y-m-d");

        if (!empty ($objId)) {
            $obj = $this->get_d($objId);

            $changeLogDao = new model_common_changeLog ('borrow');
            $changeLogDao->confirmChange_d($obj);

            if ($obj['ExaStatus'] == AUDITED) {
                // ��ԭ��¼������״̬����Ϊ��ɡ�
                $updateSql = "update oa_borrow_changlog set ExaStatus = '���',ExaDT = '{$date}' where objType = 'borrow' and tempId='{$objId}';";
                $this->_db->query($updateSql);

                // ������������ĸ������ϵ�ID
                $updateSql = "update oa_borrow_equ e1 left join oa_borrow_equ e2 on (e1.isCon = e2.isConfig and e1.borrowId = e2.borrowId and e1.linkId = e2.linkId) set e2.parentEquId = e1.id where e1.isTemp = 0 and (e1.isDel = 0 && e2.isDel = 0) and  (e1.isCon <> '' and e1.isCon <> 'NULL') and (e2.isConfig <> '' and e2.isConfig <> 'NULL') and e1.borrowId = '{$obj['originalId']}' and e1.linkId <> '' and e2.parentEquId <> e1.id;";
                $this->_db->query($updateSql);

                // �����������а����˽�ֹ����,����Ϊ�������� PMS 646
                $chkCloseTimeChangeSql = "select * from oa_borrow_changedetail where changeField = 'closeTime' and tempId = '{$objId}';";
                $chkCloseTimeChange = $this->_db->get_one($chkCloseTimeChangeSql);
                if($chkCloseTimeChange){
                    $oldVal = (isset($chkCloseTimeChange['oldValue']) && !empty($chkCloseTimeChange['oldValue']))? $chkCloseTimeChange['oldValue'] : '';
                    $newVal = (isset($chkCloseTimeChange['newValue']) && !empty($chkCloseTimeChange['newValue']))? $chkCloseTimeChange['newValue'] : '';
                    $oldValTimeStr = ($oldVal != '')? strtotime($oldVal) : '';
                    $newValTimeStr = ($newVal != '')? strtotime($newVal) : '';
                    if($newValTimeStr > $oldValTimeStr && $obj['originalId'] > 0){
                        $updateObj = array(
                            "id" => $obj['originalId'],
                            "isDelayApply" => 1
                        );
                        $this->updateById($updateObj);
                    }
                }

                // �������ϵĲ�Ʒ�����ϵ
                $borrowEquDao = new model_projectmanagent_borrow_borrowequ();
                $equs = $borrowEquDao->findAll(array("borrowId" => $obj['originalId']));
                foreach ($equs as $k => $equ){
                    if($equ['conProductId'] != '' || $equ['conProductId'] != 'null'){
                        $sltMapSql = "select '{$equ['id']}' as equId,p1.id as pid1,p2.id as pid2 from oa_borrow_product p1 left join oa_borrow_product p2 on p1.deploy = p2.deploy where p2.isTemp = 0 and p1.id = '{$equ['conProductId']}';";
                        $mapArr = $this->_db->getArray($sltMapSql);
                        if($mapArr){
                            $borrowEquDao->updateById(array("id"=>$mapArr[0]['equId'],"conProductId"=>$mapArr[0]['pid2']));
                        }
                    }
                }

                // ���³ɱ�����
                $borrowCostDao = new model_projectmanagent_borrow_cost();
                $objCost = $borrowCostDao->find(array("borrowId"=>$objId));
                if($objCost && isset($objCost['id'])){
                    $confirmInfo['confirmName'] = $objCost['confirmName'];
                    $confirmInfo['confirmId'] = $objCost['confirmId'];
                    $confirmInfo['confirmDate'] = $objCost['confirmDate'];

                    // ����ʱ��¼�ĸ�����µ�ʵ�ʼ�¼��ȥ
                    $borrowCostDao->addCostConfirm(array(
                        "id" => $obj['originalId'],
                        "equEstimate" => $obj['equEstimate'],
                        "equEstimateTax" => $obj['equEstimateTax']
                    ),$confirmInfo,1);
                }

                $linkDao = new model_projectmanagent_borrow_borrowequlink();
                $linkDao->update(
                    array("borrowId" => $obj['originalId']),
                    array(
                        "ExaStatus" => '���',
                        "ExaDT" => day_date,
                        "ExaDTOne" => day_date
                    )
                );

                $this->updateById(array("id"=>$obj['originalId'],"changeTips" => 0,"isSubAppChange" => 0,"dealStatus" => 1));
                $this->updateShipStatus_d($obj['originalId']);
            } else if ($obj['ExaStatus'] == BACK){
                // ��ش���
                $this->update(array("id" => $obj['originalId']),array("changeTips"=>0,"isSubAppChange"=>0,"dealStatus"=>"1","ExaStatus"=>AUDITED));

                // ��ԭ��¼������״̬����Ϊ��ء�
                $updateSql = "update oa_borrow_changlog set ExaStatus = '���',ExaDT = '{$date}' where objType = 'borrow' and tempId='{$objId}';";
                $this->_db->query($updateSql);
            }
        }
    }

    function workflowCallBack_change($spid){
    	$otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo ['objId'];
        if (!empty ($objId)) {
            $contract = $this->get_d($objId);

            $changeLogDao = new model_common_changeLog ('borrow');
            $changeLogDao->confirmChange_d($contract);
            // ��������ʼ����ʱ�Լ�ԭ�������״̬
            $this->update(array('id' => $objId),array('dealStatus' => '1','changeTips' => '0','isSubAppChange' => '0'));
            $this->update(array('id' => $contract['originalId']),array('dealStatus' => '1','changeTips' => '0','isSubAppChange' => '0'));

            if ($contract ['ExaStatus'] == "���") {
            	//�������״̬Ϊ�˻أ���������ͨ��������Ϊ����
            	if($contract['status'] == '3'){
            		$this->update(array('id' => $contract['originalId']),array('status' => '0'));
            	}
                //�޸�ȷ��״̬
                // $dealStatusSql = "update oa_borrow_borrow set dealStatus=2 where id=" . $contract['originalId'];
                // $this->query($dealStatusSql);
                $this->updateOutStatus_d($contract['originalId']);
                $this->updateShipStatus_d($contract['originalId']);
                //�����������ʼ�
//                $infoArr = array(
//                    'code' => $contract['Code'],
//                    'type' => '���'
//                );
//                $emailInfo = $this->mailDeal_d("borrowToShip", "", $infoArr);
            }
        }
    }

    /**
     * ���ݽ�����id��ȡ���һ�α����¼id
     */
    function findChangeId($id)
    {
        $sql = "select max(id) as Mid from oa_borrow_borrow where originalId = $id";
        $idArr = $this->_db->getArray($sql);
        return ($idArr)? $idArr[0]['Mid'] : '';
    }

    /**
     * ���������ϴ�
     */
    function uploadfile_d($row)
    {
        try {
            //���������ƺ�Id
            $this->updateObjWithFile($row['serviceId']);
            return true;
        } catch (exception $e) {
            return false;
        }
    }

    /**
     * ���۷�������ȷ���ύ
     * @param $needSalesConfirm
     * @param $salesConfirmId
     * @return array
     */
    function salesConfirmEqu($needSalesConfirm, $salesConfirmId){
        $backArr = array(
            "result" => false
        );
        switch($needSalesConfirm){
            case '1':// ����
                if($salesConfirmId>0){
                    $backArr['result'] = true;
                    $backArr['url'] = 'controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $salesConfirmId;
                }else{
                    $backArr['result'] = false;
                }
                break;
            case '2':// ���۱��
                if($salesConfirmId>0){
                    $backArr['result'] = true;
                    $backArr['url'] = 'controller/projectmanagent/borrow/ewf_change_index.php?actTo=ewfSelect&billId=' . $salesConfirmId;
                }else{
                    $backArr['result'] = false;
                }
                break;
            case '3':// �������
                if($salesConfirmId>0){
                    $backArr['result'] = true;
                    $backArr['url'] = "controller/projectmanagent/borrow/ewf_borrowequ_manager.php?actTo=ewfSelect&billId=" . $salesConfirmId;
                }else{
                    $backArr['result'] = false;
                }
                break;
        }
        return $backArr;
    }

    /**
     * ���۷�������ȷ�ϴ��
     * @param $needSalesConfirm
     * @param $salesConfirmId
     * @return bool
     */
    function salesBackEqu($needSalesConfirm, $salesConfirmId){
        $result = false;
        $objId = '';
        $today = date("Y-m-d");
        switch($needSalesConfirm){
            case '1':// ����
                $objId = $salesConfirmId;
                // ���ԭ���ķ�������ȷ�ϱ�ʾ
                $this->_db->query("update oa_borrow_equ_link set ExaStatus = '���' where borrowId = '{$objId}';");
                $result = $this->updateById(array("id"=>$objId,"needSalesConfirm"=>0,"salesConfirmId"=>0,"dealStatus"=>0));
                break;
            case '2':// ���۱��
                $objId = $salesConfirmId;
                $objArr = $this->get_d($objId);
                $this->_db->query("update oa_borrow_changlog set ExaStatus = '���۴��',ExaDT = '{$today}' where objType = 'borrow' and tempId='{$salesConfirmId}';");
                // ���ԭ���ķ�������ȷ�ϱ�ʾ
                $this->_db->query("update oa_borrow_equ_link set ExaStatus = '���' where borrowId = '{$objId}';");
                $this->updateById(array("id"=>$objId,"needSalesConfirm"=>0,"salesConfirmId"=>0,"dealStatus"=>2));
                $result = $this->updateById(array("id"=>$objArr['originalId'],"needSalesConfirm"=>0,"salesConfirmId"=>0,"dealStatus"=>2));
                break;
            case '3':// �������
                $linkObj = $this->_db->get_one("select * from oa_borrow_equ_link where id = '{$salesConfirmId}'");
                $objId = ($linkObj && isset($linkObj['borrowId']))? $linkObj['borrowId'] : '';
                if($objId != ''){
                    $this->_db->query("update oa_borrow_equ_link set ExaStatus = '���' where borrowId = '{$salesConfirmId}';");
                    $this->_db->query("update oa_borrow_changlog set ExaStatus = '���۴��',ExaDT = '{$today}' where objType = 'borrowequ' and tempId='{$salesConfirmId}';");
                    // ���ԭ���ķ�������ȷ�ϱ�ʾ
                    $result = $this->updateById(array("id"=>$objId,"dealStatus"=>1,"needSalesConfirm"=>0,"salesConfirmId"=>0));
                }
                break;
        }

        return $result;
    }
}