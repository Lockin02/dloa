<?php
/**
 * @author Michael
 * @Date 2014年3月6日 星期四 10:10:23
 * @version 1.0
 * @description:租车合同控制层
 */
class controller_outsourcing_contract_rentcar extends controller_base_action {

	function __construct() {
		$this->objName = "rentcar";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }

	/**
	 * 跳转到租车合同列表
	 */
	function c_page() {
		$this->assign ('userId', $_SESSION['USER_ID'] );
		$this->service->setCompany(0); # 个人列表,不需要进行公司过滤
		$this->view('list');
	}

	/**
	 * 跳转到租车合同汇总列表
	 */
	function c_toAllList() {
        $this->assign('projectId', isset($_GET['projectId'])?$_GET['projectId']:"");
		$this->view('list-all');
	}

	/**
	 * 跳转到租车合同签收列表tab页
	 */
	function c_toSignTab(){
		$this->display('signTab');
	}

	/**
	 * 跳转到查看租车合同项目列表
	 */
	function c_toViewProductList(){
		$this->assign('projectId' ,$_GET['projectId']);
		$this->view('view-projectList');
	}

	/**
	 * 跳转到新增租车合同页面
	 */
	function c_toAdd() {
		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ')); //合同性质
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX')); //合同类型
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK')); //合同付款方式

		$this->assign('deptId' ,$_SESSION['DEPT_ID']);
		$this->assign('deptName' ,$_SESSION['DEPT_NAME']);
		$this->assign('principalId' ,$_SESSION['USER_ID']);
		$this->assign('principalName' ,$_SESSION['USERNAME']);
		$this->assign('signDate' ,day_date);
		$this->assign('createTime' ,date("Y-m-d H:i:s"));

		$this->view ('add' ,true);
	}

	/**
	 * 重写add
	 */
	function c_add(){
		$this->checkSubmit(); //验证是否重复提交
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
				msg( '保存成功！' );
			}
		} else {
			msg( '保存失败！' );
		}
	}

	/**
	 * 跳转到编辑租车合同页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

        $rentUnitPriceCalWayOpts = ($obj['rentUnitPriceCalWay'] == 'byDay')? '<option value="byMonth">按月计费</option><option value="byDay" selected>按天计费</option>' : '<option value="byMonth" selected>按月计费</option><option value="byDay">按天计费</option>';
        $this->assign('rentUnitPriceCalWayOpts',$rentUnitPriceCalWayOpts);

		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ') ,$obj['contractNatureCode']); //合同性质
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX') ,$obj['contractTypeCode']); //合同类型
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK') ,$obj['payTypeCode']); //付款方式
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,true)); //显示附件信息

        // 付款信息
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
                $payTypeOpts = '<option value="BXFQR" '.$sltedType1.'>报销付发起人</option><option value="BXFSJ" '.$sltedType2.'>报销付司机</option>';
                $payTypeOpts .= ($obj['contractNatureCode'] == "ZCHTXZ-01")? '<option value="HETFK" '.$sltedType3.'>合同付款</option>' : '';
                $carRentCostTypesSlts = '<div id="carRentCostTypesOpts'.$indexNum.'"></div><select id="carRentCostTypesCombobox'.$indexNum.'" data-options="multiple:true" class="esayui-combobox" style="width:250px;">'.$carRentCostTypesOpts.'</select></div>';
                $bankInfoAttr = ($payInfo['payTypeCode'] == 'BXFSJ')? 'class="txt"' : 'class="readOnlyTxtNormal" readonly';
                $payInfoStr .= '<tr class="payFormTr payForm'.$indexNum.'" style="height:10px"><input type="hidden" class="hidden" id="payTypeId'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][id]" value="'.$payInfo['id'].'"/></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td rowspan="3"><input class="removePayItemBtn" id="removePayItemBtn'.$indexNum.'" data-index="'.$indexNum.'" type="button" value="-">'.
                    '<input id="payInfoCheckbox'.$indexNum.'" style="display:none" value="'.$indexNum.'" type="checkbox"></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">支付方式<span class="payTypeNum" id="payTypeNum'.$indexNum.'">'.$indexNum.'</span>：</span></td>' .
                    '<td class="form_text_right_three" colspan="5">' .
                    '<select name="rentcar[payInfo]['.$indexNum.'][payTypeCode]" id="payTypeSlt'.$indexNum.'" data-index="'.$indexNum.'">' . $payTypeOpts .
                    '</select><input type="hidden" class="hidden" id="payType'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][payType]" value="'.$payInfo['payType'].'"/>' .
                    '</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span style="color:blue">收款银行：</span></td><td class="form_text_right_three">' .
                    '<input type="text" id="bankName'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankName]" '.$bankInfoAttr.' value="'.$payInfo['bankName'].'">' .
                    '<input type="hidden" class="hidden" id="bankInfoId'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankInfoId]" value="'.$payInfo['bankInfoId'].'"/></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">收款账号：</span></td><td class="form_text_right_three"><input type="text" id="bankAccount'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankAccount]" '.$bankInfoAttr.' value="'.$payInfo['bankAccount'].'"></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">收款人：</span></td><td class="form_text_right_three"><input type="text" id="bankReceiver'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankReceiver]" '.$bankInfoAttr.' value="'.$payInfo['bankReceiver'].'"></td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span style="color:blue">包含费用项：</span></td>' .
                    '<td class="form_text_right_three includeFeeTypeWrap" colspan="5">'.$carRentCostTypesSlts.'<input type="hidden" class="hidden" id="includeFeeTypeCode'.$indexNum.'" data-index="'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][includeFeeTypeCode]" value="'.$payInfo['includeFeeTypeCode'].'"/><input type="hidden" class="hidden" id="includeFeeType'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][includeFeeType]" value="'.$payInfo['includeFeeType'].'"/></td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);
        $this->assign('payInfoCul',($payInfos)? count($payInfos) : 0);

		$this->view ('edit' ,true);
	}

	/**
	 * 重新edit
	 */
	function c_edit(){
		$this->checkSubmit(); //验证是否重复提交
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
				msg( '保存成功！' );
			}
		} else{
			msg( '保存失败！' );
		}
	}

	/**
	 * 跳转到查看租车合同页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
        if($obj['rentUnitPriceCalWay'] == 'byDay'){
            $obj['rentUnitPriceCalWay'] = '按天计费';
            $obj['rentUnitPriceLabel'] = '费用(元/天/辆)';
        }else{
            $obj['rentUnitPriceCalWay'] = '按月计费';
            $obj['rentUnitPriceLabel'] = '费用(元/月/辆)';
        }

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//是否申请盖章
		if ($obj['isNeedStamp'] == 0) {
			$this->assign("isNeedStamp" ,'否');
		} else {
			$this->assign("isNeedStamp" ,'是');
		}

		//是否使用油卡
		if ($obj['isUseOilcard'] == 1) {
			$this->assign("isUseOilcard" ,'是');
			$this->assign("isUseOilcardVal" ,1);
		} else {
			$this->assign("isUseOilcard" ,'否');
			$this->assign("isUseOilcardVal" ,0);
		}

		//金额千分位显示
		$this->assign("orderMoney" ,number_format($obj['orderMoney'] ,2)); //合同金额
		$this->assign("rentUnitPrice" ,number_format($obj['rentUnitPrice'] ,2)); //租赁金额
		$this->assign("oilcardMoney" ,number_format($obj['oilcardMoney'] ,2)); //油卡金额
		$this->assign("oilPrice" ,number_format($obj['oilPrice'] ,2)); //油价
		$this->assign("fuelCharge" ,number_format($obj['fuelCharge'] ,2)); //燃油费
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //显示附件信息
		if(isset($_GET['hideBtn'])){
			$this->assign('hideBtn',$_GET['hideBtn']);
		}

        // 付款信息
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
                $payType = '报销付发起人';
                switch($payInfo['payTypeCode']){
                    case 'BXFQR':
                        $payType = '报销付发起人';
                        break;
                    case 'BXFSJ':
                        $payType = '报销付司机';
                        break;
                    case 'HETFK':
                        $payType = '合同付款';
                        break;
                }
                $payInfoStr .= '<tr style="height:10px"></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three">支付方式'.$indexNum.'：</td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payType.'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span>收款银行：</span></td><td class="form_text_right_three">' . $payInfo['bankName'] .'</td>' .
                    '<td class="form_text_left_three"><span>收款账号：</span></td><td class="form_text_right_three">'.$payInfo['bankAccount'].'</td>' .
                    '<td class="form_text_left_three"><span>收款人：</span></td><td class="form_text_right_three">'.$payInfo['bankReceiver'].'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span>包含费用项：</span></td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payInfo['includeFeeType'].'</td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);

        $closeBtnStyle = isset($_GET['notCloseBtn'])? "style='display:none'" : "";
        $this->assign('closeBtnStyle', $closeBtnStyle);

		$this->view ( 'view' );
	}

	/**
	 * 审批时查看租车合同页面
	 */
	function c_toAudit() {
		$obj = $this->service->get_d ( $_GET ['id'] );

        if($obj['rentUnitPriceCalWay'] == 'byDay'){
            $obj['rentUnitPriceCalWay'] = '按天计费';
            $obj['rentUnitPriceLabel'] = '费用(元/天/辆)';
        }else{
            $obj['rentUnitPriceCalWay'] = '按月计费';
            $obj['rentUnitPriceLabel'] = '费用(元/月/辆)';
        }

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//是否申请盖章
		if ($obj['isNeedStamp'] == 0) {
			$this->assign("isNeedStamp" ,'否');
		} else {
			$this->assign("isNeedStamp" ,'是');
		}

		//是否使用油卡
		if ($obj['isUseOilcard'] == 1) {
			$this->assign("isUseOilcard" ,'是');
			$this->assign("isUseOilcardVal" ,1);
		} else {
			$this->assign("isUseOilcard" ,'否');
			$this->assign("isUseOilcardVal" ,0);
		}

		//金额千分位显示
		$this->assign("orderMoney" ,number_format($obj['orderMoney'] ,2)); //合同金额
		$this->assign("rentUnitPrice" ,number_format($obj['rentUnitPrice'] ,2)); //租赁金额
		$this->assign("oilcardMoney" ,number_format($obj['oilcardMoney'] ,2)); //油卡金额
		$this->assign("oilPrice" ,number_format($obj['oilPrice'] ,2)); //油价
		$this->assign("fuelCharge" ,number_format($obj['fuelCharge'] ,2)); //燃油费
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //显示附件信息

        // 付款信息
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
                $payType = '报销付发起人';
                switch($payInfo['payTypeCode']){
                    case 'BXFQR':
                        $payType = '报销付发起人';
                        break;
                    case 'BXFSJ':
                        $payType = '报销付司机';
                        break;
                    case 'HETFK':
                        $payType = '合同付款';
                        break;
                }
                $payInfoStr .= '<tr style="height:10px"></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three">支付方式'.$indexNum.'：</td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payType.'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span>收款银行：</span></td><td class="form_text_right_three">' . $payInfo['bankName'] .'</td>' .
                    '<td class="form_text_left_three"><span>收款账号：</span></td><td class="form_text_right_three">'.$payInfo['bankAccount'].'</td>' .
                    '<td class="form_text_left_three"><span>收款人：</span></td><td class="form_text_right_three">'.$payInfo['bankReceiver'].'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span>包含费用项：</span></td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payInfo['includeFeeType'].'</td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);

		$this->view ( 'view-audit' );
	}

	/**
	 *跳转申请盖章页面
	 */
	function c_toStamp(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('applyDate' ,day_date);

		//当前盖章申请人
		$this->assign('thisUserId' ,$_SESSION['USER_ID']);
		$this->assign('thisUserName' ,$_SESSION['USERNAME']);

		$this->view ('stamp' ,true);
	}

	/**
	 * 新增盖章信息操作
	 */
	function c_stamp(){
		$this->checkSubmit(); //验证是否重复提交
		$rs = $this->service->stamp_d($_POST[$this->objName]);
		if ($rs) {
			msg ( "申请成功！" );
		}else{
			msg ( "申请失败！" );
		}
	}

	/**
	 * 租车合同tab页
	 */
	function c_viewTab(){
		$this->assign('id' ,$_GET['id']);
        $closeBtnTip = isset($_GET['notCloseBtn'])? "&notCloseBtn=1" : "";
        $this->assign('closeBtnTip', $closeBtnTip);
		$this->display ( 'viewtab' );
	}

	/**
	 * 跳转到合同变更申请页面
	 */
	function c_toChange(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK') ,$obj['payTypeCode']); //付款方式
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //显示附件信息

        $rentUnitPriceCalWayOpts = ($obj['rentUnitPriceCalWay'] == 'byDay')? '<option value="byMonth">按月计费</option><option value="byDay" selected>按天计费</option>' : '<option value="byMonth" selected>按月计费</option><option value="byDay">按天计费</option>';
        $this->assign('rentUnitPriceCalWayOpts',$rentUnitPriceCalWayOpts);

        // 付款信息
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
                $payTypeOpts = '<option value="BXFQR" '.$sltedType1.'>报销付发起人</option><option value="BXFSJ" '.$sltedType2.'>报销付司机</option>';
                $payTypeOpts .= ($obj['contractNatureCode'] == "ZCHTXZ-01")? '<option value="HETFK" '.$sltedType3.'>合同付款</option>' : '';
                $carRentCostTypesSlts = '<div id="carRentCostTypesOpts'.$indexNum.'"></div><select id="carRentCostTypesCombobox'.$indexNum.'" data-options="multiple:true" class="esayui-combobox" style="width:250px;">'.$carRentCostTypesOpts.'</select></div>';
                $bankInfoAttr = ($payInfo['payTypeCode'] == 'BXFSJ')? 'class="txt"' : 'class="readOnlyTxtNormal" readonly';
                $payInfoStr .= '<tr class="payFormTr payForm'.$indexNum.'" style="height:10px"><input type="hidden" class="hidden mainElement'.$indexNum.'" id="payTypeId'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][id]" value="'.$payInfo['id'].'"/></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td rowspan="3"><input class="removePayItemBtn" id="removePayItemBtn'.$indexNum.'" data-index="'.$indexNum.'" type="button" value="-">'.
                    '<input id="payInfoCheckbox'.$indexNum.'" style="display:none" value="'.$indexNum.'" type="checkbox"></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">支付方式<span class="payTypeNum" id="payTypeNum'.$indexNum.'">'.$indexNum.'</span>：</span></td>' .
                    '<td class="form_text_right_three" colspan="5">' .
                    '<select name="rentcar[payInfo]['.$indexNum.'][payTypeCode]" id="payTypeSlt'.$indexNum.'" data-index="'.$indexNum.'">' . $payTypeOpts .
                    '</select><input type="hidden" class="hidden" id="payType'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][payType]" value="'.$payInfo['payType'].'"/>' .
                    '</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span style="color:blue">收款银行：</span></td><td class="form_text_right_three">' .
                    '<input type="text" id="bankName'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankName]" '.$bankInfoAttr.' value="'.$payInfo['bankName'].'">' .
                    '<input type="hidden" class="hidden" id="bankInfoId'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankInfoId]" value="'.$payInfo['bankInfoId'].'"/></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">收款账号：</span></td><td class="form_text_right_three"><input type="text" id="bankAccount'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankAccount]" '.$bankInfoAttr.' value="'.$payInfo['bankAccount'].'"></td>' .
                    '<td class="form_text_left_three"><span style="color:blue">收款人：</span></td><td class="form_text_right_three"><input type="text" id="bankReceiver'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][bankReceiver]" '.$bankInfoAttr.' value="'.$payInfo['bankReceiver'].'"></td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span style="color:blue">包含费用项：</span></td>' .
                    '<td class="form_text_right_three includeFeeTypeWrap" colspan="5">'.$carRentCostTypesSlts.'<input type="hidden" class="hidden" id="includeFeeTypeCode'.$indexNum.'" data-index="'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][includeFeeTypeCode]" value="'.$payInfo['includeFeeTypeCode'].'"/><input type="hidden" class="hidden" id="includeFeeType'.$indexNum.'" name="rentcar[payInfo]['.$indexNum.'][includeFeeType]" value="'.$payInfo['includeFeeType'].'"/></td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);
        $this->assign('payInfoCul',($payInfos)? count($payInfos) : 0);

		$this->view('change' ,true);
	}

	/**
	 * 变更操作
	 */
	function c_change() {
		$this->checkSubmit(); //验证是否重复提交
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
			msgBack2("变更失败！失败原因：" . $e->getMessage ());
		}
	}

	/**
	 * 变更查看tab
	 */
	function c_toChangeTab(){
		$this->permCheck (); //安全校验
		$newId = $_GET ['id'];
		$this->assign('id' ,$newId);

		$rs = $this->service->find(array('id' => $newId ) ,null ,'originalId');
		$this->assign('originalId' ,$rs['originalId']);

		$this->display('changetab');
	}

	/**
	 * 查看(变更合同-原合同)区别
	 */
	function c_changeView(){
		$id = $_GET['id'];
		$obj = $this->service->get_d( $id );

        if($obj['rentUnitPriceCalWay'] == 'byDay'){
            $obj['rentUnitPriceCalWay'] = '按天计费';
            $obj['rentUnitPriceLabel'] = '费用(元/天/辆)';
        }else{
            $obj['rentUnitPriceCalWay'] = '按月计费';
            $obj['rentUnitPriceLabel'] = '费用(元/月/辆)';
        }

		$this->assignFunc($obj);
		$this->assign('file' ,$this->service->getFilesByObjId ( $id, false));
		$this->assign('isNeedStamp' ,$obj['isNeedStamp'] ? '是' : '否');
		$this->assign('isUseOilcard' ,$obj['isUseOilcard'] ? '是' : '否');
		$this->assign('isUseOilcardVal' ,$obj['isUseOilcard'] ? 1 : 0);

        // 付款信息
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
                $payType = '报销付发起人';
                switch($payInfo['payTypeCode']){
                    case 'BXFQR':
                        $payType = '报销付发起人';
                        break;
                    case 'BXFSJ':
                        $payType = '报销付司机';
                        break;
                    case 'HETFK':
                        $payType = '合同付款';
                        break;
                }
                $payInfoStr .= '<tr style="height:10px"></tr>'.
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three">支付方式'.$indexNum.'：</td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payType.'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'">' .
                    '<td class="form_text_left_three"><span>收款银行：</span></td><td class="form_text_right_three">' . $payInfo['bankName'] .'</td>' .
                    '<td class="form_text_left_three"><span>收款账号：</span></td><td class="form_text_right_three">'.$payInfo['bankAccount'].'</td>' .
                    '<td class="form_text_left_three"><span>收款人：</span></td><td class="form_text_right_three">'.$payInfo['bankReceiver'].'</td>' .
                    '</tr>' .
                    '<tr class="payFormTr payForm'.$indexNum.'"  data-index="'.$indexNum.'"><td class="form_text_left_three"><span>包含费用项：</span></td>' .
                    '<td class="form_text_right_three" colspan="5">'.$payInfo['includeFeeType'].'</td></tr>';
            }
        }
        $this->assign('payInfoStr',$payInfoStr);

		$this->view ( 'changeview' );
	}

	/**
	 * 合同审批完成后处理盖章的方法
	 */
	function c_dealAfterAudit(){
		$this->service->workflowCallBack($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 合同变更审批完成后处理方法
	 */
	function c_dealAfterAuditChange(){
		$this->service->workflowCallBack_change($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 申请付款验证方法
	 */
	function c_canPayapply(){
		$id = $_POST['id'];
		$rs = $this->service->canPayapply_d($id);
		echo $rs;
		exit();
	}

	/**
	 * 退款申请验证
	 */
	function c_canPayapplyBack(){
		$id = $_POST['id'];
		$rs = $this->service->canPayapplyBack_d($id);
		echo $rs;
		exit();
	}

	/**
	 * 关闭合同
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
	 * 合同签收 - 待签收合同列表
	 */
	function c_signingList(){
		$this->view('signinglist');
	}

	/**
	 * 合同签收 - 已签收合同列表
	 */
	function c_signedList(){
		$this->view('signedlist');
	}

	/**
	 * 跳转到合同签收页面
	 */
	function c_toSign(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//附件添加{file}
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->showDatadicts ( array ('outsourceType' => 'HTWB' ), $obj ['outsourceType'] );
		$this->showDatadicts ( array ('payType' => 'HTFKFS' ), $obj ['payType'] );//合同付款方式
		$this->showDatadicts ( array ('outsourcing' => 'HTWBFS' ) , $obj ['outsourcing']);//合同外包方式

		$this->view('sign' ,true);
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function c_sign(){
		$this->checkSubmit(); //验证是否重复提交
		$object = $_POST[$this->objName];
		$object['oilPrice'] = ($object['oilPrice'] == '' ? 0.00 : $object['oilPrice']);
		$object['fuelCharge'] = ($object['fuelCharge'] == '' ? 0.00 : $object['fuelCharge']);
		$id = $this->service->sign_d ( $object);
		if ($id) {
			msgRf('签收成功');
		}else{
			msgRf('签收失败');
		}
	}

	/**
	 * 跳转到导入页面
	 */
	function c_toExcelIn() {
		$this->view('excelIn');
	}

	/**
	 * 导入
	 */
	function c_excelIn() {
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '租车合同导入结果列表';
		$thead = array('数据信息','导入结果');
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/**
	 * 跳转到导入关联工程项目页面
	 */
	function c_toExcelPro() {
		$this->view('excelPro');
	}

	/**
	 * 导入关联工程项目
	 */
	function c_excelPro() {
		set_time_limit(0);
		$resultArr = $this->service->excelPro_d ();

		$title = '租车合同关联工程项目结果列表';
		$thead = array('数据信息','导入结果');
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/**
	 * 跳转到导出页面
	 */
	function c_toExcelOut() {
		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ')); //合同性质
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX')); //合同类型
		if ($_GET['isCreate']) { //个人列表点导出
			$this->assign ('isCreate', 1);
			$this->assign ('createName', $_SESSION['USERNAME']);
		} else { //汇总点导出
			$this->assign ('isCreate', 0);
		}
		$this->view('excelOut');
	}

	/**
	 * 跳转到高级搜索页面
	 */
	function c_toSearchAdv() {
		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ')); //合同性质
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX')); //合同类型
		$this->view('searchAdv');
	}

	/**
	 * 导出excel
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['createDateSta'])) //录入时间上
			$this->service->searchArr['createDateSta'] = $formData['createDateSta'];
		if(!empty($formData['createDateEnd'])) //录入时间下
			$this->service->searchArr['createDateEnd'] = $formData['createDateEnd'];

		if(!empty($formData['orderCode'])) //鼎利合同编号
			$this->service->searchArr['orderCode'] = $formData['orderCode'];

		if(!empty($formData['contractNatureCode'])) //合同性质
			$this->service->searchArr['contractNatureCode'] = $formData['contractNatureCode'];

		if(!empty($formData['contractTypeCode'])) //合同类型
			$this->service->searchArr['contractTypeCode'] = $formData['contractTypeCode'];

		if(!empty($formData['orderName'])) //合同名称
			$this->service->searchArr['orderName'] = $formData['orderName'];

		if(!empty($formData['signCompany'])) //签约公司
			$this->service->searchArr['signCompany'] = $formData['signCompany'];

	 	if(!empty($formData['companyProvinceCode'])) //公司省份
			$this->service->searchArr['companyProvinceCode'] = $formData['companyProvinceCode'];

	 	if(!empty($formData['ownCompany'])) //归属公司
			$this->service->searchArr['ownCompanyArr'] = $formData['ownCompany'];

		if(!empty($formData['signDateSta'])) //签约日期上
			$this->service->searchArr['signDateSta'] = $formData['signDateSta'];
		if(!empty($formData['signDateEnd'])) //签约日期下
			$this->service->searchArr['signDateEnd'] = $formData['signDateEnd'];

	 	if(!empty($formData['createName'])) //申请人
			$this->service->searchArr['createName'] = $formData['createName'];

		if($formData['isCreate'] == 0)
			$this->service->searchArr['statusArr'] = '1,2,3,4';

        $this->service->searchArr['ExaStatusArr'] ="完成,变更审批中";

		$rows = $this->service->listBySqlId('select_financeInfo');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
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
			$rowData[$k]['signedStatus'] = ($v['signedStatus'] == 1 ? '已签收' : '未签收');
			$rowData[$k]['objCode'] = $v['objCode'];
			$rowData[$k]['isNeedStamp'] = ($v['isNeedStamp'] == 1 ? '是' : '否');
			$rowData[$k]['isStamp'] = ($v['isStamp'] == 1 ? '是' : '否');
			$rowData[$k]['stampType'] = $v['stampType'];
			$rowData[$k]['createName'] = $v['createName'];
			$rowData[$k]['updateTime'] = $v['updateTime'];
		}
		$colArr = array();
		$modelName = '外包-租车合同信息';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	 }

	/**
	 * 获取租车申请相关信息
	 */
	function c_getRentcarInformation() {
		$service = $this->service;
		$service->getParam( $_REQUEST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d('select_rentCarInformation');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 根据项目ID和车牌号获取合同信息
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
	 * 附件上传
	 */
	function c_toUploadFile() {
		$obj = $this->service->get_d($_GET['id']);
		$this->assign('file' ,$this->service->getFilesByObjId($obj['id'] ,false ,$this->service->tbl_name));
		$this->assignFunc($obj);

		$this->view('uploadfile');
	}

	/**
	 * 根据供应商id查找是否存在有效期内的执行合同
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
	 * 跳转到根据租车申请id新增合同页面
	 */
	function c_toAddByRentalcar() {
		$rentalcarDao = new model_outsourcing_vehicle_rentalcar();
		$rentalcarObj = $rentalcarDao->get_d($_GET['rentalcarId']);

		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ')); //合同性质
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX')); //合同类型
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK')); //合同付款方式

		$this->assign('deptId' ,$_SESSION['DEPT_ID']);
		$this->assign('deptName' ,$_SESSION['DEPT_NAME']);
		$this->assign('principalId' ,$_SESSION['USER_ID']);
		$this->assign('principalName' ,$_SESSION['USERNAME']);
		$this->assign('signDate' ,day_date);
		$this->assign('createTime' ,date("Y-m-d H:i:s"));

		$this->assign('rentalcarId' ,$rentalcarObj['id']); //申请单ID
		$this->assign('rentalcarCode' ,$rentalcarObj['formCode']); //申请单号

		$projectdao = new model_engineering_project_esmproject();
		$projectObj = $projectdao->get_d($rentalcarObj['projectId']);
		$this->assign('projectId' ,$rentalcarObj['projectId']); //项目ID
		$this->assign('projectCode' ,$rentalcarObj['projectCode']); //项目编号
		$this->assign('projectName' ,$rentalcarObj['projectName']); //项目名称
		$this->assign('projectType' ,$projectObj['natureName']); //项目类型
		$this->assign('projectTypeCode' ,$projectObj['nature']); //项目类型Code
		$this->assign('projectManagerId' ,$projectObj['managerId']); //项目经理ID
		$this->assign('projectManager' ,$projectObj['managerName']); //项目经理
		$this->assign('officeId' ,$projectObj['officeId']); //区域ID
		$this->assign('officeName' ,$projectObj['officeName']); //区域

		$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		$suppObj = $suppDao->get_d($_GET['suppId']);
		$this->assign('signCompanyId' ,$suppObj['id']); //签约公司id
		$this->assign('signCompany' ,$suppObj['suppName']); //签约公司
		$this->assign('companyProvince' ,$suppObj['province']); //签约公司省份
		$this->assign('companyCity' ,$suppObj['city']); //签约公司城市
		$this->assign('linkman' ,$suppObj['linkmanName']); //联系人
		$this->assign('phone' ,$suppObj['linkmanPhone']); //联系电话
		$this->assign('address' ,$suppObj['address']); //联系地址
		$this->assign('payBankName' ,$suppObj['bankName']); //付款银行
		$this->assign('payBankNum' ,$suppObj['bankAccount']); //付款账号
		$this->assign('payMan' ,$suppObj['suppName']); //付款人

		$this->assign('isApplyOilCard' ,$rentalcarObj['isApplyOilCard']); //是否申请油卡
		$this->view ('add-rentalcar' ,true);
	}

	/**
	 * 根据租车申请id新增合同
	 */
	function c_addByRentalcar(){
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($actType) {
			$obj['status'] = 6;
		}
		$rentcarId = $this->service->add_d($obj);
		if($rentcarId) {
			if($actType) {
				$title = '提交成功！';
				$this->service->mailByProjectSubmit_d($rentcarId);
			} else {
				$title = '保存成功！';
			}
		} else {
			if($actType) {
				$title = '提交失败！';
			} else {
				$title = '保存失败！';
			}
		}
		echo "<script>alert('" . $title . "');window.close();</script>";
		exit();
	}

	/**
	 * 跳转到根据租车申请id编辑租车合同页面
	 */
	function c_toEditByRentalcar() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('contractNatureCode'=>'ZCHTXZ') ,$obj['contractNatureCode']); //合同性质
		$this->showDatadicts(array('contractTypeCode'=>'ZCHTLX') ,$obj['contractTypeCode']); //合同类型
		$this->showDatadicts(array('payTypeCode'=>'ZCHTFK') ,$obj['payTypeCode']); //付款方式
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,true)); //显示附件信息
		$this->view ('edit-rentalcar' ,true);
	}

	/**
	 * 根据租车申请id编辑合同
	 */
	function c_editByRentalcar(){
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($actType) {
			$obj['status'] = 6;
		}
		$rentcarId = $this->service->edit_d($obj);
		if($rentcarId) {
			if($actType) {
				msg( '提交成功！' );
				$this->service->mailByProjectSubmit_d($rentcarId);
			} else {
				msg( '保存成功！' );
			}
		} else{
			if($actType) {
				msg( '提交失败！' );
			} else{
				msg( '保存失败！' );
			}
		}
	}

	/**
	 * ajax改变合同状态
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
	 * 跳转到打回租车合同页面
	 */
	function c_toBack() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('back' ,true);
	}

	/**
	 * 打回租车合同
	 */
	function c_back() {
		$this->checkSubmit(); //验证是否重复提交
		$rs = $this->service->back_d($_POST[$this->objName]);
		if($rs) {
			msg( '打回成功！' );
		} else {
			msg( '打回失败！' );
		}
	}

    /**
     * 获取分页数据转成Json
     */
    function c_pageJsonForAll() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ("select_financeInfo");
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

}
?>