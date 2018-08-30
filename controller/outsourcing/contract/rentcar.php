<?php
/**
 * @author Michael
 * @Date 2014��3��6�� ������ 10:10:23
 * @version 1.0
 * @description:�⳵��ͬ���Ʋ�
 */
class controller_outsourcing_contract_rentcar extends controller_base_action {

	function __construct() {
		$this->objName = "rentcar";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }

	/**
	 * ��ת���⳵��ͬ�б�
	 */
	function c_page() {
		$this->assign ('userId', $_SESSION['USER_ID'] );
		$this->service->setCompany(0); # �����б�,����Ҫ���й�˾����
		$this->view('list');
	}

	/**
	 * ��ת���⳵��ͬ�����б�
	 */
	function c_toAllList() {
        $this->assign('projectId', isset($_GET['projectId'])?$_GET['projectId']:"");
		$this->view('list-all');
	}

	/**
	 * ��ת���⳵��ͬǩ���б�tabҳ
	 */
	function c_toSignTab(){
		$this->display('signTab');
	}

	/**
	 * ��ת���鿴�⳵��ͬ��Ŀ�б�
	 */
	function c_toViewProductList(){
		$this->assign('projectId' ,$_GET['projectId']);
		$this->view('view-projectList');
	}

	/**
	 * ��ת�������⳵��ͬҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ')); //��ͬ����
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX')); //��ͬ����
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK')); //��ͬ���ʽ

		$this->assign('deptId' ,$_SESSION['DEPT_ID']);
		$this->assign('deptName' ,$_SESSION['DEPT_NAME']);
		$this->assign('principalId' ,$_SESSION['USER_ID']);
		$this->assign('principalName' ,$_SESSION['USERNAME']);
		$this->assign('signDate' ,day_date);
		$this->assign('createTime' ,date("Y-m-d H:i:s"));

		$this->view ('add' ,true);
	}

	/**
	 * ��дadd
	 */
	function c_add(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$rentcarId = $this->service->add_d($_POST[$this->objName]);
		if($rentcarId) {
			if($actType) {
				$esmDao = new model_engineering_project_esmproject();
				$areaId = $esmDao->getRangeId_d($_POST[$this->objName]['projectId']);
				if($areaId > 0) {
					$billArea = $areaId;
				} else {
					$billArea = '';
				}
				succ_show('controller/outsourcing/contract/ewf_index.php?actTo=ewfSelect&billId='.$rentcarId.'&billArea='.$billArea);
			} else {
				msg( '����ɹ���' );
			}
		} else {
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���༭�⳵��ͬҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

        $rentUnitPriceCalWayOpts = ($obj['rentUnitPriceCalWay'] == 'byDay')? '<option value="byMonth">���¼Ʒ�</option><option value="byDay" selected>����Ʒ�</option>' : '<option value="byMonth" selected>���¼Ʒ�</option><option value="byDay">����Ʒ�</option>';
        $this->assign('rentUnitPriceCalWayOpts',$rentUnitPriceCalWayOpts);

		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ') ,$obj['contractNatureCode']); //��ͬ����
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX') ,$obj['contractTypeCode']); //��ͬ����
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK') ,$obj['payTypeCode']); //���ʽ
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,true)); //��ʾ������Ϣ

        // ������Ϣ
        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $rentCarFeeNameArr = $rentalcarDao->_rentCarFeeName;
        $carRentCostTypesOpts = '';
        foreach($rentCarFeeNameArr as $k => $v){
            $carRentCostTypesOpts .= "<option value=\"{$k}\">{$v}</option>";
        }
        $payInfosSql = "SELECT
                c.id,
                c.mainId,
                c.payTypeCode,
                c.payType,
                c.bankInfoId,
                IF(c.payTypeCode = 'HETFK',v.bankAccount,c.bankAccount) AS bankAccount,
                IF(c.payTypeCode = 'HETFK',v.bankName,c.bankName) AS bankName,
                IF(c.payTypeCode = 'HETFK',v.bankReceiver,c.bankReceiver) AS bankReceiver,
                c.includeFeeType,
                c.includeFeeTypeCode
            FROM
                oa_contract_rentcar_payinfos c
            LEFT JOIN oa_outsourcessupp_vehiclesupp v ON c.bankInfoId = v.id
            WHERE
                (c.isDel = 0 or c.isDel is null)
                AND c.mainId = '{$_GET ['id']}';";
        $payInfos = $this->service->_db->getArray($payInfosSql);
        $payInfoStr = '';
        if($payInfos && count($payInfos) >= 1){
            foreach ($payInfos as $k => $payInfo){
                $indexNum = $k + 1;
                $sltedType1 = ($payInfo['payTypeCode'] == 'BXFQR')? 'selected' : '';
                $sltedType2 = ($payInfo['payTypeCode'] == 'BXFSJ')? 'selected' : '';
                $sltedType3 = ($payInfo['payTypeCode'] == 'HETFK')? 'selected' : '';
                $payTypeOpts = '<option value="BXFQR" '.$sltedType1.'>������������</option><option value="BXFSJ" '.$sltedType2.'>������˾��</option>';
                $payTypeOpts .= ($obj['contractNatureCode'] == "ZCHTXZ-01")? '<option value="HETFK" '.$sltedType3.'>��ͬ����</option>' : '';
                $carRentCostTypesSlts = '<div id="carRentCostTypesOpts'.$indexNum.'"></div><select id="carRentCostTypesCombobox'.$indexNum.'" data-options="multiple:true" class="esayui-combobox" style="width:250px;">'.$carRentCostTypesOpts.'</select></div>';
                $bankInfoAttr = ($payInfo['payTypeCode'] == 'BXFSJ')? 'class="txt"' : 'class="readOnlyTxtNormal" readonly';
                $payInfoStr .= '<tr class="payFormTr payForm'.$indexNum.'" style="height:10px"><input type="hidden" class="hidden" id="payTypeId'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][id]" value="'.$payInfo['id'].'"/></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td rowspan="3"><input class="removePayItemBtn" id="removePayItemBtn'.$indexNum.'" data-index="'.$indexNum.'" type="button" value="-">'.
                    '<input id="payInfoCheckbox'.$indexNum.'" style="display:none" value="'.$indexNum.'" type="checkbox"></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">֧����ʽ<span class="payTypeNum" id="payTypeNum'.$indexNum.'">'.$indexNum.'</span>��</span></td>' .
                    '<td class="form_text_right_three" colspan="5">' .
                    '<select name="rentcar[payInfo]['.$indexNum.'][payTypeCode]" id="payTypeSlt'.$indexNum.'" data-index="'.$indexNum.'">' . $payTypeOpts .
                    '</select><input type="hidden" class="hidden" id="payType'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][payType]" value="'.$payInfo['payType'].'"/>' .
                    '</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span style="color:blue">�տ����У�</span></td><td class="form_text_right_three">' .
                    '<input type="text" id="bankName'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankName]" '.$bankInfoAttr.' value="'.$payInfo['bankName'].'">' .
                    '<input type="hidden" class="hidden" id="bankInfoId'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankInfoId]" value="'.$payInfo['bankInfoId'].'"/></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">�տ��˺ţ�</span></td><td class="form_text_right_three"><input type="text" id="bankAccount'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankAccount]" '.$bankInfoAttr.' value="'.$payInfo['bankAccount'].'"></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">�տ��ˣ�</span></td><td class="form_text_right_three"><input type="text" id="bankReceiver'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankReceiver]" '.$bankInfoAttr.' value="'.$payInfo['bankReceiver'].'"></td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span style="color:blue">���������</span></td>' .
                    '<td class="form_text_right_three includeFeeTypeWrap" colspan="5">'.$carRentCostTypesSlts.'<input type="hidden" class="hidden" id="includeFeeTypeCode'.$indexNum.'" data-index="'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][includeFeeTypeCode]" value="'.$payInfo['includeFeeTypeCode'].'"/><input type="hidden" class="hidden" id="includeFeeType'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][includeFeeType]" value="'.$payInfo['includeFeeType'].'"/></td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);
        $this->assign('payInfoCul',($payInfos)? count($payInfos) : 0);

		$this->view ('edit' ,true);
	}

	/**
	 * ����edit
	 */
	function c_edit(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$rentcarId = $this->service->edit_d($_POST[$this->objName]);
		if($rentcarId) {
			if($actType) {
				$esmDao = new model_engineering_project_esmproject();
				$areaId = $esmDao->getRangeId_d($_POST[$this->objName]['projectId']);
				if($areaId > 0) {
					$billArea = $areaId;
				} else {
					$billArea = '';
				}
				succ_show('controller/outsourcing/contract/ewf_index.php?actTo=ewfSelect&billId='.$rentcarId.'&billArea='.$billArea);
			} else{
				msg( '����ɹ���' );
			}
		} else{
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���鿴�⳵��ͬҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
        if($obj['rentUnitPriceCalWay'] == 'byDay'){
            $obj['rentUnitPriceCalWay'] = '����Ʒ�';
            $obj['rentUnitPriceLabel'] = '����(Ԫ/��/��)';
        }else{
            $obj['rentUnitPriceCalWay'] = '���¼Ʒ�';
            $obj['rentUnitPriceLabel'] = '����(Ԫ/��/��)';
        }

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//�Ƿ��������
		if ($obj['isNeedStamp'] == 0) {
			$this->assign("isNeedStamp" ,'��');
		} else {
			$this->assign("isNeedStamp" ,'��');
		}

		//�Ƿ�ʹ���Ϳ�
		if ($obj['isUseOilcard'] == 1) {
			$this->assign("isUseOilcard" ,'��');
			$this->assign("isUseOilcardVal" ,1);
		} else {
			$this->assign("isUseOilcard" ,'��');
			$this->assign("isUseOilcardVal" ,0);
		}

		//���ǧ��λ��ʾ
		$this->assign("orderMoney" ,number_format($obj['orderMoney'] ,2)); //��ͬ���
		$this->assign("rentUnitPrice" ,number_format($obj['rentUnitPrice'] ,2)); //���޽��
		$this->assign("oilcardMoney" ,number_format($obj['oilcardMoney'] ,2)); //�Ϳ����
		$this->assign("oilPrice" ,number_format($obj['oilPrice'] ,2)); //�ͼ�
		$this->assign("fuelCharge" ,number_format($obj['fuelCharge'] ,2)); //ȼ�ͷ�
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //��ʾ������Ϣ
		if(isset($_GET['hideBtn'])){
			$this->assign('hideBtn',$_GET['hideBtn']);
		}

        // ������Ϣ
        $payInfosSql = "SELECT
                c.id,
                c.mainId,
                c.payTypeCode,
                c.payType,
                c.bankInfoId,
                IF(c.payTypeCode = 'HETFK1',v.bankAccount,c.bankAccount) AS bankAccount,
                IF(c.payTypeCode = 'HETFK1',v.bankName,c.bankName) AS bankName,
                IF(c.payTypeCode = 'HETFK1',v.bankReceiver,c.bankReceiver) AS bankReceiver,
                c.includeFeeType,
                c.includeFeeTypeCode
            FROM
                oa_contract_rentcar_payinfos c
            LEFT JOIN oa_outsourcessupp_vehiclesupp v ON c.bankInfoId = v.id
            WHERE
                (c.isDel = 0 or c.isDel is null)
                AND c.mainId = '{$_GET ['id']}';";
        $payInfos = $this->service->_db->getArray($payInfosSql);
        $payInfoStr = '';
        if($payInfos && count($payInfos) >= 1){
            foreach ($payInfos as $k => $payInfo){
                $indexNum = $k + 1;
                $payType = '������������';
                switch($payInfo['payTypeCode']){
                    case 'BXFQR':
                        $payType = '������������';
                        break;
                    case 'BXFSJ':
                        $payType = '������˾��';
                        break;
                    case 'HETFK':
                        $payType = '��ͬ����';
                        break;
                }
                $payInfoStr .= '<tr style="height:10px"></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three">֧����ʽ'.$indexNum.'��</td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payType.'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span>�տ����У�</span></td><td class="form_text_right_three">' . $payInfo['bankName'] .'</td>' .
                    '<td class="form_text_left_three"><span>�տ��˺ţ�</span></td><td class="form_text_right_three">'.$payInfo['bankAccount'].'</td>' .
                    '<td class="form_text_left_three"><span>�տ��ˣ�</span></td><td class="form_text_right_three">'.$payInfo['bankReceiver'].'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span>���������</span></td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payInfo['includeFeeType'].'</td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);

        $closeBtnStyle = isset($_GET['notCloseBtn'])? "style='display:none'" : "";
        $this->assign('closeBtnStyle', $closeBtnStyle);

		$this->view ( 'view' );
	}

	/**
	 * ����ʱ�鿴�⳵��ͬҳ��
	 */
	function c_toAudit() {
		$obj = $this->service->get_d ( $_GET ['id'] );

        if($obj['rentUnitPriceCalWay'] == 'byDay'){
            $obj['rentUnitPriceCalWay'] = '����Ʒ�';
            $obj['rentUnitPriceLabel'] = '����(Ԫ/��/��)';
        }else{
            $obj['rentUnitPriceCalWay'] = '���¼Ʒ�';
            $obj['rentUnitPriceLabel'] = '����(Ԫ/��/��)';
        }

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//�Ƿ��������
		if ($obj['isNeedStamp'] == 0) {
			$this->assign("isNeedStamp" ,'��');
		} else {
			$this->assign("isNeedStamp" ,'��');
		}

		//�Ƿ�ʹ���Ϳ�
		if ($obj['isUseOilcard'] == 1) {
			$this->assign("isUseOilcard" ,'��');
			$this->assign("isUseOilcardVal" ,1);
		} else {
			$this->assign("isUseOilcard" ,'��');
			$this->assign("isUseOilcardVal" ,0);
		}

		//���ǧ��λ��ʾ
		$this->assign("orderMoney" ,number_format($obj['orderMoney'] ,2)); //��ͬ���
		$this->assign("rentUnitPrice" ,number_format($obj['rentUnitPrice'] ,2)); //���޽��
		$this->assign("oilcardMoney" ,number_format($obj['oilcardMoney'] ,2)); //�Ϳ����
		$this->assign("oilPrice" ,number_format($obj['oilPrice'] ,2)); //�ͼ�
		$this->assign("fuelCharge" ,number_format($obj['fuelCharge'] ,2)); //ȼ�ͷ�
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //��ʾ������Ϣ

        // ������Ϣ
        $payInfosSql = "SELECT
                c.id,
                c.mainId,
                c.payTypeCode,
                c.payType,
                c.bankInfoId,
                IF(c.payTypeCode = 'HETFK',v.bankAccount,c.bankAccount) AS bankAccount,
                IF(c.payTypeCode = 'HETFK',v.bankName,c.bankName) AS bankName,
                IF(c.payTypeCode = 'HETFK',v.bankReceiver,c.bankReceiver) AS bankReceiver,
                c.includeFeeType,
                c.includeFeeTypeCode
            FROM
                oa_contract_rentcar_payinfos c
            LEFT JOIN oa_outsourcessupp_vehiclesupp v ON c.bankInfoId = v.id
            WHERE
                (c.isDel = 0 or c.isDel is null)
                AND c.mainId = '{$_GET ['id']}';";
        $payInfos = $this->service->_db->getArray($payInfosSql);
        $payInfoStr = '';
        if($payInfos && count($payInfos) >= 1){
            foreach ($payInfos as $k => $payInfo){
                $indexNum = $k + 1;
                $payType = '������������';
                switch($payInfo['payTypeCode']){
                    case 'BXFQR':
                        $payType = '������������';
                        break;
                    case 'BXFSJ':
                        $payType = '������˾��';
                        break;
                    case 'HETFK':
                        $payType = '��ͬ����';
                        break;
                }
                $payInfoStr .= '<tr style="height:10px"></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three">֧����ʽ'.$indexNum.'��</td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payType.'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span>�տ����У�</span></td><td class="form_text_right_three">' . $payInfo['bankName'] .'</td>' .
                    '<td class="form_text_left_three"><span>�տ��˺ţ�</span></td><td class="form_text_right_three">'.$payInfo['bankAccount'].'</td>' .
                    '<td class="form_text_left_three"><span>�տ��ˣ�</span></td><td class="form_text_right_three">'.$payInfo['bankReceiver'].'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span>���������</span></td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payInfo['includeFeeType'].'</td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);

		$this->view ( 'view-audit' );
	}

	/**
	 *��ת�������ҳ��
	 */
	function c_toStamp(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('applyDate' ,day_date);

		//��ǰ����������
		$this->assign('thisUserId' ,$_SESSION['USER_ID']);
		$this->assign('thisUserName' ,$_SESSION['USERNAME']);

		$this->view ('stamp' ,true);
	}

	/**
	 * ����������Ϣ����
	 */
	function c_stamp(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$rs = $this->service->stamp_d($_POST[$this->objName]);
		if ($rs) {
			msg ( "����ɹ���" );
		}else{
			msg ( "����ʧ�ܣ�" );
		}
	}

	/**
	 * �⳵��ͬtabҳ
	 */
	function c_viewTab(){
		$this->assign('id' ,$_GET['id']);
        $closeBtnTip = isset($_GET['notCloseBtn'])? "&notCloseBtn=1" : "";
        $this->assign('closeBtnTip', $closeBtnTip);
		$this->display ( 'viewtab' );
	}

	/**
	 * ��ת����ͬ�������ҳ��
	 */
	function c_toChange(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK') ,$obj['payTypeCode']); //���ʽ
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //��ʾ������Ϣ

        $rentUnitPriceCalWayOpts = ($obj['rentUnitPriceCalWay'] == 'byDay')? '<option value="byMonth">���¼Ʒ�</option><option value="byDay" selected>����Ʒ�</option>' : '<option value="byMonth" selected>���¼Ʒ�</option><option value="byDay">����Ʒ�</option>';
        $this->assign('rentUnitPriceCalWayOpts',$rentUnitPriceCalWayOpts);

        // ������Ϣ
        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $rentCarFeeNameArr = $rentalcarDao->_rentCarFeeName;
        $carRentCostTypesOpts = '';
        foreach($rentCarFeeNameArr as $k => $v){
            $carRentCostTypesOpts .= "<option value=\"{$k}\">{$v}</option>";
        }
        $payInfosSql = "SELECT
                c.id,
                c.mainId,
                c.payTypeCode,
                c.payType,
                c.bankInfoId,
                IF(c.payTypeCode = 'HETFK',v.bankAccount,c.bankAccount) AS bankAccount,
                IF(c.payTypeCode = 'HETFK',v.bankName,c.bankName) AS bankName,
                IF(c.payTypeCode = 'HETFK',v.bankReceiver,c.bankReceiver) AS bankReceiver,
                c.includeFeeType,
                c.includeFeeTypeCode
            FROM
                oa_contract_rentcar_payinfos c
            LEFT JOIN oa_outsourcessupp_vehiclesupp v ON c.bankInfoId = v.id
            WHERE
                (c.isDel = 0 or c.isDel is null)
                AND c.mainId = '{$_GET ['id']}';";
        $payInfos = $this->service->_db->getArray($payInfosSql);
        $payInfoStr = '';
        if($payInfos && count($payInfos) >= 1){
            foreach ($payInfos as $k => $payInfo){
                $indexNum = $k + 1;
                $sltedType1 = ($payInfo['payTypeCode'] == 'BXFQR')? 'selected' : '';
                $sltedType2 = ($payInfo['payTypeCode'] == 'BXFSJ')? 'selected' : '';
                $sltedType3 = ($payInfo['payTypeCode'] == 'HETFK')? 'selected' : '';
                $payTypeOpts = '<option value="BXFQR" '.$sltedType1.'>������������</option><option value="BXFSJ" '.$sltedType2.'>������˾��</option>';
                $payTypeOpts .= ($obj['contractNatureCode'] == "ZCHTXZ-01")? '<option value="HETFK" '.$sltedType3.'>��ͬ����</option>' : '';
                $carRentCostTypesSlts = '<div id="carRentCostTypesOpts'.$indexNum.'"></div><select id="carRentCostTypesCombobox'.$indexNum.'" data-options="multiple:true" class="esayui-combobox" style="width:250px;">'.$carRentCostTypesOpts.'</select></div>';
                $bankInfoAttr = ($payInfo['payTypeCode'] == 'BXFSJ')? 'class="txt"' : 'class="readOnlyTxtNormal" readonly';
                $payInfoStr .= '<tr class="payFormTr payForm'.$indexNum.'" style="height:10px"><input type="hidden" class="hidden mainElement'.$indexNum.'" id="payTypeId'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][id]" value="'.$payInfo['id'].'"/></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td rowspan="3"><input class="removePayItemBtn" id="removePayItemBtn'.$indexNum.'" data-index="'.$indexNum.'" type="button" value="-">'.
                    '<input id="payInfoCheckbox'.$indexNum.'" style="display:none" value="'.$indexNum.'" type="checkbox"></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">֧����ʽ<span class="payTypeNum" id="payTypeNum'.$indexNum.'">'.$indexNum.'</span>��</span></td>' .
                    '<td class="form_text_right_three" colspan="5">' .
                    '<select name="rentcar[payInfo]['.$indexNum.'][payTypeCode]" id="payTypeSlt'.$indexNum.'" data-index="'.$indexNum.'">' . $payTypeOpts .
                    '</select><input type="hidden" class="hidden" id="payType'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][payType]" value="'.$payInfo['payType'].'"/>' .
                    '</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span style="color:blue">�տ����У�</span></td><td class="form_text_right_three">' .
                    '<input type="text" id="bankName'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankName]" '.$bankInfoAttr.' value="'.$payInfo['bankName'].'">' .
                    '<input type="hidden" class="hidden" id="bankInfoId'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankInfoId]" value="'.$payInfo['bankInfoId'].'"/></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">�տ��˺ţ�</span></td><td class="form_text_right_three"><input type="text" id="bankAccount'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankAccount]" '.$bankInfoAttr.' value="'.$payInfo['bankAccount'].'"></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">�տ��ˣ�</span></td><td class="form_text_right_three"><input type="text" id="bankReceiver'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankReceiver]" '.$bankInfoAttr.' value="'.$payInfo['bankReceiver'].'"></td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span style="color:blue">���������</span></td>' .
                    '<td class="form_text_right_three includeFeeTypeWrap" colspan="5">'.$carRentCostTypesSlts.'<input type="hidden" class="hidden" id="includeFeeTypeCode'.$indexNum.'" data-index="'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][includeFeeTypeCode]" value="'.$payInfo['includeFeeTypeCode'].'"/><input type="hidden" class="hidden" id="includeFeeType'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][includeFeeType]" value="'.$payInfo['includeFeeType'].'"/></td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);
        $this->assign('payInfoCul',($payInfos)? count($payInfos) : 0);

		$this->view('change' ,true);
	}

	/**
	 * �������
	 */
	function c_change() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		try {
			$object = $_POST[$this->objName];
			$object['oilcardMoney'] = ($object['oilcardMoney'] == '' ? 0.00 : $object['oilcardMoney']);
			$object['oilPrice'] = ($object['oilPrice'] == '' ? 0.00 : $object['oilPrice']);
			$object['fuelCharge'] = ($object['fuelCharge'] == '' ? 0.00 : $object['fuelCharge']);
			$id = $this->service->change_d($object);
			$esmDao = new model_engineering_project_esmproject();
			$areaId = $esmDao->getRangeId_d($object['projectId']);
			if($areaId > 0) {
				$billArea = $areaId;
			} else {
				$billArea = '';
			}
			succ_show('controller/outsourcing/contract/ewf_change.php?actTo=ewfSelect&billId=' . $id .'&billArea=' . $billArea);
		} catch (Exception $e) {
			msgBack2("���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage ());
		}
	}

	/**
	 * ����鿴tab
	 */
	function c_toChangeTab(){
		$this->permCheck (); //��ȫУ��
		$newId = $_GET ['id'];
		$this->assign('id' ,$newId);

		$rs = $this->service->find(array('id' => $newId ) ,null ,'originalId');
		$this->assign('originalId' ,$rs['originalId']);

		$this->display('changetab');
	}

	/**
	 * �鿴(�����ͬ-ԭ��ͬ)����
	 */
	function c_changeView(){
		$id = $_GET['id'];
		$obj = $this->service->get_d( $id );

        if($obj['rentUnitPriceCalWay'] == 'byDay'){
            $obj['rentUnitPriceCalWay'] = '����Ʒ�';
            $obj['rentUnitPriceLabel'] = '����(Ԫ/��/��)';
        }else{
            $obj['rentUnitPriceCalWay'] = '���¼Ʒ�';
            $obj['rentUnitPriceLabel'] = '����(Ԫ/��/��)';
        }

		$this->assignFunc($obj);
		$this->assign('file' ,$this->service->getFilesByObjId ( $id, false));
		$this->assign('isNeedStamp' ,$obj['isNeedStamp'] ? '��' : '��');
		$this->assign('isUseOilcard' ,$obj['isUseOilcard'] ? '��' : '��');
		$this->assign('isUseOilcardVal' ,$obj['isUseOilcard'] ? 1 : 0);

        // ������Ϣ
        $payInfosSql = "SELECT
                c.id,
                c.mainId,
                c.payTypeCode,
                c.payType,
                c.bankInfoId,
                IF(c.payTypeCode = 'HETFK',v.bankAccount,c.bankAccount) AS bankAccount,
                IF(c.payTypeCode = 'HETFK',v.bankName,c.bankName) AS bankName,
                IF(c.payTypeCode = 'HETFK',v.bankReceiver,c.bankReceiver) AS bankReceiver,
                c.includeFeeType,
                c.includeFeeTypeCode
            FROM
                oa_contract_rentcar_payinfos c
            LEFT JOIN oa_outsourcessupp_vehiclesupp v ON c.bankInfoId = v.id
            WHERE
                (c.isDel = 0 or c.isDel is null)
                AND c.mainId = '{$_GET ['id']}';";
        $payInfos = $this->service->_db->getArray($payInfosSql);
        $payInfoStr = '';
        if($payInfos && count($payInfos) >= 1){
            foreach ($payInfos as $k => $payInfo){
                $indexNum = $k + 1;
                $payType = '������������';
                switch($payInfo['payTypeCode']){
                    case 'BXFQR':
                        $payType = '������������';
                        break;
                    case 'BXFSJ':
                        $payType = '������˾��';
                        break;
                    case 'HETFK':
                        $payType = '��ͬ����';
                        break;
                }
                $payInfoStr .= '<tr style="height:10px"></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three">֧����ʽ'.$indexNum.'��</td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payType.'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span>�տ����У�</span></td><td class="form_text_right_three">' . $payInfo['bankName'] .'</td>' .
                    '<td class="form_text_left_three"><span>�տ��˺ţ�</span></td><td class="form_text_right_three">'.$payInfo['bankAccount'].'</td>' .
                    '<td class="form_text_left_three"><span>�տ��ˣ�</span></td><td class="form_text_right_three">'.$payInfo['bankReceiver'].'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span>���������</span></td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payInfo['includeFeeType'].'</td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);

		$this->view ( 'changeview' );
	}

	/**
	 * ��ͬ������ɺ�����µķ���
	 */
	function c_dealAfterAudit(){
		$this->service->workflowCallBack($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ��ͬ���������ɺ�����
	 */
	function c_dealAfterAuditChange(){
		$this->service->workflowCallBack_change($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ���븶����֤����
	 */
	function c_canPayapply(){
		$id = $_POST['id'];
		$rs = $this->service->canPayapply_d($id);
		echo $rs;
		exit();
	}

	/**
	 * �˿�������֤
	 */
	function c_canPayapplyBack(){
		$id = $_POST['id'];
		$rs = $this->service->canPayapplyBack_d($id);
		echo $rs;
		exit();
	}

	/**
	 * �رպ�ͬ
	 */
	function c_changeStatus() {
		if($this->service->updateById(array('id'=>$_POST['id'] ,'status' => '3'))){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * ��ͬǩ�� - ��ǩ�պ�ͬ�б�
	 */
	function c_signingList(){
		$this->view('signinglist');
	}

	/**
	 * ��ͬǩ�� - ��ǩ�պ�ͬ�б�
	 */
	function c_signedList(){
		$this->view('signedlist');
	}

	/**
	 * ��ת����ͬǩ��ҳ��
	 */
	function c_toSign(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//�������{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ), $obj ['payType'] );//��ͬ���ʽ
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//��ͬ�����ʽ

		$this->view('sign' ,true);
	}

	/**
	 * ��ͬǩ�� - ǩ�չ���
	 */
	function c_sign(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$object = $_POST[$this->objName];
		$object['oilPrice'] = ($object['oilPrice'] == '' ? 0.00 : $object['oilPrice']);
		$object['fuelCharge'] = ($object['fuelCharge'] == '' ? 0.00 : $object['fuelCharge']);
		$id = $this->service->sign_d ( $object);
		if ($id) {
			msgRf('ǩ�ճɹ�');
		}else{
			msgRf('ǩ��ʧ��');
		}
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toExcelIn() {
		$this->view('excelIn');
	}

	/**
	 * ����
	 */
	function c_excelIn() {
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '�⳵��ͬ�������б�';
		$thead = array('������Ϣ','������');
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/**
	 * ��ת���������������Ŀҳ��
	 */
	function c_toExcelPro() {
		$this->view('excelPro');
	}

	/**
	 * �������������Ŀ
	 */
	function c_excelPro() {
		set_time_limit(0);
		$resultArr = $this->service->excelPro_d ();

		$title = '�⳵��ͬ����������Ŀ����б�';
		$thead = array('������Ϣ','������');
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toExcelOut() {
		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ')); //��ͬ����
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX')); //��ͬ����
		if ($_GET['isCreate']) { //�����б�㵼��
			$this->assign ('isCreate', 1);
			$this->assign ('createName', $_SESSION['USERNAME']);
		} else { //���ܵ㵼��
			$this->assign ('isCreate', 0);
		}
		$this->view('excelOut');
	}

	/**
	 * ��ת���߼�����ҳ��
	 */
	function c_toSearchAdv() {
		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ')); //��ͬ����
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX')); //��ͬ����
		$this->view('searchAdv');
	}

	/**
	 * ����excel
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['createDateSta'])) //¼��ʱ����
			$this->service->searchArr['createDateSta'] = $formData['createDateSta'];
		if(!empty($formData['createDateEnd'])) //¼��ʱ����
			$this->service->searchArr['createDateEnd'] = $formData['createDateEnd'];

		if(!empty($formData['orderCode'])) //������ͬ���
			$this->service->searchArr['orderCode'] = $formData['orderCode'];

		if(!empty($formData['contractNatureCode'])) //��ͬ����
			$this->service->searchArr['contractNatureCode'] = $formData['contractNatureCode'];

		if(!empty($formData['contractTypeCode'])) //��ͬ����
			$this->service->searchArr['contractTypeCode'] = $formData['contractTypeCode'];

		if(!empty($formData['orderName'])) //��ͬ����
			$this->service->searchArr['orderName'] = $formData['orderName'];

		if(!empty($formData['signCompany'])) //ǩԼ��˾
			$this->service->searchArr['signCompany'] = $formData['signCompany'];

	 	if(!empty($formData['companyProvinceCode'])) //��˾ʡ��
			$this->service->searchArr['companyProvinceCode'] = $formData['companyProvinceCode'];

	 	if(!empty($formData['ownCompany'])) //������˾
			$this->service->searchArr['ownCompanyArr'] = $formData['ownCompany'];

		if(!empty($formData['signDateSta'])) //ǩԼ������
			$this->service->searchArr['signDateSta'] = $formData['signDateSta'];
		if(!empty($formData['signDateEnd'])) //ǩԼ������
			$this->service->searchArr['signDateEnd'] = $formData['signDateEnd'];

	 	if(!empty($formData['createName'])) //������
			$this->service->searchArr['createName'] = $formData['createName'];

		if($formData['isCreate'] == 0)
			$this->service->searchArr['statusArr'] = '1,2,3,4';

        $this->service->searchArr['ExaStatusArr'] ="���,���������";

		$rows = $this->service->listBySqlId('select_financeInfo');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['createDate'] = $v['createDate'];
			$rowData[$k]['orderCode'] = $v['orderCode'];
			$rowData[$k]['contractNature'] = $v['contractNature'];
			$rowData[$k]['contractType'] = $v['contractType'];
			$rowData[$k]['projectName'] = $v['projectName'];
			$rowData[$k]['projectCode'] = $v['projectCode'];
			$rowData[$k]['orderName'] = $v['orderName'];
			$rowData[$k]['signCompany'] = $v['signCompany'];
			$rowData[$k]['companyProvince'] = $v['companyProvince'];
			$rowData[$k]['ownCompany'] = $v['ownCompany'];
			$rowData[$k]['signDate'] = $v['signDate'];
			$rowData[$k]['orderMoney'] = $v['orderMoney'];
            $rowData[$k]['contractStartDate'] = $v['contractStartDate'];
            $rowData[$k]['contractEndDate'] = $v['contractEndDate'];
            $rowData[$k]['rentUnitPrice'] = $v['rentUnitPrice'];
            $rowData[$k]['fuelCharge'] = $v['fuelCharge'];
            $rowData[$k]['payApplyMoney'] = $v['payApplyMoney'];
            $rowData[$k]['payedMoney'] = $v['payedMoney'];
            $rowData[$k]['invotherMoney'] = $v['invotherMoney'];
            $rowData[$k]['confirmInvotherMoney'] = $v['confirmInvotherMoney'];
            $rowData[$k]['needInvotherMoney'] = $v['needInvotherMoney'];
			$rowData[$k]['returnMoney'] = $v['returnMoney'];
			$rowData[$k]['ExaStatus'] = $v['ExaStatus'];
			$rowData[$k]['signedStatus'] = ($v['signedStatus'] == 1 ? '��ǩ��' : 'δǩ��');
			$rowData[$k]['objCode'] = $v['objCode'];
			$rowData[$k]['isNeedStamp'] = ($v['isNeedStamp'] == 1 ? '��' : '��');
			$rowData[$k]['isStamp'] = ($v['isStamp'] == 1 ? '��' : '��');
			$rowData[$k]['stampType'] = $v['stampType'];
			$rowData[$k]['createName'] = $v['createName'];
			$rowData[$k]['updateTime'] = $v['updateTime'];
		}
		$colArr = array();
		$modelName = '���-�⳵��ͬ��Ϣ';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	 }

	/**
	 * ��ȡ�⳵���������Ϣ
	 */
	function c_getRentcarInformation() {
		$service = $this->service;
		$service->getParam( $_REQUEST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d('select_rentCarInformation');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows( $rows );
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
	 * ������ĿID�ͳ��ƺŻ�ȡ��ͬ��Ϣ
	 */
	function c_ajaxGetContract() {
		$projectId = $_POST['projectId'];
		$this->service->searchArr['projectId'] = $projectId;

		$carNum = $_POST['carNum'];
		$this->service->searchArr['carNum'] = $carNum;

		$rs = $this->service->listBySqlId('select_byPidCar');
		if ($rs) {
			echo $rs;
		} else {
			echo 'false';
		}
	}

	/**
	 * �����ϴ�
	 */
	function c_toUploadFile() {
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file' ,$this->service->getFilesByObjId($obj['id'] ,false ,$this->service->tbl_name));
		$this->assignFunc($obj);

		$this->view('uploadfile');
	}

	/**
	 * ���ݹ�Ӧ��id�����Ƿ������Ч���ڵ�ִ�к�ͬ
	 */
	function c_checkBySuppId() {
		$suppId = $_POST['suppId'];
		$date = date('Y-m-d');
		$conditions = " signCompanyId = $suppId "
					." AND contractStartDate < '$date' "
					." AND contractEndDate > '$date' "
					." AND isTemp = 0 "
					." AND status = 2 ";
		$num = $this->service->findCount($conditions);
		if ($num == 0) {
			echo 'true';
		} else {
			echo 'false';
		}
	}

	/**
	 * ��ת�������⳵����id������ͬҳ��
	 */
	function c_toAddByRentalcar() {
		$rentalcarDao = new model_outsourcing_vehicle_rentalcar();
		$rentalcarObj = $rentalcarDao->get_d($_GET['rentalcarId']);

		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ')); //��ͬ����
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX')); //��ͬ����
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK')); //��ͬ���ʽ

		$this->assign('deptId' ,$_SESSION['DEPT_ID']);
		$this->assign('deptName' ,$_SESSION['DEPT_NAME']);
		$this->assign('principalId' ,$_SESSION['USER_ID']);
		$this->assign('principalName' ,$_SESSION['USERNAME']);
		$this->assign('signDate' ,day_date);
		$this->assign('createTime' ,date("Y-m-d H:i:s"));

		$this->assign('rentalcarId' ,$rentalcarObj['id']); //���뵥ID
		$this->assign('rentalcarCode' ,$rentalcarObj['formCode']); //���뵥��

		$projectdao = new model_engineering_project_esmproject();
		$projectObj = $projectdao->get_d($rentalcarObj['projectId']);
		$this->assign('projectId' ,$rentalcarObj['projectId']); //��ĿID
		$this->assign('projectCode' ,$rentalcarObj['projectCode']); //��Ŀ���
		$this->assign('projectName' ,$rentalcarObj['projectName']); //��Ŀ����
		$this->assign('projectType' ,$projectObj['natureName']); //��Ŀ����
		$this->assign('projectTypeCode' ,$projectObj['nature']); //��Ŀ����Code
		$this->assign('projectManagerId' ,$projectObj['managerId']); //��Ŀ����ID
		$this->assign('projectManager' ,$projectObj['managerName']); //��Ŀ����
		$this->assign('officeId' ,$projectObj['officeId']); //����ID
		$this->assign('officeName' ,$projectObj['officeName']); //����

		$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		$suppObj = $suppDao->get_d($_GET['suppId']);
		$this->assign('signCompanyId' ,$suppObj['id']); //ǩԼ��˾id
		$this->assign('signCompany' ,$suppObj['suppName']); //ǩԼ��˾
		$this->assign('companyProvince' ,$suppObj['province']); //ǩԼ��˾ʡ��
		$this->assign('companyCity' ,$suppObj['city']); //ǩԼ��˾����
		$this->assign('linkman' ,$suppObj['linkmanName']); //��ϵ��
		$this->assign('phone' ,$suppObj['linkmanPhone']); //��ϵ�绰
		$this->assign('address' ,$suppObj['address']); //��ϵ��ַ
		$this->assign('payBankName' ,$suppObj['bankName']); //��������
		$this->assign('payBankNum' ,$suppObj['bankAccount']); //�����˺�
		$this->assign('payMan' ,$suppObj['suppName']); //������

		$this->assign('isApplyOilCard' ,$rentalcarObj['isApplyOilCard']); //�Ƿ������Ϳ�
		$this->view ('add-rentalcar' ,true);
	}

	/**
	 * �����⳵����id������ͬ
	 */
	function c_addByRentalcar(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($actType) {
			$obj['status'] = 6;
		}
		$rentcarId = $this->service->add_d($obj);
		if($rentcarId) {
			if($actType) {
				$title = '�ύ�ɹ���';
				$this->service->mailByProjectSubmit_d($rentcarId);
			} else {
				$title = '����ɹ���';
			}
		} else {
			if($actType) {
				$title = '�ύʧ�ܣ�';
			} else {
				$title = '����ʧ�ܣ�';
			}
		}
		echo "<script>alert('" . $title . "');window.close();</script>";
		exit();
	}

	/**
	 * ��ת�������⳵����id�༭�⳵��ͬҳ��
	 */
	function c_toEditByRentalcar() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ') ,$obj['contractNatureCode']); //��ͬ����
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX') ,$obj['contractTypeCode']); //��ͬ����
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK') ,$obj['payTypeCode']); //���ʽ
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,true)); //��ʾ������Ϣ
		$this->view ('edit-rentalcar' ,true);
	}

	/**
	 * �����⳵����id�༭��ͬ
	 */
	function c_editByRentalcar(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($actType) {
			$obj['status'] = 6;
		}
		$rentcarId = $this->service->edit_d($obj);
		if($rentcarId) {
			if($actType) {
				msg( '�ύ�ɹ���' );
				$this->service->mailByProjectSubmit_d($rentcarId);
			} else {
				msg( '����ɹ���' );
			}
		} else{
			if($actType) {
				msg( '�ύʧ�ܣ�' );
			} else{
				msg( '����ʧ�ܣ�' );
			}
		}
	}

	/**
	 * ajax�ı��ͬ״̬
	 */
	function c_ajaxChangeStatus() {
		$rs = $this->service->updateById(array('id' => $_POST['id'] ,'status' => $_POST['status']));
		if ($rs) {
			$this->service->mailByProjectSubmit_d($_POST['id']);
			echo '1';
		} else {
			echo "0";
		}
	}

	/**
	 * ��ת������⳵��ͬҳ��
	 */
	function c_toBack() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('back' ,true);
	}

	/**
	 * ����⳵��ͬ
	 */
	function c_back() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$rs = $this->service->back_d($_POST[$this->objName]);
		if($rs) {
			msg( '��سɹ���' );
		} else {
			msg( '���ʧ�ܣ�' );
		}
	}

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJsonForAll() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ("select_financeInfo");
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

}
?>