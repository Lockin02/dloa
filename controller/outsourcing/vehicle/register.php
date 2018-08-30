<?php
/**
 * @author Michael
 * @Date 2014年2月10日 星期一 18:40:56
 * @version 1.0
 * @description:租车登记表控制层
 */
class controller_outsourcing_vehicle_register extends controller_base_action {

	function __construct() {
		$this->objName = "register";
		$this->objPath = "outsourcing_vehicle";
		parent::__construct ();
	 }

	/**
	 * 跳转到租车登记列表
	 */
	function c_page() {
		$this->assign ('userId' ,$_SESSION['USER_ID']);
		$this->service->setCompany(0); # 个人列表,不需要进行公司过滤
		$this->view('list');
	}

    /**
     * 获取分页数据转成Json 【重写】
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        // 需要根据合同期限过滤数据
        if(isset($_REQUEST['needConDateFielt'])){
            $carNum = isset($_REQUEST['carNum'])? $_REQUEST['carNum'] : '';
            $carNum = util_jsonUtil::iconvUTF2GB($carNum);
            $allregisterId = isset($_REQUEST['allregisterId'])? $_REQUEST['allregisterId'] : '';

            $chkResult = $this->service->getCarsBelongContractDate($carNum,$allregisterId);
            $contractStartDate = $chkResult['contractStartDate'];
            $contractEndDate = $chkResult['contractEndDate'];

            if(!empty($contractStartDate) && !empty($contractEndDate)){
                switch ($_REQUEST['needConDateFielt']){
                    // 合同期内的登记
                    case '1':
                        $service->searchArr['useCarDateSta'] = $contractStartDate;
                        $service->searchArr['useCarDateEnd'] = $contractEndDate;
                        break;
                    // 合同期外的登记
                    default:
                        $service->searchArr['useCarDateOutRangeSql'] = "sql: and (c.useCarDate < '{$contractStartDate}' or c.useCarDate > '{$contractEndDate}') ";
                        break;
                }
            }
        }

        //$service->asc = false;
        $rows = $service->page_d ();
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

	/**
	 * 跳转到租车登记明细列表页面
	 */
	function c_toDetail() {
		$this->view('detail');
	}

	/**
	 * 非项目工程师查看租车登记列表
	 */
	function c_pageView() {
		$this->permCheck (); //安全校验
        $this->assign('needConDateFielt' ,isset($_GET['needConDateFielt'])? $_GET['needConDateFielt'] : '');
		$this->assign('carNum' ,$_GET['carNum']);
		$this->assign('allregisterId' ,$_GET['allregisterId']);
		$this->view('listView');
	}

	/**
	 * 跳转到新增租车登记表页面
	 */
	function c_toAdd() {
		$this->assign('createId' ,$_SESSION ['USER_ID']);
		$this->assign('createName' ,$_SESSION ['USERNAME']);
		$this->assign('createTime' ,date ( "Y-m-d H:i:s" ));
		$this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ'));  //租车性质
		$this->showDatadicts(array('contractTypeCode' => 'ZCHTLX')); //合同类型
		$this->showDatadicts(array('carModelCode' => 'WBZCCX')); //车型
		$this->view ('add' ,true);
	}

	/**
	 * 重写add
	 */
	function c_add(){
		$this->checkSubmit(); //验证是否重复提交
		$isSub = isset ($_GET['isSub']) ? $_GET['isSub'] : null;
		$obj = $_POST[$this->objName];
		if ($isSub) {
			$obj['state'] = 1;
		}
		$id = $this->service->add_d( $obj );
		if($id) {
			if ($isSub) {
				msg( '提交成功！' );
			} else {
				msg( '保存成功！' );
			}
		} else {
			msg( '保存失败！' );
		}
	}

	/**
	 * 右键ajax提交
	 */
	function c_ajaxSubmit() {
		$rs = $this->service->submit_d($_POST['id']);
		if($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ajax批量提交
	 */
	function c_ajaxBatchSubmit() {
		$rs = $this->service->submit_d($_POST['ids'],"batch");
		if($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 跳转到编辑租车登记表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ') ,$obj['rentalPropertyCode']);  //租车性质
		$this->showDatadicts(array('contractTypeCode' => 'ZCHTLX') ,$obj['contractTypeCode']);  //合同类型
		$this->showDatadicts(array('carModelCode' => 'WBZCCX') ,$obj['carModelCode']); //车型
		$this->assign("file",$this->service->getFilesByObjId($_GET ['id'] ,true)); //显示附件信息
		$this->view ('edit' ,true);
	}

	/**
	 * 重写edit
	 */
	function c_edit(){
		$this->checkSubmit(); //验证是否重复提交
		$isSub = isset ($_GET['isSub']) ? $_GET['isSub'] : null;
		$obj = $_POST[$this->objName];
		if ($isSub) {
			$obj['state'] = 1;
		}
		$id = $this->service->edit_d( $obj );
		if($id) {
			if ($isSub) {
				msg( '提交成功！' );
			} else {
				msg( '保存成功！' );
			}
		} else {
			msg( '保存失败！' );
		}
	}

	/**
	 * 跳转到查看租车登记表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign("isCardPay" ,$obj['isCardPay'] == 1 ? '是，使用油卡支付' : '否，报销支付');
		//数字千分位显示
		$this->assign("startMileage" ,number_format($obj['startMileage'] ,2)); //开始里程
		$this->assign("endMileage" ,number_format($obj['endMileage'] ,2)); //结束里程
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //有效里程

		$this->assign("gasolinePrice" ,number_format($obj['gasolinePrice'] ,2)); //油价
		$this->assign("reimbursedFuel" ,number_format($obj['reimbursedFuel'] ,2)); //实报实销油费
		$this->assign("gasolineKMPrice" ,number_format($obj['gasolineKMPrice'] ,2)); //按公里计价油费单价
		$this->assign("shortRent" ,number_format($obj['shortRent'] ,2)); //短租车费

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //停车费
        $this->assign("tollCost" ,number_format($obj['tollCost'] ,2)); //路桥费
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //餐饮费
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //住宿费

		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //显示附件信息
		$this->view ( 'view' );
	}

	/**
	 * 按月获取所有数据返回json
	 */
	function c_statisticsJson() {
		$service = $this->service;
        $enCodeLimitRelativeCarNum = isset($_REQUEST['limitRelativeCarNum'])? $_REQUEST['limitRelativeCarNum'] : "";
        $deCodeLimitRelativeCarNum = ($enCodeLimitRelativeCarNum != '')? base64_decode($enCodeLimitRelativeCarNum) : "";
        $limitRentalProperty = isset($_REQUEST['limitRentalProperty'])? $_REQUEST['limitRentalProperty'] : "";

//		$service->getParam ( $_REQUEST );
//		$service->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
//		$rows = $service->listBySqlId ( 'select_Month' );
        $rows = $service->getStatisticsJsonData();
        $rentCarPayInfoDao =  new model_outsourcing_contract_payInfo();
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();

        $relativeCarsArrForCz = array();
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				if ($val['rentalContractId'] > 0) {
					//计算租车费和合同用车天数
					$rows[$key]['rentalCarCost'] = $service->getDaysOrFee_d($val['id'] ,$val['rentalContractId'] ,false);
					$rows[$key]['contractUseDay'] = $service->getDaysOrFee_d($val['id'] ,$val['rentalContractId'] ,true);

					//宏达租车
					if ($val['rentalPropertyCode'] == 'ZCXZ-03') {
						$rows[$key]['rentalCarCost'] = $service->getHongdaFee_d($val['id'] ,$val['rentalContractId']);
					}else{
                        $rows[$key]['rentalPropertyCode'] = $val['rentalPropertyCode'] = 'ZCXZ-01';// 含有关联合同号的,默认为长租
                        $rows[$key]['rentalProperty'] = $val['rentalProperty'] = '长租';
                    }
				} else {
					$rows[$key]['rentalCarCost'] = '';
					$rows[$key]['contractUseDay'] = '';
				}

				if ($val['rentalPropertyCode'] == 'ZCXZ-02') { //短租的情况
					$obj = $service->get_d( $val['id'] );
					$rows[$key]['rentalCarCost'] = $rows[$key]['shortRent']; //短租的直接显示短租车费的累加
					$rows[$key]['gasolineKMPrice'] = $obj['gasolineKMPrice'];
					$rows[$key]['gasolineKMCost'] = $obj['gasolineKMPrice'] * $rows[$key]['effectMileage'];
				}

				// 按计费方式带出相应的租车费用
				if(!empty($val['rentalContractId']) && $val['rentalContractId'] > 0){// 含有关联合同号的,按长租来读取对应的租车费
                    $rentalConDao = new model_outsourcing_contract_rentcar();
                    $rentalContract = $rentalConDao->get_d($val['rentalContractId']);
                    if($rentalContract){
                        $rows[$key]['rentalContractStartDate'] = $rentalContract['contractStartDate'];
                        $rows[$key]['rentalContractEndDate'] = $rentalContract['contractEndDate'];
                    }
                    $rentalCarCost = $service->getRentalCarCost($val['rentalContractId'],$val['allregisterId'],$val['carNum']);
                    $rows[$key]['rentalCarCost'] = $rentalCarCost;
                }

				// 获取租车合同关联的付款方式
                $payInfos = $rentCarPayInfoDao->findAll(" mainId = '{$val['rentalContractId']}' and (isDel is null or isDel <> 1)");
                $payInfosArr = array(
                    "payInfoId1" => '',
                    "payInfoMoney1" => '',
                    "payInfoId2" => '',
                    "payInfoMoney2" => ''
                );

                $isFirstCar = 1;$isFirstCzCar = 0;
                if($payInfos && count($payInfos) > 0){
                    // 统计当前的实时费用总额
                    $payInfos = $service->countRealCostMoney($payInfos,$rows[$key]);

                    // $carNum = base64_encode($val['carNum']);
                    $carNum = $val['carNum'];
                    // 加载相应的车牌的支付费用信息
                    $payInfosRecord = ($payInfos[0]['id'] != '')? $expensetmpDao->findExpenseTmpRecord("",$val['allregisterId'],$carNum,$payInfos[0]['id'],1) : array();
                    $payInfosArr['expenseTmpId1'] = '-';
                    $payInfosArr['payInfoId1'] = isset($payInfos[0])? $payInfos[0]['id'] : '';
                    if($payInfosRecord && isset($payInfosRecord['id'])){
                        $payInfosArr['expenseTmpId1'] = $payInfosRecord['id'];
                    }
                    $payInfosArr['pay1Cost'] = ($payInfosRecord && isset($payInfosRecord['payMoney']))? $payInfosRecord['payMoney'] : (($payInfosArr['payInfoId1'] != "")? '未生成' : '-');
                    $payInfosArr['realNeedPayCost1'] = isset($payInfos[0]['realCostMoney'])? $payInfos[0]['realCostMoney'] : 0;
                    $payInfosArr['pay1Cost'] = ($payInfos[0]['payTypeCode'] == "HETFK")? $payInfosArr['pay1Cost'] : $payInfosArr['pay1Cost'];
                    $payInfosArr['pay1payTypeCode'] = $payInfos[0]['payTypeCode'];
                    // 根据已填报的费用记录, 将合并的汇总记录归到一组,便于后面统计实际应填金额
                    if($payInfosRecord){
                        $payInfosRecordIncludeCars = explode(",",$payInfosRecord['carNumber']);
                        $isFirstCzCar = ($val['carNum'] == $payInfosRecordIncludeCars[0])? 1 : 0;
                        if(count($payInfosRecordIncludeCars) > 1){// 车牌数量大于1的才算是合并填报的, 避免单个车牌, 只填报了支付金额1却把支付金额2也显示出来了（因为合并填报是同时填报支付金额1和2的,所以如果是合并填报的记录的话系统会默认把支付金额1和2都算为已填报）
                            if(isset($relativeCarsArrForCz[$payInfosRecord['carNumber']])){
                                if(!in_array($key,$relativeCarsArrForCz[$payInfosRecord['carNumber']])){
                                    $relativeCarsArrForCz[$payInfosRecord['carNumber']][] = $key;
                                }
                            }else{
                                $relativeCarsArrForCz[$payInfosRecord['carNumber']] = array();
                                $relativeCarsArrForCz[$payInfosRecord['carNumber']][] = $key;
                            }
                        }
                    }

                    $payInfosRecord = ($payInfos[1]['id'] != '')? $expensetmpDao->findExpenseTmpRecord("",$val['allregisterId'],$carNum,$payInfos[1]['id'],1) : array();
                    $payInfosArr['expenseTmpId2'] = '-';
                    $payInfosArr['payInfoId2'] = isset($payInfos[1])? $payInfos[1]['id'] : '';
                    if($payInfosRecord && isset($payInfosRecord['id'])){
                        $payInfosArr['expenseTmpId2'] = $payInfosRecord['id'];
                    }
                    $payInfosArr['pay2Cost'] = ($payInfosRecord && isset($payInfosRecord['payMoney']))? $payInfosRecord['payMoney'] : (($payInfosArr['payInfoId2'] != '')? '未生成' : '-');
                    $payInfosArr['realNeedPayCost2'] = isset($payInfos[1]['realCostMoney'])? $payInfos[1]['realCostMoney'] : 0;
                    $payInfosArr['pay2Cost'] = ($payInfos[1]['payTypeCode'] == "HETFK")? $payInfosArr['pay2Cost'] : $payInfosArr['pay2Cost'];
                    $payInfosArr['pay2payTypeCode'] = $payInfos[1]['payTypeCode'];
                    // 根据已填报的费用记录, 将合并的汇总记录归到一组,便于后面统计实际应填金额
                    if($payInfosRecord){
                        $payInfosRecordIncludeCars = explode(",",$payInfosRecord['carNumber']);
                        $isFirstCzCar = ($val['carNum'] == $payInfosRecordIncludeCars[0])? 1 : 0;
                        if(count($payInfosRecordIncludeCars) > 1){
                            if(isset($relativeCarsArrForCz[$payInfosRecord['carNumber']])){
                                if(!in_array($key,$relativeCarsArrForCz[$payInfosRecord['carNumber']])){
                                    $relativeCarsArrForCz[$payInfosRecord['carNumber']][] = $key;
                                }
                            }else{
                                $relativeCarsArrForCz[$payInfosRecord['carNumber']] = array();
                                $relativeCarsArrForCz[$payInfosRecord['carNumber']][] = $key;
                            }
                        }
                    }
                }else{// 短租没有相关支付信息的情况
                    $carNum = $val['carNum'];
                    // 加载相应的车牌的支付费用信息
                    $payInfosRecord = $expensetmpDao->findExpenseTmpRecord("",$val['allregisterId'],$carNum,$payInfosArr['payInfoId1'],1,0,"短租");
                    $payInfosRecordIncludeCars = explode(",",$payInfosRecord['carNumber']);
                    $isFirstCar = ($payInfosRecordIncludeCars[0] == $val['carNum'])? 1 : 0;
                    $payInfosArr['expenseTmpId1'] = ($payInfosRecord && isset($payInfosRecord['id']))? $payInfosRecord['id'] : '';
                    $payInfosArr['payInfoId1'] = '';
                    $payInfosArr['pay1Cost'] = ($payInfosRecord && isset($payInfosRecord['payMoney']))? $payInfosRecord['payMoney'] : '未生成';
                    $isFirstCar = ($payInfosArr['pay1Cost'] == "未生成")? 1 : $isFirstCar;

                    // 统计当前的实时费用总额
                    $payInfosArr = $service->countRealCostMoney($payInfosArr,$rows[$key],'dz');
                }

                $rows[$key]['isFirstCar'] = $isFirstCar;// 是否为记录里面的第一条记录（短租类型才用的字段）
                $rows[$key]['isFirstCzCar'] = $isFirstCzCar;
                $rows[$key]['expenseTmpId1'] = $payInfosArr['expenseTmpId1'];
                $rows[$key]['payInfoId1'] = $payInfosArr['payInfoId1'];
                $rows[$key]['realNeedPayCost1'] = isset($payInfosArr['realNeedPayCost1'])? $payInfosArr['realNeedPayCost1'] : (($payInfosArr['pay1Cost'] == "未生成")? '' : 0);
                $rows[$key]['payInfoMoney1'] = ($rows[$key]['realNeedPayCost1'] > 0)? $payInfosArr['pay1Cost'] : (($payInfosArr['pay1Cost'] == "未生成")? '未生成' : ($payInfosArr['pay1Cost']>0)? $payInfosArr['pay1Cost'] : 0);
                $rows[$key]['pay1payTypeCode'] = $payInfosArr['pay1payTypeCode'];
                $rows[$key]['expenseTmpId2'] = $payInfosArr['expenseTmpId2'];
                $rows[$key]['payInfoId2'] = $payInfosArr['payInfoId2'];
                $rows[$key]['pay2payTypeCode'] = $payInfosArr['pay2payTypeCode'];
                $rows[$key]['realNeedPayCost2'] = isset($payInfosArr['realNeedPayCost2'])? $payInfosArr['realNeedPayCost2'] : (($payInfosArr['pay2Cost'] == "未生成")? '' : 0);
                $rows[$key]['payInfoMoney2'] = ($rows[$key]['realNeedPayCost2'] > 0)? $payInfosArr['pay2Cost'] : (($payInfosArr['pay2Cost'] == "未生成")? '未生成' : ($payInfosArr['pay2Cost']>0)? $payInfosArr['pay2Cost'] : 0);
                $rows[$key]['rentalContractNature'] = ($val['rentalPropertyCode'] == "ZCXZ-02")? "无" : $val['rentalContractNature'];// 短租的合同性质显示无

				//重新计算总费用
				$rows[$key]['allCost'] = (double)$rows[$key]['gasolineKMCost']
										+ (double)$rows[$key]['reimbursedFuel']
										+ (double)$rows[$key]['parkingCost']
                                        + (double)$rows[$key]['tollCost']
										+ (double)$rows[$key]['mealsCost']
										+ (double)$rows[$key]['accommodationCost']
										+ (double)$rows[$key]['overtimePay']
										+ (double)$rows[$key]['specialGas']
										+ $rows[$key]['rentalCarCost'];
			}

            if($deCodeLimitRelativeCarNum != ""){
                $backArr = array();
                $limitRelativeCarNum = explode(",",$deCodeLimitRelativeCarNum);
                foreach ($rows as $k => $v){
                    if(in_array($v['carNum'],$limitRelativeCarNum) && $v['rentalProperty'] == util_jsonUtil::iconvUTF2GB($limitRentalProperty)){
                        $backArr[] = $v;
                    }
                }
                $rows = $backArr;
            }
		}
		// 补充扣款信息
        $rows = $this->addDeductInfo($rows);

        // 调整合并长租的实际应填金额
        if(!empty($relativeCarsArrForCz)){
            $groupNum = 1;
            foreach ($relativeCarsArrForCz as $rowsKey){
                $realCostNeed1 = $realCostNeed2 = 0;
                // 统计
                foreach ($rowsKey as $k){
                    $realCostNeed1 = bcadd($realCostNeed1,$rows[$k]['realNeedPayCost1'],2);
                    $realCostNeed2 = bcadd($realCostNeed2,$rows[$k]['realNeedPayCost2'],2);
                }

                // 赋值
                foreach ($rowsKey as $k){
                    if(!empty($rows[$k])){
                        $rows[$k]['realNeedPayCost1'] = $realCostNeed1;
                        $rows[$k]['realNeedPayCost2'] = $realCostNeed2;
                        // 因为合并填报是同时填报支付金额1和2的,所以如果是合并填报的记录的话系统会默认把支付金额1和2都算为已填报
                        // if($realCostNeed2 > 0 && $rows[$k]['payInfoMoney2'] == 0){
                           // $rows[$k]['payInfoMoney2'] = $realCostNeed2;
                        // }(发现会有非合拼记录进入到此循环,导致支付金额错误显示,影响了后期的报销单生成,暂时屏蔽此段代码)
                        $rows[$k]['belongGroup'] = $groupNum;
                    }
                }
                $groupNum += 1;
            }
        }

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * 补充扣款信息
     * @param array $rows
     * @return array
     */
	function addDeductInfo($rows = array()){
        $deductinfoDao = new model_outsourcing_vehicle_deductinfo();
	    if(!empty($rows) && is_array($rows)){
	        foreach ($rows as $key => $row){
	            // 每个租车登记汇总里面的每个车牌只会有一条扣款信息
                $deductInfoArr = $deductinfoDao->find(array("allregisterId" => $row['allregisterId'],"carNum" => $row['carNum']));
                if($deductInfoArr){
                    // 如果存在对应的扣款信息,对应支付方式的实际应填金额应该减去该扣款金额
                    if($deductInfoArr['payinfoId'] == $row['payInfoId1'] || $deductInfoArr['expensetmpId'] == $row['expenseTmpId1']){
                        $rows[$key]['realNeedPayCost1'] = bcsub($row['realNeedPayCost1'],$deductInfoArr['deductMoney'],2);
                    }else if($deductInfoArr['payinfoId'] == $row['payInfoId2'] || $deductInfoArr['expensetmpId'] == $row['expenseTmpId2']){
                        $rows[$key]['realNeedPayCost2'] = bcsub($row['realNeedPayCost2'],$deductInfoArr['deductMoney'],2);
                    }
                    $rows[$key]['deductInfoId'] = $deductInfoArr['id'];
                    $rows[$key]['deductMoney'] = $deductInfoArr['deductMoney'];
                    $rows[$key]['deductReason'] = $deductInfoArr['deductReason'];
                }
            }
        }
	    return $rows;
    }

    /**
     * 查询租车登记记录是否有生成关联的报销单信息临时记录
     *
     * @param string $carNum
     * @param string $allregisterId
     * @param string $retalcarContId
     * @return array
     */
	function c_chkExpenseTmp($carNum = '',$allregisterId = '',$retalcarContId = ''){
	    $chkFrom = isset($_REQUEST['chkType'])? $_REQUEST['chkType'] : '';
	    $carNum = isset($_REQUEST['carNum'])? $_REQUEST['carNum'] : $carNum;
        $allregisterId = isset($_REQUEST['allregisterId'])? $_REQUEST['allregisterId'] : $allregisterId;
        $retalcarContId = isset($_REQUEST['retalcarContId'])? $_REQUEST['retalcarContId'] : $retalcarContId;

        $rentalcarExpensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $data = $rentalcarExpensetmpDao->list_d();
        var_dump($data);
        $backData = array();

        if($chkFrom == 'json'){
            echo util_jsonUtil::encode($backData);
        }else{
            return $backData;
        }
    }

	/**
	 * 根据登记汇总表ID判断是否可以变更
	 */
	function c_isChange() {
		$arr = $this->service->get_table_fields('oa_outsourcing_allregister' ,'id='.$_POST['allregisterId'] ,'state');
		if ($arr == '0') {
			echo 1;
		}
	}

	/**
	 * 跳转到租车登记变更页面
	 */
	function c_toChange() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('carModelCode' => 'WBZCCX') ,$obj['carModelCode']); //车型
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //显示附件信息
		$this->view ('change' ,true);
	}

	/**
	 * 变更信息
	 */
	function c_change(){
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$id = $this->service->change_d( $obj );
		if($id) {
			msg( '变更成功！' );
		} else {
			msg( '变更失败！' );
		}
	}

	/**
	 * 跳转到查看租车登记变更原因页面
	 */
	function c_toChangeReason() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign("file",$this->service->getFilesByObjId($_GET ['id'] ,false)); //显示附件信息
		$this->view ( 'changeReason' );
	}

	/**
	 * 跳转到excel导出页面
	 */
	function c_toExcelOut() {
		$this->permCheck (); //安全校验
		//判断从哪里点的导出
		$createId = isset ($_GET['createId']) ? $_GET['createId'] : null;
		$this->assign('createId' ,$createId); //个人列表导出

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

		if(!empty($formData['useCarDateSta'])) //用车日期下
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //用车日期下
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		if(!empty($formData['provinceId'])) //省份
			$this->service->searchArr['provinceId'] = $formData['provinceId'];
		if(!empty($formData['cityId'])) //城市
			$this->service->searchArr['cityId'] = $formData['cityId'];

		if(!empty($formData['driverName'])) //司机姓名
			$this->service->searchArr['driverNameSea'] = $formData['driverName'];

		if(!empty($formData['carNum'])) //车牌号
			$this->service->searchArr['carNum'] = $formData['carNum'];

		if(!empty($formData['createDateSta'])) //录入时间下
			$this->service->searchArr['createDateSta'] = $formData['createDateSta'];
		if(!empty($formData['createDateEnd'])) //录入时间下
			$this->service->searchArr['createDateEnd'] = $formData['createDateEnd'];

		if(!empty($formData['createId'])) //录入人
			$this->service->searchArr['createId'] = $formData['createId'];

		$this->service->searchArr['state'] = 1;
		$rows = $this->service->listBySqlId('select_default');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['driverName'] = $v['driverName'];
			$rowData[$k]['createName'] = $v['createName'];
			$rowData[$k]['createTime'] = $v['createTime'];
			$rowData[$k]['useCarDate'] = $v['useCarDate'];
			$rowData[$k]['projectName'] = $v['projectName'];
			$rowData[$k]['province'] = $v['province'];
			$rowData[$k]['city'] = $v['city'];
			$rowData[$k]['carNum'] = $v['carNum'];
			$rowData[$k]['carModel'] = $v['carModel'];
			$rowData[$k]['startMileage'] = $v['startMileage'];
			$rowData[$k]['endMileage'] = $v['endMileage'];
			$rowData[$k]['effectMileage'] = $v['effectMileage'];
			$rowData[$k]['gasolinePrice'] = $v['gasolinePrice'];
			$rowData[$k]['gasolineKMPrice'] = $v['gasolineKMPrice'];
			$rowData[$k]['reimbursedFuel'] = $v['reimbursedFuel'];
			$rowData[$k]['gasolineKMCost'] = $v['gasolineKMCost'];
			$rowData[$k]['parkingCost'] = $v['parkingCost'];
            $rowData[$k]['tollCost'] = $v['tollCost'];
			if ($v['rentalPropertyCode'] == 'ZCXZ-02') { //短租
				$rowData[$k]['rentalCarCost'] = $v['shortRent'];
			} else {
				$rowData[$k]['rentalCarCost'] = '';
			}
			$rowData[$k]['mealsCost'] = $v['mealsCost'];
			$rowData[$k]['accommodationCost'] = $v['accommodationCost'];
			$rowData[$k]['overtimePay'] = $v['overtimePay'];
			$rowData[$k]['specialGas'] = $v['specialGas'];
			$rowData[$k]['effectLogTime'] = $v['effectLogTime'];
		}

		$colArr  = array();
		$modelName = '外包-租车登记信息';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	/**
	 * 车辆供应商，跳转到项目信息
	 */
	function c_toProject(){
		$this->assign ( 'suppId' ,$_GET['suppId'] );
		$this->view ( 'projectView' );
	}

	/**
	 * 车辆供应商，项目信息列表
	 */
	function c_projectList(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ('select_detail');
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

	/**
	 * 跳转到项目导出页面
	 */
	function c_toExcelOutProject(){
		$suppName = '';
		if (isset ($_GET['suppId'])) {
			$suppName = $this->service->get_table_fields("oa_outsourcessupp_vehiclesupp" ,"id=".$_GET['suppId'] ,"suppName");
		}
		$this->assign ( 'suppName' ,$suppName);
		$this->view ( 'exceloutproject' );
	}

	/**
	 * 导出excel
	 */
	function c_projectOut() {
		set_time_limit(0);
		$service = $this->service;
		$formData = $_POST[$this->objName];

		if(trim($formData['suppName'])) //车辆供应商
			$service->searchArr['suppName'] = $formData['suppName'];

		if(trim($formData['projectCode'])) //项目编号
			$service->searchArr['projectCodeE'] = $formData['projectCode'];

		if(trim($formData['projectName'])) //项目名称
			$service->searchArr['projectNameSea'] = $formData['projectName'];

		if(trim($formData['carNum'])) //车牌号
			$service->searchArr['carNumber'] = $formData['carNum'];

		if(trim($formData['rentalContractCode'])) //租车合同编号
			$service->searchArr['rentalContractCode'] = $formData['rentalContractCode'];

		if(trim($formData['rentalCarCost'])) //租车单价
			$service->searchArr['rentalCarCost'] = $formData['rentalCarCost'];

		if(trim($formData['useCarDateSta'])) //租车起始查询时间
			$service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(trim($formData['useCarDateEnd'])) //租车结束查询时间
			$service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		$rows = $service->list_d('selcet_projectOut');
		if($rows){
			foreach($rows as $key=>$val){
				$rows[$key]['useCarDate'] =substr($val['useCarDate'] ,0 ,7);
			}
			for ($i = 0; $i < count($rows); $i++) {
				unset($rows[$i]['id']);
			}
			$colArr  = array();
			$modelName = '车辆供应商项目信息';
			return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
		}else {
			msg("查不到数据！");
		}
	}

	/**
	 * 租车登记列表导出
	 */
	function c_pageViewOut(){
		set_time_limit(0);
		$service = $this->service;
		$service->searchArr['carNum'] = $_GET['carNum'];
		$service->searchArr['allregisterId'] = $_GET['allregisterId'];
		$service->searchArr['state'] = 1;
		$rows = $service->listBySqlId('select_default');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['driverName'] = $v['driverName'];
			$rowData[$k]['createName'] = $v['createName'];
			$rowData[$k]['createTime'] = $v['createTime'];
			$rowData[$k]['useCarDate'] = $v['useCarDate'];
			$rowData[$k]['projectName'] = $v['projectName'];
			$rowData[$k]['province'] = $v['province'];
			$rowData[$k]['city'] = $v['city'];
			$rowData[$k]['carNum'] = $v['carNum'];
			$rowData[$k]['carModel'] = $v['carModel'];
			$rowData[$k]['startMileage'] = $v['startMileage'];
			$rowData[$k]['endMileage'] = $v['endMileage'];
			$rowData[$k]['effectMileage'] = $v['effectMileage'];
			$rowData[$k]['gasolinePrice'] = $v['gasolinePrice'];
			$rowData[$k]['gasolineKMPrice'] = $v['gasolineKMPrice'];
			$rowData[$k]['reimbursedFuel'] = $v['reimbursedFuel'];
			$rowData[$k]['gasolineKMCost'] = $v['gasolineKMCost'];
			$rowData[$k]['parkingCost'] = $v['parkingCost'];
            $rowData[$k]['tollCost'] = $v['tollCost'];
			if ($v['rentalPropertyCode'] == 'ZCXZ-02') { //短租
				$rowData[$k]['rentalCarCost'] = $v['shortRent'];
			} else {
				$rowData[$k]['rentalCarCost'] = $v['rentalCarCost'];
			}
			$rowData[$k]['mealsCost'] = $v['mealsCost'];
			$rowData[$k]['accommodationCost'] = $v['accommodationCost'];
			$rowData[$k]['overtimePay'] = $v['overtimePay'];
			$rowData[$k]['specialGas'] = $v['specialGas'];
			$rowData[$k]['effectLogTime'] = $v['effectLogTime'];
		}

		$colArr  = array();
		$modelName = '外包-租车登记信息';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	/**
	 * 项目信息列表汇总
	 */
	function c_toAllProject(){
        $this->assign('projectId', isset($_GET['projectId'])?$_GET['projectId']:"");
		$this->view ( 'allproject' );
	}

	/**
	 * 租车登记明细汇总列表
	 */
	function c_detailPage(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ('select_detail');
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

	/**
	 * 租车登记明细汇总表查询导出
	 */
	function c_toExcelDetail(){
		$this->view('excelDetail');
	}

	function c_excelDetail(){
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['projectName'])) //项目名称
			$this->service->searchArr['projectNameSea'] = $formData['projectName'];

		if(!empty($formData['useCarDateSta'])) //用车日期下
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //用车日期下
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		if(!empty($formData['provinceId'])) //省份
			$this->service->searchArr['provinceId'] = $formData['provinceId'];
		if(!empty($formData['cityId'])) //城市
			$this->service->searchArr['cityId'] = $formData['cityId'];

		if(!empty($formData['driverName'])) //司机姓名
			$this->service->searchArr['driverNameSea'] = $formData['driverName'];

		if(!empty($formData['carNum'])) //车牌号
			$this->service->searchArr['carNum'] = $formData['carNum'];

		if(!empty($formData['createDateSta'])) //录入时间下
			$this->service->searchArr['createDateSta'] = $formData['createDateSta'];
		if(!empty($formData['createDateEnd'])) //录入时间下
			$this->service->searchArr['createDateEnd'] = $formData['createDateEnd'];

		$rows = $this->service->listBySqlId('select_detail');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['driverName'] = $v['driverName'];
			$rowData[$k]['createName'] = $v['createName'];
			$rowData[$k]['createTime'] = $v['createTime'];
			$rowData[$k]['useCarDate'] = $v['useCarDate'];
			$rowData[$k]['projectName'] = $v['projectName'];
			$rowData[$k]['province'] = $v['province'];
			$rowData[$k]['city'] = $v['city'];
			$rowData[$k]['carNum'] = $v['carNum'];
			$rowData[$k]['carModel'] = $v['carModel'];
			$rowData[$k]['startMileage'] = $v['startMileage'];
			$rowData[$k]['endMileage'] = $v['endMileage'];
			$rowData[$k]['effectMileage'] = $v['effectMileage'];
			$rowData[$k]['gasolinePrice'] = $v['gasolinePrice'];
			$rowData[$k]['gasolineKMPrice'] = $v['gasolineKMPrice'];
			$rowData[$k]['reimbursedFuel'] = $v['reimbursedFuel'];
			$rowData[$k]['gasolineKMCost'] = $v['gasolineKMCost'];
			$rowData[$k]['parkingCost'] = $v['parkingCost'];
            $rowData[$k]['tollCost'] = $v['tollCost'];
			$rowData[$k]['rentalCarCost'] = $v['rentalCarCost'];
			$rowData[$k]['mealsCost'] = $v['mealsCost'];
			$rowData[$k]['accommodationCost'] = $v['accommodationCost'];
			$rowData[$k]['overtimePay'] = $v['overtimePay'];
			$rowData[$k]['specialGas'] = $v['specialGas'];
			$rowData[$k]['effectLogTime'] = $v['effectLogTime'];
		}

		$colArr  = array();
		$modelName = '外包-租车登记信息';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	/**
	 * 按月和车牌号获取所有数据返回json
	 */
	function c_recordJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->listBySqlId('select_default_forRecords');

		if(is_array($rows)){
			//加载合计
			$objArr = $service->listBySqlId('select_sum');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['useCarDate'] = '月统计';
				$rsArr['id'] = 'noId';
			}

			// 重新计算租车费用
            $rentalContractCost = array(
                "shortRentCost" => 0,
                "byMonth" => 0,
                "byDay" => 0,
            );
			foreach ($rows as $row){
			    if(empty($row["rentUnitPriceCalWay"]) || $row["rentUnitPriceCalWay"] == 'null'){
                    $rentalContractCost["shortRentCost"] = round(bcadd($rentalContractCost["shortRentCost"],$row['shortRent'],3),2);
                }else if($row["rentUnitPriceCalWay"] == "byMonth"){
                    $rentalContractCost["byMonth"] = ($rentalContractCost["byMonth"] > 0)? $rentalContractCost["byMonth"] : $row['rentUnitPrice'];
                }else if($row["rentUnitPriceCalWay"] == "byDay"){
                    $rentalContractCost["byDay"] = round(bcadd($rentalContractCost["byDay"],$row['rentUnitPrice'],3),2);
                }
            }

            $rsArr['rentalCarCost'] = bcadd($rentalContractCost['shortRentCost'],bcadd($rentalContractCost['byMonth'],$rentalContractCost['byDay'],6),6);
			$rows[] = $rsArr;
		}

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 跳转到租车登记导入页面
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

		$title = '租车登记导入结果列表';
		$thead = array('数据信息' ,'导入结果');
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/**
	 * 判断是否已经存在记录(能否添加)
	 */
	function c_isCanAdd() {
		$obj = array('projectId' => $_POST['projectId']
				,'useCarDate' => util_jsonUtil::iconvUTF2GB($_POST['useCarDate'])
				,'carNum' => util_jsonUtil::iconvUTF2GB($_POST['carNum'])
			);
		$rs = $this->service->isCanAdd_d( $obj );
		if ($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

    /**
     * 检查批量提交的记录是否满足提交标准
     */
	function c_isCanBatchAdd(){
	    $ids = $_REQUEST['ids'];
        $rs = $this->service->isCanBatchAdd_d( $ids );
        echo util_jsonUtil::encode ( $rs );
    }

	/**
	 * 判断是否已经存在记录(能否变更)
	 */
	function c_isCanAddByChange() {
		$obj = array('projectId' => $_POST['projectId']
				,'useCarDate' => util_jsonUtil::iconvUTF2GB($_POST['useCarDate'])
				,'carNum' => util_jsonUtil::iconvUTF2GB($_POST['carNum'])
			);
		$oldObj = $this->service->get_d( $_POST['id'] );

		if ($obj['projectId'] == $oldObj['projectId']
				&& $obj['useCarDate'] == $oldObj['useCarDate']
				&& $obj['carNum'] == $oldObj['carNum']) {
			echo 1;
		} else {
			$rs = $this->service->isCanAdd_d( $obj );
			if ($rs) {
				echo 1;
			} else {
				echo 0;
			}
		}
	}

	/**
	 * 导入租车登记到页面预览
	 */
	function c_importRegister() {
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = model_outsourcing_outsourcessupp_importVehiclesuppUtil::readExcelData2($filename ,$temp_name,1);
            $excelHeaderArr = $excelData[0];
            unset($excelData[0]);

			if (!is_array($excelData)) {
				echo "<script>alert('上传文件没有数据,请重新上传!');history.go(-1);</script>";
				exit();
			}else if(!in_array("停车费",$excelHeaderArr) || !in_array("路桥费",$excelHeaderArr)){// 根据导入模板的标题判断模板是否为最新
                echo "<script>alert('上传文件模板有误,请下载最新的导入模板后重试!');history.go(-1);</script>";
                exit();
            }

			$registerData = array();
			foreach ($excelData as $key => $val) {
				$registerData[$key]['projectCode'] = $val[0];
				$registerData[$key]['province'] = $val[1];
				$registerData[$key]['city'] = $val[2];
				$registerData[$key]['useCarDate'] = $val[3];
				$registerData[$key]['suppCode'] = $val[4];
				$registerData[$key]['driverName'] = $val[5];
				$registerData[$key]['rentalProperty'] = $val[6];
				$registerData[$key]['contractType'] = $val[7];
				$registerData[$key]['isCardPay'] = $val[8];
				$registerData[$key]['carNum'] = $val[9];
				$registerData[$key]['carModel'] = $val[10];
				$registerData[$key]['startMileage'] = $val[11];
				$registerData[$key]['endMileage'] = $val[12];
				$registerData[$key]['mealsCost'] = $val[13];
				$registerData[$key]['accommodationCost'] = $val[14];
				$registerData[$key]['parkingCost'] = $val[15];
                $registerData[$key]['tollCost'] = $val[16];
				$registerData[$key]['gasolinePrice'] = $val[17];
				$registerData[$key]['reimbursedFuel'] = $val[18];
				$registerData[$key]['overtimePay'] = $val[19];
				$registerData[$key]['specialGas'] = $val[20];
				$registerData[$key]['shortRent'] = $val[21];
				$registerData[$key]['gasolineKMPrice'] = $val[22];
				$registerData[$key]['drivingReason'] = $val[23];
				$registerData[$key]['effectLogTime'] = $val[24];
				$registerData[$key]['remark'] = $val[25];
				$registerData[$key]['fee'] = $val[26];
			}
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->dealExcelData_d($registerData);
			$rs = $resultArr['result'];
			unset($resultArr['result']);

			if (!$rs) {
				$title = '租车登记导入结果列表';
				$thead = array('数据信息' ,'导入结果');
				echo util_excelUtil::showResult($resultArr ,$title ,$thead);
			} else {

                ini_set("memory_limit", "1024M");
                $result = $this->service->excelAddNew_d($resultArr);
                $title = '租车登记导入结果列表';
                $thead = array('数据信息' ,'导入结果');
                if($result && $result > 0){
                    $dataNum = $result;
                    $tempArr = array(
                        array(
                            "result" => "导入成功!",
                            "docCode" => "导入{$dataNum}行数据"
                        )
                    );
                }else{
                    $tempArr = array(
                        array(
                            "result" => "导入失败!",
                            "docCode" => "导入0行数据"
                        )
                    );
                }
                echo util_excelUtil::showResult($tempArr ,$title ,$thead);
//				$this->view('excel-head' ,true);
//				foreach ($resultArr as $key => $val) {
//					$this->assignFunc($val['data']);
//					$this->assign("isCardPayVal" ,$val['data']['isCardPay'] == 1 ? '是，使用油卡支付' : '否，报销支付');
//					$this->assign('i' ,$key);
//					//合同附加费用
//					$fee = '';
//					if (is_array($val['data']['fee'])) {
//						foreach ($val['data']['fee'] as $k => $v) {
//							$num = $k + 1;
//							$fee .= "$num 、$v[feeName]"
//								."<input type='hidden' name='register[$key][fee][$k][feeName]' value='$v[feeName]'/>"
//								."<input type='hidden' name='register[$key][fee][$k][contractId]' value='$v[contractId]'/>"
//								."&nbsp;&nbsp;&nbsp;&nbsp;";
//						}
//					}
//					$this->assign('fee' ,$fee);
//					$this->view('excel-body');
//				}
//				$this->view('excel-foot');
			}
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');history.go(-1);</script>";
		}
	}

	/**
	 * excel导入预览完确定添加
	 */
	function c_excelAdd() {
        set_time_limit(0);
        ini_set("memory_limit", "1024M");
        // ini_set("display_errors",1);
		$this->checkSubmit(); //验证是否重复提交
		if ($this->service->excelAdd_d($_POST[$this->objName])) {
			msg('添加成功！');
		} else {
			msg('添加失败！');
		}
	}

    /**
     * 查询同一个月内是否已存在满足相应条件的审核中或通过了的租车登记汇总
     */
	function c_ajaxChkRentCarRecord(){
        $projectCode = isset($_POST['projectCode'])? util_jsonUtil::iconvUTF2GB($_POST['projectCode']) : '';// 项目编号
        $suppCode = isset($_POST['suppCode'])? util_jsonUtil::iconvUTF2GB($_POST['suppCode']) : '';// 供应商编码
        $carNum = isset($_POST['carNum'])? util_jsonUtil::iconvUTF2GB($_POST['carNum']) : '';// 车牌号码
        $useCarMonth = isset($_POST['useCarMonth'])? $_POST['useCarMonth'] : '';// 对应月份

        $service = $this->service;
        $chkResult = $service->chkRentCarRecord($projectCode,$suppCode,$carNum,$useCarMonth);

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $chkResult );
        echo util_jsonUtil::encode ( $rows );
    }

}
?>