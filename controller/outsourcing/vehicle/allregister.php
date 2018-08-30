<?php
/**
 * @author Michael
 * @Date 2014年2月10日 星期一 18:43:01
 * @version 1.0
 * @description:租车登记汇总控制层
 */
class controller_outsourcing_vehicle_allregister extends controller_base_action {
    private $bindId = "";// 在线手册的BindId
	function __construct() {
		$this->objName = "allregister";
		$this->objPath = "outsourcing_vehicle";
		parent::__construct ();
        $this->bindId = "507cdd55-bba5-4ffb-b589-7c5799b6f365";
	}

	/**
	 * 跳转到租车登记汇总列表
	 */
	function c_page() {
		$this->assign("userId" ,$_SESSION['USER_ID']);
        $this->assign('projectId', isset($_GET['projectId'])?$_GET['projectId']:"");
		$this->view('list');
	}

	/**
	 * 跳转到租车登记汇总列表(已提交审批)
	 */
	function c_toRecordPage() {
        $this->assign('projectId', isset($_GET['projectId'])?$_GET['projectId']:"");
		$this->view('listrecord');
	}

	/**
	 * 跳转到查看租车登记汇总列表(工程项目)
	 */
	function c_toViewProjectPage() {
		$this->assign('projectId' ,$_GET['projectId']);
		$this->view('view-projectList');
	}

	/**
	 * 跳转到编辑租车登记汇总列表(工程项目)
	 */
	function c_toEditProjectPage() {
		$this->assign('projectId' ,$_GET['projectId']);
		$this->view('edit-projectList');
	}

	/**
	 * 跳转到租车登记汇总列表(服务经理)
	 */
	function c_toServicePage() {
		$this->assign("userId" ,$_SESSION['USER_ID']);
		$this->view('listservice');
	}

	/**
	 * 跳转到用车信息列表（每个项目每个月每辆车）
	 */
	function c_toUseMessagePage() {
        $this->assign('projectId' ,$_GET['projectId']);
		$this->view('listMessage');
	}

	/**
	 * 获取用车信息数据转成Json
	 */
	function c_messageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);

		// $service->asc = false;
		$service->sort = 'c.useCarDate';
		$service->groupBy = 'c.id,r.carNum';

		$rows = $service->page_d('select_message');

        foreach ($rows as $k => $v){
//            $dateLimit = substr($v['useCarDate'],0,7);
//            $cost = $service->getRentalCarCostVal($v['allRegisterId'],$dateLimit);
//            $rows[$k]['rentalCarCost'] = ($cost > 0)? $cost : $v['rentalCarCost'];
            $registerDao = new model_outsourcing_vehicle_register();
            $rows[$k]['rentalCarCost'] = $registerDao->getRentalCarCostByRegisterId($v['allRegisterId'],$v['carNum']);
        }

//		if (is_array($rows)) {
//			foreach ($rows as $key => $val) {
//				$registerDao = new model_outsourcing_vehicle_register();
//				if ($val['rentalPropertyCode'] != 'ZCXZ-02') {
//					//加入合同用车天数
//					$rows[$key]['contractUseDay'] = $registerDao->getDaysOrFee_d($val['registerId'] ,$val['rentalContractId']);
//				}
//			}
//		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 带服务经理的数据转成Json
	 */
	function c_serviceJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		$rows = $service->page_d('select_service');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增租车登记汇总页面
	 */
	function c_toAdd() {
		$this->view ('add');
	}

	/**
	 * 跳转到编辑租车登记汇总页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//数字千分位显示
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //有效里程

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //停车费
        $this->assign("tollCost" ,number_format($obj['tollCost'] ,2)); //路桥费

        $this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //餐饮费
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //住宿费

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //加班费
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //特殊油费

        // 在线手册链接
        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);

		$this->view ('edit' ,true);
	}

	/**
	 * 重写编辑
	 */
	function c_edit(){
		$this->checkSubmit(); //验证是否重复提交
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$obj = $_POST[$this->objName];
        if(!empty($obj['register']) && is_array($obj['register'])){
            foreach ($obj['register'] as $k => $v){
                unset($obj['register'][$k]['deductMoney']);
                unset($obj['register'][$k]['deductReason']);
            }
        }

        $useCarDate = isset($_POST['useCarDate'])? $_POST['useCarDate'] : '';
		$registerDao = new model_outsourcing_vehicle_register();
		$rs = $registerDao->addEstimate_d( $obj['register'] ); //添加评价和扣款信息
		if($rs) {
			if($actType) {
				$tmp = 0; //从表条数标志
				foreach ($obj['register'] as $key => $val) {
					if ($val['rentalContractId'] > 0 || $val['rentalPropertyCode'] == 'ZCXZ-02') { //有合同或者短租
						$tmp++;
					}
				}
				if (count($obj['register']) == $tmp) { //判断所有登记是否都有对应的合同信息
					$result = $this->service->addContract_d( $obj , $useCarDate); //录入合同信息
					if ($result) {
//						if ($this->service->checkBudgetById_d($obj['id'])) {
//							msg('费用超出项目预算！');
//						} else {
							$esmDao = new model_engineering_project_esmproject();
							$areaId = $esmDao->getRangeId_d($obj['projectId']);
							if($areaId > 0) {
								$billArea = $areaId;
							} else {
								$billArea = '';
							}
							succ_show('controller/outsourcing/vehicle/ewf_register.php?actTo=ewfSelect&billId='.$obj['id'].'&billArea='.$billArea);
//						}
					} else {
						msg( '系统录入合同信息失败！' );
					}
				} else {
					msg( '部分登记没有关联合同！' );
				}
			} else {
                // 更新租车汇总的租车费
                $dateLimit = substr($useCarDate,0,7);
                $cost = $this->service->getRentalCarCostVal($obj['id'],$dateLimit);
                if($cost > 0){
                    $this->service->updateById(array("id" => $obj['id'],"rentalCarCost" => $cost));
                }
				msg( '保存成功！' );
			}
		} else {
			msg( '保存失败！' );
		}
	}

	/**
	 * 提交审批后查看显示租车登记汇总页面
	 */
	function c_toAudit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);

        // 实时计算租车费
        $dateLimit = substr($obj['useCarDate'],0,7);
        $cost = $this->service->getRentalCarCostVal($obj['id'],$dateLimit);
        $obj['rentalCarCost'] = ($cost > 0)? $cost : $obj['rentalCarCost'];

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//数字千分位显示
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //有效里程
		$this->assign("rentalCarCost" ,number_format($obj['rentalCarCost'] ,2)); //租车费
		$this->assign("reimbursedFuel" ,number_format($obj['reimbursedFuel'] ,2)); //实报实销油费
		$this->assign("gasolineKMCost" ,number_format($obj['gasolineKMCost'] ,2)); //按公里计价油费

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //路桥\停车费
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //餐饮费
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //住宿费

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //加班费
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //特殊油费

		$this->assign('hideBtn' ,$_GET['hideBtn'] ? 'hidden' : 'button');
		$this->view ( 'audit' );
	}

	/**
	 * 跳转到查看租车登记汇总页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
        $id = $_GET ['id'];
        $relativeCarNum = "";
        if(isset($_GET ['type']) && $_GET ['type'] == 'viewByExpenseTmpId' && isset($_GET ['tmpId'])){
            $checkRelativeToCarRentalSql = "select * from oa_contract_rentcar_expensetmp where id = '{$_GET ['tmpId']}'";
            $carRentalRelativeTmpObj = $this->service->_db->get_one($checkRelativeToCarRentalSql);
            if($carRentalRelativeTmpObj){
                $id = $carRentalRelativeTmpObj['allregisterId'];
                $relativeCarNum = $carRentalRelativeTmpObj['carNumBase64'];// 这里传入的是加密后的车牌号码
                $rentalProperty = $carRentalRelativeTmpObj['rentalProperty'];
            }
             // echo "<pre>";print_r($carRentalRelativeTmpObj);exit();
        }
        $this->assign("relativeCarNum",$relativeCarNum);
        $this->assign("rentalProperty",$rentalProperty);

		$obj = $this->service->get_d ( $id );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//数字千分位显示
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //有效里程

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //路桥\停车费
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //餐饮费
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //住宿费

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //加班费
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //特殊油费
		$this->view ( 'view' );
	}

	/**
	 * 租车登记审批通过处理
	 */
	function c_dealAfterAuditPass() {
	 	if (! empty ( $_GET ['spid'] )) {
	 		//审批流回调方法
            $this->service->workflowCallBack($_GET['spid']);
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 跳转到excel导出页面
	 */
	function c_toExcelOut() {
		$this->permCheck (); //安全校验
		if ($_GET['userId']) {
			$this->assign('userId' ,$_GET['userId']);
		} else {
			$this->assign('userId' ,"");
		}
		$this->view ( 'excelout' );
	}

	/**
	 * 导出excel
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['projectName'])) //项目名称
			$this->service->searchArr['projectNameSea'] = $formData['projectName'];
		if(!empty($formData['projectCode'])) //项目编号
			$this->service->searchArr['projectCodeSea'] = $formData['projectCode'];

		if(!empty($formData['useCarDateSta'])) //用车时间下
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //用车时间下
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		if(!empty($formData['userId'])) //登陆人
			$this->service->searchArr['projectManagerIdSea'] = $formData['userId'];

		$rows = $this->service->listBySqlId('select_excelOut');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

        foreach ($rows as $k => $v){
            $cost = $this->service->getRentalCarCostVal($v['id'],$v['substring(c.useCarDate , 1 ,7)']);
            $rows[$k]['rentalCarCost'] = ($cost > 0)? $cost : $v['rentalCarCost'];
        }

		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '外包-租车登记汇总信息';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
	 }

	/**
	 * 跳转到提交提交审批的excel导出页面
	*/
	function c_toExcelOutFinish() {
		$this->permCheck (); //安全校验
		$this->view ( 'exceloutfinish' );
	}

	/**
	 * 导出提交审批excel
	 */
	function c_excelOutFinish() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['projectName'])) //项目名称
			$this->service->searchArr['projectNameSea'] = $formData['projectName'];
		if(!empty($formData['projectCode'])) //项目编号
			$this->service->searchArr['projectCodeSea'] = $formData['projectCode'];

		if(!empty($formData['useCarDateSta'])) //用车时间下
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //用车时间下
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		$rows = $this->service->listBySqlId('select_excelOutFinish');
        if(!empty($rows)){
            foreach ($rows as $k => $v){
                $dateLimit = substr($v['useCarDate'],0,7);
                $cost = $this->service->getRentalCarCostVal($v['id'],$dateLimit);
                $rows[$k]['rentalCarCost'] = ($cost > 0)? $cost : $v['rentalCarCost'];
                $rows[$k]['allCost'] = number_format(
                    $v['gasolineKMCost']
                    + $v['reimbursedFuel']
                    + $v['rentalCarCost']
                    + $v['parkingCost']
                    + $v['tollCost']
                    + $v['mealsCost']
                    + $v['accommodationCost']
                    + $v['overtimePay']
                    + $v['specialGas'] ,2
                );
            }
        }
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '外包-租车登记汇总信息';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
	 }

	/**
	 * 判断是否可以提交审批(不判断时间)并录入合同信息
	 */
	function c_isCanSubmit() {
		$id = $_POST['id'];
        $limitUseCarDate = isset($_POST['limitUseCarDate'])? $_POST['limitUseCarDate'] : '';
		$rs = $this->service->isCanSubmit_d( $id , $limitUseCarDate);
		if ($rs) {
//			if ($this->service->checkBudgetById_d($id)) { //判断是否超出预算
//				echo "budget";
//			} else {
                if($this->service->chkPayInfoForNofeeCont($id)){
                    echo 'hasNoDone';
                }else{
                    echo 'true';
                }
//			}
		} else {
			echo 'false';
		}
	}

	/**
	 * 跳转到查看用车记录详细页面
	 */
	function c_toRecord() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		$this->assignFunc($obj);

		$registerDao = new model_outsourcing_vehicle_register();
		$registerObj = $registerDao->get_d( $_GET['registerId'] );
		$this->assign("carNum" ,$registerObj['carNum']); //车牌
		$this->assign("rentalProperty" ,$registerObj['rentalProperty']); //租车性质
		$this->assign("driverName" ,$registerObj['driverName']); //司机姓名

		$vehicleDao = new model_outsourcing_outsourcessupp_vehicle();
		$vehicleObj = $vehicleDao->find(array("suppId"=>$registerObj['suppId'] ,"carNumber"=>$registerObj['carNum']));
		$this->assign("carModel" ,$vehicleObj['carModel']); //车型
		$this->assign("phoneNum" ,$vehicleObj['phoneNum']); //联系电话

		$this->view ( 'record' );
	}

	/**
	 * 跳转到用车信息汇总导出页面
	 */
	function c_toExcelOutMessage() {
		$this->view ( 'excelOutMessage' );
	}

	/**
	 * 用车信息汇总导出
	 */
	function c_excelOutMessage() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['projectName'])) //项目名称
			$this->service->searchArr['projectNameSea'] = $formData['projectName'];
		if(!empty($formData['projectCode'])) //项目编号
			$this->service->searchArr['projectCodeSea'] = $formData['projectCode'];

		if(!empty($formData['useCarDateSta'])) //用车时间下
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //用车时间下
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];
		if(!empty($formData['suppName'])) //供应商名称
			$this->service->searchArr['suppNameSea'] = $formData['suppName'];

		if(!empty($formData['rentalContractCode'])) //租车合同编号
			$this->service->searchArr['rentalContractCodeSea'] = $formData['rentalContractCode'];
		if(!empty($formData['carNum'])) //车牌号
			$this->service->searchArr['carNumSea'] = $formData['carNum'];

		$this->service->searchArr['ExaStatusArr'] = "部门审批,完成,打回";
		$this->service->sort = 'c.useCarDate';
		$this->service->groupBy = 'c.id,r.carNum';
		$rows = $this->service->listBySqlId('select_message');

		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$data = array();
		$registerDao = new model_outsourcing_vehicle_register();
		foreach ($rows as $key => $val) {
			$data[$key]['useCarDate'] = substr($val['useCarDate'] , 0 ,-3);
			$data[$key]['projectName'] = $val['projectName'];
			$data[$key]['projectCode'] = $val['projectCode'];
			switch ($val['state']) {
				case '0' : $data[$key]['state'] = '未提交';break;
				case '1' : $data[$key]['state'] = '未审批';break;
				case '2' : $data[$key]['state'] = '审批完成';break;
				case '3' : $data[$key]['state'] = '打回';break;
				default : $data[$key]['state'] = '';
			}
			$data[$key]['projectType'] = $val['projectType'];
			$data[$key]['officeName'] = $val['officeName'];
			$data[$key]['province'] = $val['province'];
			$data[$key]['city'] = $val['city'];
			$data[$key]['suppName'] = $val['suppName'];
			$data[$key]['rentalContractCode'] = $val['rentalContractCode'];
			$data[$key]['carNum'] = $val['carNum'];
			if ($val['rentalPropertyCode'] != 'ZCXZ-02') { //合同用车天数
				$data[$key]['contractUseDay'] = $registerDao->getDaysOrFee_d($val['id'] ,$val['rentalContractId']);
			} else {
				$data[$key]['contractUseDay'] = $val['registerNum'];
			}

            $registerDao = new model_outsourcing_vehicle_register();
            $val['rentalCarCost'] = $registerDao->getRentalCarCostByRegisterId($val['allRegisterId'],$val['carNum']);

			$data[$key]['registerNum'] = $val['registerNum'];
			$data[$key]['startMileage'] = number_format($val['startMileage'] ,2);
			$data[$key]['endMileage'] = number_format($val['endMileage'] ,2);
			$data[$key]['effectMileage'] = number_format($val['effectMileage'] ,2);
			$data[$key]['gasolineKMPrice'] = number_format($val['gasolineKMPrice'] ,2);
			$data[$key]['gasolineKMCost'] = number_format($val['gasolineKMCost'] ,2);
			$data[$key]['reimbursedFuel'] = number_format($val['reimbursedFuel'] ,2);
			$data[$key]['rentalCarCost'] = number_format($val['rentalCarCost'] ,2);
			$data[$key]['parkingCost'] = number_format($val['parkingCost'] ,2);
            $data[$key]['tollCost'] = number_format($val['tollCost'] ,2);
			$data[$key]['mealsCost'] = number_format($val['mealsCost'] ,2);
			$data[$key]['accommodationCost'] = number_format($val['accommodationCost'] ,2);
			$data[$key]['overtimePay'] = number_format($val['overtimePay'] ,2);
			$data[$key]['specialGas'] = number_format($val['specialGas'] ,2);
			$data[$key]['allCost'] = number_format(
				$val['gasolineKMCost']
				+ $val['reimbursedFuel']
				+ $val['rentalCarCost']
				+ $val['parkingCost']
                + $val['tollCost']
				+ $val['mealsCost']
				+ $val['accommodationCost']
				+ $val['overtimePay']
				+ $val['specialGas'] ,2
			);
			$data[$key]['effectLogTime'] = $val['effectLogTime'];
			$data[$key]['estimate'] = $val['estimate'];
			$data[$key]['remark'] = $val['remark'];
		}
		$colArr  = array();
		$modelName = '外包-用车信息汇总';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$data ,$modelName);
	}

	/**
	 * 跳转到打回页面
	 */
	function c_toBack() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//数字千分位显示
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //有效里程

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //路桥\停车费
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //餐饮费
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //住宿费

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //加班费
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //特殊费
		$this->view ('back' ,true);
	}

	function msgRF2($title,$url){
        echo "<script>alert('" . $title . "');history.go(-1);</script>";
    }
	/**
	 * 打回
	 */
	function c_back() {
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];

        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $deductinfoDao = new model_outsourcing_vehicle_deductinfo();
        $errorsMsg = "";
        foreach ($obj['register'] as $key => $val) {
            if ($val['back'] == 1) {
                // 打回时清除掉相应的扣款记录
                $deductinfo = $deductinfoDao->find(" allregisterId = '{$obj['id']}' and carNum like '%{$val['carNum']}%' and FIND_IN_SET('{$val['id']}',registerIds) > 0");
                if($deductinfo){
                    $deductinfoDao->delete(array("id"=>$deductinfo['id']));
                }

                $expenseTmpdata = $expensetmpDao->findAll(" allregisterId = '{$obj['id']}' and FIND_IN_SET('{$val['carNum']}',carNumber) > 0 and FIND_IN_SET('{$val['id']}',registerIds) > 0");
                $ids = '';
                foreach ($expenseTmpdata as $k => $v){
                    $ids .= ($ids == '')? $v['id'] : ','.$v['id'];
                }
                if($ids != ''){
                    $deleteResult = $expensetmpDao->deleteRecordById($ids);
                    if($deleteResult['result'] == "fail"){
                        $errorsMsg = $deleteResult['msg'];
                        break;
                    }
                }
            }
        }

        if($errorsMsg == ""){
            $rs = $this->service->back_d( $obj );
            if($rs) {
                msg( '打回成功！' );
            } else {
                msg( '打回失败！' );
            }
        }else{
            $this->msgRF2($errorsMsg,"index1.php?model=outsourcing_vehicle_allregister&action=toBack&id=".$obj['id']);
        }
	}

	/**
	 * 跳转到申请付款页面
	 */
	function c_toPayment() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//数字千分位显示
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //有效里程
		$this->assign("rentalCarCost" ,number_format($obj['rentalCarCost'] ,2)); //租车费
		$this->assign("reimbursedFuel" ,number_format($obj['reimbursedFuel'] ,2)); //实报实销油费
		$this->assign("gasolineKMCost" ,number_format($obj['gasolineKMCost'] ,2)); //按公里计价油费

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //路桥\停车费
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //餐饮费
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //住宿费

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //加班费
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //特殊油费

		$this->view ('payment' ,true);
	}

    /**
     * 获取分页数据转成Json【重写】
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ();
//        foreach ($rows as $k => $v){
//            $dateLimit = substr($v['useCarDate'],0,7);
//            $cost = $service->getRentalCarCostVal($v['id'],$dateLimit);
//            $rows[$k]['rentalCarCost'] = ($cost > 0)? $cost : $v['rentalCarCost'];
//        }

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