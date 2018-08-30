<?php

//2012-12-27����
/**
 * @author LiuB
 * @Date 2012��3��8�� 10:30:28
 * @version 1.0
 * @description:��ͬ������Ʋ�
 */
class controller_contract_contract_contract extends controller_base_action
{
    private $bindId = "";
    function __construct()
    {
        $this->objName = "contract";
        $this->objPath = "contract_contract";
        //		$this->lang="contract";//���԰�ģ��
        $this->bindId = "458b579c-3a93-4648-9453-7af8407f1ede";
        parent :: __construct();
    }

    /**
     *Ĭ��action��ת����
     */
    function c_index() {
        // �鵵��Ϣ�޸�Ȩ��
        $otherdatasDao = new model_common_otherdatas();
        $limit = $otherdatasDao->getUserPriv("contract_contract_contract", $_SESSION ['USER_ID']);
        $archivedInfoModifyLimit = isset($limit['�鵵��Ϣ�޸�Ȩ��'])? $limit['�鵵��Ϣ�޸�Ȩ��'] :'';
        $restartContractLimit = isset($limit['�򿪺�ͬȨ��'])? $limit['�򿪺�ͬȨ��'] : '';
        $this->assign('archivedInfoModifyLimit', $archivedInfoModifyLimit);
        $this->assign('restartContractLimit', $restartContractLimit);

        $this->c_page ();
    }

    /***********************************��ͬ�б�\ҳ��**************************************************************/

    /**
     * ����鿴0ҳ��
     */
    //�鿴�ͻ���ͬ��
    function c_toMaintenance()
    {
        if (!empty ($_GET['lastAdd'])) {
            $this->assign('lastAdd', $_GET['lastAdd']);
        } else {
            $this->assign('lastAdd', '');
        }
        if (!empty ($_GET['lastChange'])) {
            $this->assign('lastChange', $_GET['lastChange']);
        } else {
            $this->assign('lastChange', '');
        }
        $this->view('maintenance-list');
    }

    //�鿴Tab
    function c_showViewTab()
    {
        $this->assign('id', $_GET['id']);
        $isTemp = $this->service->isTemp($_GET['id']);
        $rows = $this->service->get_d($_GET['id']);
        $this->assign('contractCode', $rows['contractCode']);
        $this->assign('originalId', $rows['originalId']);
        $this->view('showView-tab');
    }

    //�����鿴ҳ
    function c_showView()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assign('invoiceLimitR', "1");
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        //��ȡ�¿�Ʊ����
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $invoiceCodeArr = explode(",", $obj['invoiceCode']);
        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $ExtInfo = $this->service->getContractExtFields($_GET['id']);
        $invoiceValuesArr = isset($ExtInfo['invoiceValues'])? util_jsonUtil::decode($ExtInfo['invoiceValues']) : array();
        $i = 0;
        $invoiceType = "";
        $typeArr = isset($typeArr['KPLX'])? $typeArr['KPLX'] : array();
        array_unshift($invoiceValueArr,'');
        foreach ($typeArr as $k => $v) {
            $dataCodeArr[] = $v['dataCode'];
            if (in_array($v['dataCode'], $invoiceCodeArr) || ($invoiceValuesArr && isset($invoiceValuesArr[$v['dataCode']]))) {
                $invoiceVal = isset($invoiceValuesArr[$v['dataCode']])? $invoiceValuesArr[$v['dataCode']] : $invoiceValueArr[$i];
                $invoiceType .= <<<EOT
						<input type="hidden" id="$v[dataCode]V" value="1"/>
					    &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
	                    <span id="$v[dataCode]Hide" style="display:none"> : <span id="$v[dataCode]Money" class="rimless_text formatMoney" >{$invoiceVal}</span></span>
EOT;
            } else {
                $invoiceType .= <<<EOT
						<input type="hidden" id="$v[dataCode]V" value="0"/>
					    &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
	                    <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" readonly="readonly" value="{$invoiceValueArr[$i]}" class="rimless_text formatMoney" /></span>
EOT;
            }
            $i++;
        }
        //��ȡ��ͬ�����ˣ�����ͨ�����������/Ĭ�ϻ��е�һ�������ˣ�
        $appArr = explode(",", $obj['appNameStr']);
        //Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˡ������ˣ��������ֶ�Ȩ�޹���
        if (!in_array($_SESSION['USER_ID'], $appArr) && $obj['areaPrincipalId'] != $_SESSION['USER_ID'] && $obj['createId'] != $_SESSION['USER_ID'] && $obj['prinvipalId'] != $_SESSION['USER_ID'] && $actType != 'audit') {
            $obj = $this->service->filterWithoutField('��ͬ���', $obj, 'keyForm', array(
                'contractMoney',
                'contractTempMoney',
                'exgross',
                'costEstimates',
                'costEstimatesTax'
            ));
            //��Ʊ����
            if (isset($this->service->this_limit['������']) && !empty($this->service->this_limit['������'])) {
                $this->assign('invoiceLimitR', "1");
            } else {
                $this->assign('invoiceLimitR', "0");
            }
            //��ͬ�ı�Ȩ��(2012-10-10л���������ͬ�ı�Ȩ�޲�����ͬ��ȡ��ԭ��ͬ�ı�Ȩ��)
            if (isset($this->service->this_limit['��ͬ���']) && !empty($this->service->this_limit['��ͬ���'])) {
                $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
                if ($file2 == "�����κθ���" && !empty($obj['originalId'])) {
                    $file2 = $this->service->getFilesByObjId($obj['originalId'], false, 'oa_contract_contract2');
                }
                $this->assign('file2', $file2);
            } else {
                $this->assign('file2', '��û�����Ȩ�ޡ�');
            }
        } else {
            $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
            if ($file2 == "�����κθ���" && !empty($obj['originalId'])) {
                $file2 = $this->service->getFilesByObjId($obj['originalId'], false, 'oa_contract_contract2');
            }
            $this->assign('file2', $file2);
        }
        // �ɱ�����ǧ��λ��������״̬��ǰ��js��ʽ�������NaN�����
        if ($obj['costEstimatesTax'] != '******') {
            $obj['costEstimatesTax'] = number_format($obj['costEstimatesTax'], 2);
        }
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //�����¼
        if (!empty($obj['originalId'])) {
            $cid = $obj['originalId'];
        } else {
            $cid = $obj['id'];
        }
        $changeReason = $this->service->getChangeReasonById($cid);
        $this->assign('changeReason', $changeReason);
        if ($obj['sign'] == 1) {
            $this->assign('sign', '��');
        } else {
            $this->assign('sign', '��');
        }
        if ($obj['shipCondition'] == 0) {
            $this->assign('shipCondition', '��������');
        } else {
            $this->assign('shipCondition', '֪ͨ����');
        }
        //����
        $file = $this->service->getFilesByObjId($obj['id'], false);
        if ($file == "�����κθ���" && !empty($obj['originalId'])) {
            $file = $this->service->getFilesByObjId($obj['originalId'], false);
        }
        $this->assign('file', $file);


        $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
        $this->assign('contractNature', $this->getDataNameByCode($obj['contractNature']));
        $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
        $this->assign('invoiceType', $this->getDataNameByCode($obj['invoiceType']));
//        $this->assign('signSubject', $this->getDataNameByCode($obj['signSubject']));

        //�Ƿ�
        $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
        $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
        $this->assign("exgrossval", EXGROSS);

        $regionDao = new model_system_region_region();
        $areaPrincipal = $regionDao->find(array("id" => $obj['areaCode']), null, "areaPrincipal");
        $this->assign('AreaLeaderNow', $areaPrincipal['areaPrincipal']);

        //��ǰ�̻�����
        $chanceCost = $this->service->getChanceCostByid($obj['originalId']);
        $this->assign("chanceCost", $chanceCost);

        //�¿�Ʊ����
        $dataCode = implode(",", $dataCodeArr);
        $this->assign("invoiceType", $invoiceType);
        $this->assign("dataCode", $dataCode);
        $this->view('showView');
    }

    //���ǰ�鿴ҳ
    function c_showViewOld()
    {
        $conId = $_GET['id'];
        $obj = $this->service->getLastContractInfo_d($conId);
        if (empty ($obj)) {
            echo '<span>�ޱ����Ϣ</span>';
        } else {

            foreach ($obj as $key => $val) {
                $this->assign($key, $val);
            }
            if ($obj['sign'] == 1) {
                $this->assign('sign', '��');
            } else {
                $this->assign('sign', '��');
            }
            if ($obj['shipCondition'] == 0) {
                $this->assign('shipCondition', '��������');
            } else {
                $this->assign('shipCondition', '֪ͨ����');
            }
            //����
            $file = $this->service->getFilesByObjId($obj['id'], false);
            if ($file == "�����κθ���" && !empty($obj['originalId'])) {
                $file = $this->service->getFilesByObjId($obj['originalId'], false);
            }
            $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
            if ($file2 == "�����κθ���" && !empty($obj['originalId'])) {
                $file2 = $this->service->getFilesByObjId($obj['originalId'], false, 'oa_contract_contract2');
            }
            $this->assign('file', $file);
            $this->assign('file2', $file2);

            $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
            $this->assign('contractNature', $this->getDataNameByCode($obj['contractNature']));
            $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
            $this->assign('invoiceType', $this->getDataNameByCode($obj['invoiceType']));
//            $this->assign('signSubject', $this->getDataNameByCode($obj['signSubject']));
            //�¿�Ʊ����
            //��ȡ�¿�Ʊ����
            $dataDao = new model_system_datadict_datadict();
            $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
            $invoiceCodeArr = explode(",", $obj['invoiceCode']);
            $invoiceValueArr = explode(",", $obj['invoiceValue']);
            $i = 0;
            $invoiceType = '';
            foreach ($typeArr as $val) {
                foreach ($val as $v) {
                    $dataCodeArr[] = $v['dataCode'];
                    if (in_array($v[dataCode], $invoiceCodeArr)) {
                        $invoiceType .= <<<EOT
						<input type="hidden" id="$v[dataCode]V" value="1"/>
					    &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
	                    <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="serviceInvMoney" readonly="readonly" value="{$invoiceValueArr[$i]}" class="rimless_text formatMoney" /></span>
EOT;
                    } else {
                        $invoiceType .= <<<EOT
						<input type="hidden" id="$v[dataCode]V" value="0"/>
					    &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
	                    <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="serviceInvMoney" readonly="readonly" value="{$invoiceValueArr[$i]}" class="rimless_text formatMoney" /></span>
EOT;
                    }
                    $i++;
                }
            }
            $this->assign("invoiceType", $invoiceType);
            $dataCode = implode(",", $dataCodeArr);
            $this->assign("dataCode", $dataCode);
            //�Ƿ�
            $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
            $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
            $this->view('showViewOld');
        }

    }

    //�����ϸ�鿴ҳ
    function c_toshowChangeView()
    {
        $goodsId = $_GET['id'];
        $contractId = $_GET['contractId'];
        $this->assign('goodsId', $goodsId);
        $this->assign('contractId', $contractId);
        $this->assign('isTemp', $_GET['isTemp']);
        $this->view('showchangeview');
    }

    /*
	 * ��ת����ͬ�����б�
	 */
    function c_page()
    {
        if (!empty ($_GET['lastAdd'])) {
            $this->assign('lastAdd', $_GET['lastAdd']);
        } else {
            $this->assign('lastAdd', '');
        }
        if (!empty ($_GET['lastChange'])) {
            $this->assign('lastChange', $_GET['lastChange']);
        } else {
            $this->assign('lastChange', '');
        }

        $assLimit = isset($this->service->this_limit['��������']) ? $this->service->this_limit['��������'] : '';
        isset($_GET['autoload']) ? $this->assign('autoload', $_GET['autoload']) : $this->assign('autoload', ''); # �Զ�����
        $this->assign('assLimit', $assLimit);

        $this->view('list');
    }

    /**
     * ����ȷ�� ������㷽ʽ�б�
     */
    function c_incomeAccountingList()
    {

        $this->view('incomeAccountingList');
    }

    /**
     * ��ת��������ͬ����ҳ��
     */
    function c_toAdd()
    {
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");

        // �����е����ƿ�Ʊ���� PMS 647
        $otherDatasDao = new model_common_otherdatas();
        $limitInvoiceType = $otherDatasDao->getConfig('limitInvoiceTypeForContract', null, 'arr');

        $invoiceType = '';
        $dataCodeArr = array();
        foreach ($typeArr as $val) {
            foreach ($val as $v) {
                $dataCodeArr[] = $v['dataCode'];
                $disabledStr = (in_array($v['dataCode'],$limitInvoiceType))? "disabled" : "";
                $disabledExtStr = (in_array($v['dataCode'],$limitInvoiceType))? "data-isDisable='1'" : "data-isDisable=''";
                if ($v['dataCode'] == 'HTBKP') {
                    $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                        <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                } else {
                    $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>
                        <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                        <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();" /></span>
EOT;
                }
            }
        }
        $dataCode = implode(',', $dataCodeArr);
        //������ת��������id
        $ids = isset ($_GET['ids']) ? $_GET['ids'] : null;
        $this->assign('dataCode', $dataCode);
        $this->assign('ids', $ids);
        $this->assign('createTime', date('Y-m-d'));
        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('contractSigner', $_SESSION['USERNAME']);
        $this->assign('contractSignerId', $_SESSION['USER_ID']);
        $this->assign('prinvipalName', $_SESSION['USERNAME']);
        $this->assign('prinvipalId', $_SESSION['USER_ID']);
        $this->assign('prinvipalDept', $_SESSION['DEPT_NAME']); //û�к����ڼ�
        $this->assign('prinvipalDeptId', $_SESSION['DEPT_ID']);

        //��ȡ��˾����
        $branchDao = new model_deptuser_branch_branch();
        $companyInfo = $branchDao->getByCode($_SESSION['Company']);
        $this->assign('businessBelong', $_SESSION['Company']);
        $this->assign('businessBelongName', $companyInfo['NameCN']);

        $this->assign('signSubject', $_SESSION['Company']);
        $this->assign('signSubjectName', $companyInfo['NameCN']);

        $this->assign('formBelong', $_SESSION['COM_BRN_PT']);
        $this->assign('formBelongName', $_SESSION['COM_BRN_CN']);
        //���ø�������
        //		$stampConfigDao = new model_system_stamp_stampconfig();
        //		$stampArr = $stampConfigDao->getStampType_d();
        //		$this->showSelectOption ( 'stampType', null , true , $stampArr);//��������
        //��ͬ����Ƿ��ֹ�����
        //��Ʊ����
        $this->assign('invoiceType', $invoiceType);
        $this->assign('contractInput', ORDER_INPUT);
        //�����ݲ��������⴦��
        if (dsjAreaId) {
            $regionDao = new model_system_region_region();
            $rs = $regionDao->find(array('id' => dsjAreaId, 'isStart' => '0'), null, 'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
            //��ǰ��¼��Ϊ�����ݲ�����������Ա�ģ�Ҫ�����⴦��
            if ($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))) {
                $areaCode = dsjAreaId;
                $areaName = $rs['areaName'];
                $areaPrincipalId = $rs['areaPrincipalId'];
                $areaPrincipal = $rs['areaPrincipal'];
                //ִ����������д��
                $exeDeptCode = 'GCSCX-17';
                $exeDeptName = '�����ݲ�';
            }
        }
        $this->assign('areaCode', isset($areaCode) ? $areaCode : '');
        $this->assign('areaName', isset($areaName) ? $areaName : '');
        $this->assign('areaPrincipalId', isset($areaPrincipalId) ? $areaPrincipalId : '');
        $this->assign('areaPrincipal', isset($areaPrincipal) ? $areaPrincipal : '');
        $this->assign('exeDeptCode', isset($exeDeptCode) ? $exeDeptCode : '');
        $this->assign('exeDeptName', isset($exeDeptName) ? $exeDeptName : '');

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);

        $this->view('add', true);
    }

    /**
     *  �������� �������������ϴ���������£�
     */
    function c_handleDispose()
    {
        $handleType = $_GET['handleType'];
        if ($handleType == "FJSC") {
            $this->assign("handle", "1");
            $this->c_toUploadFile();
        } else if ($handleType == "GZSQ") {
            $this->assign("handle", "1");
            $this->c_toStamp();
        } else if ($handleType == "YSWJ") {
            $this->assign("handle", "1");
            $this->c_toCheckFile();
        }
    }

    /**
     * ��ת���༭��ͬ����ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        $this->view('edit');
    }

    /**
     * ��ת���༭�ͻ���ͬ��ҳ��
     */
    function c_toMaintenanceEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('maintenance-edit');
    }

    /**
     * �޸Ŀͻ���ͬ��
     */
    function c_editMaintenance($isEditInfo = false)
    {
        //		$this->permCheck (); //��ȫУ��
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object, $isEditInfo)) {
            msg('�༭�ɹ���');
        }
    }

    /**
     * �޸Ķ���
     */
    function c_edit($isEditInfo = false)
    {
        //		$this->permCheck (); //��ȫУ��
        $this->checkSubmit();
        $rows = $_POST[$this->objName];
        // �������ϵ������Ϣ
        foreach ($rows['material'] as $k => $v){
            if($v['isDel'] != 1){
                $rows['material'][$k]['isDel'] = 0;
            }
            unset($rows['material'][$k]['id']);
        }

        // ���Ͽ�Ʊ������Ϣ��Json���� PMS 647
        $invoiceJsonData = "";
        if(!empty($rows['invoiceCode']) && !empty($rows['invoiceValue'])){
            $catchArr = array();
            $i = 0;
            if(in_array('HTBKP',$rows['invoiceCode'])){
                $catchArr['HTBKP'] = "";
                $i = 1;
            }
            foreach ($rows['invoiceValue'] as $k => $v){
                if($v !== '' && ($v >= 0 || $v <= 0)){
                    $catchArr[$rows['invoiceCode'][$i]] = $v;
                    $i++;
                }
            }
            $invoiceJsonData = util_jsonUtil::encode($catchArr);
        }

        //������ת���۹����̻�����
        if (isset($_GET['turnChanceIds'])) {
            $rows['turnChanceIds'] = $_GET['turnChanceIds'];
        }
        $act = isset($_GET['act']) ? $_GET['act'] : "";
        // ��֤��ͬ��Ϣ
        if ($checkResult = $this->service->checkContractMoney_d($rows)) {
            $act = '';
        }

        $id = $this->service->edit_d($rows, $act);
        if($id){
            $this->service->invoiceTypeRecord($rows['id'],$invoiceJsonData);
        }
        if ($checkResult) {
            msg($checkResult);
        }

        if ($id && $act == "app") {
            if (!empty($rows['chanceId'])) {
                $chanceDao = new model_projectmanagent_chance_chance();
                $chanceDao->updateChanceNewDate($rows['chanceId'], $id);
            }
            //���ⲿ���ύ�ĺ�ֱͬ���ύ����
            if ($_SESSION['DEPT_ID'] == hwDeptId) {
                succ_show('controller/contract/contract/ewf_index_hw_list.php?actTo=ewfSelect&billId=' . $id);
            }
            if ($id == "confirm") {
                msg("��ͬ���ύȷ�ϳɱ�����");
            } else {
                //��ȡ��������id ��
                $deptIds = $this->service->getDeptIds($rows);
                $configDeptIds = contractFlowDeptIds; //config�ڶ���� ����ID
                if (!empty($deptIds)) {
                    $deptIdStr = $configDeptIds . "," . $deptIds;
                } else {
                    $deptIdStr = $configDeptIds;
                }
                $deptIdStrArr = explode(",", $deptIdStr);
                $deptIdStrArr = array_unique($deptIdStrArr);
                $deptIdStr = implode(",", $deptIdStrArr);
                if ($rows['winRate'] == "50%") {
                    succ_show('controller/contract/contract/ewf_index_50.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                } else {
                    succ_show('controller/contract/contract/ewf_index_Other.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                }
            }
        } else {
            if ($id) {
                //				$msg=$this->getLangByKey("addSuccess");
                if (!empty($rows['chanceId'])) {
                    $chanceDao = new model_projectmanagent_chance_chance();
                    $chanceDao->updateChanceNewDate($rows['chanceId'], '');
                }
                msg("�༭�ɹ�");
            } else {
                msg('�༭ʧ��');
            }
        }
    }

    /**
     * �޸Ķ���
     * ���ṩ����ʹ��
     */
    function c_hwedit($isEditInfo = false)
    {
        //		$this->permCheck (); //��ȫУ��
        $object = $_POST[$this->objName];
        if ($this->service->hwedit_d($object, $isEditInfo)) {
            msg('�༭�ɹ���');
        }
    }

    /**
     * �޸Ķ���
     */
    function c_editstar($isEditInfo = false)
    {
        //		$this->permCheck (); //��ȫУ��
        $_POST[$this->objName]['id'] = $_GET['id'];
        $_POST[$this->objName]['sign'] = $_REQUEST['value'];
        $object = $_POST[$this->objName];
        if ($this->service->updateById($object)) {
            msg('�༭�ɹ���');
        }
    }

    /**
     * ��ת���鿴��ͬ����ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //��ǰ�̻�����
        $chanceCost = $this->service->getChanceCostByid($_GET['id']);
        $this->assign("chanceCost", $chanceCost);

        $this->assign("exgrossval", EXGROSS);
        $this->view('view');
    }

    /**
     * ����ҳ���õ��Ĳ鿴
     */
    function c_toViewApp()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['sign'] == 1) {
            $this->assign('sign', '��');
        } else {
            $this->assign('sign', '��');
        }
        if ($obj['shipCondition'] == 0) {
            $this->assign('shipCondition', '��������');
        } else {
            $this->assign('shipCondition', '֪ͨ����');
        }
        //����
        $file = $this->service->getFilesByObjId($obj['id'], false);
        $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
        $this->assign('file', $file);
        $this->assign('file2', $file2);

        $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
        $this->assign('contractNature', $this->getDataNameByCode($obj['contractNature']));
        $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
        $this->assign('invoiceType', $this->getDataNameByCode($obj['invoiceType']));
        $this->view('view-app');
    }

    /**
     * �����Ʒ�鿴�����嵥
     */
    function c_toViewEqu()
    {
        $this->assign('conProductId', $_GET['id']);
        $this->assign('contractId', $_GET['contractId']);
        $this->view('view-equ');
    }

    /**
     *  �ҵĺ�ͬ
     */
    function c_myContract()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('todo', isset($_GET['todo']) ? 1 : 0); // �����ʶ
        $this->view('mycontract');
    }

    /**
     * ���˺�ͬ�б�--HR
     */
    function c_contractByuser()
    {
        $this->assign('userId', $_GET['USER_ID']);
        $this->view('contractbyuserlist');
    }

    /**
     *  ȷ�Ϻ�ͬ�ɱ����㣨����
     */
    function c_confirmCostEstimates()
    {
        $this->view('confirmCostEstimates');
    }

    /**
     *  ȷ�Ϻ�ͬ�ɱ����㣨�з���
     */
    function c_confirmCostRdpro()
    {
        $this->view('confirmCostRdpro');
    }

    /**
     * �ɱ�����ȷ��ҳ��
     */
    function c_confirmCostView()
    {

        $type = "oa_contract_contract";
        $this->assign("serviceId", $_GET['id']);
        $this->assign("serviceType", $type);
        $text = $type . "2";
        $this->assign("serviceType2", $text);
        $obj = $this->service->get_d($_GET['id']);
        //������Ⱦ
        $this->assignFunc($obj);
        $this->assign("engConfirmName", $_SESSION['USERNAME']);
        $this->assign("engConfirmId", $_SESSION['USER_ID']);
        $this->assign("engConfirmDate", date("Y-m-d"));
        $type = $_GET['type'];
        $this->assign("type", $type);
        if ($type == "Ser") {
            $this->assign("costMoney", $obj['serCost']);
        } else if ($type == "Rd") {
            $this->assign("costMoney", $obj['rdCost']);
        }
        $costType = $obj['isSubAppChange'];
        if ($costType == '1') {
            $this->assign("costType", "��ͬ���");
            $mid = $this->service->findChangeId($_GET['id']);
            $conId = $mid;
        } else {
            $this->assign("costType", "��ͬ����");
            $conId = $_GET['id'];
        }
        $this->assign("contractId", $conId);

        //�ɱ�ȷ�ϲ�Ʒ��html
        $costLimit = $this->service->this_limit['�ɱ�ȷ��'];
        $ruArr = $this->service->costinfoView($costLimit, $conId);
        $this->assign("costInfo", $ruArr['str']);
        $this->assign("costAppRemark", $ruArr['remark']);

        $this->view('confirmCostView');
    }

    /**
     * �ɱ�����ȷ��ҳ��
     */
    function c_confirmCostApp()
    {
        $obj = $this->service->get_d($_GET['contractId']);

        //������Ⱦ
        $this->assignFunc($obj);
        $this->assign("engConfirmName", $_SESSION['USERNAME']);
        $this->assign("engConfirmId", $_SESSION['USER_ID']);
        $this->assign("engConfirmDate", date("Y-m-d"));
        $type = $_GET['type'];
        $this->assign("type", $type);
        if ($type == "Ser") {
            $this->assign("costMoney", $obj['serCost']);
        } else if ($type == "Rd") {
            $this->assign("costMoney", $obj['rdCost']);
        }
        $costType = $obj['isSubAppChange'];
        if ($costType == '1') {
            $this->assign("costType", "��ͬ���");
            $mid = $this->service->findChangeId($_GET['contractId']);
            $this->assign("contractId", $mid);
        } else {
            $this->assign("costType", "��ͬ����");
            $this->assign("contractId", $_GET['contractId']);
        }
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->get_d($_GET['id']);

        //�ж��Ƿ���� ͬһ���ߣ���ͬ���Ͳ�Ʒ
        $isDeff = $this->service->deffLinePro($costArr, $_GET['contractId']);
        $this->assign("isdeff", $isDeff);
        //Ԥ��ë����
        $cMoney = $obj['contractMoney'];
        if ($isDeff == '2') {
            $cid = $_GET['contractId'];
            $productline = $costArr['productLine'];
            $sql = "select sum(confirmMoney) as allMoney,sum(if(issale='0',confirmMoney,0)) as serMoney,sum(if(issale='1',confirmMoney,0)) as saleMoney from oa_contract_cost where contractId = '" . $cid . "' and productLine = '" . $productline . "'";
            $allMoney = $this->service->_db->getArray($sql);
            $costArr['confirmMoney'] = $allMoney[0]['allMoney'];
            $costArr['serMoney'] = $allMoney[0]['serMoney'];
            $costArr['saleMoney'] = $allMoney[0]['saleMoney'];
        }
        $bMoney = $costArr['confirmMoney'];
        if ($obj['contractType'] == "HTLX-ZLHT") {
            $days = abs($this->service->getChaBetweenTwoDate($obj['beginDate'], $obj['endDate'])); //��������
            $saleCostTemp = $bMoney / 720;
            $costEstimates = bcmul($days, $saleCostTemp, 2);
            $exGrossTemp = bcdiv(($cMoney - $costEstimates), $cMoney, 4);
            $exGross = bcmul($exGrossTemp, '100', 2);
        } else {
            $exGrossTemp = bcdiv(($cMoney - $bMoney), $cMoney, 4);
            $exGross = bcmul($exGrossTemp, '100', 2);
        }
        $this->assignFunc($costArr);
        $this->assign("exgross", $exGross);
        //��ϸ����
        $equDao = new model_contract_contract_equ();
        $equlist = $equDao->exeEqulistCost($_GET['contractId'], $costType);
        $this->assign('equList', $equlist);
        $this->assign('productLine', $costArr['productLine']);

        $this->view('confirmCostApp');
    }

    /**
     * �ύ�ɱ�����ȷ��
     */
    function c_subConfirmCost()
    {
        $arr = $_POST[$this->objName];
        $contractId = $arr['contractId'];
        //��ȡ��ͬ��Ϣ
        $rows = $this->service->getContractInfo($contractId);
        //������
        $this->service->uploadfile_d($arr);
        if ($arr['costType'] == '��ͬ����') {
            $type = "add";
        } else if ($arr['costType'] == '��ͬ���') {
            $type = $rows['originalId'];
        }
        //���������ͬ�ɱ�ȷ����ϸ
        $this->service->handleCostInfo($arr, $type);
        //����ȷ����Ϣ-������Ԥ��ë����
        $exGross = $this->service->handleSubConfirmCoseNew($contractId);
        if ($exGross === 'none') {
            $this->c_updateEngCostExaState($contractId);
            $this->c_updateEngConfirm($contractId);
            msg("ȷ�ϳɹ�����ȴ�����ִ������ɱ�ȷ�ϣ�");
        } else {
            // ���ۺ�ͬ��Ʒ���������Ǳ����߽����ĺ�ͬë���ʴ���70%������ɱ�������ֱ��������� PMS2373 2017-01-09
            $infoArr['exGross'] = $exGross;
//            $noAudit = $this->noAuditChk($contractId,$infoArr);
            $noAudit = false;
            if($noAudit){
                // �������ȷ��
                $this->c_updateEngCostExaState($contractId);
                $this->c_updateEngConfirm($contractId);

                $contractObj = $this->service->getContractInfo($contractId);
                if($contractObj['exgross'] > 70){// ���¼��һ��,���������ָ�������ʧ�ܷ��ش����ë����,�����жϴ���ֱ��ͨ������
                    if($contractObj['isSubAppChange'] == 0){// ��¼��,�Ǳ��
                        // �Զ�ͨ������,�����������Ϣ
                        $dateObj = array(
                            'id' => $contractId,
                            'standardDate' => $contractObj['standardDate'],
                            'dealStatus' => '1',
                            'ExaStatus' => '���',
                            'ExaDTOne' => date("Y-m-d"),
                            'state' => '2',
                            'isSubAppChange' => 0
                        );
                        $this->service->updateById($dateObj);
                        $this->service->dealAfterAudit_d($contractId);// �ڸ����б������Ϣ
                        $this->service->confirmContractWithoutAudit_d($contractId);// ȷ�Ϻ�ͬ
                        msg("�˺�ͬ�����������ύ�ɹ���");
                    }else{// �����ͬ
                        $this->service->updateById(array('id' => $contractId, 'ExaStatus' => '���')); // ��ʱ��¼��������ͨ��
                        $this->service->confirmChangeNoAudit($contractId,1);
                        msg("�˺�ͬ�����������ύ�ɹ���");
                    }
                }else{
                    $this->c_subConfirmCostAppNew($contractId, $exGross);
                }
            }else{
                $this->c_subConfirmCostAppNew($contractId, $exGross);
            }
        }
//         msg("ȷ�ϳɹ������ύ��ִ�в��ž�����ˣ�");
    }

    /**
     * �ɱ�ȷ���쵼���
     */
    function c_subConfirmCostApp()
    {
        $act = isset($_GET['act']) ? $_GET['act'] : "app";
        $arr = $_POST[$this->objName];
        $contractId = $arr['contractId'];

        //��ȡ��ͬ��Ϣ
        $rows = $this->service->getContractInfo($contractId);

        if ($arr['costType'] == '��ͬ����') {
            $type = "add";
        } else if ($arr['costType'] == '��ͬ���') {
            $type = $rows['originalId'];
        }

        $this->service->handleCostApp($act, $arr, $type, $rows);

        //����ȷ����Ϣ-������Ԥ��ë����
        $exGross = $this->service->handleSubConfirmCose($arr, $rows, $type);
        if ($act == 'back') {
            msg("�Ѵ����ִ�в��ųɱ�ȷ�ϣ�");
        } else if ($exGross === 'none') {
            msg("ȷ�ϳɹ�����ȴ�����ִ�в�����ˣ�");
        } else {
            $handleDao = new model_contract_contract_handle();
            //����Ǳ����ȫ��ȷ�Ϻ���º�ͬ��ȷ��״̬
            if ($type != 'add') {
                //������ò�Ʒ�߳ɱ�ȷ��״̬
                $costDao = new model_contract_contract_cost();
                $costDao->returnStateByCid($type, $contractId);
                //���·�����ɱ�ȷ�ϱ�־λ ���
                $this->service->endTheEngTig($rows['originalId']);
                //���������¼
                $handleDao->handleAdd_d(array(
                    "cid" => $rows['originalId'],
                    "stepName" => "�ύ����",
                    "isChange" => 2,
                    "stepInfo" => "",
                ));
            } else {
                $this->service->endTheEngTig($contractId);
                $handleDao->handleAdd_d(array(
                    "cid" => $contractId,
                    "stepName" => "�ύ����",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
            }
            //����������ҵ��״̬���²���
            $isdeff = $arr['isdeff']; //ͬ���Ͳ�Ʒ��ͬ���߱�ʶ
            $productLine = $arr['productLine']; //��Ʒ��
            $costId = $arr['id']; //�����������id

            //�����������ڵı������ݣ�
            $proId = $isdeff . "," . $productLine . "," . $costId;

            if ($arr['costType'] == '��ͬ����') {
                if ($exGross < EXGROSS) {
                    succ_show('controller/contract/contract/ewf_index_50_list.php?actTo=ewfSelect&billId=' . $contractId
                        . '&proId=' . $proId);
                } else {
                    succ_show('controller/contract/contract/ewf_index_Other_list.php?actTo=ewfSelect&billId=' . $contractId
                        . '&proId=' . $proId);
                }
            } else if ($arr['costType'] == '��ͬ���') {
                if ($exGross < EXGROSS) {
                    succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId=' . $contractId
                        . '&proId=' . $proId);
                } else {
                    succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $contractId
                        . '&proId=' . $proId);
                }
            }
        }
    }

    /**
     * �ɱ�ȷ���쵼���(��)
     * @author weijb
     * @Date 2015��10��15�� 11:18:28
     * @description �վ������������ȥ���ɱ�������
     * ���ԭsubConfirmCostApp�����������������ڷ�������ȷ�ϣ������ύ�ɱ�����ȷ�Ϻ�ִ�С�
     */
    function c_subConfirmCostAppNew($contractId, $exGross)
    {
        // ��ȡ��ͬ��Ϣ
        $rows = $this->service->getContractInfo($contractId);

        if ($rows['isSubAppChange'] == '1') {
            $type = $rows['originalId'];
        } else {
            $type = "add";
        }

        $noNeedAudit = false;
        if($rows['contractType'] == 'HTLX-PJGH' && $exGross >= 50 ){// �������ͬ����ë���ʴ��ڵ���50ʱ,�������� PMS 594
            $noNeedAudit = true;
        }

        //����ȷ����Ϣ-������Ԥ��ë����
//     	$exGross = $this->service->handleSubConfirmCoseNew($contractId);
//     	if ($exGross == 'none') {
//             msg("ȷ�ϳɹ�����ȴ�����ִ������ɱ�ȷ�ϣ�");
//         } else {
// 	        $handleDao = new model_contract_contract_handle();
// 	    	//����Ǳ����ȫ��ȷ�Ϻ���º�ͬ��ȷ��״̬
// 	    	if ($type != 'add') {
// 	    		//������ò�Ʒ�߳ɱ�ȷ��״̬
// 	    		$costDao = new model_contract_contract_cost();
// 	    		$costDao->returnStateByCid($type, $contractId);
// 	    		//���·�����ɱ�ȷ�ϱ�־λ ���
// 	    		$this->service->endTheEngTig($rows['originalId']);
// 	    		//���������¼
// 	    		$handleDao->handleAdd_d(array(
// 	    				"cid"=> $rows['originalId'],
// 	    				"stepName"=> "�ύ����",
// 	    				"isChange"=> 2,
// 	    				"stepInfo"=> "",
// 	    		));
// 	    	} else {
// 	    		$this->service->endTheEngTig($contractId);
// 	    		$handleDao->handleAdd_d(array(
// 	    				"cid"=> $contractId,
// 	    				"stepName"=> "�ύ����",
// 	    				"isChange"=> 0,
// 	    				"stepInfo"=> "",
// 	    		));
// 	    	}

        if ($type != 'add') {
            if($noNeedAudit){
                $this->service->updateById(array('id' => $contractId, 'ExaStatus' => '���')); // ��ʱ��¼��������ͨ��
                $this->service->confirmChangeNoAudit($contractId,1);
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1'
                );
                $this->service->updateById($dateObj);
                msg("�˱����������������ɹ���");
            }else if($rows['contractType'] == 'HTLX-PJGH'){
                succ_show('controller/contract/contract/ewf_index_change_pjht.php?actTo=ewfSelect&billId=' . $contractId);
            }else if ($exGross < EXGROSS) {
                succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId=' . $contractId);
            } else {
                succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $contractId);
            }
        } else {
            if($noNeedAudit){
                // ����ȷ������
                $this->service->confirmEqu_d($rows['id'], "add", $rows['isSubAppChange']);
                $this->c_updateSaleCostExaState($contractId);//���¸������״̬

                // �Զ�ͨ������,�����������Ϣ
                $dateObj = array(
                    'id' => $contractId,
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1',
                    'ExaStatus' => '���',
                    'ExaDTOne' => date("Y-m-d"),
                    'state' => '2',
                    'isSubAppChange' => 0

                );
                $this->service->updateById($dateObj);
                $this->service->dealAfterAudit_d($contractId);// �ڸ����б������Ϣ

                $this->service->confirmContractWithoutAudit_d($contractId);// ȷ�Ϻ�ͬ
                msg("�˺�ͬ�����������ύ�ɹ���");
            }else if($rows['contractType'] == 'HTLX-PJGH'){
                succ_show('controller/contract/contract/ewf_index_pjht.php?actTo=ewfSelect&billId=' . $contractId);
            }else if ($exGross < EXGROSS) {
                succ_show('controller/contract/contract/ewf_index_50_list.php?actTo=ewfSelect&billId=' . $contractId);
            } else {
                succ_show('controller/contract/contract/ewf_index_Other_list.php?actTo=ewfSelect&billId=' . $contractId);
            }
        }
//         }
    }

    /**
     * ����������ɱ��������״̬
     */
    function c_updateSaleCostExaState($contractId)
    {
        $costDao = new model_contract_contract_cost();
        $costDao->update(array('contractId' => $contractId, 'issale' => 1), array('ExaState' => 1));
    }

    /**
     * ���·�����ɱ��������״̬
     */
    function c_updateEngCostExaState($contractId)
    {
        $costDao = new model_contract_contract_cost();
        $costDao->update(array('contractId' => $contractId, 'issale' => 0), array('ExaState' => 1));
    }

    /**
     * ֪ͨ�����б�
     */
    function c_shipCondition()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->view('shipcondition');
    }

    /**
     * ��ͬ�鿴ҳ��
     */
    function c_toViewTab()
    {
        //		$this->permCheck (); //��ȫУ��
        $this->assign('id', $_GET['id']);
        $isTemp = $this->service->isTemp($_GET['id']);
        $rows = $this->service->get_d($_GET['id']);
        $this->assign('contractCode', $rows['contractCode']);
        $this->assign('originalId', $rows['originalId']);
        $this->assign('contractType', $rows['contractType']);

        $this->display('viewTab');
        //		$this->display('view-tab');
    }

    /**
     * ��ͬ�鿴Tab---�ر���Ϣ
     */
    function c_toCloseInfo()
    {
        $rows = $this->service->get_d($_GET['id']);
        if ($rows['state'] == '3' || $rows['state'] == '7') {
            foreach ($rows as $key => $val) {
                $this->assign($key, $val);
            }
            $this->display('closeinfo');
        } else {
            echo '<span>���������Ϣ</span>';
        }
    }

    /*********************���������б���*********************************/
    /**
     * ���������б�ҳ
     */
    function c_otherList()
    {
        $this->view("otherList");
    }


    /*********************���������б���********END*************************/
    /**********************��Ʊ�տ�Ȩ�޲���*********************************/
    /**
     * ��Ʊtab
     */
    function c_toInvoiceTab()
    {
        $obj = $_GET['obj'];
        if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['��Ʊ��Ϣ'])) {
            $url = '?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
            succ_show($url);
        } else {
            echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
        }
    }

    /**
     * ��Ʊ����tab
     */
    function c_toInvoiceApplyTab()
    {
        $obj = $_GET['obj'];
        if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['��Ʊ��Ϣ'])) {
            $url = '?model=finance_invoiceapply_invoiceapply&action=getInvoiceapplyList&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
            succ_show($url);
        } else {
            echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
        }
    }

    /**
     * ����tab
     */
    function c_toIncomeTab()
    {
        $obj = $_GET['obj'];
        if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['������Ϣ'])) {
            $url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
            succ_show($url);
        } else {
            echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
        }
    }

    /**********************��Ʊ�տ�Ȩ�޲���*********************************/

    /********************** S ������Ŀ���� *********************************/
    /**
     * ��Ŀ��ͬ�б�
     * 2012-04-09
     * edit by kuangzw
     */
    function c_listForEngineering()
    {
        $this->view('listforEngineering');
    }

    /**
     * ��Ŀ��ͬ�б�pagejson
     */
    function c_esmContractJson()
    {
        $service = $this->service;
        $rows = array();
        $goodsLimitStr = "";

        //ʡ��Ȩ������
        $provinceArr = array();

        //���Ȼ�ȡ��Ӧ�İ��´�Ȩ��id
        $otherDataDao = new model_common_otherdatas();
        $limitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
        $productLineLimit = $limitArr['��Ʒ��'];
        $exeDeptLimit = $limitArr['ִ������'];

        $esmProductType = ESMPRODUCTTYPE;
        $esmContract = ESMCONTRACT;
        //��ͬ�������
        if ($esmProductType || $esmContract) {
            $goodsLimitStr = "sql: and (";

            //��Ʒ����
            if ($esmProductType) {
                $goodsArr = explode(',', $esmProductType);
                $goodsLimitStr .= "(";
                foreach ($goodsArr as $k => $v) {
                    if ($k == 0) {
                        $goodsLimitStr .= "FIND_IN_SET($v,c.goodsTypeStr)";
                    } else {
                        $goodsLimitStr .= "or FIND_IN_SET($v,c.goodsTypeStr)";
                    }
                    $k++;
                }
                $goodsLimitStr .= ") ";
            }
            //��ͬ���Ͳ���
            if ($esmContract) {
                $contractArr = explode(',', $esmContract);
                if ($goodsArr) $goodsLimitStr .= " or (";

                //����ʡ��Ȩ��
                $goodsLimitStr .= " c.contractType in ('" . implode($contractArr, "','") . "')";
                if ($goodsArr) $goodsLimitStr .= ")";
            }
            $goodsLimitStr .= ")";
        }
        if (!empty($productLineLimit) && !empty($exeDeptLimit)) {
            if ($goodsLimitStr) {
                if (strstr($productLineLimit, ';;') == false) {
                    $productLineLimitArr = explode(",", $productLineLimit);
                    $prolineLimitStr = " and (";
                    foreach ($productLineLimitArr as $k => $v) {
                        if ($k == 0) {
                            $prolineLimitStr .= "FIND_IN_SET('" . $v . "',newProLineStr)";
                        } else {
                            $prolineLimitStr .= "or FIND_IN_SET('" . $v . "',newProLineStr)";
                        }
                        $k++;
                    }
                    $prolineLimitStr .= ")";
                    $goodsLimitStr .= $prolineLimitStr;
                }
                if (strstr($exeDeptLimit, ';;') == false) {
                    $exeDeptLimitArr = explode(",", $exeDeptLimit);
                    $exeDeptLimitStr = " and (";
                    foreach ($exeDeptLimitArr as $k => $v) {
                        if ($k == 0) {
                            $exeDeptLimitStr .= "FIND_IN_SET('" . $v . "',exeDeptStr)";
                        } else {
                            $exeDeptLimitStr .= "or FIND_IN_SET('" . $v . "',exeDeptStr)";
                        }
                        $k++;
                    }
                    $exeDeptLimitStr .= ")";
                    $goodsLimitStr .= $exeDeptLimitStr;
                }
                $service->getParam($_REQUEST);
                $service->searchArr['mySearchCondition'] = $goodsLimitStr;
                $service->sort = 'c.createTime';
                $rows = $service->page_d();
                if (!empty ($rows)) {
                    //��ȫ��
                    $rows = $this->sconfig->md5Rows($rows);
                    $conProDao = new model_contract_conproject_conproject();
                    foreach ($rows as $k => $v) {
                        $proRate = $conProDao->getSurplusProportionByCid($v['id']);
                        if (!empty($proRate)) {
                            $rows[$k]['projectRate'] = 100 - $proRate;
                        }
                    }
                }
            }
        } else {
            $rows = "";
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /********************** E ������Ŀ���� *********************************/

    /**********************************************************************************************************************/
    /**
     * ���������ϴ�ҳ��
     */
    function c_toUploadFile()
    {
        $this->assign("serviceId", $_GET['id']);
        $this->assign("serviceType", $_GET['type']);
        $text = $_GET['type'] . "2";
        $this->assign("serviceType2", $text);
        $this->display('uploadfile');
    }

    function c_toCheckFile()
    {
        $this->assign("serviceId", $_GET['id']);
        $text = $_GET['type'] . "3";
        $this->assign("serviceType3", $text);
        $this->display('checkfile');
    }

    /**
     * ���������ϴ�����
     */
    function c_uploadfile()
    {
        $row = $_POST[$this->objName];
        if(isset($row['checkFile'])){
            $upSql = "update oa_contract_contract set checkFile = '��' where id= ".$row['serviceId'];
            $this->service->_db->query($upSql);
        }
        $id = $this->service->uploadfile_d($row);
        if ($id && $_GET['handle'] == "1") {
            $dao = new model_contract_contract_aidhandle();
            $dao->add_d(array("contractId" => $row['serviceId'], "handleType" => "FJSC"));
            msg('��ӳɹ���');
        } else {
            if ($id) {
                msg('��ӳɹ���');
            } else {
                msg('���ʧ�ܣ�');
            }
        }
    }

    /**
     *
     *������������ҳ��
     */
    function c_toDownFile()
    {
        //add chenrf 20130417 ��������Ȩ��
        /**********start************/
        $limit = $this->service->this_limit['��������'];
        if ($limit != '1') {
            msg('�޸�������Ȩ��');
            exit;
        }
        /**********end************/

        $this->assign("serviceId", $_GET['id']); //��ͬID
        $this->assign("serviceType", $_GET['type']); //��������
        $this->assign("contractName", $_GET['contractName']); //��ͬ����
        $text = $_GET['type'] . "2";
        $this->assign("serviceType2", $text);
        $file = $this->service->getFilesByObjId($_GET['id'], false);
        $this->assign('file', $file);
        $file2 = $this->service->getFilesByObjId($_GET['id'], false, 'oa_contract_contract2');
        $this->assign('file2', $file2);
        $this->view('downfile');
    }

    /**
     * ���ͬ������������ҳ��
     */
    function c_toDownAllFile()
    {
        $this->assign("ids", $_GET['ids']); //��ͬID
        $this->assign("serviceType", $_GET['type']); //��������
        $this->assign("serviceType2", $_GET['type'] . "2"); //��������
        $this->view('downallfile');
    }

    /**
     * ���ͬ������������
     */
    function c_downAllFile()
    {
        $managementDao = new model_file_uploadfile_management();
        $managementDao->downAllFileByIds($_GET['ids'], $_GET['type'], $_GET['filename']);
    }

    /**
     * ��ͬcombogrid ѡ���ͬҳ��
     */
    function c_selectContract()
    {
        $this->assign('showButton', $_GET['showButton']);
        $this->assign('showcheckbox', $_GET['showcheckbox']);
        $this->assign('checkIds', $_GET['checkIds']);
        $this->view('selectcontract');
    }

    /**********************************end**********************************************************************************/
    /******************************************�������ݷ���********************************************************************************/

    /**********************************end**********************************************************************************/
    /******************************************�������ݷ���********************************************************************************/

    /*
	 * ajax���ݺ�ͬID ��ȡ��Ʒ�ڵĲ�����Ϣ��������������
	 */
    function c_ajaxFlowDeptIds()
    {
        //$this->permDelCheck ();
        try {
            $rows = $this->service->getContractInfo($_POST['id']);
            //��ȡ��������id ��
            $deptIds = $this->service->getDeptIds($rows);
            $configDeptIds = contractFlowDeptIds; //config�ڶ���� ����ID
            if (!empty($deptIds)) {
                $deptIdStr = $configDeptIds . "," . $deptIds;
            } else {
                $deptIdStr = $configDeptIds;
            }
            $deptIdStrArr = explode(",", $deptIdStr);
            $deptIdStrArr = array_unique($deptIdStrArr);
            $deptIdStr = implode(",", $deptIdStrArr);
            echo $deptIdStr;
        } catch (Exception $e) {
            echo "";
        }
    }

    /**
     * �б��ύ���� ����
     */
    function c_ajaxSubApp()
    {
        try {
            $id = $_POST['id'];
            $sql = "update oa_contract_contract set isSubApp = '1',dealStatus='0' where id='" . $id . "'";
            $this->service->query($sql);
            $constSql = "delete from oa_contract_cost  where contractId = '" . $id . "'";
            $this->service->query($constSql);
            $conArr = $this->service->get_d($id);
            $tomail = $this->service->costConUserIdBycid($conArr['newProLineStr']);
            $content = array(
                "contractCode" => $conArr['contractCode'],
                "contractName" => $conArr['contractName'],
                "customerName" => $conArr['customerName']
            );
            $this->service->mailDeal_d("contractCost_Confirm", $tomail, $content);
            if (!empty($id)) {
                $updateB = "update oa_contract_equ_link set ExaStatus='δ�ύ' where contractId=$id";
                $this->service->_db->query($updateB);
                //              //ɾ��������ȷ�ϵ���ʱ��¼
                //              $delSql = "delete from oa_contract_equ_link where contractId = '".$id."'";
                //              $this->service->query($delSql);
                //              //ɾ����ȷ�ϵĺ�ͬ������Ϣ
                //              $delEqu = "delete from oa_contract_equ where contractId = '".$id."' and isBorrowToorder != '1'";
                //              $this->service->query($delEqu);
            }
            $handleDao = new model_contract_contract_handle();
            $handleDao->handleAdd_d(array(
                "cid" => $id,
                "stepName" => "�ύ�ɱ�ȷ��",
                "isChange" => 0,
                "stepInfo" => "",
            ));

            echo "1";
        } catch (Exception $e) {
            echo "";
        }
    }

    /**
     * �����������
     */
    function c_add($isAddInfo = false)
    {
        $this->checkSubmit();
        $rows = $_POST[$this->objName];
        //������ת���۹����̻�����
        if (isset($_GET['turnChanceIds'])) {
            $rows['turnChanceIds'] = $_GET['turnChanceIds'];
        }

        // ���Ͽ�Ʊ������Ϣ��Json���� PMS 647
        $invoiceJsonData = "";
        if(!empty($rows['invoiceCode']) && !empty($rows['invoiceValue'])){
            $catchArr = array();
            $i = 0;
            if(in_array('HTBKP',$rows['invoiceCode'])){
                $catchArr['HTBKP'] = "";
                $i = 1;
            }
            foreach ($rows['invoiceValue'] as $k => $v){
                if($v !== '' && ($v >= 0 || $v <= 0)){
                    $catchArr[$rows['invoiceCode'][$i]] = $v;
                    $i++;
                }
            }
            $invoiceJsonData = util_jsonUtil::encode($catchArr);
        }

        $act = isset($_GET['act']) ? $_GET['act'] : "";
        // ��֤��ͬ��Ϣ
        if ($checkResult = $this->service->checkContractMoney_d($rows)) {
            $act = '';
        }
        $id = $this->service->add_d($rows, $act, $invoiceJsonData);
        if ($checkResult) {
            msgGo($checkResult, '?model=contract_contract_contract&action=mycontract');
        }

        if ($id && $act == "app") {
            if (!empty($rows['chanceId'])) {
                $chanceDao = new model_projectmanagent_chance_chance();
                $chanceDao->updateChanceNewDate($rows['chanceId'], $id);
            }
            //���ⲿ���ύ�ĺ�ֱͬ���ύ����
            if ($_SESSION['DEPT_ID'] == hwDeptId) {
                succ_show('controller/contract/contract/ewf_index_hw_list.php?actTo=ewfSelect&billId=' . $id);
            }
            if ($id == "confirm") {
                msgGo("��ͬ���ύȷ�ϳɱ�����", '?model=contract_contract_contract&action=mycontract');
            } else {
                //��ȡ��������id ��
                $deptIds = $this->service->getDeptIds($rows);
                $configDeptIds = contractFlowDeptIds; //config�ڶ���� ����ID
                if (!empty($deptIds)) {
                    $deptIdStr = $configDeptIds . "," . $deptIds;
                } else {
                    $deptIdStr = $configDeptIds;
                }
                $deptIdStrArr = explode(",", $deptIdStr);
                $deptIdStrArr = array_unique($deptIdStrArr);
                $deptIdStr = implode(",", $deptIdStrArr);
                if ($rows['winRate'] == "50%") {
                    succ_show('controller/contract/contract/ewf_index_50.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                } else {
                    succ_show('controller/contract/contract/ewf_index_Other.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                }
            }
        } else {
            if ($id) {
                if (!empty($rows['chanceId'])) {
                    $chanceDao = new model_projectmanagent_chance_chance();
                    $chanceDao->updateChanceNewDate($rows['chanceId'], '');
                    msg("��ӳɹ�");
                } else {
                    msgGo("��ӳɹ�", '?model=contract_contract_contract&action=mycontract');
                }
            } else {
                msgGo('���ʧ�ܣ�');
            }
        }
    }

    /**
     * ��ʼ������
     */
    function c_init()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assign('invoiceLimitR', "1");
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        $closeType = isset($_GET['closeType']) ? $_GET['closeType'] : null;

        //��ȡ��ͬ�����ˣ�����ͨ�����������/Ĭ�ϻ��е�һ�������ˣ�
        $appArr = explode(",", $obj['appNameStr']);
        //��ȡ����ͬ��Ϣ
        if (!empty($obj['parentId'])) {
            $parentArr = $this->service->get_d($obj['parentId']);
            $this->assign('parentId', $parentArr['id']);
            $this->assign('parentNameV', $parentArr['contractName']);
            $this->assign('parentCode', $parentArr['contractCode']);
        } else {
            $this->assign('parentNameV', "");
            $this->assign('parentCode', "");
        }

        $invoiceTypeInfoArr = $this->service->makeInvoiceValueArr($obj);

        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            //Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˡ������ˣ��������ֶ�Ȩ�޹���
            if (!in_array($_SESSION['USER_ID'], $appArr) && $obj['areaPrincipalId'] != $_SESSION['USER_ID'] && $obj['createId'] != $_SESSION['USER_ID'] && $obj['prinvipalId'] != $_SESSION['USER_ID'] && $actType != 'audit') {
                $obj = $this->service->filterWithoutField('��ͬ���', $obj, 'keyForm', array(
                    'contractMoney',
                    'contractTempMoney',
                    'exgross',
                    'costEstimates',
                    'saleCost',
                    'serCost',
                    'saleCostTax',
                    'costEstimatesTax'
                ));
                //��Ʊ����
                if (isset($this->service->this_limit['������']) && !empty($this->service->this_limit['������'])) {
                    $this->assign('invoiceLimitR', "1");
                } else {
                    $this->assign('invoiceLimitR', "0");
                }
                //��ͬ�ı�Ȩ��(2012-10-10л���������ͬ�ı�Ȩ�޲�����ͬ��ȡ��ԭ��ͬ�ı�Ȩ��)
                //				if(isset($this->service->this_limit['��ͬ�ı�']) && !empty($this->service->this_limit['��ͬ�ı�']))
                if (isset($this->service->this_limit['��ͬ���']) && !empty($this->service->this_limit['��ͬ���'])) {
                    $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
                    $this->assign('file2', $file2);
                } else {
                    $this->assign('file2', '��û�����Ȩ�ޡ�');
                }
            } else {
                $file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract2');
                $this->assign('file2', $file2);
            }
            // �ɱ�����ǧ��λ��������״̬��ǰ��js��ʽ�������NaN�����
            if ($obj['costEstimatesTax'] != '******') {
                $obj['costEstimatesTax'] = number_format($obj['costEstimatesTax'], 2);
            }
            //��ϸ���ϳɱ�Ȩ��
            $this->assign('equCoseLimit', $this->service->this_limit['��ϸ���ϳɱ�']);
            //������Ⱦ
            $this->assignFunc($obj);
            if ($obj['sign'] == 1) {
                $this->assign('sign', '��');
            } else {
                $this->assign('sign', '��');
            }
            if ($obj['shipCondition'] == "0") {
                $this->assign('shipCondition', '��������');
            } else if ($obj['shipCondition'] == "1") {
                $this->assign('shipCondition', '֪ͨ����');
            } else {
                $this->assign('shipCondition', '');
            }
            if ($obj['isRenewed'] == "0") {
                $this->assign('isRenewed', '��ǩ��ͬ');
            } else if ($obj['isRenewed'] == "1") {
                $this->assign('isRenewed', '��ǩ��ͬ');
            } else {
                $this->assign('isRenewed', '');
            }
            //����
            $file = $this->service->getFilesByObjId($obj['id'], false);
            $this->assign('file', $file);
            $file3 = $this->service->getFilesByObjId($obj['id'], false, 'oa_contract_contract3');
            $this->assign('file3', $file3);


            $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
            $this->assign('contractNature', $this->getDataNameByCode($obj['contractNature']));
            $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
            $this->assign('invoiceType', $this->getDataNameByCode($obj['invoiceType']));
//            $this->assign('signSubject', $this->getDataNameByCode($obj['signSubject']));

            //�Ƿ�
            $this->assign('isStamp', $this->service->rtYesOrNo_d($obj['isStamp']));
            $this->assign('isNeedStamp', $this->service->rtYesOrNo_d($obj['isNeedStamp']));
            $this->assign('actType', $actType);
            $this->assign('closeType', $closeType);

            $this->assign("exgrossval", EXGROSS);

            $regionDao = new model_system_region_region();
            $areaPrincipal = $regionDao->find(array("id" => $obj['areaCode']), null, "areaPrincipal");
            $this->assign('AreaLeaderNow', $areaPrincipal['areaPrincipal']);
            //�Ƿ������۱����ʶ
            $this->assign('isSubAppChange', $obj['isSubAppChange']);
            //��ȡ�̻�
            $chanceDao = new model_projectmanagent_chance_chance();
            $chanceArr = $chanceDao->get_d($obj['chanceId']);
            $this->assign('chanceCode', $chanceArr['chanceCode']);
            $this->assign('chanceId', $chanceArr['id']);

            //��ǰ�̻�����
            $chanceCost = $this->service->getChanceCostByid($_GET['id']);
            $this->assign("chanceCost", $chanceCost);
            //��ȡ�¿�Ʊ����
            $dataDao = new model_system_datadict_datadict();
            $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
            $invoiceCodeArr = explode(",", $obj['invoiceCode']);
            $invoiceValueArr = explode(",", $obj['invoiceValue']);
            $i = 0;
            $invoiceType = '';

            // ��Ϊ��Ʊ����ǰ�����һ������Ҫ��ʾ�����,���Կ�Ʊ�������ǰ��Ҫ��һ���յ�Ԫ��,�Ա��ڿ�Ʊ���͵�����˳����ƥ��
            array_unshift($invoiceValueArr,'');
            $typeArr = $typeArr['KPLX'];
            foreach ($typeArr as $v) {
                $dataCodeArr[] = $v['dataCode'];
                if ($v['dataCode'] == 'HTBKP' && in_array($v['dataCode'], $invoiceCodeArr)) {
                    $invoiceType .= <<<EOT
                            <input type="hidden" id="$v[dataCode]V" value="1"/>
                            &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
                            <span id="$v[dataCode]Hide" style="display:none"></span>
EOT;
                }
//                    else if (in_array($v['dataCode'], $invoiceCodeArr)) {
                else if (isset($invoiceTypeInfoArr[$v['dataCode']])){
                    $invoiceType .= <<<EOT
                            <input type="hidden" id="$v[dataCode]V" value="1"/>
                            &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
                            <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="serviceInvMoney" readonly="readonly" value="{$invoiceTypeInfoArr[$v['dataCode']]}" class="rimless_text formatMoney" /></span>
EOT;
                } else {
                    $invoiceType .= <<<EOT
                            <input type="hidden" id="$v[dataCode]V" value="0"/>
                            &nbsp <span id="$v[dataCode]" >&nbsp$v[dataName]($v[expand1]%)</span>
                            <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="serviceInvMoney" readonly="readonly" value="{$invoiceValueArr[$i]}" class="rimless_text formatMoney" /></span>
EOT;
                }
                $i++;
            }

            $dataCode = implode(',', $dataCodeArr);
            $this->assign('dataCode', $dataCode);
            $this->assign("invoiceType", $invoiceType);
            if ($obj['isSame'] == 0) {
                $this->assign("isSameText", '��');
            } else {
                $this->assign("isSameText", '��');
            }
            // �Ƿ��ܺ�ͬ
            if ($obj['isFrame'] == 0) {
                $this->assign("isFrame", '��');
            } else {
                $this->assign("isFrame", '��');
            }
            $this->view('view');
        } else {
            $dataDao = new model_system_datadict_datadict();
            $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
            $invoiceCodeArr = explode(",", $obj['invoiceCode']);
            $invoiceValueArr = explode(",", $obj['invoiceValue']);
            $i = 0;
            $invoiceType = '';

            // �����е����ƿ�Ʊ���� PMS 647
            $otherDatasDao = new model_common_otherdatas();
            $limitInvoiceType = $otherDatasDao->getConfig('limitInvoiceTypeForContract', null, 'arr');

            // ��Ϊ��Ʊ����ǰ�����һ������Ҫ��ʾ�����,���Կ�Ʊ�������ǰ��Ҫ��һ���յ�Ԫ��,�Ա��ڿ�Ʊ���͵�����˳����ƥ��
            array_unshift($invoiceValueArr,'');
            foreach ($typeArr as $val) {
                foreach ($val as $v) {
                    $dataCodeArr[] = $v['dataCode'];
                    $disabledStr = (in_array($v['dataCode'],$limitInvoiceType))? "disabled" : "";
                    $disabledExtStr = (in_array($v['dataCode'],$limitInvoiceType))? "data-isDisable='1'" : "data-isDisable=''";
                    if ($v['dataCode'] == 'HTBKP') {
                        //if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        if (isset($invoiceTypeInfoArr[$v['dataCode']])){
                            // ����ǽ��õĿ�Ʊ������֮ǰ���������,�򽫶�Ӧ�ķ�Ʊ���ȥ��
                            $checkedStr = ($disabledStr == "disabled")? "" : 'checked="checked"';
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" $checkedStr  onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        } else {
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }
                    } else {
                        //if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        if (isset($invoiceTypeInfoArr[$v['dataCode']])){
                            // ����ǽ��õĿ�Ʊ������֮ǰ���������,�򽫶�Ӧ�ķ�Ʊ���ȥ��
                            $checkedStr = ($disabledStr == "disabled")? "" : 'checked="checked"';
                            $value = ($disabledStr == "disabled")? "" : $invoiceTypeInfoArr[$v['dataCode']];
                            $displayStr = ($disabledStr == "disabled")? 'style="display:none"' : "";
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" $checkedStr onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                                <span id="$v[dataCode]Hide" $displayStr> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$value" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        } else {
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                                <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }
                    }
                    $i++;
                }
            }
            $this->assign('dataCode', implode(',', $dataCodeArr));
            //������Ⱦ
            $this->assignFunc($obj);

            if ($obj['sign'] == 1) {
                $this->assign('signYes', 'checked');
            } else {
                $this->assign('signNo', 'checked');
            }
            //����
            $file = $this->service->getFilesByObjId($obj['id'], true);
            $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
            $file3 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract3');
            $this->assign('file', $file);
            $this->assign('file2', $file2);
            $this->assign('file3', $file3);
            $this->showDatadicts(array(
                'contractType' => 'HTLX'
            ), $obj['contractType']);
            $this->showDatadicts(array(
                'contractNature' => $obj['contractType']
            ), $obj['contractNature']);
            $this->showDatadicts(array(
                'invoiceType' => 'FPLX'
            ), $obj['invoiceType']);
//            $this->showDatadicts(array(
//                'customerType' => 'KHLX'
//            ), $obj['customerType']);
            $this->showDatadicts(array(
                'module' => 'HTBK'
            ), $obj['module']);

            // ����ͻ�����������
            $customerTypeName = '';
            $sql = "select * from oa_system_datadict where parentCode = 'KHLX' and dataCode = '{$obj['customerType']}'";
            if($obj['customerType'] != ''){
                $result = $this->service->_db->getArray($sql);
                $customerTypeName = (isset($result[0]) && isset($result[0]['dataName']))? $result[0]['dataName'] : '';
            }
            $this->assign('customerTypeName', $customerTypeName);

            //���ø�������
            //			$stampConfigDao = new model_system_stamp_stampconfig();
            //			$stampArr = $stampConfigDao->getStampType_d();
            //			$this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//��������

            //������ת��������id
            $ids = isset ($_GET['ids']) ? $_GET['ids'] : null;
            $this->assign('ids', $ids);
            $this->assign('invoiceType', $invoiceType);
            $this->assign('newInvoiceId', isset($objArrs['id']) ? $objArrs['id'] : "");
            //�����ݲ��������⴦��
            if (dsjAreaId) {
                $regionDao = new model_system_region_region();
                $rs = $regionDao->find(array('id' => dsjAreaId, 'isStart' => '0'), null, 'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
                //��ǰ��¼��Ϊ�����ݲ�����������Ա�ģ�Ҫ�����⴦��
                if ($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))) {
                    $this->assign('areaCode', dsjAreaId);
                    $this->assign('areaName', $rs['areaName']);
                    $this->assign('areaPrincipalId', $rs['areaPrincipalId']);
                    $this->assign('areaPrincipal', $rs['areaPrincipal']);
                    //ִ����������д��
                    $exeDeptCode = 'GCSCX-17';
                    $exeDeptName = '�����ݲ�';
                }
            }
            $this->assign('exeDeptCode', isset($exeDeptCode) ? $exeDeptCode : '');
            $this->assign('exeDeptName', isset($exeDeptName) ? $exeDeptName : '');
            if (isset ($_GET['perm']) && $_GET['perm'] == 'hwedit') { //����༭
                $this->assign('isHwEdit', 1);
                $this->view('hwedit');
            } else {
                $this->view('edit', true);
            }
        }
    }

    /**
     * �̻�ת��ͬҳ��
     */
    function c_toAddchance()
    {
        //������Ⱦ
        $chanceDao = new model_projectmanagent_chance_chance();
        $obj = $chanceDao->get_d($_GET['chanceId']);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $invoiceCodeArr = explode(",", $obj['invoiceCode']);
        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $i = 0;
        $invoiceType = '';

        // �����е����ƿ�Ʊ���� PMS 647
        $otherDatasDao = new model_common_otherdatas();
        $limitInvoiceType = $otherDatasDao->getConfig('limitInvoiceTypeForContract', null, 'arr');

        // ��Ϊ��Ʊ����ǰ�����һ������Ҫ��ʾ�����,���Կ�Ʊ�������ǰ��Ҫ��һ���յ�Ԫ��,�Ա��ڿ�Ʊ���͵�����˳����ƥ��
        array_unshift($invoiceValueArr,'');
        $dataCodeArr = array();
        foreach ($typeArr as $val) {
            foreach ($val as $v) {
                $dataCodeArr[] = $v['dataCode'];
                $disabledStr = (in_array($v['dataCode'],$limitInvoiceType))? "disabled" : "";
                $disabledExtStr = (in_array($v['dataCode'],$limitInvoiceType))? "data-isDisable='1'" : "data-isDisable=''";
                if ($v['dataCode'] == 'HTBKP') {
                    if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        if($disabledStr == "disabled"){// ������ʱԭ�����ڽ������ֵ��,�����乴ѡ״̬�����ҽ�ֹ��ѡ����
                            $invoiceType .= <<<EOT
                                <input type="checkbox"  checked="checked" disabled>
                                <input type="checkbox" style="display:none" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]"  onclick="isBKPCheck('$v[dataCode]');" checked="checked">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }else {
                            $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked"  onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }
                    } else {
                        $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]"  onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                    }
                } else {
                    if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        if($disabledStr == "disabled"){// ������ʱԭ�����ڽ������ֵ��,�����乴ѡ״̬�����ҽ�ֹ��ѡ����,�������޸�
                            $invoiceType .= <<<EOT
                            <input type="checkbox"  checked="checked" disabled>
                            <input type="checkbox" style="display:none" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">
                            <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                            <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValueArr[$i]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }else {
                            $invoiceType .= <<<EOT
                            <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">
                            <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                            <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValueArr[$i]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }
                    } else {
                        $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>
                        <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
                        <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                    }
                }
                $i++;
            }
        }
        $dataCode = implode(',', $dataCodeArr);
        $this->assignFunc($obj);
        //����
        $file = $chanceDao->getFilesByObjId($_GET['chanceId'], true);
        $this->assign('file', $file);
        $this->showDatadicts(array('contractType' => 'HTLX'), $obj['chanceType']);
        $this->showDatadicts(array('contractNature' => $obj['chanceType']), $obj['chanceNature']);
        //$this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);
        $this->showDatadicts(array('module' => 'HTBK'), $obj['module']);

        // ����ͻ�����������
        $customerTypeName = '';
        $sql = "select * from oa_system_datadict where parentCode = 'KHLX' and dataCode = '{$obj['customerType']}'";
        if($obj['customerType'] != ''){
            $result = $this->service->_db->getArray($sql);
            $customerTypeName = (isset($result[0]) && isset($result[0]['dataName']))? $result[0]['dataName'] : '';
        }
        $this->assign('customerTypeName', $customerTypeName);

        $this->assign('createName', $_SESSION['USERNAME']);
        $this->assign('contractSigner', $_SESSION['USERNAME']);
        $this->assign('contractSignerId', $_SESSION['USER_ID']);
        $this->assign('prinvipalName', $_SESSION['USERNAME']);
        $this->assign('prinvipalId', $_SESSION['USER_ID']);
        $this->assign('prinvipalDept', $_SESSION['DEPT_NAME']); //û�к����ڼ�
        $this->assign('prinvipalDeptId', $_SESSION['DEPT_ID']);

        $this->assign('signSubjectName', $obj['formBelongName']);
        $this->assign('signSubject', $obj['formBelong']);

        $this->assign('dataCode', $dataCode);
        $this->assign('invoiceType', $invoiceType);
        //��ͬ����Ƿ��ֹ�����
        $this->assign('contractInput', ORDER_INPUT);
        //�����ݲ��������⴦��
        if (dsjAreaId) {
            $regionDao = new model_system_region_region();
            $rs = $regionDao->find(array('id' => dsjAreaId, 'isStart' => '0'), null, 'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
            //��ǰ��¼��Ϊ�����ݲ�����������Ա�ģ�Ҫ�����⴦��
            if ($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))) {
                $this->assign('areaCode', dsjAreaId);
                $this->assign('areaName', $rs['areaName']);
                $this->assign('areaPrincipalId', $rs['areaPrincipalId']);
                $this->assign('areaPrincipal', $rs['areaPrincipal']);
                //ִ����������д��
                $exeDeptCode = 'GCSCX-17';
                $exeDeptName = '�����ݲ�';
            }
        }
        $this->assign('exeDeptCode', isset($exeDeptCode) ? $exeDeptCode : '');
        $this->assign('exeDeptName', isset($exeDeptName) ? $exeDeptName : '');
        //���̻����ƴ�����ͬ����
        $this->assign('contractName', $obj['chanceName']);

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);
        $this->view('add-chance', true);
    }

    /**
     *��ת��excel�ϴ�ҳ��
     */
    function c_toExcel()
    {
        $this->assign("dateTime", date("Y-m-d"));
        $this->display('importexcel');
    }

    /**
     * �ӳٷ����б� --֪ͨ����
     */
    function c_informShipments()
    {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('informShipments');
    }

    /**
     * �ӳٷ����б� --֪ͨ����
     */
    function c_informS()
    {
        $inform = $_POST["inform"];
        $flag = $this->service->inform_d($inform);
        msg("����֪ͨ�ѷ��ͣ�");
    }

    /**
     * �رպ�ͬҳ��
     */
    function c_closeContract()
    {
        $row = $this->service->get_d($_GET['id']);
        $esmDao = new model_engineering_project_esmproject();
        $esmArr = $esmDao->getProjectList_d(array($_GET['id']));

        if (!empty($esmArr)) {
            $esmTip = 0;
            $esmCode = "";
            foreach ($esmArr as $v) {
                if(strpos($v['id'],"c") === false){

                    if (($v['status'] == 'GCXMZT03' || $v['status'] == 'GCXMZT06')) {
                        $esmTip = 0;
                        $esmCode .= $v['projectCode'] . ",";
                    }else{
                        $esmTip = 1;
                    }
                }
            }
        } else {
            $esmTip = 0;
        }
        foreach ($row as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('userName', $_SESSION['USERNAME']);
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->assign('dateTime', date('Y-m-d  H:i:s'));
        if($row['DeliveryStatus'] == 'WFH' && ( strpos($row['goodsTypeStr'],"11") === false ) && $esmTip == 0){
            $this->display('close');
        }else{
            if (($row['DeliveryStatus'] != 'TZFH' && $row['DeliveryStatus'] != 'YFH') || $esmTip != 0) {
                echo "<b>��� �������� / ������Ŀ  ��δ�رգ��ݲ��������쳣�رա�<b/>";
            } else if((!empty($row['invoiceMoney']) && $row['invoiceMoney'] != '0') || (!empty($row['incomeMoney']) && $row['incomeMoney'] != '0')){
                echo "<b>�򱾺�ͬ�п�Ʊ���տ���ݲ���Ҫ�󣬲��������쳣�رգ���ͨ���������̴���<b/>";
            }else {
                $this->display('close');
            }
        }
    }

    /**
     * �رպ�ͬ
     */
    function c_close($isEditInfo = false)
    {
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $object = $_POST['close'];
        $id = $this->service->close_d($object, $isEditInfo);
        if ($id && $_GET['actType'] == "app") {
            succ_show('controller/contract/contract/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
        } else {
            msg('�رճɹ���');
        }
    }

    /**
     * �ر���������ת����
     */
    function c_confirmCloseApprovalNo()
    {
        //�������ص�����
        $this->service->workflowCallBack_close($_GET['spid']);
        $urlType = isset ($_GET['urlType']) ? $_GET['urlType'] : null;
        //��ֹ�ظ�ˢ��
        if ($urlType) {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        } else {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }

    /**
     *  ����ȷ��������㷽ʽ
     */
    function c_incomeAcc()
    {
        $row = $this->service->get_d($_GET['id']);
        foreach ($row as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('incomeAcc');
    }

    function c_incomeAccEdit()
    {
        $rows = $_POST['acc'];
        $id = $this->service->incomeAccEdit_d($rows);
        $msg = $_POST["msg"] ? $_POST["msg"] : 'ȷ�ϳɹ���';
        if ($id) {
            msg($msg);
        }
    }

    /**
     * ��ת--��ͬ����
     */
    function c_toShare()
    {
        $row = $this->service->get_d($_GET['id']);
        foreach ($row as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('shareName', $_SESSION['USERNAME']);
        $this->assign('shareNameId', $_SESSION['USER_ID']);
        $this->assign('shareDate', date('y-m-d'));
        //��ȡ�Թ������Ա
        $dao = new model_contract_common_share();
        $toshareArr = $dao->getShareByConId($_GET['id']);
        if (!empty($toshareArr)) {
            $toshareName = '';
            $toshareNameId = '';
            foreach ($toshareArr as $val) {
                $toshareName .= $val['toshareName'] . ",";
                $toshareNameId .= $val['toshareNameId'] . ",";
            }
            $toshareName = rtrim($toshareName, ",");
            $toshareNameId = rtrim($toshareNameId, ",");
            $this->assign('toshareName', $toshareName);
            $this->assign('toshareNameId', $toshareNameId);
        } else {
            $this->assign('toshareName', "");
            $this->assign('toshareNameId', "");
        }

        $this->display('share');
    }

    /**
     * ��ͬ������ɺ���ת����
     */
    function c_configContract()
    {
        //�������ص�����
        $this->service->workflowCallBack($_GET['spid']);

        $urlType = isset ($_GET['urlType']) ? $_GET['urlType'] : null;
        //��ֹ�ظ�ˢ��
        if ($urlType) {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        } else {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }

    /**
     * ��ת�������ҳ��
     */
    function c_toStamp()
    {
        //	 	$this->permCheck (); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //�������{file}
        // 		$this->assign('file', $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2'));
        $this->assign('file', '�����κθ���');
        $this->assign('applyDate', day_date);

        //���ø�������
        //		$stampConfigDao = new model_system_stamp_stampconfig();
        //		$stampArr = $stampConfigDao->getStampType_d();
        //		$this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//��������

        //��ǰ����������
        $this->assign('thisUserId', $_SESSION['USER_ID']);
        $this->assign('thisUserName', $_SESSION['USERNAME']);

        //����ҵ�񾭰���Ϊ��ǰ��¼��
        $this->assign('attnId', $_SESSION['USER_ID']);
        $this->assign('attn', $_SESSION['USERNAME']);
        $this->assign('attnDeptId', $_SESSION['DEPT_ID']);
        $this->assign('attnDept', $_SESSION['DEPT_NAME']);

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), null, true);
        $this->assign('contractType', 'HTGZYD-04');
        $this->assign('contractTypeName', $this->getDataNameByCode('HTGZYD-04'));
        //$this->view('stamp');
        $this->view('stamp-add');
    }

    /**
     * ����������Ϣ����
     */
    function c_stamp()
    {
        $object = $_POST[$this->objName];
        $rs = $this->service->stamp_d($object);
        if ($rs && $object['handle'] == "1") {
            $dao = new model_contract_contract_aidhandle();
            $dao->add_d(array("contractId" => $_POST['contractId'], "handleType" => "GZSQ"));
            msg('����ɹ���');
        } else {
            if ($rs) {
                msg("����ɹ���");
            } else {
                msg("����ʧ�ܣ�");
            }
        }
    }

    /***********************************************************************************************************************************************************/
    /********************************************�� ͬ �� �� ****************************************************************************************/
    /**
     * ��ת����ͬ���ҳ��
     */
    function c_toChange()
    {
        $this->permCheck(); //��ȫУ��
        //��ʱ��¼id
        $tempId = isset($_GET['tempId']) ? $_GET['tempId'] : '';
        //�ж��Ƿ������ʱ����ļ�¼����������ȷ�ϴ�أ�������صļ�¼
        if (empty($tempId)) {
            $sql = "select tempId,ExaStatus,changeReason from oa_contract_changlog where id = (select max(id) as id from oa_contract_changlog " .
                "where objType = 'contract' and objId = " . $_GET['id'] . " and changeManId = '" . $_SESSION['USER_ID'] . "')";
            $rs = $this->service->_db->getArray($sql);
            $tempId = !empty($rs) && $rs[0]['ExaStatus'] != AUDITED ? $rs[0]['tempId'] : '';
        } else {
            $sql = "select changeReason from oa_contract_changlog where objType = 'contract' and tempId = " . $tempId;
            $rs = $this->service->_db->getArray($sql);
            $changeReason = !empty($rs) ? $rs[0]['changeReason'] : '';
        }
        $this->assign('tempId', empty($tempId) ? '' : $tempId);
        $this->assign('changeReason', isset($_GET['tempId']) && !empty($changeReason) ? $changeReason : ''); //���ԭ��
        $contractId = isset($_GET['tempId']) ? $_GET['tempId'] : $_GET['id'];
        $this->assign('contractId', $contractId);
        $obj = $this->service->get_d($contractId);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //idʼ��ΪԴ��id
        if (isset($_GET['tempId'])) {
            $this->assign('id', $_GET['id']);
        } else {
            $this->assign('turnChanceIds', '');
        }
        if ($obj['sign'] == 1) {
            $this->assign('signYes', 'checked');
        } else {
            $this->assign('signNo', 'checked');
        }

        $invoiceTypeInfoArr = $this->service->makeInvoiceValueArr($obj);

        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $invoiceCodeArr = explode(",", $obj['invoiceCode']);
        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $i = 0;
        $invoiceType = '';

        // �����е����ƿ�Ʊ���� PMS 647
        $otherDatasDao = new model_common_otherdatas();
        $limitInvoiceType = $otherDatasDao->getConfig('limitInvoiceTypeForContract', null, 'arr');

        // ��Ϊ��Ʊ����ǰ�����һ������Ҫ��ʾ�����,���Կ�Ʊ�������ǰ��Ҫ��һ���յ�Ԫ��,�Ա��ڿ�Ʊ���͵�����˳����ƥ��
        array_unshift($invoiceValueArr,'');
        $dataCodeArr = array();
        foreach ($typeArr as $val) {
            foreach ($val as $v) {
                $dataCodeArr[] = $v['dataCode'];
                $disabledStr = (in_array($v['dataCode'],$limitInvoiceType))? "disabled" : "";
                $disabledExtStr = (in_array($v['dataCode'],$limitInvoiceType))? "data-isDisable='1'" : "data-isDisable=''";
                if ($v['dataCode'] == 'HTBKP') {
                    //if (in_array($v['dataCode'], $invoiceCodeArr)) {
                    if(isset($invoiceTypeInfoArr[$v['dataCode']])){
                        if($disabledStr == "disabled"){// ������ʱԭ�����ڽ������ֵ��,�����乴ѡ״̬�����ҽ�ֹ��ѡ����
                            $invoiceType .= <<<EOT
                                <input type="checkbox"  checked="checked" disabled>
                                <input type="checkbox" style="display:none" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" checked="checked">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }else{
                           $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" checked="checked">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                        }
                    } else {
                        $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isBKPCheck('$v[dataCode]');" $disabledExtStr $disabledStr>
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                    }
                } else {
                    // ������ʱԭ�����ڽ������ֵ��,�����乴ѡ״̬�����ҽ�ֹ��ѡ����,�������޸�
                    // if (in_array($v['dataCode'], $invoiceCodeArr)) {
                    if(isset($invoiceTypeInfoArr[$v['dataCode']])){
                        $invoiceValue = $invoiceTypeInfoArr[$v['dataCode']];
                        if($disabledStr == "disabled"){
                            $invoiceType .= <<<EOT
                            <input type="checkbox"  checked="checked" disabled>
                            <input style="display:none" type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">$v[dataName]($v[expand1]%)
                            <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValue" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }else{
                            $invoiceType .= <<<EOT
                            <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">$v[dataName]($v[expand1]%)
                            <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValue" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                        }
                    } else {
                        $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');" $disabledExtStr $disabledStr>$v[dataName]($v[expand1]%)
                        <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                    }
                }
                $i++;
            }

        }
        $dataCode = implode(',', $dataCodeArr);
        //����
        $file = $this->service->getFilesByObjId($contractId, true);
        $file2 = $this->service->getFilesByObjId($contractId, true, 'oa_contract_contract2');
        $file3 = $this->service->getFilesByObjId($contractId, true, 'oa_contract_contract3');
        $this->assign('dataCode', $dataCode);
        $this->assign('file', $file);
        $this->assign('file2', $file2);
        $this->assign('file3', $file3);

        $this->showDatadicts(array('contractType' => 'HTLX'), $obj['contractType']);
        $this->showDatadicts(array('contractNature' => $obj['contractType']), $obj['contractNature']);
        $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType']);
        $this->showDatadicts(array('module' => 'HTBK'), $obj['module']);

        // ����ͻ�����������
        $customerTypeName = '';
        $sql = "select * from oa_system_datadict where parentCode = 'KHLX' and dataCode = '{$obj['customerType']}'";
        if($obj['customerType'] != ''){
            $result = $this->service->_db->getArray($sql);
            $customerTypeName = (isset($result[0]) && isset($result[0]['dataName']))? $result[0]['dataName'] : '';
        }
        $this->assign('customerTypeName', $customerTypeName);

        //������ת��������id
        $ids = isset ($_GET['ids']) ? $_GET['ids'] : null;
        $this->assign('invoiceType', $invoiceType);
        $this->assign('ids', $ids);
        //�����ݲ��������⴦��
        if (dsjAreaId) {
            $regionDao = new model_system_region_region();
            $rs = $regionDao->find(array('id' => dsjAreaId, 'isStart' => '0'), null, 'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
            //��ǰ��¼��Ϊ�����ݲ�����������Ա�ģ�Ҫ�����⴦��
            if ($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))) {
                $this->assign('areaCode', dsjAreaId);
                $this->assign('areaName', $rs['areaName']);
                $this->assign('areaPrincipalId', $rs['areaPrincipalId']);
                $this->assign('areaPrincipal', $rs['areaPrincipal']);
                //ִ����������д��
                $exeDeptCode = 'GCSCX-17';
                $exeDeptName = '�����ݲ�';
            }
        }
        $this->assign('exeDeptCode', isset($exeDeptCode) ? $exeDeptCode : '');
        $this->assign('exeDeptName', isset($exeDeptName) ? $exeDeptName : '');

        $this->view('change', true);
    }

    // ���������ͬ��Ʒ
    function c_toChangePro()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['sign'] == 1) {
            $this->assign('signYes', 'checked');
        } else {
            $this->assign('signNo', 'checked');
        }
        //����
        $file = $this->service->getFilesByObjId($obj['id'], true);
        $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
        $this->assign('file', $file);
        $this->assign('file2', $file2);
        $this->showDatadicts(array('contractType' => 'HTLX'), $obj['contractType']);
        $this->showDatadicts(array('contractNature' => $obj['contractType']), $obj['contractNature']);
        $this->showDatadicts(array('invoiceType' => 'FPLX'), $obj['invoiceType']);
        $this->showDatadicts(array('customerType' => 'KHLX'), $obj['customerType']);

        //������ת��������id
        $ids = isset ($_GET['ids']) ? $_GET['ids'] : null;
        $this->assign('ids', $ids);
        $this->view('changePro');
    }

    /**
     * ǰ̨ajax ��֤�Ƿ���Ҫ�ύ����
     */
    function c_changeSubAjax()
    {
        $rows = $_POST[$this->objName];
        $hasNewMaterial = $this->c_compareMaterial($rows);
        $deptIds = $this->c_compareProduct($rows, 'ajax');
        $isChangeMoney = $this->c_compareMoney($rows, 'ajax');
        //�ж�ֽ�ʺ�ͬ�Ƿ�
        $paperContractFlag = $this->c_comparePaperContract($rows);
        if (empty($deptIds) && ($isChangeMoney === 'none' || $isChangeMoney == '1') && $paperContractFlag === 'none' && $hasNewMaterial == '0') {
            echo "0";
        } else if ($paperContractFlag == '1') {
            echo "1";
        } else {
            echo "1";
        }
    }

    /**
     * �����ͬ
     */
    function c_change()
    {
        $this->checkSubmit(); // �ظ��ύ��֤
        try {
            $rows = $_POST[$this->objName];

            // ���Ͽ�Ʊ������Ϣ��Json���� PMS 647
            $invoiceJsonData = "";
            if(!empty($rows['invoiceCode']) && !empty($rows['invoiceValue'])){
                $catchArr = array();
                $i = 0;
                if(in_array('HTBKP',$rows['invoiceCode'])){
                    $catchArr['HTBKP'] = "";
                    $i = 1;
                }
                foreach ($rows['invoiceValue'] as $k => $v){
                    if($v !== '' && ($v >= 0 || $v <= 0)){
                        $catchArr[$rows['invoiceCode'][$i]] = $v;
                        $i++;
                    }
                }
                $invoiceJsonData = util_jsonUtil::encode($catchArr);
            }

            //������ת���۹����̻�����
            if (isset($_GET['turnChanceIds'])) {
                $rows['turnChanceIds'] = $_GET['turnChanceIds'];
            }

            // ��֤��ͬ��Ϣ
            if ($checkResult = $this->service->checkContractMoney_d($rows)) {
                msgGo($checkResult, '?model=contract_contract_contract&action=mycontract');
            }

            //����
            if ($rows['isSub'] == '0') {
                $this->service->change_d($rows,null,null,$invoiceJsonData);
                msg("����ɹ���");
            } else {
                $handleDao = new model_contract_contract_handle();
                //�Ƚϱ���Ĳ�Ʒ��ԭ��Ʒ�Ĳ���  ������ ��������id��
                $deptIds = $this->c_compareProduct($rows);
                //���ⲿ���ύ�ı��
                if ($_SESSION['DEPT_ID'] == hwDeptId) {
                    if ($rows['isChangeSub'] == '1') { // ������
                        $id = $this->service->change_d($rows, 0, $deptIds,$invoiceJsonData);
                        $handleDao->handleAdd_d(array(
                            "cid" => $rows['id'],
                            "stepName" => "��ͬ���",
                            "isChange" => 1,
                            "stepInfo" => "",
                        ));
                        succ_show('controller/contract/contract/ewf_index_change_hw.php?actTo=ewfSelect&billId=' . $id);
                    } else {
                        /*ֱ�ӱ�� ��������������*/
                        $this->service->changeNotApp_d($rows,$invoiceJsonData);
                        $handleDao->handleAdd_d(array(
                            "cid" => $rows['id'],
                            "stepName" => "��ͬ���",
                            "isChange" => 1,
                            "stepInfo" => "��������",
                        ));
                        msg("����ɹ���");
                    }
                }

                //��ȡ������չ�ֶ�ֵ
                $regionDao = new model_system_region_region();
                $expand = $regionDao->getExpandbyId($rows['areaCode']);
                if ($rows['noApp'] == '1') {
                    $this->service->changeNotApp_d($rows,$invoiceJsonData);
                    $handleDao->handleAdd_d(array(
                        "cid" => $rows['id'],
                        "stepName" => "��ͬ���",
                        "isChange" => 1,
                        "stepInfo" => "��������",
                    ));
                    msg("����ɹ���");
                } else {
                    if (empty($deptIds)) {
                        if ($rows['isChangeSub'] == '1') {
                            $id = $this->service->change_d($rows, 0, null, $invoiceJsonData);
                            $handleDao->handleAdd_d(array(
                                "cid" => $rows['id'],
                                "stepName" => "��ͬ���",
                                "isChange" => 1,
                                "stepInfo" => "",
                            ));
                            $handleDao->handleAdd_d(array(
                                "cid" => $rows['id'],
                                "stepName" => "�ύ�ɱ�ȷ��",
                                "isChange" => 2,
                                "stepInfo" => "",
                            ));
                            $sql = "update oa_contract_changlog set ExaStatus = '����ȷ��' where objType = 'contract' and tempId = " . $id;
                            $this->service->_db->query($sql);
                            msg("��ͬ���ύȷ�ϳɱ�����!");
                        } else {
                            //�Ƚ��Ƿ������
                            $isChangeMoney = $this->c_compareMoney($rows);
                            //�ж�ֽ�ʺ�ͬ�Ƿ�
                            $paperContractFlag = $this->c_comparePaperContract($rows);
                            if ($isChangeMoney === 'none' && $paperContractFlag === 'none') {
                                /*ֱ�ӱ�� ��������������*/
                                $this->service->changeNotApp_d($rows, $invoiceJsonData);
                                $handleDao->handleAdd_d(array(
                                    "cid" => $rows['id'],
                                    "stepName" => "��ͬ���",
                                    "isChange" => 1,
                                    "stepInfo" => "��������",
                                ));
                                msg("����ɹ���");
                            } else {
                                $rows['signStatus'] = '2';
                                if ($isChangeMoney == '1' && $paperContractFlag === 'none') {
                                    /*Ԥ��ë�����ӣ� ��������������*/
                                    $this->service->changeNotApp_d($rows, $invoiceJsonData);
                                    $handleDao->handleAdd_d(array(
                                        "cid" => $rows['id'],
                                        "stepName" => "��ͬ���",
                                        "isChange" => 1,
                                        "stepInfo" => "��������",
                                    ));
                                    msg("����ɹ���");
                                } else {
                                    $isChangeMoney; //������ٵ�Ԥ��ë����
                                    //������
                                    $id = $this->service->change_d($rows, 1, null, $invoiceJsonData);
                                    if ($id) {
                                        $configDeptIds = contractFlowDeptIds; //config�ڶ���� ����ID
                                        if ($deptIds == 'noDept') {
                                            $deptIds = "";
                                        }
                                        $deptIdStr = $configDeptIds . "," . $deptIds;
                                        $deptIdStrArr = explode(",", $deptIdStr);
                                        $deptIdStrArr = array_unique($deptIdStrArr);
                                        $deptIdStr = implode(",", $deptIdStrArr);
                                        if ($isChangeMoney < EXGROSS) {
                                            succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                                        } else {
                                            succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($expand == '1' || $deptIds == 'tobo') {
                            //������
                            $id = $this->service->change_d($rows, 0, $deptIds, $invoiceJsonData);
                            if ($id) {
                                $configDeptIds = contractFlowDeptIds; //config�ڶ���� ����ID
                                if ($deptIds == 'noDept') {
                                    $deptIds = "";
                                }
                                $deptIdStr = $configDeptIds . "," . $deptIds;
                                $deptIdStrArr = explode(",", $deptIdStr);
                                $deptIdStrArr = array_unique($deptIdStrArr);
                                $deptIdStr = implode(",", $deptIdStrArr);
                                $oldrows = $this->service->getContractInfo($rows['id']);
                                $oldexGross = $oldrows['exgross'];
                                if ($oldexGross < EXGROSS) {
                                    succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                                } else {
                                    succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $id . '&billDept=' . $deptIdStr);
                                }
                            }
                        } else {
                            $id = $this->service->change_d($rows, 0,null ,$invoiceJsonData);
                            $handleDao->handleAdd_d(array(
                                "cid" => $rows['id'],
                                "stepName" => "��ͬ���",
                                "isChange" => 1,
                                "stepInfo" => "",
                            ));
                            $handleDao->handleAdd_d(array(
                                "cid" => $rows['id'],
                                "stepName" => "�ύ�ɱ�ȷ��",
                                "isChange" => 2,
                                "stepInfo" => "",
                            ));
                            $sql = "update oa_contract_changlog set ExaStatus = '����ȷ��' where objType = 'contract' and tempId = " . $id;
                            $this->service->_db->query($sql);
                            msg("��ͬ���ύȷ�ϳɱ�����!");
                        }
                    }
                }
            }
        } catch (Exception $e) {
            msgBack2("���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage());
        }
    }


    /**
     * �����������ת����
     */
    function c_confirmChangeToApprovalNo()
    {
        //�������ص�����
        $this->service->workflowCallBack_change($_GET['spid']);

        $urlType = isset ($_GET['urlType']) ? $_GET['urlType'] : null;
        //��ֹ�ظ�ˢ��
        if ($urlType) {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        } else {
            echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
        }
    }

    /**
     * ����������ϣ���� 2012-09-10�°�����ǰ�ĺ�ͬ��
     */
    function c_toChangeEqu()
    {
        $this->assign("contractId", $_GET['contractId']);
        $this->view("toChangeEqu");
    }

    /******************************** �� ͬ ǩ �� *********************************************************************************/
    /**
     * ��ͬǩ��Tabҳ
     */
    function c_contractSign()
    {
        $this->display("contractSign");
    }

    /**
     * δǩ�յĺ�ͬ
     */
    function c_Signin()
    {
        $this->assign("isfinance", 1);
        $this->view('signin');
    }

    /**
     * ǩ�պ�ͬ
     */
    function c_Signins()
    {
        $this->view('signins');
    }

    /**
     * ��ǩ�յĺ�ͬ
     */
    function c_comSignin()
    {
        $this->assign("isfinance", 1);
        $this->view('comsignin');
    }

    /**
     * ǩ��ҳ��
     */
    function c_signEditView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['sign'] == 1) {
            $this->assign('signYes', 'checked');
        } else {
            $this->assign('signNo', 'checked');
        }

        //�¿�Ʊ����
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $invoiceCodeArr = explode(",", $obj['invoiceCode']);
        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $i = 0;
        $invoiceType = '';

        // ��Ϊ��Ʊ����ǰ�����һ������Ҫ��ʾ�����,���Կ�Ʊ�������ǰ��Ҫ��һ���յ�Ԫ��,�Ա��ڿ�Ʊ���͵�����˳����ƥ��
        array_unshift($invoiceValueArr,'');

        foreach ($typeArr as $val) {
            foreach ($val as $v) {
                $dataCodeArr[] = $v['dataCode'];
                if ($v['dataCode'] == 'HTBKP') {
                    if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                    } else {
                        $invoiceType .= <<<EOT
                                <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]">
                                <label for="$v[dataCode]">$v[dataName]($v[expand1]%)</label>
EOT;
                    }
                } else {
                    if (in_array($v['dataCode'], $invoiceCodeArr)) {
                        $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" checked="checked" onclick="isCheckType('$v[dataCode]');">$v[dataName]($v[expand1]%)
                        <span id="$v[dataCode]Hide"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="$invoiceValueArr[$i]" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                    } else {
                        $invoiceType .= <<<EOT
                        <input type="checkbox" id="$v[dataCode]" name="contract[invoiceCode][]" value="$v[dataCode]" onclick="isCheckType('$v[dataCode]');">$v[dataName]($v[expand1]%)
                        <span id="$v[dataCode]Hide" style="display:none"> : <input type="text" id="$v[dataCode]Money" name="contract[invoiceValue][]" value="" class="rimless_text formatMoney" onBlur="checkConMoney();conversion();"/></span>
EOT;
                    }
                }
                $i++;
            }

        }
        $dataCode = implode(',', $dataCodeArr);
        //����
        $file = $this->service->getFilesByObjId($obj['id'], true);
        $file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_contract_contract2');
        $this->assign('dataCode', $dataCode);
        $this->assign('file', $file);
        $this->assign('file2', $file2);
        $this->showDatadicts(array(
            'contractType' => 'HTLX'
        ), $obj['contractType']);
        $this->showDatadicts(array(
            'contractNature' => $obj['contractType']
        ), $obj['contractNature']);
        $this->showDatadicts(array(
            'invoiceType' => 'FPLX'
        ), $obj['invoiceType']);
        $this->showDatadicts(array(
            'customerType' => 'KHLX'
        ), $obj['customerType']);
        $this->showDatadicts(array(
            'module' => 'HTBK'
        ), $obj['module']);
        //���ø�������
        //			$stampConfigDao = new model_system_stamp_stampconfig();
        //			$stampArr = $stampConfigDao->getStampType_d();
        //			$this->showSelectOption ( 'stampType', $obj['stampType'] , true , $stampArr);//��������
        $this->assign("nowDate", date("Y-m-d"));
        $this->assign("invoiceType", $invoiceType);
        $this->assign("newInvoiceId", isset($objArrs['id']) ? $objArrs['id'] : "");
        $this->view('signeditview');

    }

    /**
     * ��ͬǩ��
     */
    function c_signInVerify($isEditInfo = false)
    {
        $object = $_POST[$this->objName];
        if ($this->service->signin_d($object, $isEditInfo)) {
            $obj = $this->service->get_d($object['id']);

            $toMailstr[] = $object['prinvipalId'];
            $toMailstr[] = $object['areaPrincipalId'];
            //��ȡĬ�Ϸ�����
            include(WEB_TOR . "model/common/mailConfig.php");
            $toMailstr[] = isset($mailUser['contractSignEdit']['TO_ID']) ? $mailUser['contractSignEdit']['TO_ID'] : "";
            $toMailstr = implode(",", $toMailstr);

            //�����ʼ�
            $emailDao = new model_common_mail();
            $content = "��λ�ã�<br/> ��ͬ ��" . $obj['contractName'] . "(" . $obj['contractCode'] . ")�� ���� " . $_SESSION['USERNAME'] . " ǩ�����";
            $emailInfo = $emailDao->batchEmail("1", $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'contractSignEdit', '', null, $toMailstr, $content);

            msgRF('ǩ����ɣ�');
        }
    }

    /********************************�ֹ��ı��ͬ״̬���� ����ʾ�ã�������Ҫ����޸ķ���״̬��*********************************************************************/
    /**
     * �ֶ��ı��ͬ״̬ (ִ���� ---> �����)
     */
    function c_completeOrder()
    {
        $orderId = $_GET['id'];
        $this->service->completeOrder_d($orderId);
    }

    /**
     * �ֶ��ı��ͬ״̬ (����� --> ִ����)
     */
    function c_exeOrder()
    {
        $orderId = $_GET['id'];
        $this->service->exeOrder_d($orderId);
    }

    /***************************�� �� �� ��********************************************************************************************************/
    /**
     *  ���������б�
     */
    function c_shipments()
    {
        if (isset ($_GET['finish']) && $_GET['finish'] == 1) {
            $this->assign('listJS', 'contract-shipped-grid.js');
        } else {
            $this->assign('listJS', 'contract-shipments-grid.js');
        }
        $this->assign('shipCondition', $_GET['shipCondition']);
        $this->view('shipments-list');
    }

    /**
     *  ����ȷ�������б�
     */
    function c_assignment()
    {
        $this->view('assign-list');
    }

    /**
     *  ���������б�
     */
    function c_shipportal()
    {
        header("Content-type: text/html;charset=gb2312");
        $this->assign('listJS', 'contract-ship-portalgrid.js');
        $this->assign('shipCondition', 0);
        $this->view('ship-portallist');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_shipmentsJson()
    {
        $rateDao = new model_stock_outplan_contractrate();
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;

        if (!empty($this->service->this_limit['��������'])) {
            if (!strstr($this->service->this_limit['��������'], ";;")) {
                $service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(c.areaCode,'" . $this->service->this_limit['��������'] . "')";
            }
            $rows = $service->pageBySqlId("select_shipments");
        } else {
            $rows = "";
        }


        //����������ȱ�ע
        $orderIdArr = array();
        foreach ($rows as $key => $val) {
            $orderIdArr[$key] = $rows[$key]['id'];
        }
        $orderIdStr = implode(',', $orderIdArr);
        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        $rateDao->asc = false;
        $rateArr = $rateDao->list_d();
        //��ȡ�����ƻ����ƻ�����ʱ��
        $sql = "select max(shipPlanDate) as maxDate,docId from oa_stock_outplan where docType='oa_contract_contract' GROUP BY docId";
        $maxShipArr = $service->_db->getArray($sql);

        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $key => $val) {
                $rows[$key]['contractRate'] = "";
                if (is_array($rateArr) && count($rateArr)) {
                    foreach ($rateArr as $index => $value) {
                        if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_contract_contract' == $rateArr[$index]['relDocType']) {
                            $rows[$key]['contractRate'] = $rateArr[$index]['keyword'];
                        }
                    }
                }
                //�ۼ�����
                $rows[$key]['grandDays'] = floor((strtotime(date("Y-m-d")) - strtotime($val['ExaDTOne'])) / 86400);
                //Ԥ����ɽ�������
                foreach ($maxShipArr as $i => $v) {
                    if ($rows[$key]['id'] == $maxShipArr[$i]['docId']) {
                        $rows[$key]['maxShipPlanDate'] = $maxShipArr[$i]['maxDate'];
                    }
                }

            }
        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��ȡ��ҳ����ת��Json -- ����ȷ��
     */
    function c_assignmentJson()
    {
        $rateDao = new model_stock_outplan_assignrate();
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        if (!empty($this->service->this_limit['��������'])) {
            if (!strstr($this->service->this_limit['��������'], ";;")) {
                $service->searchArr['areaCodeSql'] = "sql: and FIND_IN_SET(c.areaCode,'" . $this->service->this_limit['��������'] . "')";
            }
            $service->searchArr['ExaStatusSql'] = "sql: and (c.ExaStatus in ('���','���������') or c.isSubApp='1')";
            $service->searchArr['isSellSql'] = "sql: and (c.isSell='1' or c.isSubApp='1')";
            $rows = $service->pageBySqlId("select_assignment");
        } else {
            $rows = "";
        }

        //����������ȱ�ע
        $orderIdArr = array();
        foreach ($rows as $key => $val) {
            $orderIdArr[$key] = $rows[$key]['id'];
            //�ж�����Ǳ���ĵ��ݣ����Ҳ��滻����ID
            if ($val['isSubAppChange'] == '1') {
                $mid = $this->service->findChangeId($val['id']);
                $rows[$key]['id'] = $mid;
                $rows[$key]['oldId'] = $val['id'];
            }
        }

        $orderIdStr = implode(',', $orderIdArr);
        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        $rateDao->searchArr['relDocType'] = 'oa_contract_contract';
        $rateDao->asc = false;
        $rateArr = $rateDao->list_d();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $key => $val) {
                $rows[$key]['contractRate'] = "";
                if (is_array($rateArr) && count($rateArr)) {
                    foreach ($rateArr as $index => $value) {
                        if ($rows[$key]['id'] == $rateArr[$index]['relDocId'] && 'oa_contract_contract' == $rateArr[$index]['relDocType']) {
                            $rows[$key]['contractRate'] = $rateArr[$index]['keyword'];
                        }
                    }
                }
            }
        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**************************************************�� �� �� ��**********END***********************************************************************/

    /************************************************ ��ͬpageJson ���� *************************************************************************************************/

    /**
     * ��ͬ��Ϣ �б�pageJson
     */
    function c_conPageJson()
    {
        set_time_limit(0);
        $service = $this->service;
        $rows = array();
        $service->getParam($_REQUEST);

        //������Ȩ������
        $limit = $this->service->initLimit();
        if ($limit == true) {
            //			$rows = $service->page_d();
            $rows = $service->pageBySqlId('select_gridinfo');

            if (!empty ($rows)) {
                //��ȡ�б�ע��Ϣ�ĺ�ͬid
                $remarkIsArr = $this->service->getRemarkIs();
                // ��ʼ�������
                $linkmanDao = new model_contract_contract_linkman(); // ��ͬ��ϵ��
                $esmprojectDao = new model_engineering_project_esmproject(); // ������Ŀ
                $regionDao = new model_system_region_region();

                $cidStr = '';
                foreach ($rows as $key => $val) {
                    // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
                    $invoiceCodeArr = explode(",",$val['invoiceCode']);
                    $isNoInvoiceCont = '';
                    foreach ($invoiceCodeArr as $Arrk => $Arrv){
                        if($Arrv == "HTBKP"){
                            $isNoInvoiceCont = '1';
                        }
                    }
                    $rows[$key]['isNoInvoiceCont'] = $isNoInvoiceCont;

                    if (in_array($val['id'], $remarkIsArr)) {
                        $rows[$key]['conflag'] = "1";
                    }
                    //�жϲ����غ�ͬ��ǰ����״̬
                    $exeStautsView = $this->service->exeStatusView_d($val);
                    $rows[$key]['exeStatus'] = $exeStautsView[0];
                    $rows[$key]['exeStatusNum'] = $exeStautsView[1];
                    //������չֵ
                    //��ȡ������չ�ֶ�ֵ
                    $expand = $regionDao->getExpandbyId($val['areaCode']);
                    $rows[$key]['expand'] = $expand;
                    //��ͬ����
//                    $fee = $this->service->getContractFeeAll($val['id']);
//                    $rows[$key]['contractFee'] = $fee;
                    //����ǰ�б��ͬid�ַ���
                    $cidStr .= $val['id'] . ",";

                    // ����ͻ���Ϣ
                    $rs = $linkmanDao->findAll(array('contractId' => $val['id']), null, 'linkmanName,telephone');
                    if (!empty($rs)) {
                        foreach ($rs as $k => $v) {
                            if ($k == 0) {
                                $rows[$key]['linkmanName'] = $v['linkmanName'];
                                $rows[$key]['linkmanTel'] = $v['telephone'];
                            } else {
                                $rows[$key]['linkmanName'] .= PHP_EOL . $v['linkmanName'];
                                $rows[$key]['linkmanTel'] .= PHP_EOL . $v['telephone'];
                            }
                        }
                    }
                    // �����ִͬ�е���Ŀ����
//                    if ($val['contractType'] == 'HTLX-FWHT') {
                        $rs = $esmprojectDao->findAll(array('contractId' => $val['id'], 'contractType' => 'GCXMYD-01'),
                            null, 'managerName');
                        if (!empty($rs)) {
                            foreach ($rs as $k => $v) {
                                if ($k == 0) {
                                    $rows[$key]['esmManagerName'] = $v['managerName'];
                                } else {
                                    $rows[$key]['esmManagerName'] .= ',' . $v['managerName'];
                                }
                            }
                        }
//                    }

                    $rows[$key]['esmManagerName'] = ($rows[$key]['esmManagerName'] == '')? "-" : $rows[$key]['esmManagerName'];

                    //������Ŀ����
                    $rows[$key]['budgetAll'] = $val['proj_budgetAll'];
                    $rows[$key]['curIncome'] = $val['proj_curIncome'];
                    $rows[$key]['feeAll'] = $val['proj_feeAll'];
                    $rows[$key]['conProgress'] = $val['proj_conProgress'];
                    $rows[$key]['gross'] = $val['proj_gross'];
                    $rows[$key]['rateOfGross'] = $val['proj_rateOfGross'];
                    $rows[$key]['comPoint'] = $val['proj_comPoint'];
                    $rows[$key]['icomeMoney'] = $val['proj_icomeMoney'];
                    $rows[$key]['incomeProgress'] = $val['proj_incomeProgress'];
                    $rows[$key]['invoiceProgress'] = $val['proj_invoiceProgress'];

                    $rows[$key]['surplusInvoiceMoney'] = empty($isNoInvoiceCont)? (isset($rows[$key]['surplusInvoiceMoney'])? $rows[$key]['surplusInvoiceMoney'] : 0) : 0;
                }

                //��ͬ��������Ȩ��
                $download = isset ($this->service->this_limit['��ͬ��Ϣ����']) ? $this->service->this_limit['��ͬ��Ϣ����'] : null;
                if ($download == '1') {
                    foreach ($rows as $key => $val) {
                        $rows[$key]['downloadLimit'] = 1;
                    }
                }
                //�����������Ȩ��
                $download = isset ($this->service->this_limit['�����������']) ? $this->service->this_limit['�����������'] : null;
                if ($download == '1') {
                    foreach ($rows as $key => $val) {
                        $rows[$key]['financialDate'] = 1;
                    }
                }
                //�����ܿ���Ȩ��
                $download = isset ($this->service->this_limit['��������']) ? $this->service->this_limit['��������'] : null;
                if ($download == '1') {
                    foreach ($rows as $key => $val) {
                        $rows[$key]['financial'] = 1;
                    }
                }
                //��ȫ��
                $rows = $this->sconfig->md5Rows($rows);
//                //����������
//                $rows = $this->service->projectProcess_d($rows);

                //ͳ�ƽ��
//                $rows = $service->getRowsallMoney_d($rows, "select_contractInfo");
                //��������ΪʲôҪunset�����ȷ�����
                unset($service->searchArr['advSql']);
                //�����ֶι���
                $rows = $this->fieldFilter($rows);
                //��Ʊ����˰��
                $datadictDao = new model_system_datadict_datadict(); //��ȡ�����ֵ���Ϣ
                $rs = $datadictDao->findAll(array('parentCode' => 'KPLX'), null, 'dataCode,expand1');
                if (!empty($rs)) {
                    $invoiceArr = array();
                    foreach ($rs as $v) {
                        $invoiceArr[$v['dataCode']] = $v['expand1'] . '%';
                    }
                    foreach ($rows as $key => $val) {
                        if (!empty($val['invoiceCode'])) {
                            $invoiceCodeArr = explode(',', $val['invoiceCode']);
                            $KPLXSD = array();
                            foreach ($invoiceCodeArr as $v) {
                                array_push($KPLXSD, $invoiceArr[$v]);
                            }
                            $rows[$key]['KPLXSD'] = implode('&', array_unique($KPLXSD));
                        }
                    }
                }
            }
        }

        // PMS 522 ��ͬӦ�տ�����������ô���
        $rows = $this->service->dealSpecRecordsForNoSurincome("rowsMatch",$rows,array("surOrderMoney","icomeMoney","proj_icomeMoney"));

        $arr = array();
        $arr['collection'] = $rows;
        $arr['Sql'] = $service->listSql;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        session_start();
        $_SESSION['advSql'] = $service->advSql;
        //		session.setAttribute("advSql",$service->advSql);
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * �ɱ�ȷ���б�
     */
    function c_costEstimatesJson()
    {
        set_time_limit(0);
        $service = $this->service;

        // �Ƿ�Ϊ�鿴���������ݴ���
        if(isset($_REQUEST['isNoDeal']) && $_REQUEST['isNoDeal'] == "1"){
            $engConfirmCost = isset($_REQUEST['engConfirmCost'])? $_REQUEST['engConfirmCost'] : '';
            $projectStatus = isset($_REQUEST['projectStatus'])? $_REQUEST['projectStatus'] : '';

            if($engConfirmCost == '1' && $projectStatus == '1'){
                $_REQUEST['id'] = "-10";
            }else if($engConfirmCost == '1' && $projectStatus == ''){
                $_REQUEST['projectStatus'] = '0';
            }
        }else{
            unset($_REQUEST['isNoDeal']);
        }

        $service->getParam($_REQUEST);

        //�ɱ�ȷ��Ȩ��
        $costLimit = $this->service->this_limit['�ɱ�ȷ��'];
        $moduleLimit = $this->service->this_limit['���Ȩ��'];
        if (strstr($costLimit, ';;') || strstr($moduleLimit, ";;")) { //Ȩ�޸�Ϊ���ţ�����ȫ�����˴���Ԥ���Է���չ
            $sql = $this->sqlList("1");
            $rows = $service->pageBySql($sql);
        } else {
            if ($costLimit && $moduleLimit) {
                $costLimitArr = explode(",", $costLimit);
                $costimitStr = "sql: and (";
                foreach ($costLimitArr as $k => $v) {
                    if ($k == 0) {
                        $costimitStr .= "FIND_IN_SET('" . $v . "',newProLineStr)";
                    } else {
                        $costimitStr .= "or FIND_IN_SET('" . $v . "',newProLineStr)";
                    }
                }
                $costimitStr .= " or module IN(" . util_jsonUtil::strBuild($moduleLimit) . ")";
                $costimitStr .= ")";
                /////////
                $costLimitStr = "";
                foreach ($costLimitArr as $k => $v) {
                    if ($k == 0) {
                        $costLimitStr .= "productLine = '" . $v . "'";
                    } else {
                        $costLimitStr .= "or productLine = '" . $v . "'";
                    }
                }
                $service->searchArr['mySearchCondition'] = $costimitStr;
                $sql = $this->sqlList($costLimitStr);
                $rows = $service->pageBySql($sql);
            } else if ($costLimit && !$moduleLimit) {
                $costLimitArr = explode(",", $costLimit);
                $costimitStr = "sql: and (";
                foreach ($costLimitArr as $k => $v) {
                    if ($k == 0) {
                        $costimitStr .= "FIND_IN_SET('" . $v . "',newProLineStr)";
                    } else {
                        $costimitStr .= "or FIND_IN_SET('" . $v . "',newProLineStr)";
                    }
                }
                $costimitStr .= ")";
                /////////
                $costLimitStr = "";
                foreach ($costLimitArr as $k => $v) {
                    if ($k == 0) {
                        $costLimitStr .= "productLine = '" . $v . "'";
                    } else {
                        $costLimitStr .= "or productLine = '" . $v . "'";
                    }
                }
                $service->searchArr['mySearchCondition'] = $costimitStr;
                $sql = $this->sqlList($costLimitStr);
                $rows = $service->pageBySql($sql);
            } else if (!$costLimit && $moduleLimit) {
                $costimitStr = "sql: and (";
                $costimitStr .= "module IN(" . util_jsonUtil::strBuild($moduleLimit) . ")";
                $costimitStr .= ")";
                $service->searchArr['mySearchCondition'] = $costimitStr;
                $sql = $this->sqlList(1);
                $rows = $service->pageBySql($sql);
            } else {
                $rows = "";
            }
        }

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    //�ɱ�ȷ���б�Ĳ�ѯsql
    function sqlList($costLimitStr)
    {
        $sql = "
            SELECT
                c.*,if(FIND_IN_SET('1', costState) or c.engConfirm=1,1,0) as engConfirmCost
            @FROM
                oa_contract_contract c
            left join
            (
                select
                  GROUP_CONCAT(CAST(ExaState AS char)) as costState,contractId
                from oa_contract_cost
                where ($costLimitStr) and issale=0
                GROUP BY contractId
            )cc
            on c.id=cc.contractId

            WHERE
                1=1 and (FIND_IN_SET(17,goodsTypeStr) or FIND_IN_SET(18,goodsTypeStr))";
        return $sql;
    }

    //�ҵĺ�ͬPagejson
    function c_MyconPageJson()
    {
        $service = $this->service;
        $rows = array();
        //������Ȩ������
        $limit = true;
        $service->getParam($_REQUEST);
        //����һ�������������Ҹ���ĺ�ͬ����ֻ���˺�ͬ������Ϊ��½�˵ĺ�ͬ
//        if (isset($_REQUEST['myContract']) && $_REQUEST['myContract'] == '1') {
            $service->searchArr['prinvipalOrCreateId'] = $_SESSION['USER_ID'];// PMS2537 Ĭ���ҵĺ�ͬ����ֻ��ʾ�û�������ĺ�ͬ; ���� PMS 566������˺�ͬ¼����
//        }
        //ȡ�������ڴӴ���������ת����
        if ($_REQUEST['todo'] == '1') {
            $sql = "SELECT
						*
					FROM
						(
							SELECT
								c.*
							FROM
								oa_contract_contract c
							LEFT JOIN oa_contract_cost t ON c.id = t.contractId
							WHERE
								t.state = '3'
							AND c.isSubAppChange = 0
							GROUP BY
								c.id
							UNION ALL
								SELECT
									c.*
								FROM
									oa_contract_contract c
								LEFT JOIN (SELECT max(id) AS Mid,originalId FROM oa_contract_contract GROUP BY originalId) AS c1 ON c.id = c1.originalId
								LEFT JOIN oa_contract_cost t ON c1.Mid = t.contractId
								WHERE
									c.state = '3'
								AND c.isSubAppChange = 1
								GROUP BY
									c.id
							UNION ALL
								SELECT
									*
								FROM
									oa_contract_contract
								WHERE
									dealStatus = '4'
						) c ";
            //ƴװ��������
            $where = $service->createQuery("", $service->searchArr);
            $where .= " AND (c.prinvipalId = '" . $_SESSION['USER_ID'] . "' or c.createId = '" . $_SESSION['USER_ID'] . "');";
            $sql .= $where;
            $rows = $service->_db->getArray($sql);
        } else {
            //��ʡ�������ݹ��ˣ� ���ڰ汾���� ��ʱ����  Tday �ֶ�
            $userId = $_SESSION['USER_ID'];
//         	$fsql="select provinceId,customerType from oa_system_saleperson where personId = '".$userId."'";
            $fsql = "select group_concat(CAST(salesAreaId AS CHAR)) as salesAreaId from oa_system_saleperson where personId = '" . $userId . "'";
            $proLimitArr = $service->_db->getArray($fsql);
//         	$provinceStr = $proLimitArr[0]['provinceId'];
//         	$customerTypeStr = $proLimitArr[0]['customerType'];
            $salesAreaId = $proLimitArr[0]['salesAreaId'];
            if (!empty($salesAreaId)) {
                $salesAreaStr = '';
                $salesAreaArr = explode(",", $salesAreaId);
                foreach ($salesAreaArr as $v) {
                    $salesAreaStr .= "'$v'" . ",";
                }
                $salesAreaStr = rtrim($salesAreaStr, ",");
//         		$conditionProSql = "sql: and (c.prinvipalId='".$_SESSION['USER_ID']."' or c.areaPrincipalId = '".$_SESSION['USER_ID']."'  or (c.contractProvinceId = ".$provinceStr." and c.customerType in (".$customerTypeStr.")))";
                $conditionProSql = "sql: and (c.createId='" . $_SESSION['USER_ID'] . "' or c.prinvipalId='" . $_SESSION['USER_ID'] . "' or c.areaPrincipalId = '" . $_SESSION['USER_ID'] . "' or c.areaCode in(" . $salesAreaStr . "))";
                $service->searchArr['Tday'] = $conditionProSql;
            } else {
                if ($_SESSION['DEPT_NAME'] == '����ҵ����') {
                    $conditionProSql = "sql: and (c.createId='" . $_SESSION['USER_ID'] . "' or c.prinvipalId='" . $_SESSION['USER_ID'] . "' or c.areaPrincipalId = '" . $_SESSION['USER_ID'] . "'  )";
                } else {
                    $conditionProSql = "sql: and (c.createId='" . $_SESSION['USER_ID'] . "' or c.prinvipalId='" . $_SESSION['USER_ID'] . "' or c.areaPrincipalId = '" . $_SESSION['USER_ID'] . "'  )";
                }
                $service->searchArr['Tday'] = $conditionProSql;
            }
        }
        if ($limit == true) {
            //			$rows = $service->page_d();
            if ($_REQUEST['todo'] == '0') {
                $rows = $service->pageBySqlId('select_gridinfo');
            }

            if (!empty ($rows)) {
                //��ȡ�б�ע��Ϣ�ĺ�ͬid
                $remarkIsArr = $this->service->getRemarkIs();
                foreach ($rows as $key => $val) {
                    if (in_array($val['id'], $remarkIsArr)) {
                        $rows[$key]['flag'] = "1";
                    }
                    //�жϲ����غ�ͬ��ǰ����״̬
                    $exeStautsView = $this->service->exeStatusView_d($val);
                    $rows[$key]['exeStatus'] = $exeStautsView[0];
                    $rows[$key]['exeStatusNum'] = $exeStautsView[1];
                    //������չֵ
                    //��ȡ������չ�ֶ�ֵ
                    $regionDao = new model_system_region_region();
                    $expand = $regionDao->getExpandbyId($val['areaCode']);
                    $rows[$key]['expand'] = $expand;
                    //�ж��Ƿ���Ҫȷ�Ϸ�������
                    $confirmEqu = $this->service->getConfirmEqubyId($val['id'], $val['isSubAppChange']);
                    $rows[$key]['confirmEqu'] = $confirmEqu;


                }
                //��ȫ��
                $rows = $this->sconfig->md5Rows($rows);
                //                //����������
                //                $rows = $this->service->projectProcess_d($rows);
                //ͳ�ƽ��
                if ($_REQUEST['todo'] == '0') {
                    $rows = $service->getRowsallMoney_d($rows, "select_contractInfo",1);
                }
                //�����ֶι���
                //$rows = $this->fieldFilter($rows);
            }
        }

        // PMS 522 ��ͬӦ�տ�����������ô���
        $rows = $this->service->dealSpecRecordsForNoSurincome("rowsMatch",$rows,array("surOrderMoney"));

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��ͬ�����ֶι��� - ���
     */
    function fieldFilter($rows)
    {
        foreach ($rows as $key => $val) {
            //��ȡ��ͬ�����ˣ�����ͨ�����������/Ĭ�ϻ��е�һ�������ˣ�
            $appArr = explode(",", $val['appNameStr']);
            if (!in_array($_SESSION['USER_ID'], $appArr) && $val['areaPrincipalId'] != $_SESSION['USER_ID'] && $val['prinvipalId'] != $_SESSION['USER_ID']) {
                $rows[$key] = $this->service->filterWithoutField('��ͬ���', $rows[$key], 'keyForm', array(
                    'contractMoney',
                    'contractTempMoney',
                    'deductMoney',
                    'badMoney'
                ));
                $rows[$key] = $this->service->filterWithoutField('��ͬ���-����', $rows[$key], 'keyForm', array(
                    'exgross',
                    'costEstimates',
                    'costEstimatesTax'
                ));

                $rows[$key] = $this->service->filterWithoutField('��Ʊ��Ϣ', $rows[$key], 'keyForm', array(
                    'invoiceApplyMoney',
                    'invoiceMoney',
                    'uninvoiceMoney',
                    'softMoney',
                    'hardMoney',
                    'repairMoney',
                    'serviceMoney',
                    'surplusInvoiceMoney'
                ));
                $rows[$key] = $this->service->filterWithoutField('������Ϣ', $rows[$key], 'keyForm', array(
                    'incomeMoney'
                ));
                $rows[$key] = $this->service->filterWithoutField('������', $rows[$key], 'keyForm', array(
                    'serviceconfirmMoneyAll',
                    'financeconfirmMoneyAll',
                    'financeconfirmPlan',
                    'gross',
                    'rateOfGross',
                    'surOrderMoney',
                    'surincomeMoney'
                ));
            }
        }
        return $rows;
    }

    /************************************************ ��ͬpageJson ���� ******END********************************************************************************************/
    /*----------------------------start:�ִ�ӿ�----------------------------------*/
    /**
     * ������Ʒ���ʱ�����������嵥ģ��
     */
    function c_getItemListAtRkProduct()
    {
        $contractId = isset ($_POST['contractId']) ? $_POST['contractId'] : null;
        $rows = $this->service->getContractInfo($contractId, array(
            "equ"
        ));
        // k3������ش���
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $listStr = $this->service->showProItemAtRkProduct($rows);
        echo util_jsonUtil :: iconvGB2UTF($listStr);
    }

    /**
     * �������۳���ʱ�����������嵥ģ��
     */
    function c_getItemListAtCkSales()
    {
        $contractId = isset ($_POST['contractId']) ? $_POST['contractId'] : null;
        $rows = $this->service->getContractInfo($contractId, array(
            "equ"
        ));
        // k3������ش���
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $listStr = $this->service->showProItemAtCkSales($rows);
        echo util_jsonUtil :: iconvGB2UTF($listStr);
    }

    /*----------------------------start:�ִ�ӿ�----------------------------------*/

    /*************************************�豸�ܻ�� start **************************************/
    /**
     * ��ͬ�����豸-�ƻ�ͳ���б�
     */
    function c_shipEquList()
    {
        $equNo = isset ($_GET['productCode']) ? $_GET['productCode'] : "";
        $equName = isset ($_GET['productName']) ? $_GET['productName'] : "";
        $searchArr = array();
        if ($equNo != "") {
            $searchArr['productCode'] = $equNo;
        }
        if ($equName != "") {
            $searchArr['productName'] = $equName;
        }
        $service = $this->service;
        $service->getParam($_GET);
        $service->__SET('searchArr', $searchArr);
        $service->__SET('groupBy', "p.productId,p.productNumb");

        $rows = $service->pageEqu_d();
        $this->pageShowAssign();

        $this->assign('equNumb', $equNo);
        $this->assign('equName', $equName);
        $this->assign('list', $this->service->showEqulist_s($rows));
        $this->display('list-equ');
        unset ($this->show);
        unset ($service);
    }

    /***********************************�豸�ܻ�� end *********************************/

    /***************************�������************************************************************/
    /**
     * �������Ȩ��
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * ������ ����
     */
    function c_FinancialImportexcel()
    {
        $this->assign("dateTime", date("Y-m-d"));
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= $thisYear - 5; $i--) {
            $yearStr .= "<option value=$i>" . $i . "��</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->display("FinancialImportexcel");
    }

    /**
     * �ϴ�EXCEL
     */
    function c_finalceMoneyImport()
    {
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        $import = $_POST['import'];
        //		$objNameArr = array (
        //			0 => 'contractType',//��ͬ����
        //			1 => 'contractCode', //��ͬ���
        //			2 => 'serviceconfirmMoney', //����ȷ������
        //			3 => 'financeconfirmMoney', //����ȷ���ܳɱ�
        //			4 => 'deductMoney'//�ۿ���
        //		   ) ;
        $objNameArr = array(
            0 => 'contractCode', //��ͬ���
            1 => 'money', //���
        );

        if ($import['importType'] == "��ʼ������") {
            $this->service->addFinalceMoneyExecel_d($objNameArr, $import['importInfo'], $import['normType']);
        } else {
            //������Ϣ ����
            $infoArr = array(
                "importMonth" => $import['Year'] . $import['Month'], //�����·�
                "moneyType" => $import['importInfo'], //�������
                "importName" => $_SESSION['USERNAME'], //������
                "importNameId" => $_SESSION['USER_ID'], //������ID
                "importDate" => date("Y-m-d"), //����ʱ��
            );
            $this->service->addFinalceMoneyExecelAlone_d($objNameArr, $infoArr, $import['normType']);
        }
    }

    /**
     * �ֹ����� �޸Ĳ������� ��������ֵ����
     */
    function c_handleupdateGross()
    {
        $id = $_GET['contractId'];
        $this->service->updateGross($id);
    }

    /**
     * ��֤����Ľ���Ƿ��Ѵ���
     */
    function c_getFimancialImport()
    {
        $importType = util_jsonUtil :: iconvUTF2GB($_POST['importType']);
        $importMonth = util_jsonUtil :: iconvUTF2GB($_POST['importMonth']);
        $importSub = util_jsonUtil :: iconvUTF2GB($_POST['importSub']);

        if ($importType == "���µ���") {
            $num = $this->service->getFimancialImport_d($importMonth, $importSub);
            echo $num;
        } else {
            echo "0";
        }
    }

    /**
     * ��������ϸ�б�Tab
     */
    function c_financialDetailTab()
    {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialDetailTab");
    }

    /**
     * ��������Ϣ
     */
    function c_financialDetail()
    {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialDetail");
    }

    function c_financialdetailpageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $rows = $service->getFinancialDetailInfo($conId, $tablename, $moneyType);
        //echo "<pre>";
        //print_R($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��������Ϣ
     */
    function c_financialImportDetail()
    {
        $this->assign("conId", $_GET['id']);
        $this->assign("tablename", $_GET['tablename']);
        $this->assign("moneyType", $_GET['moneyType']);
        $this->display("financialImportDetail");
    }

    function c_financialImportDetailpageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $sql = $service->getFinancialImportDetailInfo($conId, $tablename, $moneyType);
        $service->sort = false;
        $rows = $service->pageBySql($sql);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ������ͳ�Ʊ�
     */
    function c_financialStatistics()
    {
        $this->view("financialStatistics");
    }

    function c_financialStatisticspageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $conId = $_GET['id'];
        $tablename = $_GET['tablename'];
        $moneyType = $_GET['moneyType'];
        $rows = $service->getfinancialStatistics($conId, $tablename, $moneyType);
        $rows = $service->getfinancialStatisticsInitMoney($rows);
        //echo "<pre>";
        //print_R($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /***************************************************************************************/

    /**
     * ��ת����ͬͳ��ҳ��
     */
    function c_toCountContract()
    {
        header("Content-type: text/html;charset=gb2312");
        $this->view("count");
    }

    /**
     * ͳ�ƺ�ͬ��Ϣ
     */
    function c_countContract()
    {
        $arr = array();
        //��������ĺ�ͬ����
        $lastAddNum = $this->service->getLastAddContractNum();
        $arr['lastAddNum'] = $lastAddNum;
        //��������ĺ�ͬ����
        $lastChangeNum = $this->service->getLastChangeContractNum();
        $arr['lastChangeNum'] = $lastChangeNum;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��ת����ͬǩԼͳ��ҳ��
     */
    function c_toCountSignContract()
    {
        header("Content-type: text/html;charset=gb2312");
        $this->view("signcount");
    }

    /**
     * ͳ����ǩԼδ��ɺ�ͬ����
     */
    function c_countSignContract()
    {
        $arr = array();
        //ǩԼһ����
        $oneMonthNum = $this->service->getMonthContractNum(1);
        $arr['oneMonthNum'] = $oneMonthNum;
        //ǩԼ������
        $twoMonthNum = $this->service->getMonthContractNum(2);
        $arr['twoMonthNum'] = $twoMonthNum;
        //ǩԼ������
        $threeMonthNum = $this->service->getMonthContractNum(3);
        $arr['threeMonthNum'] = $threeMonthNum;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��ִͬ��״̬��
     */
    function c_contractExelist()
    {
        //��ͬ״̬����
        $stateArr = array(
            "0" => "δ�ύ",
            "1" => "������",
            "2" => "ִ����",
            "3" => "�ѹر�",
            "4" => "�����",
            "5" => "�Ѻϲ�",
            "6" => "�Ѳ��",
            "7" => "�쳣�ر�",
        );
        $this->service->searchArr['id'] = $_GET['contractId'];
        $this->assign('contractId', $_GET['contractId']);
        $objArr = $this->service->list_d('select_gridinfo');
        $obj = $objArr[0];
        $objState = $obj['state'];

        // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
        $invoiceCodeArr = explode(",",$obj['invoiceCode']);
        $isNoInvoiceCont = false;
        foreach ($invoiceCodeArr as $Arrk => $Arrv){
            if($Arrv == "HTBKP"){
                $isNoInvoiceCont = true;
            }
        }

        //������Ⱦ
        $this->assignFunc($obj);
        $this->assign('state', $stateArr[$objState]);
        $this->assign('isNoInvoiceCont', ($isNoInvoiceCont)? "1" : "");

        // PMS 522 ��ͬӦ�տ�����������ô���
        $SpecRecordsForNoSurincomeArr = $this->service->dealSpecRecordsForNoSurincome();
        $isNoSurincomeMoney = (in_array($obj['customerType'],$SpecRecordsForNoSurincomeArr) || $obj['state']== 7)? 'y' : 'n';
        $surincomeMoney = (in_array($obj['customerType'],$SpecRecordsForNoSurincomeArr) || $obj['state']== 7)? 0 : ($obj['invoiceMoney'] - $obj['incomeMoney']);
        $this->assign('isNoSurincomeMoney', $isNoSurincomeMoney); //����Ӧ�տ�

        //����ȷ����Ϣ
        $this->assign('surincomeMoney', $surincomeMoney); //����Ӧ�տ�
        if ($obj['serviceconfirmMoneyAll'] != '0') {
            $financeconfirmPlanNum = ($obj['serviceconfirmMoneyAll'] / $obj['contractMoney']) * 100;
            $financeconfirmPlanNum = round($financeconfirmPlanNum, 2) . "%";
            $this->assign('financeconfirmPlan', $financeconfirmPlanNum); //����ȷ�Ͻ���
        } else {
            $this->assign('financeconfirmPlan', ""); //����ȷ�Ͻ���
        }
        $this->assign('gross', $obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']); //ë��
        if (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) != 0) {
            $rateGross = (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) / $obj['serviceconfirmMoneyAll']) * 100;
            $rateGross = round($rateGross, 2) . "%";
            $this->assign('rateOfGross', $rateGross);
        } else {
            $this->assign('rateOfGross', "");
        }
        //��ͬ����
        $this->assign('conFee',$this->service->getConFeeByid($obj['id']));
        //�ۺ�˰��
        $this->assign('cRate', $this->service->getTxaRate($obj)*100);
        $this->assign('cRateNum', $this->service->getTxaRate($obj));
        //˰���ͬ��
        $this->assign('contractMoneyRate', round($obj['contractMoney']/(1+$this->service->getTxaRate($obj)),2));
        //��ͬ�Լ����
        $objCom = $this->service->objCom_d($obj);
        if($isNoInvoiceCont){
            $objCom['invoiceCheck'] = '<img src="images/icon/heng.png">';
            $objCom['invoiceCheck_t'] = '<img src="images/icon/heng.png">';
        }
        $this->assignFunc($objCom);
        $this->view("contractexelist");
    }



    function c_contractExelistOld()
    {
        //��ͬ״̬����
        $stateArr = array(
            "0" => "δ�ύ",
            "1" => "������",
            "2" => "ִ����",
            "3" => "�ѹر�",
            "4" => "�����",
            "5" => "�Ѻϲ�",
            "6" => "�Ѳ��",
            "7" => "�쳣�ر�",
        );
        $this->service->searchArr['id'] = $_GET['contractId'];
        $this->assign('contractId', $_GET['contractId']);
        $objArr = $this->service->list_d('select_gridinfo');
        $obj = $objArr[0];
        $objState = $obj['state'];
        //������Ⱦ
        $this->assignFunc($obj);
        $this->assign('state', $stateArr[$objState]);
        $this->assign('surOrderMoney', $obj['contractMoney'] - $obj['incomeMoney']); //��ͬӦ�տ�
        $this->assign('surincomeMoney', $obj['invoiceMoney'] - $obj['incomeMoney']); //����Ӧ�տ�
        if ($obj['serviceconfirmMoneyAll'] != '0') {
            $financeconfirmPlanNum = ($obj['serviceconfirmMoneyAll'] / $obj['contractMoney']) * 100;
            $financeconfirmPlanNum = round($financeconfirmPlanNum, 2) . "%";
            $this->assign('financeconfirmPlan', $financeconfirmPlanNum); //����ȷ�Ͻ���
        } else {
            $this->assign('financeconfirmPlan', ""); //����ȷ�Ͻ���
        }
        $this->assign('gross', $obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']); //ë��
        if (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) != 0) {
            $rateGross = (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) / $obj['serviceconfirmMoneyAll']) * 100;
            $rateGross = round($rateGross, 2) . "%";
            $this->assign('rateOfGross', $rateGross);
        } else {
            $this->assign('rateOfGross', "");
        }

        //�������
        $equDao = new model_contract_contract_equ();
        $equlist = $equDao->exeEqulist($obj['id']);
        $this->assign('equlist', $equlist);
        $this->view("contractexelistOld");
    }

    //����ٷֱ�
    function getProportion($proMoney, $conMoney)
    {
        $exGrossTemp = bcdiv($proMoney, $conMoney, 4);
        $exGross = bcmul($exGrossTemp, '100', 2);
        return $exGross;
    }

    /**
     * ��ȡ��ͬ����켣
     */
    function c_getContractTracks()
    {
        $list = $this->service->getContractTracks($_GET['contractId']);
        echo util_jsonUtil :: encode($list);
    }

    /***********************************��������portlet*****************************/

    /**
     * ��ת����ͬͳ��ҳ��
     */
    function c_toCountShip()
    {
        header("Content-type: text/html;charset=gb2312");
        $planModel = new model_stock_outplan_outplan();
        $showPageShip = $this->service->showCountShipPage_d();
        $showPageOut = $planModel->showCountShipPage_d();
        $this->assign('showPageShip', $showPageShip);
        $this->assign('showPageOut', $showPageOut);
        $this->view("ship-count");
    }

    /**
     * ͳ�ƺ�ͬ��Ϣ
     */
    function c_countShip()
    {
        $arr = array();
        //��������ĺ�ͬ����
        $lastAddNum = $this->service->getNotRunShipNum();
        $arr['notRunShipNum'] = $lastAddNum;
        //��������ĺ�ͬ����
        $lastChangeNum = $this->service->getRunningShipNum();
        $arr['runningShipNum'] = $lastChangeNum;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��ӵȼ�����
     */
    function c_setGrade()
    {
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view("grade");
    }

    /**
     * ���б����Ϣ���õ�Session��ȥ PMS 2532
     *  -- ����Ҫ���͵���Ϣ����ʱʹ��,����IE���������ʱ��,��Ϊ����URL�Ĳ�����������ҳ���޷�����
     */
    function c_setColInfoToSession()
    {
        $_REQUEST = util_jsonUtil::iconvUTF2GBArr($_REQUEST);
        $ColId = isset($_REQUEST['ColId'])? $_REQUEST['ColId'] : '';
        $ColName = isset($_REQUEST['ColName'])? $_REQUEST['ColName'] : '';
        $stype = isset($_REQUEST['sType'])? $_REQUEST['sType'] : 'exportContract';

        $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = '{$stype}';");
        if($records){// ��������������ؼ�¼
            // $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = '{$stype}';");
            $this->service->_db->query("UPDATE oa_system_session_records SET svalue = '{$ColId}' where userId = '{$_SESSION['USER_ID']}' and skey = 'ColId' and stype = '{$stype}';");
            $this->service->_db->query("UPDATE oa_system_session_records SET svalue = '{$ColName}' where userId = '{$_SESSION['USER_ID']}' and skey = 'ColName' and stype = '{$stype}';");
        }else{
            $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = '{$stype}', skey = 'ColId', svalue = '{$ColId}';");
            $this->service->_db->query("INSERT INTO oa_system_session_records SET userId = '{$_SESSION['USER_ID']}', stype = '{$stype}', skey = 'ColName', svalue = '{$ColName}';");
        }
        echo 1;
    }

    /**
     * ����ͬ����
     * @author zengzx
     */
    function c_exportExcel()
    {
        $service = $this->service;
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        $stateArr = array(
            "0" => "δ�ύ",
            "1" => "������",
            "2" => "ִ����",
            "3" => "�ѹر�",
            "4" => "�����",
            "5" => "�Ѻϲ�",
            "6" => "�Ѳ��",
            "7" => "�쳣�ر�",
        );
        $signStatus = array(
            '0' => 'δǩ��',
            '1' => '��ǩ��',
            '2' => '���δǩ��',
        );

        if(!isset($_GET['colId']) && !isset($_GET['colName'])){// ���ǰ��û�����Ӧ����ID�Լ�����,��SESSION�л�ȡ
            $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'exportContract';");
            if($records){
                foreach ($records as $record){
                    if(isset($record['skey']) && $record['skey'] == 'ColId'){
                        $colIdStr = $record['svalue'];
                    }else if(isset($record['skey']) && $record['skey'] == 'ColName'){
                        $colNameStr = $record['svalue'];
                    }
                }
                // $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'exportContract';");
            }else{
                $colIdStr = '';
                $colNameStr = '';
            }
        }else{
            $colIdStr = $_GET['colId'];
            $colNameStr = $_GET['colName'];
        }

        $searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
        $searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
        $searchArr[$searchConditionKey] = $searchConditionVal;
        if (isset($_SESSION['advSql'])) {
            $_REQUEST['advSql'] = $_SESSION['advSql'];
        }
        $service->getParam($_REQUEST);
        //��¼��
//         $appId = $_SESSION['USER_ID'];
        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);
        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        ini_set('memory_limit', '1024M');
        $rows = $service->listBySqlId('select_gridinfo');
//        if (!empty ($rows)) {
//            //ͳ�ƽ��
//            $rows = $service->getRowsallMoney_d($rows, "select_contractInfo");
//            if (isset($service->searchArr['advSql'])) {
//                unset($service->searchArr['advSql']);
//            }
//            //�����ֶι���
//            $rows = $this->fieldFilter($rows);
//        }

        $arr = array();
        $arr['collection'] = $rows;

        //ƥ�䵼����
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);
        // ��ʼ�������
        $linkmanDao = new model_contract_contract_linkman(); // ��ͬ��ϵ��
        $esmprojectDao = new model_engineering_project_esmproject(); // ������Ŀ
        //��Ʊ����˰��
        $datadictDao = new model_system_datadict_datadict(); //��ȡ�����ֵ���Ϣ
        $rs = $datadictDao->findAll(array('parentCode' => 'KPLX'), null, 'dataCode,expand1');
        if (!empty($rs)) {
            $invoiceArr = array();
            foreach ($rs as $v) {
                $invoiceArr[$v['dataCode']] = $v['expand1'] . '%';
            }
        }

        // PMS 522 ��ͬӦ�տ�����������ô���
        $rows = $this->service->dealSpecRecordsForNoSurincome("rowsMatch",$rows,array("surOrderMoney","icomeMoney","proj_icomeMoney"));

        foreach ($rows as $key => $row) {
            //������Ŀ����
            $rows[$key]['budgetAll'] = $row['proj_budgetAll'];
            $rows[$key]['curIncome'] = $row['proj_curIncome'];
            $rows[$key]['feeAll'] = $row['proj_feeAll'];
            $rows[$key]['conProgress'] = bcdiv($row['proj_conProgress'],100,4);
            $rows[$key]['conProgress'] = sprintf("%.4f",$rows[$key]['conProgress']);
            $rows[$key]['gross'] = $row['proj_gross'];
            $rows[$key]['rateOfGross'] = $row['proj_rateOfGross'];
            $rows[$key]['comPoint'] = sprintf("%.4f",$row['proj_comPoint']);
            $rows[$key]['icomeMoney'] = $row['proj_icomeMoney'];
            $rows[$key]['incomeProgress'] = $row['proj_incomeProgress'];
            $rows[$key]['invoiceProgress'] = $row['proj_invoiceProgress'];

            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $rows[$key][$index];
            }
            if(isset($colArr['state']) && !empty($colArr['state'])){
                $colIdArr['state'] = isset($stateArr[$row['state']]) ? $stateArr[$row['state']] : '';
            }
            if(isset($colArr['signStatus']) && !empty($colArr['signStatus'])) {
                $colIdArr['signStatus'] = isset($signStatus[$row['signStatus']]) ? $signStatus[$row['signStatus']] : '';
            }
            if(isset($colArr['signSubject']) && !empty($colArr['signSubject'])) {
                $colIdArr['signSubject'] = empty($row['signSubjectName']) ? '' : $row['signSubjectName'];
            }
            if( $row['id'] != ''){
                if(isset($colArr['isNeedStamp']) && !empty($colArr['isNeedStamp'])){
                    if ($row['isNeedStamp'] == '1') {
                        $colIdArr['isNeedStamp'] = '��';
                    } else {
                        $colIdArr['isNeedStamp'] = '��';
                    }
                }

                if(isset($colArr['isRenewed']) && !empty($colArr['isRenewed'])) {
                    if ($row['isRenewed'] == '1') {
                        $colIdArr['isRenewed'] = '��';
                    } else {
                        $colIdArr['isRenewed'] = '��';
                    }
                }

                if (isset($colIdArr['isAcquiring']) && !empty($row['isAcquiring'])) {
                    if ($row['isAcquiring'] == 1) {
                        $colIdArr['isAcquiring'] = '���յ�';
                    } else {
                        $colIdArr['isAcquiring'] = 'δ�յ�';
                    }
                }
            }

            // ������Ϣ  By weijb 2015.11.09
            $colIdArr['contractTimeInterval'] = '';
            $colIdArr['contractResume'] = '';
            $colIdArr['linkmanName'] = '';
            $colIdArr['linkmanTel'] = '';
            $colIdArr['esmManagerName'] = '';

            if(isset($colArr['linkmanName']) || isset($colArr['linkmanTel'])){
                // ����ͻ���Ϣ
                $rs = $linkmanDao->findAll(array('contractId' => $row['id']), null, 'linkmanName,telephone');
                if (!empty($rs)) {
                    foreach ($rs as $k => $v) {
                        if ($k == 0) {
                            $colIdArr['linkmanName'] = $v['linkmanName'];
                            $colIdArr['linkmanTel'] = $v['telephone'];
                        } else {
                            $colIdArr['linkmanName'] .= PHP_EOL . $v['linkmanName'];
                            $colIdArr['linkmanTel'] .= PHP_EOL . $v['telephone'];
                        }
                    }
                }
            }

            // �����ִͬ�е���Ŀ����
//            if ($row['contractType'] == 'HTLX-FWHT') {
            if(isset($colArr['esmManagerName'])){
                $rs = $esmprojectDao->findAll(array('contractId' => $row['id'], 'contractType' => 'GCXMYD-01'),
                    null, 'managerName');
                if (!empty($rs)) {
                    foreach ($rs as $k => $v) {
                        if ($k == 0) {
                            $colIdArr['esmManagerName'] = $v['managerName'];
                        } else {
                            $colIdArr['esmManagerName'] .= ',' . $v['managerName'];
                        }
                    }
                }
//            }

                $colIdArr['esmManagerName'] = ($colIdArr['esmManagerName'] == '')? "-" : $colIdArr['esmManagerName'];

            }

            if (isset($colIdArr['surplusInvoiceMoney']) && !empty($row['surplusInvoiceMoney'])) {
                // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
                $invoiceCodeArr = explode(",",$row['invoiceCode']);
                $isNoInvoiceCont = '';
                foreach ($invoiceCodeArr as $Arrk => $Arrv){
                    if($Arrv == "HTBKP"){
                        $isNoInvoiceCont = '1';
                    }
                }
                $colIdArr['surplusInvoiceMoney'] = empty($isNoInvoiceCont)? (isset($rows[$key]['surplusInvoiceMoney'])? $rows[$key]['surplusInvoiceMoney'] : 0) : 0;
            }

            //��Ʊ����˰��
            if(isset($colArr['KPLXSD'])){
                if (isset($invoiceArr) && !empty($row['invoiceCode'])) {
                    $invoiceCodeArr = explode(',', $row['invoiceCode']);
                    $KPLXSD = array();
                    foreach ($invoiceCodeArr as $v) {
                        array_push($KPLXSD, $invoiceArr[$v]);
                    }
                    $colIdArr['KPLXSD'] = implode('&', array_unique($KPLXSD));
                }
            }
            array_push($dataArr, $colIdArr);
        }

        if (isset($_GET['CSV'])) {
            return model_contract_common_contExcelUtil::exportCSV($colArr, $dataArr, '�б���Ϣ');
        } else {
            return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr);
        }
    }

    /**
     * �Զ���߼�����
     */
    function c_search()
    {
        $this->assign("gridName", $_GET['gridName']);
        $this->display('search');
    }

    /**
     * ��ͬ���� - ��ͬǩ��-��ͬ��Ϣ����
     */
    function c_singInExportExcel()
    {
        $service = $this->service;
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        $stateArr = array(
            "0" => "δ�ύ",
            "1" => "������",
            "2" => "ִ����",
            "3" => "�ѹر�",
            "4" => "�����",
            "5" => "�Ѻϲ�",
            "6" => "�Ѳ��",
            "7" => "�쳣�ر�",
        );
        $signStatus = array(
            '0' => 'δǩ��',
            '1' => '��ǩ��',
            '2' => '���δǩ��',
        );
        $isAcquiringArr = array(
            '0' => 'δ�յ�',
            '1' => '���յ�'
        );
        $isTempArr = array(
            '0' => '��',
            '1' => '��'
        );

        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $state = $_GET['state'];
        $ExaStatus = $_GET['ExaStatus'];
        if (empty($ExaStatus)) {
            $ExaStatus = "���";
        }
        $contractType = $_GET['contractType'];
        $beginDate = $_GET['beginDate']; //��ʼʱ��
        $endDate = $_GET['endDate']; //��ֹʱ��
        $ExaDT = $_GET['ExaDT']; //����ʱ��
        $areaNameArr = $_GET['areaNameArr']; //��������
        $orderCodeOrTempSearch = $_GET['orderCodeOrTempSearch']; //��ͬ���
        $prinvipalName = $_GET['prinvipalName']; //��ͬ������
        $customerName = $_GET['customerName']; //�ͻ�����
        $customerType = $_GET['customerType']; //�ͻ�����
        $orderNatureArr = $_GET['orderNatureArr']; //��ͬ����
        $DeliveryStatusArr = $_GET['DeliveryStatusArr'];
        $signIn = $_GET['signStatusArr'];
        $searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
        $searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
        //��¼��
        $appId = $_SESSION['USER_ID'];
        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);
        $searchArr['state'] = $state;
        $searchArr['ExaStatus'] = $ExaStatus;
        $searchArr['contractType'] = $contractType;
        $searchArr['beginDate'] = $beginDate; //��ʼʱ��
        $searchArr['endDate'] = $endDate; //��ֹʱ��
        $searchArr['ExaDT'] = $ExaDT; //����ʱ��
        $searchArr['areaNameArr'] = $areaNameArr; //��������
        $searchArr['contractCode'] = $orderCodeOrTempSearch; //��ͬ���
        $searchArr['prinvipalName'] = $prinvipalName; //��ͬ������
        $searchArr['customerName'] = $customerName; //�ͻ�����
        $searchArr['customerType'] = $customerType; //�ͻ�����
        $searchArr['contractNatureArr'] = $orderNatureArr; //��ͬ����
        $searchArr['DeliveryStatusArr'] = $DeliveryStatusArr;
        $searchArr['signStatusArr'] = $signIn;
        $searchArr[$searchConditionKey] = $searchConditionVal;
        $searchArr['isTemp'] = '0';
        foreach ($searchArr as $key => $val) {
            if ($searchArr[$key] === null || $searchArr[$key] === '' || $searchArr[$key] == "undefined") {
                unset ($searchArr[$key]);
            }
        }
        $this->service->searchArr = $searchArr;
        $this->service->sort = 'c.createTime';
        $this->service->asc = true;
        $rows = $service->listBySqlId('select_gridinfo');

        foreach ($rows as $index => $row) {
            foreach ($row as $key => $val) {
                if ($key == 'state') {
                    $rows[$index][$key] = $stateArr[$val];
                } else if ($key == 'signStatus') {
                    $rows[$index][$key] = $signStatus[$val];
                } else if ($key == 'isAcquiring') {
                    $rows[$index][$key] = $isAcquiringArr[$val];
                } else if ($key == 'isNeedStamp') {
                    $rows[$index][$key] = $isTempArr[$val];
                }
            }
        }
        //ƥ�䵼����
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);
        foreach ($rows as $key => $row) {
            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }
            array_push($dataArr, $colIdArr);
        }
        foreach ($dataArr as $key => $val) {
            $dataArr[$key]['customerType'] = $this->getDataNameByCode($val['customerType']);
            $dataArr[$key]['contractType'] = $this->getDataNameByCode($val['contractType']);
            $dataArr[$key]['signContractType'] = $this->getDataNameByCode($val['signContractType']);
        }
        return model_contract_common_contExcelUtil :: export2ExcelUtil($colArr, $dataArr);
    }

    /**
     * ��ͬ�鿴ҳ��
     */
    function c_toViewShipInfoTab()
    {
        //		$this->permCheck (); //��ȫУ��
        $this->assign('id', $_GET['id']);
        $isTemp = $this->service->isTemp($_GET['id']);
        $rows = $this->service->get_d($_GET['id']);
        $this->assign('linkId', $_GET['linkId']);
        $this->assign('viewType', (isset($_GET['viewType'])? $_GET['viewType'] : 'original'));
        $this->assign('contractCode', $rows['contractCode']);
        $this->assign('originalId', $rows['originalId']);
        $this->assign('contractType', $rows['contractType']);
        $this->display('viewshipinfo-tab');
    }

    /**
     * ��ͬ�ر���Ҫ���б�----�з���ͬ�б�
     */
    function c_toRdprojectList()
    {
        $this->view("toRdprojectList");
    }

    /**
     * ��ͬ�ر���Ҫ���б�----���޺�ͬ�б�
     */
    function c_toLeaseList()
    {
        $this->view("toLeaseList");
    }

    /**
     * ��ͬ��Ϣ�б� ��ע
     */
    function c_listRemark()
    {
        $this->assign("contractId", $_GET['id']);
        $this->view("listremark");
    }

    //�������
    //    function c_listremarkAdd() {
    //		$contractId = $_POST['contractId'];
    //		$content = $_POST['content'];
    //		$content = util_jsonUtil :: iconvUTF2GB($content);
    //	  $arr = array(
    //         "contractId" => $contractId,
    //         "content" => $content,
    //         "createName" => $_SESSION['USERNAME'],
    //         "createId" => $_SESSION['USER_ID'],
    //         "createTime" => date ( "Y-m-d H:i:s" )
    //	  );
    //		$this->service->listremarkAdd_d($arr);
    //	}
    function c_listremarkAdd()
    {
        $rows = $_POST['objInfo'];
        $rows['createName'] = $_SESSION['USERNAME'];
        $rows['createId'] = $_SESSION['USER_ID'];
        $rows['createTime'] = date("Y-m-d H:i:s");
        $id = $this->service->listremarkAdd_d($rows);
        if ($id) {
            msg('��ӳɹ���');
        }
    }

    //��ȡ����
    function c_getRemarkInfo()
    {
        $contractId = $_POST['contractId'];
        $info = $this->service->getRemarkInfo_d($contractId);
        //        echo $info;
        echo util_jsonUtil :: iconvGB2UTF($info);

    }

    /**
     * ������д���ʱ�� ҳ��
     */
    function c_financialRelatedDate()
    {
        $this->assign("contractId", $_GET['id']);
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);

        $this->view("financialRelatedDate");
    }

    function c_financialRelatedDateAdd()
    {
        $rows = $_POST['contract'];
        if ($this->service->financialRelatedDateAdd_d($rows)) {
            msg('�����ɹ���');
        }
    }

    /**
     * �����ͬ��Ʒ
     */
    function c_changeContractPro()
    {
        $this->view('changeContractPro');
    }

    /**
     *�ȽϽ�����ת�������ϲ��죨������ת�����ã�
     */
    function c_compareMaterial($rows)
    {
        $old_rows = $this->service->getContractInfo($rows['id']);
        $hasChange = 0;
        $oldMaterialIds = array();
        // �ռ�ԭ���Ľ���������ID
        foreach ($old_rows['equ'] as $k => $v) {
            if ($v['isBorrowToorder'] == '1') {
                $oldMaterialIds[] = $v['id'];
            }
        }
        // �Ա������Ƿ�������Ľ���������
        foreach ($rows['material'] as $k => $v) {
            if ($v['isBorrowToorder'] == '1' && !in_array($v['id'], $oldMaterialIds)) {
                $hasChange += 1;
            }
        }

        // ������µĽ����������򷵻�1�����򷵻�0
        return ($hasChange > 0) ? '1' : '0';
    }

    /**
     *�Ƚϲ�Ʒ���죨����ã�
     */
    function c_compareProduct($rows, $isAjax = 'none')
    {
        if ($isAjax == 'ajax') {
            $oldrows = util_jsonUtil :: iconvGB2UTFArr($this->service->getContractInfo($rows['id']));
        } else {
            $oldrows = $this->service->getContractInfo($rows['id']);
        }
        /********��Ʒ�Ƚ�*******************************/
        //�������汾��Ʒ
        foreach ($rows['product'] as $k => $v) {
            if (isset($rows['id']) && isset($rows['contractId']) && $rows['id'] != $rows['contractId']) { //���ڼ�������ʱ�����¼����
                $temp = $v['conProductName'] . "_" . $v['conProductId'] . "_" . $v['number'] .
                    "_" . $v['price'] . "_" . $v['money'] . "_" . $v['deploy'] . "_" . $v['newProLineCode'] . "_" . $v['exeDeptId'];
            } else {
                $temp = $v['id'] . "_" . $v['conProductName'] . "_" . $v['conProductId'] . "_" . $v['number'] .
                    "_" . $v['price'] . "_" . $v['money'] . "_" . $v['deploy'] . "_" . $v['newProLineCode'] . "_" . $v['exeDeptId'];
            }
            $rows['product'][$k] = $temp;
        }
        //����ԭ��ͬ��Ʒ
        foreach ($oldrows['product'] as $k => $v) {
            if (isset($rows['id']) && isset($rows['contractId']) && $rows['id'] != $rows['contractId']) { //���ڼ�������ʱ�����¼����
                $temp = $v['conProductName'] . "_" . $v['conProductId'] . "_" . $v['number'] .
                    "_" . $v['price'] . "_" . $v['money'] . "_" . $v['deploy'] . "_" . $v['newProLineCode'] . "_" . $v['exeDeptId'];
            } else {
                $temp = $v['id'] . "_" . $v['conProductName'] . "_" . $v['conProductId'] . "_" . $v['number'] .
                    "_" . $v['price'] . "_" . $v['money'] . "_" . $v['deploy'] . "_" . $v['newProLineCode'] . "_" . $v['exeDeptId'];
            }
            $oldrows['product'][$k] = $temp;
        }
        if (empty($oldrows['product'])) {
            $oldrows['product'] = array();
        }
        //�Ƚϲ���
        $result = array_diff($rows['product'], $oldrows['product']);
        /****************************************/
        /********����*******************************/
        //�������汾����
        foreach ($rows['equ'] as $k => $v) {
            $tempP = $v['id'] . "_" . $v['productName'] . "_" . $v['productId'] . "_" . $v['number'] . "_" . $v['price'] . "_" . $v['money'] . "_" . $v['license'];
            $rows['equ'][$k] = $tempP;
        }
        //����ԭ��ͬ����
        foreach ($oldrows['equ'] as $k => $v) {
            $tempPB = $v['id'] . "_" . $v['productName'] . "_" . $v['productId'] . "_" . $v['number'] . "_" . $v['price'] . "_" . $v['money'] . "_" . $v['license'];;
            $oldrows['equ'][$k] = $tempPB;
        }
        if (empty($oldrows['equ'])) {
            $oldrows['equ'] = array();
        }
        //�Ƚϲ���
        $resultM = array_diff($rows['equ'], $oldrows['equ']);
        /****************************************/

        //�����������key ֵ
        $result = array_merge($result);
        //ת����������valueֵ
        foreach ($result as $k => $v) {
            $val = explode("/", $v);
            $result[$k] = $val;
        }
        $deptIds = $this->service->getDeptIdsByChange($result);
        if ((!empty($result) || !empty($resultM)) && empty($deptIds)) {
            if (empty($result) && !empty($resultM)) {
                if (isset($rows['id']) && isset($rows['contractId']) && $rows['id'] != $rows['contractId']) { //���ڼ�������ʱ�����¼����
                    return $deptIds;
                }
                return "tobo";
            }
            return "noDept";
        }
        return $deptIds;
    }

    /**
     * �ȽϽ����죨����ã�
     */
    function c_compareMoney($rows, $isAjax = 'none')
    {
        $oldrows = $this->service->getContractInfo($rows['id']);
        //����汾���
        $money = $rows['contractMoney'];
        //ԭ�汾���
        $oldmoney = $oldrows['contractMoney'];
        $oldexGross = $oldrows['exgross'];
        //�Ƚϲ���
        if ($money != $oldmoney) {
            //�ж�Ԥ��ë�����Ƿ����
            $costEstimates = $oldrows['costEstimates'];
            $exGrossTemp = bcdiv(($money - $costEstimates), $money, 4);
            $exGross = bcmul($exGrossTemp, '100', 2);
            if ($exGross > $oldexGross) {
                if($isAjax === 'none'){
                    $sql = "update oa_contract_contract set exgross='" . $exGross . "' where id='" . $rows['id'] . "'";
                    $this->service->query($sql);
                }

                return "1";
            } else {
                return $exGross;
            }
        } else {
            return "none";
        }
    }

    /**
     * �Ƚ�ֽ�ʺ�ͬ���죨����ã�
     */
    function c_comparePaperContract($rows)
    {
        $oldrows = $this->service->get_d($rows['id']);
        $paperContractOld = util_jsonUtil::iconvGB2UTF($oldrows['paperContract']);
        if ($rows['paperContract'] == util_jsonUtil::iconvGB2UTF("��") && $paperContractOld == util_jsonUtil::iconvGB2UTF("��")) {
            return "1";
        } else {
            return "none";
        }
    }

    /**
     * ��֤��ͬ����Ƿ��ظ�
     */
    function c_checkCode()
    {
        $code = $_POST['contractCode'];
        $str = $this->service->checkCode_d($code);
        echo $str;
    }

    /**
     * �쳣�ر����� �޸�����״̬����ת
     */
    function c_closeAppEdit()
    {
        $id = $_GET['id'];
        $sql = "update oa_contract_contract SET ExaStatus='���������' where id='" . $id . "'";
        $this->service->query($sql);
        echo "<script>this.location='view/reloadParent.php'</script>";
    }

    /**
     * ������ - �Զ�ƥ���ͬ����
     */
    function c_ajaxGetContract()
    {
        //��ȡ����
        $contractCode = $_POST['contractCode'];
        $contractName = util_jsonUtil :: iconvUTF2GB($_POST['contractName']);
        $ExaStatus = util_jsonUtil :: iconvUTF2GB($_POST['ExaStatus']);

        //����Ǳ��ƥ��
        if ($contractCode) {
            $confition = array("contractCode" => $contractCode, 'isTemp' => 0);
        } else {
            $confition = array("contractName" => $contractName, 'isTemp' => 0);
        }
        if ($ExaStatus) { // ��ͬ����״̬����
            $confition['ExaStatus'] = $ExaStatus;
        }
        $arr = $this->service->findAll($confition, null, null);
        if ($arr) {
            $rtObj = $arr[0];
            $rtObj['thisLength'] = count($arr);

            echo util_jsonUtil :: encode($rtObj);
        } else {
            return false;
        }
    }

    /** ��ͬ�յ�*/
    function c_ajaxAcquiring()
    {
        try {
            $result = $this->service->ajaxAcquiring_d($_POST['id']);
            if($result){
                echo 1;
            }else{
                echo 0;
            }
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * �б�ִ�н���ҳ��
     */
    function c_exeStatusView()
    {
        //��ͬ��������
        $leftHTML = $this->service->leftCycle_d($_GET['cid']);
        $this->assign("leftHTML", $leftHTML);
        $handleDao = new model_contract_contract_handle();
        $ff = $handleDao->getIsCon($_GET['cid']);
        //���� ����ͼ
        if ($ff == 'null') {
            $this->view("exestatusview");
        } else {
            //��̬���� ����ͼtab
            $this->assign("tabHtml", $handleDao->getTabHtml($ff, $_GET['cid']));
            $this->view("handleViewTab");
        }
    }

    //����ͼ ģ��ҳ��
    function c_handleView()
    {
        $handleDao = new model_contract_contract_handle();
        //����ͼ�����滻
        $this->assign("htmlStr", $handleDao->handleHtmStr($_GET['num'], $_GET['cid']));
        $this->view("handleView");
    }

    /**
     * �ֹ����ýӿ�--�������к�ͬ���ݵ� ִ��״̬(ֻ��������״̬Ϊ 2,4�ĺ�ͬ)
     */
    function c_handleUpdateConState()
    {
        set_time_limit(0);
        $sql = "select * from oa_contract_contract where state in (2,4) and isTemp = '0' and ExaStatus = '���'";
        $arr = $this->service->_db->getArray($sql);
        $n = count($arr);
        //    echo "<pre>";
        //    print_R($n);
        //״̬����
        $sataeArr = array(
            "2" => "ִ����",
            "4" => "�����"
        );

        $ptStr = "";
        foreach ($arr as $k => $v) {
            $stateBefore = $v['state'];
            //     	$ff = $this->service->updateContractState($v['id']);
            $stateAfter = $this->findUpdateContractState($v['id']);
            if ($stateBefore != $stateAfter) {
                $pt = "<tr><td>��ͬID ����" . $v['id'] . "��     ��ͬ�� ����" . $v['contractCode'] . "��   ����ǰ״̬����" . $sataeArr[$stateBefore] . "�� " .
                    "���º�״̬ ��" . $sataeArr[$stateAfter] . "��  ----  <input  id='" . $v['id'] . "' type='button' class='txt_btn_a' value='����' onclick='updateState(" . $v['id'] . ");' /> </td></tr> ";
            } else {
                $pt = "<tr><td id='" . $v['id'] . "'>��ͬID ����" . $v['id'] . "��     ��ͬ�� ����" . $v['contractCode'] . "��   ����ǰ״̬����" . $sataeArr[$stateBefore] . "�� " .
                    "���º�״̬ ��" . $sataeArr[$stateAfter] . "��  ----  </td></tr>";
            }

            $ptStr .= $pt;

            //       echo str_pad($pt,4096).'<hr />';
            //        flush();
            //		ob_flush();
            //		sleep(0.1);
        }
        $this->assign("ptStr", $ptStr);
        $this->view("handleUpdateConState");

    }

    function findUpdateContractState($contractid)
    {

        $rows = $this->service->getContractInfo($contractid);

        if ($rows['ExaStatus'] == '���') {
            $DeliveryStatus = $rows['DeliveryStatus'];
            $contractType = $rows['contractType'];
            $objCode = $rows['objCode'];
            $date = date("Y-m-d");
            //��ȡ������Ŀ״̬
            $projectStateDao = new model_engineering_project_esmproject();
            $projectState = $projectStateDao->checkIsCloseByRobjcode_d($objCode);
            //�жϺ�ͬ�Ƿ��з�������
            if (empty ($rows['equ'])) {
                $shipTips = 0;
            } else {
                $shipTips = 1;
            }
            // if ($contractType == "HTLX-FWHT" && $projectState != 2) {
            if($projectState != 2){
                if ($shipTips == 0) {
                    if ($projectState == 1) {
                        $state = 4;
                    } else {
                        $state = 2;
                    }
                } else {
                    if ($projectState == 1 && ($DeliveryStatus == "YFH" || $DeliveryStatus == "TZFH")) {
                        $state = 4;
                    } else {
                        $state = 2;
                    }
                }
            } else {
                if ($DeliveryStatus == "YFH" || $DeliveryStatus == "TZFH") {
                    $state = 4;
                } else {
                    $state = 2;
                }
            }
            return $state;
        }
    }

    function c_getUpdateConState()
    {
        $cid = $_POST['cid'];
        $ff = $this->service->updateContractState($cid);
        if ($ff) {
            echo 1;
        } else {
            echo 2;
        }

    }

    /**
     * ��ϸ���ϳɱ�ҳ��
     */
    function c_equCoseView()
    {
        $contractId = $_GET['contractId'];
        $service = $this->service;
        $this->assign("contractId", $contractId);
        if (isset($_GET['istemp'])) {
            $istemp = $_GET['istemp'];
        } else {
            //�ж��Ƿ�Ϊ�����ͬ
            $obj = $service->get_d($contractId);
            $istemp = $obj['isTemp'];
        }
        //��ϸ����
        $equDao = new model_contract_contract_equ();
        $equlist = $equDao->exeEqulistCost($contractId, $istemp);
        $this->assign('equList', $equlist);
        //��Ʒ����ϸ
        $costDao = new model_contract_contract_cost();
        //����Ǳ����ͬ����Ҫ��ȡԭ��ͬ�ɱ���¼(����ĳɱ���¼����µ�ԭ��ͬ)
//         if($istemp == '1'){
//         	$rs = $service->find(array('id' => $contractId),null,'originalId');
//         	if(!empty($rs)){
//         		$contractId = $rs['originalId'];
//         	}
//         }
        $productLine = $costDao->productlineCost($contractId);
        $this->assign('line', $productLine);

        //��ǰ�̻�����
        $chanceCost = $service->getChanceCostByid($_GET['contractId']);
        $this->assign("chanceCost", $chanceCost);
        $this->view("equcostview");
    }

    function c_feeCostView()
    {

        $rows = $this->service->getContractInfo($_GET['id']);
        $arr = $this->service->getContractFeeAll($_GET['id'], 'view');

        $arrHTML = $this->service->handleFeeHTML($arr, $rows);
        $this->assign("feehtml", $arrHTML);
        $this->view("feeCostView");
    }

    /**
     * ����T���б�
     */
    function c_financialTdayList()
    {
        //        $this->service->searchArr['isDel'] = 0;
        //        $this->service->searchArr['isCom'] = 0;
        //        if (!empty($_REQUEST['searchKey']))
        //            $this->service->searchArr[$_REQUEST['searchKey']] = $_REQUEST['searchVal'];
        //        $isCom = $_REQUEST['isCom'];
        //        if (isset($isCom)) {
        //            if ($isCom == 2) { //���Ϊ��ѯ��ɾ����
        //                $this->service->searchArr['isDel'] = 1;
        //            } else if ($isCom == 0) {
        //                $this->service->searchArr['isDel'] = 0;
        //                $this->service->searchArr['isCom'] = 0;
        //            } else
        //                $this->service->searchArr['isCom'] = $isCom;
        //        }
        //        if ($_GET['identify'] == 'contractTool') {
        //            $this->service->searchArr['isSellSql'] = "sql: and (r.Tday is null || r.Tday='0000-00-00')";
        //        }
        //        $this->service->pageSize = 10000; //����ҳ����ʱ����д
        //        $rows = $this->service->pageBySqlId('select_financialTday');
        //        $arrHTML = $this->service->financialTdayHTML($rows);
        //        $this->assign("Thtml", $arrHTML);
        //        $this->assign('searchVal', $_REQUEST['searchVal']);
        //        $this->assign('searchKey', $_REQUEST['searchKey']);
        //        $this->assign('isCom', $_REQUEST['isCom']);

        $this->view("financialTdaylist");
    }

    /**
     * T��ȷ���б�����
     */
    function c_TdayPageJson()
    {
        $service = $this->service;
        $rows = array();
        $service->getParam($_REQUEST);

        $rows = $this->service->pageBySqlId('select_financialTday');

        foreach ($rows as $k => $v) {
            $id = $v['id'];
            $isConfirm = $v['isConfirm'];
            if (empty($v['Tday'])) {
                if (!empty($v['payDT']) && $v['payDT'] != '0000-00-00') { //����տ�������ڼƻ��������ڣ���ֱ����ʾ
                    $Tday = $v['payDT'];
                } else { //������ݸ��������жϲ���������T��
                    $Tday = $service->handlePayDT($v['contractId'], $v['paymenttermId'], $v['dayNum'], $v['schedulePer']);
                }
            } else {
                if ($v['Tday'] != '0000-00-00')
                    $Tday = $v['Tday'];
            }
            //������Ŀ��������
            $rows[$k]['proEndDate'] = $v['completeDate'];

            //��ʶ
            if ($v['Tday'] == '' || $v['Tday'] == '0000-00-00') {
                $isFlag = "-";
            } else {
                $isFlag = '<img src="images/icon/ok3.png">';
            }
            if ($v['TdayPush'] == '0') {
                $htmlButton = ' <input type="button" class="txt_btn_a" value="ȷ��" onclick = "confirmTday(' . $id . ',' . $k . ',' . $isConfirm . ')">';
                $htmlInput = ' <input type="text" class="txtshort"  onfocus="WdatePicker()" id="tday' . $k . '" value="' . $Tday . '">';
                $htmlChangeTip = '';
            } else {
                $htmlButton = ' <input type="button" class="txt_btn_a" value="���" onclick = "confirmTday(' . $id . ',' . $k . ',' . $isConfirm . ')">
                <span class="blue" onclick = "changeHistory(' . $id . ',' . $k . ');">
                <img title="�鿴�����ʷ" src="images/icon/view.gif"></span>
                <input type="hidden" id="tdayOld' . $k . '" value="' . $Tday . '">';
                $htmlInput = ' <input type="text" class="txtshort"  onfocus="WdatePicker()" id="tday' . $k . '" value="' . $Tday . '">';
                $htmlChangeTip = '<input type="text" class="txtlong"  id="changeTips' . $k . '" >';
            }
            //add by chenrf
            if ($v['isDel'] == '1') {
                $isCom = "��ɾ��";
                $htmlButton = '<input type="button" class="txt_btn_a" value="ȷ��" disabled="disabled">';
                $htmlInput = " <span style='text-decoration:line-through;color:red;'>{$Tday}</span>";
                $htmlChangeTip = '';
            }
            $rows[$k]['isFlag'] = $isFlag;
            $rows[$k]['Tday'] = $htmlInput;
            $rows[$k]['confirmBtn'] = $htmlButton;
            $rows[$k]['changeTips'] = $htmlChangeTip;
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        session_start();
        $_SESSION['advSql'] = $service->advSql;
        //		session.setAttribute("advSql",$service->advSql);
        echo util_jsonUtil :: encode($arr);
    }

    //update T��
    function c_updateTday()
    {
        $id = $_POST['id'];
        $tday = $_POST['tday'];
        $changeTips = util_jsonUtil :: iconvUTF2GB($_POST['changeTips']);
        $f = $this->service->updateTday_d($id, $tday, $changeTips);
        echo $f;
    }

    //updateT�� - ����
    function c_updateTdayBatch()
    {
        $checkArr = $_POST['checkArr'];
        $f = $this->service->updateTdayBatch_d($checkArr);
        echo $f;
    }

    function c_showChanceHistory()
    {
        $id = $_GET['id'];
        $info = $this->service->getChanceHistory_d($id);
        $this->assign("info", $info);
        $this->view("showChanceHistory");
    }

    /**
     * ���ݺ�ͬID ���¼���Ԥ��ë���ʣ��ɱ�����ӿڷ���
     */
    function c_handleCountCost()
    {
        $cid = $_GET['cid'];
        $rt = $this->service->countCost($cid);
        echo $rt;
    }

    /**
     * ����ȷ��ҳ��- ��̬���� �ɱ������Ԥ��ë����
     */
    function c_getCostByEqu()
    {
        $rst = array(
            'result' => 'ok'
        );
        if ($this->service->this_limit['��ͬ���'] && $this->service->this_limit['��ͬ���'] == 1) {
            try {
                $rtArr = $this->service->getCostByEqu_d($_POST['cid'], $_POST['equArr'], ($_POST['isChange'] == 1));
                $rst = array_merge($rst, $rtArr);
            } catch (Exception $e) {
                $rst['result'] = 'error';
                $rst['msg'] = $e->getMessage();
            }
        } else {
            $rst['result'] = 'error';
            $rst['msg'] = 'û�����Ȩ��';
        }
        echo util_jsonUtil::encode($rst);
    }

    /**
     * ��¼�������ͬ�б�
     */
    function c_toChecklist()
    {
        $this->view('checklist');
    }

    /**
     * ���¸���������ͬ�б�
     */
    function c_toUpdatePayList()
    {
        $this->view('updatepaylist');
    }

    /**
     * ��������ȷ��ҳ��
     */
    function c_confirmEquView()
    {
        $confirmEqu = $_GET['confirmEqu'];
        $cid = $_GET['contractId'];
        if ($confirmEqu == '2' || $confirmEqu == '3') {
            $cid = $this->service->findChangeId($_GET['contractId']);
            $obj = $this->service->getContractInfoWithTemp($cid, null, 1);
        } else {
            $obj = $this->service->getContractInfo($cid);
        }

        $equDao = new model_contract_contract_equ();
        $products = $equDao->showItemChange($obj['product'], null);
        $this->assign("products", $products);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($confirmEqu == '2') {
            $this->assign('isSubAppChange', '1');
        }
        $this->assign('confirmEqu', $confirmEqu);
        $this->assign('oldId', $_GET['contractId']);

        $this->view("confirmequView");
    }

    //ȷ�Ϸ�������
    function c_confirmEqu()
    {
        set_time_limit(0);
        $act = isset($_GET['act']) ? $_GET['act'] : "app";
        $rows = $_POST[$this->objName];
        $cid = $rows['id'];
        $isSubAppChange = $rows['isSubAppChange'];
        $handleDao = new model_contract_contract_handle();
        if ($rows['confirmEqu'] == '2') {
            // ������ʱ��¼��ë����
//            $chkSql = "select productId,number from oa_contract_equ where contractId = '{$cid}' AND isDel = 0;";
//            $equArr = $this->service->_db->getArray($chkSql);
//            $rtArr = $this->service->getCostByEqu_d($cid, $equArr);
            $noAudit = false;
//            if(!empty($rtArr)){
//                // ���ۺ�ͬ��Ʒ���������Ǳ����߽����ĺ�ͬë���ʴ���70%������ɱ�������ֱ���������(���ϱ��) PMS2373 2017-01-10
//                $infoArr['exGross'] = $rtArr['exgross'];
//                $noAudit = $this->noAuditChk($cid,$infoArr);
//            }

            // ��ȡ����Ƿ�������ʶ
            $rs = $this->service->find(array('id' => $rows['oldId']), null, 'changeNoAudit');
            if ($rs['changeNoAudit'] == 1 && $act != 'back') { // ���������������
                $this->service->updateById(array('id' => $cid, 'ExaStatus' => '���')); // ��ʱ��¼��������ͨ��
                $this->service->confirmChangeNoAudit($cid);

                $handleDao->handleAdd_d(array(
                    "cid" => $rows['oldId'],
                    "stepName" => "����ͨ��",
                    "isChange" => 2,
                    "stepInfo" => "",
                ));
                
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1',
                    'changeNoAudit' => 0
                );
                $this->service->updateById($dateObj);
                msg("�˱����������������ɹ���");
            } else if($noAudit) {
                $this->service->updateById(array('id' => $cid, 'ExaStatus' => '���')); // ��ʱ��¼��������ͨ��
                $this->service->confirmChangeNoAudit($cid);
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1'
                );
                $this->service->updateById($dateObj);
                msg("�˱����������������ɹ���");
            }else {
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '1'
                );
                $this->service->updateById($dateObj);
                if ($act == 'back') {
                    $handleDao->handleAdd_d(array(
                        "cid" => $rows['oldId'],
                        "stepName" => "�������ȷ��",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));
                    msg("�Ѵ������ȷ�����ϣ�");
                } else {
                    $handleDao->handleAdd_d(array(
                        "cid" => $rows['oldId'],
                        "stepName" => "����ȷ������",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));
                    $handleDao->handleAdd_d(array(
                        "cid" => $rows['oldId'],
                        "stepName" => "�ύ����",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));
                    $configDeptIds = contractFlowDeptIds; //config�ڶ���� ����ID
                    $deptIds = "";
                    $deptIdStr = $configDeptIds . "," . $deptIds;
                    $deptIdStrArr = explode(",", $deptIdStr);
                    $deptIdStrArr = array_unique($deptIdStrArr);
                    $deptIdStr = implode(",", $deptIdStrArr);
                    succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId=' . $cid . '&billDept=' . $deptIdStr);
                    //       	   	  msg("ȷ�ϳɹ������ύ����Ʒ�߳ɱ���ˣ�");
                }
            }
        } else if ($rows['confirmEqu'] == '3') {
            if ($act == 'back') {
                $dateObj = array(
                    'id' => $rows['oldId'],
                    'standardDate' => $rows['standardDate'],
                    'dealStatus' => '2'
                );
                $this->service->updateById($dateObj);

                // == ���ֺ�ͬ�������ȷ�ϱ���غ�,��ʱ��ͬ״̬�Լ�֮ǰ�ĸ���û����,���»�ϲ��ߵķ������ȷ�Ϻ�����ύ����,�Լ���غ������ȷ��ҳ����ֱ�ӵ�����ȷ�ϰ�ť,��ʱ������ 2017-01-10 huanghaojin == //
                // ������ʱ��ͬ�Ĳ���״̬
                $this->service->updateById(array('id' => $cid, 'dealStatus' => '2'));
                // ɾ��ԭ���ύ�ĳɱ�����
                $delSql = "delete from oa_contract_cost where contractId='{$cid}' AND ExaState = 0 AND state = 3;";
                $this->service->query($delSql);

                $handleDao->handleAdd_d(array(
                    "cid" => $rows['oldId'],
                    "stepName" => "�������ȷ��",
                    "isChange" => 2,
                    "stepInfo" => "",
                ));
                msg("�Ѵ������ȷ�����ϣ�");
            } else {
                //����ȷ����Ϣ-������Ԥ��ë����
                $exGross = $this->service->handleSubConfirmCoseNew($cid, "3");
                if ($exGross === 'none') {
                    $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange, $rows['oldId']);
                    $this->c_updateSaleCostExaState($cid);
                    msg("ȷ�ϳɹ�����ȴ�����ִ������ɱ�ȷ�ϣ�");
                } else {
                    // ���ۺ�ͬ��Ʒ���������Ǳ����߽����ĺ�ͬë���ʴ���70%������ɱ�������ֱ����ɱ��(�����ͬ) PMS2373 2017-01-10
                    $infoArr['exGross'] = $exGross;
//                    $noAudit = $this->noAuditChk($cid,$infoArr);
                    $noAudit = false;
                    if($noAudit){
                        // ����ȷ������
                        $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange, $rows['oldId']);
                        $this->c_updateSaleCostExaState($cid);

                        // �Զ�ͨ������,�����������Ϣ
                        $dateObj = array(
                            'id' => $cid,
                            'ExaStatus' => '���'
                        );
                        $this->service->updateById($dateObj);
                        $this->service->confirmChangeNoAudit($cid,2);
                        msg("�˺�ͬ��������������ɹ���");
                    }else{
                        $handleDao->handleAdd_d(array(
                            "cid" => $rows['oldId'],
                            "stepName" => "����ȷ������",
                            "isChange" => 2,
                            "stepInfo" => "",
                        ));
                        $this->c_subConfirmCostAppNew($cid, $exGross);
                    }
                }
//                 msg("ȷ�ϳɹ������ύ��ִ�в��ųɱ���ˣ�");
            }

        } else {
            if ($act == 'back') {
                $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange);
                msg("�Ѵ������ȷ�����ϣ�");
            } else {
                //����ȷ����Ϣ-������Ԥ��ë����
                $exGross = $this->service->handleSubConfirmCoseNew($cid, "3");
                if ($exGross === 'none') {
                    $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange);
                    $this->c_updateSaleCostExaState($cid);
                    msg("ȷ�ϳɹ�����ȴ�����ִ������ɱ�ȷ�ϣ�");
                } else {
                    // ���ۺ�ͬ��Ʒ���������Ǳ����߽����ĺ�ͬë���ʴ���70%������ɱ�������ֱ���������(¼���ͬ) PMS2373 2017-01-09
                    $infoArr['exGross'] = $exGross;
//                    $noAudit = $this->noAuditChk($cid,$infoArr);
                    $noAudit = false;
                    if($noAudit){
                        $contractObj = $this->service->getContractInfo($cid);
                        // ����ȷ������
                        $this->service->confirmEqu_d($rows['id'], $act, $isSubAppChange);
                        $this->c_updateSaleCostExaState($cid);//���¸������״̬

                        // �Զ�ͨ������,�����������Ϣ
                        $dateObj = array(
                            'id' => $cid,
                            'standardDate' => $contractObj['standardDate'],
                            'dealStatus' => '1',
                            'ExaStatus' => '���',
                            'ExaDTOne' => date("Y-m-d"),
                            'state' => '2',
                            'isSubAppChange' => 0

                        );
                        $this->service->updateById($dateObj);
                        $this->service->dealAfterAudit_d($cid);// �ڸ����б������Ϣ

                        $this->service->confirmContractWithoutAudit_d($cid);// ȷ�Ϻ�ͬ
                        msg("�˺�ͬ�����������ύ�ɹ���");
                    }else{
                        $handleDao->handleAdd_d(array(
                            "cid" => $rows['oldId'],
                            "stepName" => "����ȷ������",
                            "isChange" => 2,
                            "stepInfo" => "",
                        ));
                        $this->c_subConfirmCostAppNew($cid, $exGross);
                    }
                }
//                 msg("ȷ�ϳɹ������ύ��ִ�в��ųɱ���ˣ�");
            }
        }
    }

    /**
     * ����ͬ�Ƿ���Ҫ����,����true��false
     * @param $contractId
     * @param array $infoArr
     * @return bool
     */
    function noAuditChk($contractId,$infoArr = array()){
        $exGross = isset($infoArr['exGross'])? $infoArr['exGross'] : 0;
        $contract = $this->service->find(array('id' => $contractId), null, 'newProLineStr');
        $arr = explode(",",$contract['newProLineStr']);
        $arr = array_unique($arr);// ��Ʒ��ȥ��
        // �������ë���� > 70,��Ϊ�����Ǳ�����߽����ĵ�һ���ߵĲ�������
        if($exGross > 70 && count($arr) == 1){
            if($arr[0] == 'HTCPX-YQYB' || $arr[0] == 'HTCPX-ZXJY'){// ����Ϊ�����Ǳ�����߽���
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * ��ת����ͬ����ҳ��
     */
    function c_toDelivery()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);

        $productIds = ''; //�Ѿ������������id

        $basicDao = new model_purchase_plan_basic(); //�ɹ�����
        $basicObj = $basicDao->findAll(array('sourceID' => $_GET['id']));
        $basicIds = '';
        if (is_array($basicObj)) {
            $equipmentDao = new model_purchase_plan_equipment(); //�ɹ������嵥
            foreach ($basicObj as $key => $val) {
                $basicIds .= $val['id'] . ',';

                $equipmentObj = $equipmentDao->findAll(array('basicId' => $val['id']));
                if (is_array($equipmentObj)) {
                    foreach ($equipmentObj as $k => $v) {
                        $productIds .= $v['productId'] . ',';
                    }
                }
            }
            $basicIds = substr($basicIds, 0, -1);
        }
        $this->assign('basicIds', $basicIds);

        $produceapplyDao = new model_produce_apply_produceapply(); //��������
        $produceapplyObj = $produceapplyDao->findAll(array('relDocId' => $_GET['id']));
        $produceapplyIds = '';
        if (is_array($produceapplyObj)) {
            $itemDao = new model_produce_apply_produceapplyitem(); //���������嵥
            foreach ($produceapplyObj as $key => $val) {
                $produceapplyIds .= $val['id'] . ',';

                $itemObj = $itemDao->findAll(array('mainId' => $val['id']));
                if (is_array($itemObj)) {
                    foreach ($itemObj as $k => $v) {
                        $productIds .= $v['productId'] . ',';
                    }
                }
            }
            $produceapplyIds = substr($produceapplyIds, 0, -1);
        }
        $this->assign('produceapplyIds', $produceapplyIds);

        $encryptionDao = new model_stock_delivery_encryptionequ(); //����������
        $encryptionObj = $encryptionDao->findAll(array('sourceDocId' => $_GET['id']));
        if (is_array($encryptionObj)) {
            foreach ($encryptionObj as $key => $val) {
                $productIds .= $val['productId'] . ',';
            }
        }

        $productIds = substr($productIds, 0, -1);
        $this->assign('productIds', $productIds);

        //��ȡ�����ƻ����ƻ�����ʱ��
        $sql = "select max(shipPlanDate) as maxDate,docId from oa_stock_outplan where docType='oa_contract_contract'
          and docId = '" . $_GET['id'] . "'
          GROUP BY docId";
        $maxShipArr = $this->service->_db->getArray($sql);
        $this->assign("maxShipDate", $maxShipArr[0]['maxDate']);

        $this->view("delivery");
    }

    /**
     * ��д��ȡ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;

        $handleType = isset($_REQUEST['handleType'])? $_REQUEST['handleType'] : '';
        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;

        if($handleType == 'YSWJ'){// ����������ļ����б��ȡ������,����ӿͻ������õ�����
            $rows = $service->pageBySqlId('select_gridinfoForYswj');
        }else{
            $rows = $service->page_d ();
        }

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJsons()
    {
        $service = $this->service;
        $incomeSql = " ";
        if(isset($_REQUEST['isIncome'])){
            switch($_REQUEST['isIncome']){
                case "0" :
                    $incomeSql = " and (c.contractMoney-c.incomeMoney != 0)";
                    break;
                case "1" :
                    $incomeSql = " and (c.contractMoney-c.incomeMoney = 0)";
                    break;
            }
            unset($_REQUEST['isIncome']);
        }

        $service->getParam($_REQUEST);

        // �Զ���ű�ƴ��
        $conditionSql = CONTOOLIDS_C;
//        $conditionSql = "sql: " . CONTOOLIDS_C;
        $conditionSql .= $incomeSql;

        // ����һ��������ˣ�����not in ����
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'areaNameNotIn' => $odDao->getConfig("contractCheck_filter_areaName"), // �������
            'prinvipalIdNotIn' => $odDao->getConfig("contractCheck_filter_prinvipalId"), //�����˹���
            'customerNameNotIn' => $odDao->getConfig("contractCheck_filter_customerName") // �ͻ����ƹ���
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        // �ͻ����͹���
        $customerTypeNotLike = $odDao->getConfig("contractCheck_filter_customerTypeName");
        if ($customerTypeNotLike) {
            $conditionSql .= $customerTypeNotLike;
        }

        $limit = $this->service->initLimit_treport($conditionSql,"t");
//        $service->searchArr['mySearchCondition'] = $conditionSql;

        //$service->asc = false;
        if($limit){
            $rows = $service->page_d();

            foreach ($rows as $key => $val){
                // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
                $invoiceCodeArr = explode(",",$val['invoiceCode']);
                $isNoInvoiceCont = '';
                foreach ($invoiceCodeArr as $Arrk => $Arrv){
                    if($Arrv == "HTBKP"){
                        $isNoInvoiceCont = '1';
                    }
                }
                $rows[$key]['isNoInvoiceCont'] = $isNoInvoiceCont;
                $rows[$key]['surplusInvoiceMoney'] = empty($isNoInvoiceCont)? (isset($rows[$key]['surplusInvoiceMoney'])? $rows[$key]['surplusInvoiceMoney'] : 0) : 0;
            }
        }

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �����¼���տ����ע��Ϣ
     */
    function c_saveCheckRemarks(){
        $updateArr['id'] = isset($_POST['id'])? $_POST['id'] : '';
        $remarks = isset($_POST['remarks'])? $_POST['remarks'] : '';
        $type = isset($_POST['type'])? $_POST['type'] : '';

        switch ($type){
            case 'fcheck':
                $updateArr['fcheckRemarks'] = util_jsonUtil::iconvUTF2GB($remarks);
                break;
            case 'check':
                $updateArr['checkRemarks'] = util_jsonUtil::iconvUTF2GB($remarks);
                break;
        }

        $result = $this->service->updateById($updateArr);
        echo ($result)? 1 : 0;
    }

    /**
     * ��ǩ����ͬ�����Ϣ
     */
    function c_parentView()
    {
        //��ͬ״̬����
        $stateArr = array(
            "0" => "δ�ύ",
            "1" => "������",
            "2" => "ִ����",
            "3" => "�ѹر�",
            "4" => "�����",
            "5" => "�Ѻϲ�",
            "6" => "�Ѳ��",
            "7" => "�쳣�ر�",
        );
        $this->service->searchArr['id'] = $_GET['contractId'];
        $this->assign('contractId', $_GET['contractId']);
        $objArr = $this->service->list_d('select_gridinfo');
        $obj = $objArr[0];
        $objState = $obj['state'];
        //�������ֶ�Ȩ��
        $financeLimit = isset ($this->service->this_limit['������']) ? $this->service->this_limit['������'] : null;
        if ($financeLimit == '1') {
            $this->assign("FinanceCon", "1");
        } else {
            $this->assign("FinanceCon", "0");
        }
        //		echo "<pre>";
        //		print_R($obj);
        //������Ⱦ
        $this->assignFunc($obj);
        $this->assign('state', $stateArr[$objState]);
        $this->assign('surOrderMoney', $obj['contractMoney'] - $obj['incomeMoney']); //��ͬӦ�տ�
        $this->assign('surincomeMoney', $obj['invoiceMoney'] - $obj['incomeMoney']); //����Ӧ�տ�
        if ($obj['serviceconfirmMoneyAll'] != '0') {
            $financeconfirmPlanNum = ($obj['serviceconfirmMoneyAll'] / $obj['contractMoney']) * 100;
            $financeconfirmPlanNum = round($financeconfirmPlanNum, 2) . "%";
            $this->assign('financeconfirmPlan', $financeconfirmPlanNum); //����ȷ�Ͻ���
        } else {
            $this->assign('financeconfirmPlan', ""); //����ȷ�Ͻ���
        }
        $this->assign('gross', $obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']); //ë��
        if (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) != 0) {
            $rateGross = (($obj['serviceconfirmMoneyAll'] - $obj['financeconfirmMoneyAll']) / $obj['serviceconfirmMoneyAll']) * 100;
            $rateGross = round($rateGross, 2) . "%";
            $this->assign('rateOfGross', $rateGross);
        } else {
            $this->assign('rateOfGross', "");
        }
        //��Ʊ���������
        $this->assign('invoicePortion', $this->getProportion($obj['invoiceMoney'], $obj['contractMoney']));
        $this->assign('incomePortion', $this->getProportion($obj['incomeMoney'], $obj['contractMoney']));

        //�������
        $equDao = new model_contract_contract_equ();
        $equlist = $equDao->exeEqulist($obj['id']);
        $this->assign('equlist', $equlist);
        //��Ŀ
        $proDao = new model_contract_conproject_conproject();
        $this->assign('proList', $proDao->prolist($obj['id']));
        //��ִͬ�н���
        $this->assign('exePortion', $proDao->getConduleBycid($obj['id']));
        $this->view("parentView");
    }

    /**
     * �ӱ�����ת���鿴��ͬ���б�
     */
    function c_toViewByReport()
    {
        $this->assign('ids', $_GET['ids'] ? $_GET['ids'] : 0);
        $this->view('view-report');
    }

    /**
     * ��ͬ���������б�
     */
    function c_basicList()
    {
        $this->view('basiclist');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJsonBasic()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);

        $rows = $service->page_d("select_basicList");
        $rows = $service->basicDataProcess_d($rows);

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��ͬ�������ݵ���
     */
    function c_basicExportExcel()
    {
        set_time_limit(0); //ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        ini_set('memory_limit', '1024M'); //�����ڴ�

        $service = $this->service;
        $rows = $service->list_d("select_basicList");
        $dataArr = $service->basicDataProcess_d($rows);
        //��ͷ����
        $thArr = array('ExaDTOne' => '��ͬ����ʱ��', 'contractCode' => '��ͬ���', 'contractName' => '��ͬ����', 'contractMoney' => '��ͬ���', 'projectCode' => '��Ŀ���',
            'projectName' => '��Ŀ����', 'proMoney' => '��Ŀ���', 'projectType' => '��Ŀ����', 'createTime' => '��ͬ�ύ����', 'costAppDate' => '�ɱ��������',
            'shipTimes' => '������ͬ��������', 'standardDate' => '��׼����', 'shipPlanDate' => 'Ԥ�Ʒ�������', 'shipDate' => 'ʵ�ʷ�������', 'estimates' => '��Ŀ����',
            'saleCost' => 'ʵ�ʷ����ɱ�', 'cost' => '��Ŀ����', 'earnings' => '��Ŀ����', 'exgross' => 'Ԥ��ë����', 'rateOfGross' => 'ë����', 'schedule' => '��Ŀ����',
            'isAcquiringDate' => '��ͬ�յ�����', 'signinDate' => '��ͬǩ������'
        );

        return model_contract_common_contractExcelUtil :: exportBasicExcelUtil($thArr, $dataArr, '��ͬ��������');
    }

    /**
     * ���·�����ȷ��״̬���ɱ���Ϣ
     */
    function c_updateEngConfirm($contractId)
    {
        $service = $this->service;
        // ��ȡ���з��������Ʒ����
        $sql = "SELECT COUNT(DISTINCT(newProLineCode)) AS num FROM oa_contract_product WHERE contractId = " . $contractId . " AND proTypeId <> 11 AND isDel = 0";
        $rs = $service->_db->getArray($sql);
        $pNum = 0;
        if (!empty($rs)) {
            $pNum = $rs[0]['num'];
        }
        // ��ȡ���з�������ȷ�ϳɱ�
        $costDao = new model_contract_contract_cost();
        $cNum = $costDao->findCount(array('contractId' => $contractId, 'issale' => 0));
        if ($pNum == $cNum) { // ���з��������Ʒ����ȷ�ϳɱ�ʱִ��
            // ��ȡ��ͬ��Ϣ
            $rows = $service->getContractInfo($contractId);
            $handleDao = new model_contract_contract_handle();
            if ($rows['isSubAppChange'] == '1') {
                //���·�����ɱ�ȷ�ϱ�־λ ���
                $service->endTheEngTig($contractId);
                $service->endTheEngTig($rows['originalId']);
                //���������¼
                $handleDao->handleAdd_d(array(
                    "cid" => $rows['originalId'],
                    "stepName" => "�ύ����",
                    "isChange" => 2,
                    "stepInfo" => "",
                ));
            } else {
                $service->endTheEngTig($contractId);
                $handleDao->handleAdd_d(array(
                    "cid" => $contractId,
                    "stepName" => "�ύ����",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
            }
        }
    }

    /**
     * �ύ��������
     */
    function c_dealAfterSubAudit()
    {
        $service = $this->service;
        // ��ȡ��ͬ��Ϣ
        $contractId = $_REQUEST['id'];
        $rows = $service->getContractInfo($contractId);
        // �����ദ��
        if ($rows['isSubAppChange'] == '1') {
            $service->confirmEqu_d($contractId, "app", $rows['isSubAppChange'], $rows['originalId'],false);
        } else {
            $service->confirmEqu_d($contractId, "app", $rows['isSubAppChange'],'',false);
        }
        $this->c_updateSaleCostExaState($contractId);
        // �����ദ��
        $this->c_updateEngCostExaState($contractId);
        $this->c_updateEngConfirm($contractId);
        // ���±����¼����״̬Ϊ������
        $sql = "update oa_contract_changlog set ExaStatus = '������' where objType = 'contract' and tempId = " . $contractId;
        $this->service->_db->query($sql);
        echo "<script>location.href='view/reloadParent.php';</script>";
    }

    /**
     * �жϺ�ͬ�������Ƿ�Ϊ���ⲿ��
     * ���ⲿ���߲�ͬ��������
     */

    function c_isOverseasDept()
    {
        $userDao = new model_deptuser_user_user(); //��Ա��Ϣ
        $rs = $userDao->find(array('USER_ID' => $_POST['userId']), null, 'DEPT_ID');
        if ($rs['DEPT_ID'] == hwDeptId) {
            echo hwDeptId;
        }
    }

    /**
     * ��ͬ��ϸ-���ڷ��ñ�������ҳ��ķ���ͳ��
     */
    function c_statistictList()
    {
//    	$this->assign('userId',$_GET['userId']);
//    	$this->assign('areaId',$_GET['areaId']);
//    	$this->assign('year',date('Y'));
//
//    	$this->view('liststatistict');
        $this->assign('userId', (isset($_GET['userId']) ? $_GET['userId'] : ''));
        $this->assign('areaId', (isset($_GET['areaId']) ? $_GET['areaId'] : ''));
        $this->assign('year', (isset($_GET['year']) ? $_GET['year'] : date('Y')));


        if ((isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId'])) && (isset($_GET['view_type']) && !empty($_GET['view_type']))) {
            $this->assign('view_type', (isset($_GET['view_type']) ? $_GET['view_type'] : ''));
            if ($_GET['view_type'] == 'view_all') {
                $this->view('listallstatistict');
            }
        } else if ((isset($_GET['userId']) || isset($_GET['areaId'])) && (!empty($_GET['areaId']) || !empty($_GET['userId']))) {
            $this->view('liststatistict');
        } else {
            $this->view('listallstatistict');
        }
    }

    /**
     * ��ȡ��ҳ����ת��Json - ����ͳ��
     */
    function c_statistictPageJson()
    {
        $service = $this->service;

        if(isset($_REQUEST['isForExsummary']) && $_REQUEST['isForExsummary'] == 1){
            $sql = "select contractId from oa_bi_conproduct_month where 1=1 ";
            $sql .= (isset($_REQUEST['prinvipalId']) && !empty($_REQUEST['prinvipalId']))? " and prinvipalId = '{$_REQUEST['prinvipalId']}'" : '';
            $sql .= (isset($_REQUEST['areaCode']) && !empty($_REQUEST['areaCode']))?  " and areaCode = '{$_REQUEST['areaCode']}'" : '';
            $sql .= (isset($_REQUEST['ExaYear']) && !empty($_REQUEST['ExaYear']))?  " and storeYear = '{$_REQUEST['ExaYear']}'" : '';
            $sql .= " GROUP BY contractId";
            $sql = "select group_concat(t.contractId) as ids from ({$sql})t;";
            $contractIds = $this->service->_db->get_one($sql);
            if($contractIds){
                $_REQUEST['ids'] = $contractIds['ids'];
            }
        }

        $service->getParam($_REQUEST);

        $rows = $service->pageBySqlId('select_gridinfo');

        if (!empty ($rows)) {
            //��ȡ�б�ע��Ϣ�ĺ�ͬid
            $remarkIsArr = $this->service->getRemarkIs();
            $regionDao = new model_system_region_region();
            $cidStr = '';
            foreach ($rows as $key => $val) {
                if (in_array($val['id'], $remarkIsArr)) {
                    $rows[$key]['conflag'] = "1";
                }
                //�жϲ����غ�ͬ��ǰ����״̬
                $exeStautsView = $this->service->exeStatusView_d($val);
                $rows[$key]['exeStatus'] = $exeStautsView[0];
                $rows[$key]['exeStatusNum'] = $exeStautsView[1];
                //������չֵ
                //��ȡ������չ�ֶ�ֵ
                $expand = $regionDao->getExpandbyId($val['areaCode']);
                $rows[$key]['expand'] = $expand;
                //��ͬ����
                //					$fee = $this->service->getContractFeeAll($val['id']);
                //					$rows[$key]['contractFee'] = $fee;
                //����ǰ�б��ͬid�ַ���
                $cidStr .= $val['id'] . ",";

            }
            //��ȡ��Ŀ���������Ϣ
            $cidStr = rtrim($cidStr, ",");
            $proBudgetArr = $service->getproBudgetByids($cidStr);
            //������Ŀ����
            foreach ($rows as $k => $v) {
                foreach ($proBudgetArr as $va) {
                    if ($v['id'] == $va['contractId']) {
                        $rows[$k]['budgetAll'] = $va['budgetAll'];
                        $rows[$k]['feeOther'] = $va['feeOther'];
                        $rows[$k]['budgetOutsourcing'] = $va['budgetOutsourcing'];
                        $rows[$k]['feeFieldCount'] = $va['feeFieldCount'];
                        $rows[$k]['feeOutsourcing'] = $va['feeOutsourcing'];
                        $rows[$k]['feeAll'] = $va['feeAll'];
                        $rows[$k]['projectProcess'] = $va['projectProcess'];
                    }
                }
            }
            //��ȫ��
            $rows = $this->sconfig->md5Rows($rows);
            //                //����������
            //                $rows = $this->service->projectProcess_d($rows);

            //ͳ�ƽ��
            $rows = $service->getRowsallMoney_d($rows, "select_contractInfo");
            //��������ΪʲôҪunset�����ȷ�����
            unset($service->searchArr['advSql']);
            //�����ֶι���
            $rows = $this->fieldFilter($rows);
            //��Ʊ����˰��
            $datadictDao = new model_system_datadict_datadict(); //��ȡ�����ֵ���Ϣ
            $rs = $datadictDao->findAll(array('parentCode' => 'KPLX'), null, 'dataCode,expand1');
            if (!empty($rs)) {
                $invoiceArr = array();
                foreach ($rs as $v) {
                    $invoiceArr[$v['dataCode']] = $v['expand1'] . '%';
                }
                foreach ($rows as $key => $val) {
                    if (!empty($val['invoiceCode'])) {
                        $invoiceCodeArr = explode(',', $val['invoiceCode']);
                        $KPLXSD = array();
                        foreach ($invoiceCodeArr as $v) {
                            array_push($KPLXSD, $invoiceArr[$v]);
                        }
                        $rows[$key]['KPLXSD'] = implode('&', array_unique($KPLXSD));
                    }
                }
            }
        }
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        session_start();
        $_SESSION['advSql'] = $service->advSql;
        //		session.setAttribute("advSql",$service->advSql);
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ���ݽ�����id��ȡ�����̻����� - ���ڽ�����ת���۹رչ����̻�
     */
    function c_getChanceByBorrowIds()
    {
        echo util_jsonUtil::iconvGB2UTF($this->service->getChanceByBorrowIds_d($_POST['borrowIds']));
    }

    /**
     * ���º�ͬ״̬
     */
    function c_updateConState(){
    	$this->service->updateConState_d();
    }

    /*************************T ��  ����********************************************************************/
    //T �� ԭʼ����
    function c_TdayDataList(){
        $this->view('TdayDataList');
    }
    function c_tdatDataJson(){
        $service = $this->service;
        $service->getParam($_REQUEST);
        $conditionSql = "  and 1=1 ";
        if(isset($_REQUEST['isIncome'])){
            if($_REQUEST['isIncome'] == '0'){
                $conditionSql .= " and (c.contractMoney-c.incomeMoney-c.deductMoney-c.badMoney > 0)";
            }elseif($_REQUEST['isIncome'] == '1'){
                $conditionSql .= " and (c.contractMoney-c.incomeMoney-c.deductMoney-c.badMoney = 0)";
            }
            unset ($_REQUEST['isIncome']);
        }
        if(isset($_REQUEST['isReplan'])){
            if($_REQUEST['isReplan'] == '0'){
                $conditionSql .= " and r.payInfo is not null";
            }elseif($_REQUEST['isReplan'] == '1'){
                $conditionSql .= " and (r.tday != ' ' and r.tday != '0000-00-00' AND r.tday != ',' AND r.tday != ',,' AND r.tday != ',,,' AND r.tday != ',,,,' AND r.tday != ',,,,,' AND r.tday != ',,,,,,' AND r.tday != ',,,,,,,' AND r.tday != ',,,,,,,,' AND r.tday != ',,,,,,,,,' AND r.tday != ',,,,,,,,,,' AND r.tday != ',,,,,,,,,,,')";
            }
            unset ($_REQUEST['isReplan']);
        }

        // ����һ��������ˣ�����not in ����
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // �ͻ����ƹ���
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        //������Ȩ������
        $limit = $this->service->initLimit_treport($conditionSql,"t");

        //$service->asc = false;
        $reDao = new model_contract_contract_receiptplan();
        $esmDao = new model_engineering_project_esmproject();
        if($limit){
            $rows = $service->pageBySqlId("select_tdayDataList");
        }

        //���� �ؿ���������
        foreach($rows as $k => $v){
           $reArr = $reDao->getDetail_d($v['id']);
           $esmState = $esmDao->getStatusNameByContractId_d($v['id']);
           if(empty($esmState)){
           	   switch($v['DeliveryStatus']) {
           	   	 case "WFH" : $esmState = "δ����";
           	       break;
           	     case "BFFH" : $esmState = "���ַ���";
           	       break;
           	     case "YFH" : $esmState = "�ѷ���";
                   break;
                 case "TZFH" : $esmState = "ֹͣ����";
                   break;
           	   }
           }
           $incocmeAll = 0;
           $endDate = "";
           $reMoney = 0;
           for($i=0;$i<15;$i++){
           	   $tt = "";
           	   $j = $i + 1;
               $rows[$k]["incomeDate_".$j] = $reArr[$i]["Tday"];
               $rows[$k]["incomePtn_".$j] = $reArr[$i]["paymentPer"];
               $rows[$k]["incomeMoney_".$j] = $reArr[$i]["money"] - $reArr[$i]["incomMoney"]-$reArr[$i]["deductMoney"];
               $rows[$k]["invoiceMoney_".$j] = $reArr[$i]["money"] - $reArr[$i]["invoiceMoney"]-$reArr[$i]["deductMoney"];
               if(!empty($reArr[$i]["Tday"])){
                   $incocmeAll +=  $reArr[$i]["money"] - $reArr[$i]["incomMoney"]-$reArr[$i]["deductMoney"];
               }
               if(!empty($reArr[$i]['id'])){
               	  $tt = $this->service->getTdayListEndDate($v['id'], $reArr[$i]['paymenttermId']);
                  $tState = $this->service->getTdayListEndDate($v['id'], $reArr[$i]['paymenttermId']);
               }else{
               	  $tt = "-";
               }
               if(!empty($reArr[$i]['id'])){
               	 $endDate .= $tt." ; ";
               }
               if(!empty($reArr[$i]["Tday"])){
               	 $reMoney += $reArr[$i]['money'];
               }
           }
            //ת���ѧ����
            $incocmeAll = number_format($incocmeAll,2,'.','');
           $rows[$k]["Tmoney"] = $incocmeAll;
           $rows[$k]["projectEndDate"] = $endDate;
           $rows[$k]["projectState"] = $esmState;
           $rows[$k]["unTdayMoney"] = bcsub($v["unIncomeMoney"],$incocmeAll,2);


        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);

    }

     //T �� �����
    function c_TdayInitList(){
    	$this->view('TdayInitList');
    }
    function c_tInitJson(){
        $service = $this->service;
        $service->getParam($_REQUEST);
        $conditionSql = " and 1=1 ";
        if(isset($_REQUEST['isIncome'])){
            if($_REQUEST['isIncome'] == '0'){
                $conditionSql .= " and (r.money-r.incomMoney-r.deductMoney > 0)";
            }elseif($_REQUEST['isIncome'] == '1'){
                $conditionSql .= " and (r.money-r.incomMoney-r.deductMoney = 0)";
            }
            unset ($_REQUEST['isIncome']);
        }
        $t_date = date("Y-m-1");//ͳ��ʱ��
        if(isset($_REQUEST['advArr']) && is_array($_REQUEST['advArr'])){
        	foreach($_REQUEST['advArr'] as $key => $val){
        		if($val['searchField'] == "c.ExaDTOne"){
                    $t_date = $val['value'];
                    break;
        		}
        	}
        }

        // ����һ��������ˣ�����not in ����
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // �ͻ����ƹ���
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        //������Ȩ������
        $limit = $this->service->initLimit_treport($conditionSql,"t");
        $reDao = new model_contract_checkaccept_checkaccept();
        if($limit){
            $rows = $service->pageBySqlId("select_tdayInitList");
        }
        // ���������
        $tempCid = 0;
        $checkArr = "";
        $tempKey = 0;
        foreach($rows as $k => $v){
           if($tempCid != $v['contractId']){//�жϲ�ͬ���ȡ ������Ϣ
              $tempCid = $v['contractId'];
              $tempKey = 0;
			  $checkArr = $reDao->getDetail_d($v['contractId']);
			  $rows[$k]["clauseInfo"] = $checkArr[$tempKey]['clauseInfo'];
			  unset($checkArr[$tempKey]);
              $tempKey += 1;
           }else{//���򣬸�ֵ��ȥ���Ѹ�ֵ val
               $rows[$k]["clauseInfo"] = $checkArr[$tempKey]['clauseInfo'];
			   unset($checkArr[$tempKey]);
			   $tempKey += 1;
           }

           //����Ӧ�տ�
           if(strtotime(date("Y-m-1")) > strtotime($v['Tday']) && $v['unIncomeMoney'] > 0){
           	   $rows[$k]["unCmoney"] = $v['unIncomeMoney'];
           }
           //Ӧ�տ��ж�
           if(!empty($v['Tday'])){
	         $Q = ceil(date("m")/3);//��ǰ����
	         $Y = date("Y");
	         if($Q+1>4){//�¼���
	         	$next_Q = 1;
	         }else{
	         	$next_Q = $Q+1;
	         }
	         $t_Q = ceil(substr($v['Tday'],5,-3)/3);
	         $t_Y = substr($v['Tday'],0,-6);
             $t_3 =  date("Y-m-d", strtotime("-3 months", strtotime($t_date)));
             $t_6 =  date("Y-m-d", strtotime("-6 months", strtotime($t_date)));
             $t_12 =  date("Y-m-d", strtotime("-12 months", strtotime($t_date)));

               $Qarr = array(1 => array('01','03'),2 => array('04','06'),3 => array('07','09'),4 => array('10','12'));// �����·ݶ�Ӧ����
               $nextQ_Y = ($next_Q < $Q)? ($Y + 1) : $Y;// �¼����������
               $QStart = $Y."-".$Qarr[$Q][0]."-01";// �����ȿ�ʼ����
               $QEnd = ($Qarr[$Q][1]+1 <= 12)? $Y."-".($Qarr[$Q][1]+1)."-01" : ($Y+1)."-01-01";// �����Ƚ������ڼ�һ�죨���¼��ȵĿ�ʼ���ڣ���
               $nextQStart = $nextQ_Y."-".$Qarr[$next_Q][0]."-01";// �¼��ȵĿ�ʼ����
               $nextQEnd = ($Qarr[$next_Q][1]+1 <= 12)? $nextQ_Y."-".($Qarr[$next_Q][1]+1)."-01" : ($nextQ_Y+1)."-01-01";// �¼��Ƚ������ڼ�һ�죨���¼����Ժ�Ŀ�ʼ���ڣ���

             if(strtotime($v['Tday']) >= strtotime($t_date) && $t_Q == $Q && ($t_Y == $Y)){
                 $rows[$k]["income_a"] = $v['unIncomeMoney'];
             //}else if(strtotime($v['Tday']) >= strtotime($t_date) && $t_Q == $next_Q  && ($t_Y == $Y)){
             }else if(strtotime($v['Tday']) >= strtotime($t_date) && (strtotime($v['Tday']) >= strtotime($nextQStart) && strtotime($v['Tday']) < strtotime($nextQEnd))){
                 $rows[$k]["income_b"] = $v['unIncomeMoney'];
             //}else if(strtotime($v['Tday']) >= strtotime($t_date) && ($t_Y-$Y>= -1 || $t_Y-$Y <=1)){
             }else if(strtotime($v['Tday']) >= strtotime($t_date) && (strtotime($v['Tday']) >= strtotime($nextQEnd))){
             	 $rows[$k]["income_c"] = $v['unIncomeMoney'];
             }else if(strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) >= strtotime($t_3) ){
                 $rows[$k]["income_d"] = $v['unIncomeMoney'];
             }else if(strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) < strtotime($t_3) && strtotime($v['Tday']) >= strtotime($t_6)){
                 $rows[$k]["income_e"] = $v['unIncomeMoney'];
             }else if(strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) < strtotime($t_6) && strtotime($v['Tday']) >= strtotime($t_12)){
                 $rows[$k]["income_f"] = $v['unIncomeMoney'];
             }else if(strtotime($v['Tday']) < strtotime($t_date) && strtotime($v['Tday']) <  strtotime($t_12)){
                 $rows[$k]["income_g"] = $v['unIncomeMoney'];
             }

//               if(strtotime($v['Tday']) >= strtotime($t_date) && $t_Q == $Q && ($t_Y == $Y)){
//                   $rows[$k]["income_a"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) >= strtotime($t_date) && $t_Q == $next_Q  && ($t_Y == $Y)){
//                   $rows[$k]["income_b"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) >= strtotime($t_date) && ($t_Y-$Y>= -1 || $t_Y-$Y <=1)){
//                   $rows[$k]["income_c"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) < strtotime($t_date) && $this->count_days(strtotime($t_date),strtotime($v['Tday']))<=90 ){
//                   $rows[$k]["income_d"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) < strtotime($t_date) && $this->count_days(strtotime($t_date),strtotime($v['Tday']))>90 && $this->count_days(strtotime($t_date),strtotime($v['Tday']))<=180){
//                   $rows[$k]["income_e"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) < strtotime($t_date) && $this->count_days(strtotime($t_date),strtotime($v['Tday']))>180 && $this->count_days(strtotime($t_date),strtotime($v['Tday']))<=365){
//                   $rows[$k]["income_f"] = $v['unIncomeMoney'];
//               }else if(strtotime($v['Tday']) < strtotime($t_date) && $this->count_days(strtotime($t_date),strtotime($v['Tday']))>365){
//                   $rows[$k]["income_g"] = $v['unIncomeMoney'];
//               }
           }
        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);

    }

    //����
    function c_TinitExportExcel()
    {
        $service = $this->service;
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        $stateArr = array(
            "0" => "δ�ύ",
            "1" => "������",
            "2" => "ִ����",
            "3" => "�ѹر�",
            "4" => "�����",
            "5" => "�Ѻϲ�",
            "6" => "�Ѳ��",
            "7" => "�쳣�ر�",
        );
        $signStatus = array(
            '0' => 'δǩ��',
            '1' => '��ǩ��',
            '2' => '���δǩ��',
        );
        $rows = array();

        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
        $searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
        $searchArr[$searchConditionKey] = $searchConditionVal;

        $service->getParam($_REQUEST);

        if ($_REQUEST['searchSql'] != "undefined") {
            $conditionSql = stripslashes($_REQUEST['searchSql']);
            $conditionSql = str_replace("sql:"," ",$conditionSql);
            unset($_REQUEST['searchSql']);
        }else{
        	$conditionSql = " and 1=1 ";
        }


        if(isset($_REQUEST['isIncome'])){
            if($_REQUEST['isIncome'] == '0'){
                $conditionSql .= " and (r.money-r.incomMoney-r.deductMoney > 0)";
            }elseif($_REQUEST['isIncome'] == '1'){
                $conditionSql .= " and (r.money-r.incomMoney-r.deductMoney = 0)";
            }
            unset ($_REQUEST['isIncome']);
        }
        $t_date = date("Y-m-1");//ͳ��ʱ��
        if(isset($_REQUEST['advArr']) && is_array($_REQUEST['advArr'])){
        	foreach($_REQUEST['advArr'] as $key => $val){
        		if($val['searchField'] == "c.ExaDTOne"){
                    $t_date = $val['value'];
                    break;
        		}
        	}
        }
//        $service->searchArr['mySearchCondition'] = $conditionSql;

        // ����һ��������ˣ�����not in ����
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // �ͻ����ƹ���
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        //��¼��
//         $appId = $_SESSION['USER_ID'];
        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);

        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        ini_set('memory_limit', '1024M');
        $limit = $this->service->initLimit_treport($conditionSql,"t");
        if($limit){
            $rows = $service->listBySqlId('select_tdayInitList');
        }

        if (!empty ($rows)) {
            if (isset($service->searchArr['advSql'])) {
                unset($service->searchArr['advSql']);
            }
        }

        $arr = array();
        $arr['collection'] = $rows;

        //ƥ�䵼����
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);

        $tempCid = 0;
        $checkArr = "";
        $tempKey = 0;
        $reDao = new model_contract_checkaccept_checkaccept();
        $esmDao = new model_engineering_project_esmproject();

        foreach ($rows as $key => $row) {
        	if($tempCid != $row['contractId']){//�жϲ�ͬ���ȡ ������Ϣ
              $tempCid = $row['contractId'];
              $tempKey = 0;
			  $checkArr = $reDao->getDetail_d($row['contractId']);
			  $row["clauseInfo"] = $checkArr[$tempKey]['clauseInfo'];
			  unset($checkArr[$tempKey]);
              $tempKey += 1;
           }else{//���򣬸�ֵ��ȥ���Ѹ�ֵ val
               $row["clauseInfo"] = $checkArr[$tempKey]['clauseInfo'];
			   unset($checkArr[$tempKey]);
			   $tempKey += 1;
           }

           //����Ӧ�տ�
           if(strtotime(date("Y-m-1")) > strtotime($row['Tday']) && $row['unIncomeMoney'] > 0){
           	   $row["unCmoney"] = $row['unIncomeMoney'];
           }
           //Ӧ�տ��ж�
           if(!empty($row['Tday'])){
	         $Q = ceil(date("m")/3);//��ǰ����
	         if($Q+1>4){//�¼���
	         	$next_Q = 1;
	         }else{
	         	$next_Q = $Q+1;
	         }
	         $Y = date("Y");
	         $t_Y = substr($row['Tday'],0,-6);
	         $t_Q = ceil(substr($row['Tday'],5,-3)/3);

               $t_3 =  date("Y-m-d", strtotime("-3 months", strtotime($t_date)));
               $t_6 =  date("Y-m-d", strtotime("-6 months", strtotime($t_date)));
               $t_12 =  date("Y-m-d", strtotime("-12 months", strtotime($t_date)));

               $Qarr = array(1 => array('01','03'),2 => array('04','06'),3 => array('07','09'),4 => array('10','12'));// �����·ݶ�Ӧ����
               $nextQ_Y = ($next_Q < $Q)? ($Y + 1) : $Y;// �¼����������
               $QStart = $Y."-".$Qarr[$Q][0]."-01";// �����ȿ�ʼ����
               $QEnd = ($Qarr[$Q][1]+1 <= 12)? $Y."-".($Qarr[$Q][1]+1)."-01" : ($Y+1)."-01-01";// �����Ƚ������ڼ�һ�죨���¼��ȵĿ�ʼ���ڣ���
               $nextQStart = $nextQ_Y."-".$Qarr[$next_Q][0]."-01";// �¼��ȵĿ�ʼ����
               $nextQEnd = ($Qarr[$next_Q][1]+1 <= 12)? $nextQ_Y."-".($Qarr[$next_Q][1]+1)."-01" : ($nextQ_Y+1)."-01-01";// �¼��Ƚ������ڼ�һ�죨���¼����Ժ�Ŀ�ʼ���ڣ���


               if(strtotime($row['Tday']) >= strtotime($t_date) && $t_Q == $Q && ($t_Y == $Y)){
                   $row["income_a"] = $row['unIncomeMoney'];
                   //}else if(strtotime($row['Tday']) >= strtotime($t_date) && $t_Q == $next_Q  && ($t_Y == $Y)){
               }else if(strtotime($row['Tday']) >= strtotime($t_date) && (strtotime($row['Tday']) >= strtotime($nextQStart) && strtotime($row['Tday']) < strtotime($nextQEnd))){// �¼����ж��߼�
                   $row["income_b"] = $row['unIncomeMoney'];
                   //}else if(strtotime($row['Tday']) >= strtotime($t_date) && ($t_Y-$Y>= -1 || $t_Y-$Y <=1)){
               }else if(strtotime($row['Tday']) >= strtotime($t_date) && (strtotime($row['Tday']) >= strtotime($nextQEnd))){// �¼����Ժ��ж��߼�
                   $row["income_c"] = $row['unIncomeMoney'];
               }else if(strtotime($row['Tday']) < strtotime($t_date) &&  strtotime($row['Tday']) >= strtotime($t_3)){
                   $row["income_d"] = $row['unIncomeMoney'];
               }else if(strtotime($row['Tday']) < strtotime($t_date) && strtotime($row['Tday']) < strtotime($t_3) && strtotime($row['Tday']) >= strtotime($t_6)){
                   $row["income_e"] = $row['unIncomeMoney'];
               }else if(strtotime($row['Tday']) < strtotime($t_date) && strtotime($row['Tday']) < strtotime($t_6) && strtotime($row['Tday']) >= strtotime($t_12)){
                   $row["income_f"] = $row['unIncomeMoney'];
               }else if(strtotime($row['Tday']) < strtotime($t_date) && strtotime($row['Tday']) <  strtotime($t_12)){
                   $row["income_g"] = $row['unIncomeMoney'];
               }

           }

            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }

            array_push($dataArr, $colIdArr);
        }
        $tt = date("Y-m-d");
        return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr,"��ͬ�տ�����T����Ϣ��".$tt);
    }

    function c_TdataExportExcel()
    {
        $service = $this->service;
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        ini_set('memory_limit', '1024M');	//�����ڴ�
        $stateArr = array(
            "0" => "δ�ύ",
            "1" => "������",
            "2" => "ִ����",
            "3" => "�ѹر�",
            "4" => "�����",
            "5" => "�Ѻϲ�",
            "6" => "�Ѳ��",
            "7" => "�쳣�ر�",
        );
        $signStatus = array(
            '0' => 'δǩ��',
            '1' => '��ǩ��',
            '2' => '���δǩ��',
        );
        $rows = array();

        if(!isset($_GET['colId']) && !isset($_GET['colName'])){// ���ǰ��û�����Ӧ����ID�Լ�����,��SESSION�л�ȡ
            $records = $this->service->_db->getArray("select * from oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'exportContractTdate';");
            if($records){
                foreach ($records as $record){
                    if(isset($record['skey']) && $record['skey'] == 'ColId'){
                        $colIdStr = $record['svalue'];
                    }else if(isset($record['skey']) && $record['skey'] == 'ColName'){
                        $colNameStr = $record['svalue'];
                    }
                }
                $this->service->_db->query("DELETE FROM oa_system_session_records where userId = '{$_SESSION['USER_ID']}' and stype = 'exportContractTdate';");
            }else{
                $colIdStr = '';
                $colNameStr = '';
            }
        }else{
            $colIdStr = $_GET['colId'];
            $colNameStr = $_GET['colName'];
        }

        $searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
        $searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
        $searchArr[$searchConditionKey] = $searchConditionVal;

        if ($_REQUEST['searchSql'] != "undefined") {
            $conditionSql = stripslashes($_REQUEST['searchSql']);
            $conditionSql = str_replace("sql:"," ",$conditionSql);
            unset($_REQUEST['searchSql']);
        }else{
        	$conditionSql = " and 1=1 ";
        }
        $service->getParam($_REQUEST);

        if(isset($_REQUEST['isIncome'])){
            if($_REQUEST['isIncome'] == '0'){
                $conditionSql .= " and (c.contractMoney-c.incomeMoney-c.deductMoney-c.badMoney > 0)";
            }elseif($_REQUEST['isIncome'] == '1'){
                $conditionSql .= " and (c.contractMoney-c.incomeMoney-c.deductMoney-c.badMoney = 0)";
            }
            unset ($_REQUEST['isIncome']);
        }
        if(isset($_REQUEST['isReplan'])){
            if($_REQUEST['isReplan'] == '0'){
                $conditionSql .= " and r.payInfo is not null";
            }elseif($_REQUEST['isReplan'] == '1'){
                $conditionSql .= " and (r.tday != ' ' and r.tday != '0000-00-00' AND r.tday != ',' AND r.tday != ',,' AND r.tday != ',,,' AND r.tday != ',,,,' AND r.tday != ',,,,,' AND r.tday != ',,,,,,' AND r.tday != ',,,,,,,' AND r.tday != ',,,,,,,,' AND r.tday != ',,,,,,,,,' AND r.tday != ',,,,,,,,,,' AND r.tday != ',,,,,,,,,,,')";
            }
            unset ($_REQUEST['isReplan']);
        }
        $t_date = date("Y-m-d");//ͳ��ʱ��
        if(isset($_REQUEST['advArr']) && is_array($_REQUEST['advArr'])){
        	foreach($_REQUEST['advArr'] as $key => $val){
        		if($val['searchField'] == "c.ExaDTOne"){
                    $t_date = $val['value'];
                    break;
        		}
        	}
        }
        $service->searchArr['mySearchCondition'] = $conditionSql;
        //��¼��
//         $appId = $_SESSION['USER_ID'];
        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);

        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        ini_set('memory_limit', '1024M');
        // ����һ��������ˣ�����not in ����
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // �ͻ����ƹ���
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        //������Ȩ������
        $limit = $this->service->initLimit_treport($conditionSql,"t");

        //$service->asc = false;
        if($limit){
            $rows = $service->listBySqlId("select_tdayDataList");
        }else{
            $rows = array();
        }

        $arr = array();
        $arr['collection'] = $rows;

        //ƥ�䵼����
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);

        $tempCid = 0;
        $checkArr = "";
        $tempKey = 0;
        $reDao = new model_contract_contract_receiptplan();
        $esmDao = new model_engineering_project_esmproject();
        foreach ($rows as $key => $row) {
        	$reArr = $reDao->getDetail_d($row['id']);
            $esmState = $esmDao->getStatusNameByContractId_d($row['id']);
            if(empty($esmState)){
           	   switch($row['DeliveryStatus']) {
           	   	 case "WFH" : $esmState = "δ����";
           	       break;
           	     case "BFFH" : $esmState = "���ַ���";
           	       break;
           	     case "YFH" : $esmState = "�ѷ���";
           	       break;
           	   }
           }
            $incocmeAll = 0;
            $endDate = "";
            $reMoney = 0;
            for($i=0;$i<15;$i++){
           	   $j = $i + 1;
               $row["incomeDate_".$j] = $reArr[$i]["Tday"];
               $row["incomePtn_".$j] = $reArr[$i]["paymentPer"];
               $row["incomeMoney_".$j] = $reArr[$i]["money"] - $reArr[$i]["incomMoney"]-$reArr[$i]["deductMoney"];
               $row["invoiceMoney_".$j] = $reArr[$i]["money"] - $reArr[$i]["invoiceMoney"]-$reArr[$i]["deductMoney"];
                if(!empty($reArr[$i]["Tday"])){
                    $incocmeAll +=  $reArr[$i]["money"] - $reArr[$i]["incomMoney"]-$reArr[$i]["deductMoney"];
                }
               if(!empty($reArr[$i]['id'])){
               	  $tt = $this->service->getTdayListEndDate($row['id'], $reArr[$i]['paymenttermId']);
                  $tState = $this->service->getTdayListEndDate($row['id'], $reArr[$i]['paymenttermId']);
               }else{
               	  $tt = "-";
               }
               if(!empty($reArr[$i]['id'])){
               	 $endDate .= $tt." ; ";
               }
               if(!empty($reArr[$i]["Tday"])){
               	 $reMoney += $reArr[$i]['money'];
               }
            }
            //ת���ѧ����
            $incocmeAll = number_format($incocmeAll,2,'.','');
            $row["Tmoney"] = $incocmeAll;
            $row["projectEndDate"] = $endDate;
            $row["projectState"] = $esmState;
            $row["unTdayMoney"] = bcsub($row["unIncomeMoney"],$incocmeAll,2);



            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }

            array_push($dataArr, $colIdArr);
        }
        // $colArr = util_jsonUtil::iconvUTF2GBArr($colArr);

        return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr,"��ͬT����Ϣ��".$t_date);
    }

    function count_days($a,$b){
	    $a_dt = getdate($a);
	    $b_dt = getdate($b);
	    $a_new = mktime(12, 0, 0, $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
	    $b_new = mktime(12, 0, 0, $b_dt['mon'], $b_dt['mday'], $b_dt['year']);
	    return round(abs($a_new-$b_new)/86400);
	}

    /*************************T ��  ����****end****************************************************************/

   /********************* ����Ӧ�տ��  ��ʼ*****************************************************/
    /**
     * Ӧ���˿����
     */
    function c_reportIncomeList(){
    	$this->view('reportIncomeList');
    }
    function c_reportIncomeJson(){
        set_time_limit(0);
        $service = $this->service;
        $service->getParam($_REQUEST);

        $overPoint = isset($_REQUEST['overPoint'])? $_REQUEST['overPoint'] : '';
        $overPointY = $overPointM = '';
        if($overPoint != ''){
            $overPointArr = explode(".",$overPoint);
            $overPointY = $overPointArr[0];
            $overPointM = $overPointArr[1];
            $service->searchArr['overPointDate'] = str_replace('.','',$overPoint);
        }

        // ����һ��������ˣ�����not in ����
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // �ͻ����ƹ���
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        $limit = $service->initLimit_treport(null,"r");

        $service->asc = "formBelongName,areaName";
        $service->__SET('groupBy', "c.areaName");
        if($limit){
            $rows = $service->pageBySqlId("select_roportIncomeList");
        }

        //���� �ؿ���������
        foreach($rows as $k => $v){
        	//���� δ�ؿ��ܶ������
        	$unIncomeArr = $service->getUnIncomeArr($v['idStr'],$overPointY,$overPointM);
	        $rows[$k]['unInomeMoney'] = $unIncomeArr['unInomeMoney'];
	        $rows[$k]['unInomeMoney_q'] = $unIncomeArr['unInomeMoney_q'];
	        $rows[$k]['unInomeMoney_nq'] = $unIncomeArr['unInomeMoney_nq'];
	        $rows[$k]['unInomeMoney_aq'] = $unIncomeArr['unInomeMoney_aq'];
	        $rows[$k]['noTMoney'] = $unIncomeArr['noTMoney'];

	        $rows[$k]['unAccMoney'] = $rows[$k]['unInomeMoney'] + $rows[$k]['unInomeMoney_q'] + $rows[$k]['unInomeMoney_nq'] + $rows[$k]['unInomeMoney_aq'] + $rows[$k]['noTMoney'];
	        $rows[$k]['accMoney'] = $rows[$k]['incomeMoney'] + $rows[$k]['unAccMoney'];
	        $rows[$k]['conTMoney'] = $rows[$k]['unInomeMoney'] + $rows[$k]['unInomeMoney_q'] + $rows[$k]['unInomeMoney_nq'] + $rows[$k]['unInomeMoney_aq'];
	        $rows[$k]['rondIncome'] = round($rows[$k]['incomeMoney'] / ($rows[$k]['incomeMoney'] + $rows[$k]['unInomeMoney']),4) * 100 . "%";
        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);

    }
    function c_reportIncomeExcel(){

        $service = $this->service;
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);

        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
        $searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
        $searchArr[$searchConditionKey] = $searchConditionVal;

        if ($_REQUEST['searchSql'] != "undefined") {
            $conditionSql = stripslashes($_REQUEST['searchSql']);
            unset($_REQUEST['searchSql']);
        }else{
        	$conditionSql = "sql: and 1=1 ";
        }

        $service->getParam($_REQUEST);
        //��¼��
//         $appId = $_SESSION['USER_ID'];
        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);

        if (!empty($this->service->searchArr)) {
            $this->service->searchArr = array_merge($this->service->searchArr, $searchArr);
        } else {
            $this->service->searchArr = $searchArr;
        }

        $overPoint = isset($_REQUEST['overPoint'])? $_REQUEST['overPoint'] : '';
        $overPointY = $overPointM = '';
        if($overPoint != ''){
            $overPointArr = explode(".",$overPoint);
            $overPointY = $overPointArr[0];
            $overPointM = $overPointArr[1];
            $service->searchArr['overPointDate'] = str_replace('.','',$overPoint);
        }

        // ����һ��������ˣ�����not in ����
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // �ͻ����ƹ���
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        $limit = $service->initLimit_treport(null,"r");

        $service->asc = "formBelongName,areaName";
        $service->__SET('groupBy', "c.areaName");
        $rows = array();
        if($limit){
            ini_set('memory_limit', '1024M');
            $rows = $service->listBySqlId('select_roportIncomeList');
        }
        $arr = array();
        $arr['collection'] = $rows;

        //ƥ�䵼����
        $dataArr = array();
        $colIdArr = array_flip($colIdArr);

        foreach ($rows as $key => $row) {
        	//���� δ�ؿ��ܶ������
            $unIncomeArr = $service->getUnIncomeArr($row['idStr'],$overPointY,$overPointM);

	        $row['unInomeMoney'] = $unIncomeArr['unInomeMoney'];
	        $row['unInomeMoney3'] = $unIncomeArr['unInomeMoney3'];
	        $row['unInomeMoney6'] = $unIncomeArr['unInomeMoney6'];
	        $row['unInomeMoney12'] = $unIncomeArr['unInomeMoney12'];
	        $row['unInomeMoney24'] = $unIncomeArr['unInomeMoney24'];
	        $row['unInomeMoney_q'] = $unIncomeArr['unInomeMoney_q'];
	        $row['unInomeMoney_nq'] = $unIncomeArr['unInomeMoney_nq'];
	        $row['unInomeMoney_aq'] = $unIncomeArr['unInomeMoney_aq'];
	        $row['noTMoney'] = $unIncomeArr['noTMoney'];

	        $row['unAccMoney'] = $row['unInomeMoney'] + $row['unInomeMoney_q'] + $row['unInomeMoney_nq'] + $row['unInomeMoney_aq'] + $row['noTMoney'];
	        $row['accMoney'] = $row['incomeMoney'] + $row['unAccMoney'];
	        $row['conTMoney'] = $row['unInomeMoney'] + $row['unInomeMoney_q'] + $row['unInomeMoney_nq'] + $row['unInomeMoney_aq'];
	        $row['rondIncome'] = round($row['incomeMoney'] / ($row['incomeMoney'] + $row['unInomeMoney']),4) * 100 . "%";

            foreach ($colIdArr as $index => $val) {
                $colIdArr[$index] = $row[$index];
            }

            array_push($dataArr, $colIdArr);
        }
        return model_contract_common_contExcelUtil::export2ExcelUtil($colArr, $dataArr,"Ӧ���˿����");

    }


    /**
     * ����Ӧ�տ�
     */
    function c_reportUnAccList(){
    	$this->view('reportUnAccList');
    }
    function c_reportUnAccJson(){
        set_time_limit(0);
        $service = $this->service;
        $service->getParam($_REQUEST);
        $overPoint = isset($_REQUEST['overPoint'])? $_REQUEST['overPoint'] : '';
        $overPointY = $overPointM = '';
        if($overPoint != ''){
            $overPointArr = explode(".",$overPoint);
            $overPointY = $overPointArr[0];
            $overPointM = $overPointArr[1];
            $service->searchArr['overPointDate'] = str_replace('.','',$overPoint);
        }

        $limit = $service->initLimit_treport(null,"r");

        // ����һ��������ˣ�����not in ����
        $odDao = new model_common_otherdatas();
        $filterArr = array(
            'customerNameNotIn' => $odDao->getConfig("contractReport_filter_customerName") // �ͻ����ƹ���
        );
        foreach ($filterArr as $k => $v) {
            if ($v)
                $service->searchArr[$k] = $v;
        }

        $service->asc = "formBelongName,areaName";
        $service->__SET('groupBy', "c.areaName");

        if($limit){
            $rows = $service->pageBySqlId("select_roportIncomeList");
        }
        //���� �ؿ���������
        foreach($rows as $k => $v){
        	//���� δ�ؿ��ܶ������
        	$unIncomeArr = $service->getUnIncomeArr($v['idStr'],$overPointY,$overPointM);

	        $rows[$k]['unInomeMoney'] = $unIncomeArr['unInomeMoney'];
	        $rows[$k]['unInomeMoney3'] = $unIncomeArr['unInomeMoney3'];
	        $rows[$k]['unInomeMoney6'] = $unIncomeArr['unInomeMoney6'];
	        $rows[$k]['unInomeMoney12'] = $unIncomeArr['unInomeMoney12'];
	        $rows[$k]['unInomeMoney24'] = $unIncomeArr['unInomeMoney24'];

        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);

    }

   /********************* ����Ӧ�տ��  ����*****************************************************/

    /**
     * ��ת��ͬ����ֵ����ҳ��
     */
    function c_toUpdateConRedundancy(){
        $this->view('updateConRedundancy');
    }

    /**
     *  ͨ��Ajax���º�ͬ�����
     */
    function c_ajaxUpdateCheckedItems(){
        $cid = isset($_POST['contractId'])? $_POST['contractId'] : '';
        $result = $this->service->updateContractObjComList_d($cid,'contractUpdate');
        echo $result? '1' : '0';
    }

    /**
     *  ͨ��Ajax���º�ͬ��Ϣ����������
     */
    function c_ajaxUpdateSalesContractVal(){
        $cid = isset($_POST['contractId'])? $_POST['contractId'] : '';
        $states = isset($_POST['states'])? $_POST['states'] : '';
        $result = $this->service->updateSalesContractVal_d($cid,'contractUpdate',$states);
        echo $result? '1' : '0';
    }

    /**
     *  ���º�ͬ������ֵ
     */
    function c_updateConRedundancy(){
        echo str_pad("������...",4096).'<hr />';
        flush();
        ob_flush();
        sleep(0.1);
        ini_set("memory_limit","1000M");
        set_time_limit(0);
        $service =  $this->service;
        $service->updateConRedundancy_d();
        echo "�������";
    }

    /**
     * ���º�ͬ����Ŀ״̬
     */
    function c_updateProjectStatus() {
        echo $this->service->updateProjectStatus_d($_GET['id']);
    }


    function c_ajaxGetExcelBtnLimit(){
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

        $type = isset($_REQUEST['type'])? $_REQUEST['type'] : '';
        if($type != ''){
            switch ($type){
                case 't':// T�ձ���
                    if(isset($sysLimit['T�ձ���Ȩ��'])){
                        echo $sysLimit['T�ձ���Ȩ��'];
                    }else{
                        echo "0";
                    }
                    break;
                case 'r':// Ӧ�տ��
                    if(isset($sysLimit['Ӧ�տ��Ȩ��'])){
                        echo $sysLimit['Ӧ�տ��Ȩ��'];
                    }else{
                        echo "0";
                    }
                    break;
            }
        }else{
           echo "0";
        }
    }

    /**
     * ��麣���޸�Ȩ��
     */
    function c_chkHwEditLimit(){
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

        if(isset($sysLimit['�����޸�'])){
            echo $sysLimit['�����޸�'];
        }else{
            echo "0";
        }
    }

    /**
     * ͨ����ͬID�Լ���ͬ����ȡ��ִͬ�н���
     */
    function c_getExePortion(){
        $cid = isset($_REQUEST['contractId'])? $_REQUEST['contractId'] : '';
        $contractMoney = isset($_REQUEST['contractMoney'])? $_REQUEST['contractMoney'] : 0;
        if($cid != ''){
            $esmprojectDao = new model_engineering_project_esmproject(); // ������Ŀ
            $proTmp = $esmprojectDao->getProByCid($cid);

            $allProjMoneyWithSchl = 0;// ��ͬ������Ŀ��ͬ��*��Ŀ����
            foreach($proTmp as $p){
                // �����Ϊ���������ֿ�ѧ������,���¼�����������
                $projectMoneyWithTax = sprintf("%.3f", $p['projectMoneyWithTax']);
                $projectMoneyWithTax = bcmul($projectMoneyWithTax,1,10);
                $allProjMoneyWithSchl += bcmul($projectMoneyWithTax,bcdiv($p['projectProcess'],100,6),6);
            }
            $allProjMoneyWithSchl = sprintf("%.3f", $allProjMoneyWithSchl);
            $conProgress = bcmul(bcdiv($allProjMoneyWithSchl,$contractMoney,9),100,3);
            $conProgress = round($conProgress,2);
            echo $conProgress;
        }else{
            echo 0;
        }
    }

    /**
     * ajax��������ͬ���Ƿ��ڿɱ䷶Χ�� (PMS 657)
     */
    function c_ajaxChkContractMoneyForChange(){
        $cid = isset($_REQUEST['contractId'])? $_REQUEST['contractId'] : '';
        $cMoney = isset($_REQUEST['contractMoney'])? $_REQUEST['contractMoney'] : '';
        $validMoneys = $this->service->getValidContractMoney($cid);
        $validAmount = 0;
        if(is_array($validMoneys)){
            foreach ($validMoneys as $key => $val){
                $validAmount = round(bcadd($validAmount,$val,4),2);
                $validAmount = sprintf("%.2f", $validAmount);
            }
            $validAmount = ($validAmount < 0)? 0 : $validAmount;
        }

        if($cMoney < $validAmount){
            echo $validAmount;
        }else{
            echo "ok";
        }
    }

    /**
     * �鵵��Ϣ�޸ı�
     */
    function c_toUpdateArchivedInfo(){
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        $this->assign('file', '�����κθ���');
        $this->assign('applyDate', day_date);

        //��ǰ����������
        $this->assign('thisUserId', $_SESSION['USER_ID']);
        $this->assign('thisUserName', $_SESSION['USERNAME']);

        //����ҵ�񾭰���Ϊ��ǰ��¼��
        $this->assign('attnId', $_SESSION['USER_ID']);
        $this->assign('attn', $_SESSION['USERNAME']);
        $this->assign('attnDeptId', $_SESSION['DEPT_ID']);
        $this->assign('attnDept', $_SESSION['DEPT_NAME']);

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), null, true);
        $this->assign('contractType', 'HTGZYD-04');
        $this->assign('contractTypeName', $this->getDataNameByCode('HTGZYD-04'));
        $this->view('update-archivedInfo');
    }

    /**
     * �޸Ĺ鵵��Ϣ
     */
    function c_updateArchivedInfo(){
        $postData = $_POST[$this->objName];
        $id = isset($postData['id'])? $postData['id'] : '';
        $updateArr = array();
        $updateArr['id'] = $id;
        $updateArr['contractName'] = isset($postData['contractName'])? $postData['contractName'] : '';
        $updateArr['partAContractCode'] = isset($postData['partAContractCode'])? $postData['partAContractCode'] : '';
        $updateArr['partAContractName'] = isset($postData['partAContractName'])? $postData['partAContractName'] : '';
        $updateArr['paperSignTime'] = isset($postData['paperSignTime'])? $postData['paperSignTime'] : '';
        $updateArr = $this->service->addUpdateInfo($updateArr);

        if(empty($id)){
            msg('����ʧ��!');
        }else{
            $result = $this->service->updateById($updateArr);
            if($result){
                msg('����ɹ�!');
            }else{
                msg('����ʧ��!');
            }
        }
    }

    /**
     * �򿪺�ͬ PMS 731
     */
    function c_restartContract(){
        $id = isset($_GET['id'])? $_GET['id'] : '';
        if(!empty($id)){
            $result = $this->service->updateById(array("id"=>$id,"state"=>4));

            if($result){
                // ���ִ�й켣��¼
                $tracksDao = new model_contract_contract_tracks();
                $proDao = new model_contract_conproject_conproject();
                $contract = $this->service->get_d($id);
                $tracksObject = array(
                    'contractId' => $contract['id'],//��ͬID
                    'contractCode'=> $contract['contractCode'],//��ͬ���
                    'exePortion' => $proDao->getConduleBycid($contract['id']),//��ִͬ�н���
                    'schedule' => "",
                    'modelName'=>'contractRestart',
                    'operationName'=>'���´򿪺�ͬ',
                    'result'=>'1',
                    'recordTime'=>date("Y-m-d H:i:s"),
                    'expand2'=>'model_contract_contract_contract:c_restartContract'
                );
                $result = $tracksDao->addRecord($tracksObject);
            }
            echo ($result)? "ok" : "fail";
        }else{
            echo "fail";
        }
    }
}