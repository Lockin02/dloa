<?php

/**
 * @author Show
 * @Date 2012年7月29日 16:32:12
 * @version 1.0
 * @description:费用明细 Model层
 */
class model_engineering_cost_esmcostdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_costdetail";
		$this->sql_map = "engineering/cost/esmcostdetailSql.php";
		parent :: __construct();
	}

	/**
	 * 返回确认状态
	 */
	function rtConfirmStatus_d($thisVal){
		switch($thisVal){
			case '0' : return '未确认';break;
			case '1' : return '审核通过';break;
			case '2' : return '打回';break;
			case '3' : return '报销中';break;
			case '4' : return '已报销';break;
			default : return $thisVal;
		}
	}

	/**
	 * 放回星期几
	 */
	function rtWeekDay_d($thisVal){
		switch($thisVal){
			case '0' : return '日';break;
			case '1' : return '一';break;
			case '2' : return '二';break;
			case '3' : return '三';break;
			case '4' : return '四';break;
			case '5' : return '五';break;
			case '6' : return '六';break;
			default : return $thisVal;
		}
	}

    /*********************** 外部信息获取 *************************/
    /**
     * 获取日志信息
     */
    function getWorklog_d($worklogId){
        $worklogDao = new model_engineering_worklog_esmworklog();
        return $worklogDao->find(array('id' => $worklogId));
    }
    /*********************** 增删改查 ****************************/

    //批量新增
    function addBatch_d($object){
//        echo "<pre>";
//        print_r($object);
//        die();

        $worklogArr = $object['worklog'];
        unset($object['worklog']);

        //实例化发票明细
        $invoiceDetailDao = new model_engineering_cost_esminvoicedetail();
        //本次录入金额
        $countMoney = 0;
        try{
            $this->start_d();

            foreach ( $object as $key => $val ) {
            	if(empty($val['costMoney'])){
					continue;
            	}
                //获取发票明细
                $invoiceDetail = $val['invoiceDetail'];
                unset($val['invoiceDetail']);

            	//判断是否包含删除标志位
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;
				if ($isDelTag != 1) {
	                //合并业务数据
	                $val = array_merge($worklogArr,$val);
	                //插入费用明细
	                $id = parent::add_d ( $val,true );

	                $invoiceDetailDao->batchAdd_d($invoiceDetail,$id);
	                //计算本次录入总金额
	                $countMoney = bcadd($countMoney,$val['costMoney'],2);
				}
            }

			//日志id加载
			if($worklogArr['worklogId']){
				$worklogArr['id'] = $worklogArr['worklogId'];
			}
            $this->updateWorklog_d($worklogArr,$countMoney);

			//计算人员的项目费用
            if($worklogArr['projectId']){
				//获取当前项目的费用
				$projectCountArr = $this->getCostFormMember_d($worklogArr['projectId']);

				//更新人员费用信息
				$esmmemberDao = new model_engineering_member_esmmember();
				$esmmemberDao->update(
					array('projectId' => $worklogArr['projectId'] ,'memberId' => $_SESSION['USER_ID']),
					$projectCountArr
				);
            }

//            $this->rollBack();
            $this->commit_d();
            return $countMoney;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

    //批量编辑
    function editBatch_d($object,$invoiceStatus = null){
//        echo "<pre>";
//        print_r($object);
//        die();

        $worklogArr = $object['worklog'];
        unset($object['worklog']);

        $idsArr = array('addIds' => array(),'editIds' => array(),'delIds' => array());

        //实例化发票明细
        $invoiceDetailDao = new model_engineering_cost_esminvoicedetail();
        try{
            $this->start_d();

            $obj = array ();
            foreach ( $object as $key => $val ) {
            	if(empty($val['costMoney'])){
					continue;
            	}
                //获取发票明细
                $invoiceDetail = $val['invoiceDetail'];
                unset($val['invoiceDetail']);

            	//判断是否包含删除标志位
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;

				if (empty ($val ['id'] ) && $isDelTag== 1) {

				}elseif(empty( $val ['id'])){
	                //合并业务数据
	                $val = array_merge($worklogArr,$val);
	                if($invoiceStatus){
						$val['status'] = $invoiceStatus;
	                }
                    //插入费用明细
                    $id = parent::add_d ( $val,true );
	                //计算本次录入总金额
	                $countMoney = bcadd($countMoney,$val['costMoney'],2);

	                //设置返回数组
	                array_push($idsArr['addIds'],$id);
					//处理发票信息
               		$invoiceDetailDao->batchAdd_d($invoiceDetail,$id,$invoiceStatus);
				}elseif($isDelTag == 1){
					//删除
					$this->delete(array('id' => $val['id']));

	                //设置返回数组
	                array_push($idsArr['delIds'],$val['id']);
					//处理发票信息
               		$invoiceDetailDao->batchAdd_d($invoiceDetail,$id,$invoiceStatus);
				}else{
                    //编辑费用明细
                    parent::edit_d ( $val,true );
                    $id = $val['id'];
	                //计算本次录入总金额
	                $countMoney = bcadd($countMoney,$val['costMoney'],2);
	                //设置返回数组
	                array_push($idsArr['editIds'],$id);
					//处理发票信息
               		$invoiceDetailDao->batchAdd_d($invoiceDetail,$id,$invoiceStatus);
				}
            }

			//日志id加载
			if($worklogArr['worklogId']){
				$worklogArr['id'] = $worklogArr['worklogId'];
			}
			//由excel导入费用日志，总费用带入方式不同
			if($worklogArr['isExcel'] == 1){
				//更新日志信息
				$this->updateWorklog_d($worklogArr,$worklogArr['costMoney']);
				unset($worklogArr['isExcel']);
				unset($worklogArr['costMoney']);
			}else{
				//更新日志信息
				$this->updateWorklog_d($worklogArr,$countMoney);
			}


			//计算人员的项目费用
            if($worklogArr['projectId']){
				//获取当前项目的费用
				$projectCountArr = $this->getCostFormMember_d($worklogArr['projectId']);

				//更新人员费用信息
				$esmmemberDao = new model_engineering_member_esmmember();
				$esmmemberDao->update(
					array('projectId' => $worklogArr['projectId'] ,'memberId' => $_SESSION['USER_ID']),
					$projectCountArr
				);
            }

            $this->commit_d();
            return $idsArr;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

    //批量编辑 -- 针对打回的数组
    function reeditBatch_d($object){
//        echo "<pre>";
//        print_r($object);
//        die();

        $worklogArr = $object['worklog'];
        unset($object['worklog']);

        //实例化发票明细
        $invoiceDetailDao = new model_engineering_cost_esminvoicedetail();
        try{
            $this->start_d();

            $obj = array ();
            foreach ( $object as $key => $val ) {
            	$val['status'] = 0;
                //获取发票明细
                $invoiceDetail = $val['invoiceDetail'];
                unset($val['invoiceDetail']);
                if($val['id']){
                    //插入费用明细
                    parent::edit_d ( $val,true );
                    $id = $val['id'];
                }else{
                    //插入费用明细
                    $id = parent::add_d ( $val,true );
                }

                $invoiceDetailDao->batchAdd_d($invoiceDetail,$id);
            }

            //本次录入金额
            $moneyArr = $this->getWorklogMoney_d($worklogArr['id']);

            //更新日志信息
            $this->updateWorklog_d($worklogArr,array('costMoney' => $moneyArr['costMoney'],'confirmMoney' => $moneyArr['confirmMoney'],'backMoney' => $moneyArr['backMoney']));

			//计算人员的项目费用
            if($worklogArr['projectId']){
				//获取当前项目的费用
				$projectCountArr = $this->getCostFormMember_d($worklogArr['projectId']);

				//更新人员费用信息
				$esmmemberDao = new model_engineering_member_esmmember();
				$esmmemberDao->update(
					array('projectId' => $worklogArr['projectId'] ,'memberId' => $_SESSION['USER_ID']),
					$projectCountArr
				);
            }

            $this->commit_d();
//            $this->rollBack();
            return $moneyArr['confirmMoney'];
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

    /**
     * 更新日志状态
     */
    function updateStatus_d($worklogIds,$status = '0'){
		try{

			$sql = "update " .$this->tbl_name . " set status = '$status' where worklogId in ($worklogIds)";
			$this->_db->query($sql);

			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
    }

    /**
     * 更新状态
     */
    function updateCost_d($ids,$status){
		$sql = "update ".$this->tbl_name." set status = '$status' where id in ($ids)";
		$this->query($sql);
    }

    /************************ 业务逻辑方法 ****************************/
    //更新日志的费用信息
    function updateWorklog_d($worklogArr,$costMoney){
    	//实例化日志
        $worklogDao = new model_engineering_worklog_esmworklog();
        try{
        	$this->start_d();

            //更新金额
            $worklogDao->updateCostMoney_d($worklogArr['id'],$costMoney);

            //更新日志信息
            $worklogDao->editOrg_d($worklogArr);

            //更新任务的相关信息
            if($worklogArr['activityId']){
		        //实例化任务
		        $activityDao = new model_engineering_activity_esmactivity();
				$activityArr = $worklogDao->getWorklogCountInfo_d($worklogArr['activityId']);

				//更新任务费用预算
				$activityDao->update(array('id' => $worklogArr['activityId']),array('feeAll' => $activityArr['costMoney']));
            }

            $this->commit_d();
            return true;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

    //验证配置
    function checkConfig_d(){
        //引入配置信息
        include (WEB_TOR."model/common/commonConfig.php");
        //报销模板
        if(!isset($COSTMODEL)){
            return '未设置工程报销费用模板,请联系管理员进行设置';
        }
        return 1;
    }

    /**
     * 获取包含未审核费用的周次
     */
    function getUnconfirmWeek_d($projectId){
    	$this->searchArr = array('projectId' => $projectId,'statusArr' => '0');
		$rows = $this->list_d();
		if($rows[0]['worklogId']){
			//先拿到日志id
			$worklogId = $rows[0]['worklogId'];
			//根据日志id查询日志信息
			$esmworklogDao = new model_engineering_worklog_esmworklog();
			$esmworklogObj = $esmworklogDao->get_d($worklogId);
			//根据日志执行日期计算周次
			$weekTimes = model_engineering_util_esmtoolutil::getEsmWeekNo( $esmworklogObj['executionDate'] );

			return $weekTimes;
		}else{
			return false;
		}
    }

    /*********************** 页面显示方法 ******************************/


	/**************************************** 工程日志部分使用 **********************************/
	/**
	 * 实例化模板
	 */
	function initTempAdd_d($contentIds){
        //查询模板小类型
        $sql = "select
					c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
					c.invoiceTypeName,c.isReplace,c.isEqu,c.isSubsidy
				from
					cost_type c
				where c.CostTypeID in(".$contentIds.") and c.isNew = '1' order by c.ParentCostTypeID,c.orderNum,c.CostTypeID";
        $costTypeArr = $this->_db->getArray($sql);

        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        foreach( $costTypeArr as $k => $v){
            $countI = $v['CostTypeID'];
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $thisI = $countI . "_0";

            $str .=<<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[ParentCostTypeID]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$v[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$v[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$v[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$v[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$v[isSubsidy]"/>
                        <input type="hidden" id="showDays$countI" value="$v[showDays]"/>
                    </td>
	                <td valign="top" class="form_text_right">
EOT;
			//如果需要显示天数，则显示
			if($v['showDays']){
				$str .=<<<EOT
						<span>
							<input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" style="width:60px" onblur="detailSet($countI);countAll();"/>
							X
							天数
							<input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="1" readonly="readonly"/>
						</span>
EOT;
			}else{
				$str .=<<<EOT
	                    <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();"/>
						<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="1"/>
EOT;
			}

			// 是否不需要发票
			if($v['isSubsidy'] == 1){
				$billArr = $this->getBillArr_d($billTypeArr,$v['invoiceType']);
				$str .=<<<EOT
		                </td>
	                    <td valign="top" colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
	                            <tr id="tr_$thisI">
	                                <td width="30%">
	                                    <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
	                                    <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="readOnlyTxtShort" readonly="readonly"/>
	                                </td>
	                                <td width="20%">
	                                    <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="alert('该类型不需要录入发票，不能进行新增操作');"/>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                    <td valign="top">
	                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" class="txtlong"></textarea>
	                    </td>
	                </tr>
EOT;
			}else{
       			$billTypeStr = $this->initBillType_d($billTypeArr,null,$v['invoiceType'],$v['isReplace']);//模板实例化字符串
				$str .=<<<EOT
		                </td>
	                    <td valign="top" colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
	                            <tr id="tr_$thisI">
	                                <td width="30%">
	                                    <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
	                                </td>
	                                <td width="20%">
	                                    <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                    <td valign="top">
	                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" class="txtlong"></textarea>
	                    </td>
	                </tr>
EOT;
			}
        }
		return $str;
	}

    //编辑初始化模板
    //TODO initTemplateEdit
    function initTempEdit_d($worklogId){
        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);
        $initBillStr = $this->initBillType_d($billTypeArr);

        //查询模板小类型
        $sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isReplace,isEqu,isSubsidy from cost_type where isNew = '1'";
        $sysCostTypeArr = $this->_db->getArray($sql);

        //实例化发票对象
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

    	//如果是重新编辑
		$costTypeArr = $this->findAll(array('worklogId' => $worklogId));

        //模板实例化字符串
        $str = null;
        //单据总金额
        $countMoney = 0;
        foreach( $costTypeArr as $k => $v){
            //设置费用类型Id
            $countI = $v['costTypeId'];
            //查询本日志内的该项费用金额
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $countMoney = bcadd($countMoney,$v['costMoney']);

            //获取匹配费用类型
            $thisCostType = $this->initExpenseEdit_d($sysCostTypeArr,$v['costTypeId']);

            $str .=<<<EOT
                <tr class="$trClass" id="tr$v[costTypeId]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[parentCostType]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[costType]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][status]" value="0"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
                    </td>
                    <td valign="top" class="form_text_right">
EOT;
			//如果需要显示天数，则显示
			if($thisCostType['showDays']){
				$str .=<<<EOT
						<span>
							<input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" value="$v[costMoney]" style="width:60px" onblur="detailSet($countI);countAll();"/>
							X
							天数
							<input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="$v[days]" readonly="readonly"/>
						</span>
EOT;
			}else{
				$str .=<<<EOT
	                    <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" value="$v[costMoney]" onblur="detailSet($countI);countAll();"/>
						<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
			}

            $str .=<<<EOT
					</td>
                    <td colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
EOT;
            //获取发票信息
            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
            foreach($esminvoiceDetailArr as $thisK => $thisV){
				// 是否不需要发票
				if($thisCostType['isSubsidy'] == 1){
					$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
					$str .=<<<EOT
	                    <tr id="tr_$thisI">
		                    <td width="30%">
                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
                                <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
	                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" readonly="readonly"/>
	                        </td>
	                        <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="该类型不需要录入发票，不能进行新增操作" onclick="alert('该类型不需要录入发票，不能进行新增操作');"/>
	                        </td>
	                    </tr>
EOT;
				}else{
	                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
	                $thisI = $countI . "_" .$thisK;
	                //图片显示判定
	                $imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
	                //方法判定
	                $funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
	                $invTitle = $thisK == 0 ? "添加行" : "删除本行发票";
	                //发票部分
	                $str .=<<<EOT
	                    <tr id="tr_$thisI">
	                        <td width="30%">
	                            <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceTypeId]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
	                        </td>
	                        <td width="25%">
	                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
	                            <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
	                        </td>
	                        <td width="25%">
	                            <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
	                        </td>
	                        <td width="20%">
	                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
	                        </td>
	                    </tr>
EOT;
				}
            }

			//设置备注栏高度
            $remarkHeight = (count($esminvoiceDetailArr) - 1)*33 + 20 ."px";

            $str .=<<<EOT
                        </table>
                    </td>
	                <td valign="top">
                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" style="height:$remarkHeight" class="txtlong">$v[remark]</textarea>
                    </td>
                </tr>
EOT;
        }

        return $str;
    }

    //编辑初始化模板
    //TODO initTemplateView_d
    function initTempView_d($worklogId){
        //返回数组
        $rtArr = array();

        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //实例化发票对象
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

        $costTypeArr = $this->findAll(array('worklogId' => $worklogId));

		//标志位
		$markArr = array();

		//合同列计算
		$countArr = array();
		//相同费用计算
		foreach($costTypeArr as $key => $val){
            //获取发票信息
            $costTypeArr[$key]['invDetail'] = $esminvoiceDetailDao->findAll(array('costDetailId' => $val['id']));

			//发票信息长度
            $costTypeArr[$key]['invLength'] = count($costTypeArr[$key]['invDetail']);

			if(isset($countArr[$val['parentCostTypeId']])){
				$countArr[$val['parentCostTypeId']] += $costTypeArr[$key]['invLength'];
			}else{
				$countArr[$val['parentCostTypeId']] = $costTypeArr[$key]['invLength'];
			}
		}

//        echo "<pre>";
//        print_r($costTypeArr);

        //模板实例化字符串
        $str = null;
        //单据总金额
        $countMoney = 0;
        $invoiceMoney = 0;
        $invoiceNumber = 0;
        foreach( $costTypeArr as $k => $v){
            if($v['costMoney'] == 0){
                continue;
            }
//	    	echo "<pre>";
//			print_r($v);
            //跨行长度
    		$mailSize = $countArr[$v['parentCostTypeId']];

            //查询本日志内的该项费用金额
            $detailMoney = bcmul($v['costMoney'],$v['days'],2);
            $countMoney = bcadd($countMoney,$detailMoney,2);

            //设置费用类型Id
            $countI = $v['costTypeId'];
            //查询本日志内的该项费用金额
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

            //带天数金额显示
            if($v['days'] > 1){
            	$costMoneyHtm = "<span class='formatMoney green' title='单价:".$v['costMoney']." X 天数:".$v['days']."'>$detailMoney</span>";
            }else{
            	$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
            }

            //发票跨行设置
    		$invSize = $v['invLength'];

    		//确认结果显示
    		$confirmStatus = $this->rtConfirmStatus_d($v['status']);

            foreach($v['invDetail'] as $thisK => $thisV){
            	//发票合计
	            $invoiceMoney = bcadd($invoiceMoney,$thisV['invoiceMoney'],2);
	            $invoiceNumber = bcadd($invoiceNumber,$thisV['invoiceNumber']);
            	if($thisK == 0){
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);

            		if(!in_array($v['parentCostTypeId'],$markArr)){
           				$trClass = count($markArr)%2 == 0? 'tr_odd' : 'tr_even';

			            $str .=<<<EOT
			            	<tr class="$trClass tr$v[id]">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[parentCostType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
			                    <td valign="top" rowspan="$invSize">$confirmStatus</td>
				            </tr>
EOT;
					array_push($markArr,$v['parentCostTypeId']);
            		}else{
			            $str .=<<<EOT
			            	<tr class="$trClass tr$v[id]">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
			                    <td valign="top" rowspan="$invSize">$confirmStatus</td>
				            </tr>
EOT;
            		}
            	}else{
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
		            $str .=<<<EOT
		            	<tr class="$trClass tr$v[id]">
			                <td>
								$billType
			                </td>
			                <td>
								<span class="formatMoney">$thisV[invoiceMoney]</span>
			                </td>
			                <td>$thisV[invoiceNumber]</td>
			            </tr>
EOT;
            	}
            }
        }
        return $str;
    }

    /**
     * 确认费用页面
     */
    function initTempConfirm_d($worklogId){
        //返回数组
        $rtArr = array();

        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //实例化发票对象
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

        $costTypeArr = $this->findAll(array('worklogId' => $worklogId));

		//标志位
		$markArr = array();

		//合同列计算
		$countArr = array();
		//相同费用计算
		foreach($costTypeArr as $key => $val){
            //获取发票信息
            $costTypeArr[$key]['invDetail'] = $esminvoiceDetailDao->findAll(array('costDetailId' => $val['id']));

			//发票信息长度
            $costTypeArr[$key]['invLength'] = count($costTypeArr[$key]['invDetail']);

			if(isset($countArr[$val['parentCostTypeId']])){
				$countArr[$val['parentCostTypeId']] += $costTypeArr[$key]['invLength'];
			}else{
				$countArr[$val['parentCostTypeId']] = $costTypeArr[$key]['invLength'];
			}
		}

//        echo "<pre>";
//        print_r($costTypeArr);

        //模板实例化字符串
        $str = null;
        //单据总金额
        $countMoney = 0;
        $invoiceMoney = 0;
        $invoiceNumber = 0;
        foreach( $costTypeArr as $k => $v){
            if($v['costMoney'] == 0){
                continue;
            }
//	    	echo "<pre>";
//			print_r($v);
            //跨行长度
    		$mailSize = $countArr[$v['parentCostTypeId']];

            //查询本日志内的该项费用金额
            $detailMoney = bcmul($v['costMoney'],$v['days'],2);
            $countMoney = bcadd($countMoney,$detailMoney,2);

            //设置费用类型Id
            $countI = $v['costTypeId'];
            //查询本日志内的该项费用金额
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

            //带天数金额显示
            if($v['days'] > 1){
            	$costMoneyHtm = "<span class='formatMoney green' title='单价:".$v['costMoney']." X 天数:".$v['days']."'>$detailMoney</span>";
            }else{
            	$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
            }

            //发票跨行设置
    		$invSize = $v['invLength'];
			//返回审核状态
    		$thisRs = $this->rtConfirmStatus_d($v['status']);

            foreach($v['invDetail'] as $thisK => $thisV){
            	//发票合计
	            $invoiceMoney = bcadd($invoiceMoney,$thisV['invoiceMoney'],2);
	            $invoiceNumber = bcadd($invoiceNumber,$thisV['invoiceNumber']);
            	if($thisK == 0){
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
            		if(!in_array($v['parentCostTypeId'],$markArr)){
           				$trClass = count($markArr)%2 == 0? 'tr_odd' : 'tr_even';

			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[parentCostType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
EOT;
						if($v['status'] == "0"){
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
	                                	<input type="hidden" name="esmcostdetail[detail][$k][id]" value="$v[id]"/>
										<select name="esmcostdetail[detail][$k][status]" class="txtshort"><option value="1">审核通过</option><option value="2">打回</option></select>
				                    </td>
					            </tr>
EOT;
						}else{
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
										$thisRs
				                    </td>
					            </tr>
EOT;
						}
					array_push($markArr,$v['parentCostTypeId']);
            		}else{
			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
EOT;
						if($v['status'] == "0"){
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
	                                	<input type="hidden" name="esmcostdetail[detail][$k][id]" value="$v[id]"/>
										<select name="esmcostdetail[detail][$k][status]" class="txtshort"><option value="1">审核通过</option><option value="2">打回</option></select>
				                    </td>
					            </tr>
EOT;
						}else{
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
										$thisRs
				                    </td>
					            </tr>
EOT;
						}
            		}
            	}else{
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
		            $str .=<<<EOT
		            	<tr class="$trClass">
			                <td>
								$billType
			                </td>
			                <td>
								<span class="formatMoney">$thisV[invoiceMoney]</span>
			                </td>
			                <td>$thisV[invoiceNumber]</td>
			            </tr>
EOT;
            	}
            }
        }
        return $str;
    }

    //编辑初始化模板
    function initTempReedit_d($worklogId){
        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);
        $initBillStr = $this->initBillType_d($billTypeArr);

        //查询模板小类型
        $sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isReplace,isEqu,isSubsidy from cost_type where isNew = '1'";
        $sysCostTypeArr = $this->_db->getArray($sql);

        //实例化发票对象
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

    	//如果是重新编辑
		$costTypeArr = $this->findAll(array('worklogId' => $worklogId),'id asc');

        //模板实例化字符串
        $str = null;
        //单据总金额
        $countMoney = 0;
        foreach( $costTypeArr as $k => $v){
            //设置费用类型Id
            $countI = $v['costTypeId'];
            //查询本日志内的该项费用金额
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $countMoney = bcadd($countMoney,$v['costMoney']);

            //获取匹配费用类型
            $thisCostType = $this->initExpenseEdit_d($sysCostTypeArr,$v['costTypeId']);

			//可修改部分
            if($v['status'] == 0 || $v['status'] == 2){
	            $str .=<<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]">
	                    <td valign="top">
	                        <img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType($countI)"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][status]" value="0"/>
	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
EOT;
				//如果需要显示天数，则显示
				if($thisCostType['showDays']){
					$str .=<<<EOT
							<span>
								<input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" value="$v[costMoney]" style="width:60px" onblur="detailSet($countI);countAll();"/>
								X
								天数
								<input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="$v[days]" readonly="readonly"/>
							</span>
EOT;
				}else{
					$str .=<<<EOT
		                    <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" value="$v[costMoney]" onblur="detailSet($countI);countAll();"/>
							<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
				}

	            $str .=<<<EOT
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;
	            //获取发票信息
	            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
	            foreach($esminvoiceDetailArr as $thisK => $thisV){
					// 是否不需要发票
					if($thisCostType['isSubsidy'] == 1){
						$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
						$str .=<<<EOT
		                    <tr id="tr_$thisI">
			                    <td width="30%">
	                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
	                                <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
		                        </td>
		                        <td width="25%">
	                                <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
		                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
		                        </td>
		                        <td width="25%">
	                                <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" readonly="readonly"/>
		                        </td>
		                        <td width="20%">
	                                <img style="cursor:pointer;" src="images/add_item.png" title="该类型不需要录入发票，不能进行新增操作" onclick="alert('该类型不需要录入发票，不能进行新增操作');"/>
		                        </td>
		                    </tr>
EOT;
					}else{
		                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
		                $thisI = $countI . "_" .$thisK;
		                //图片显示判定
		                $imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
		                //方法判定
		                $funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
		                $invTitle = $thisK == 0 ? "添加行" : "删除本行发票";
		                //发票部分
		                $str .=<<<EOT
		                    <tr id="tr_$thisI">
		                        <td width="30%">
		                            <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceTypeId]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
		                        </td>
		                        <td width="25%">
		                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
		                            <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
		                        </td>
		                        <td width="25%">
		                            <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
		                        </td>
		                        <td width="20%">
		                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
		                        </td>
		                    </tr>
EOT;
					}
	            }

				//设置备注栏高度
	            $remarkHeight = (count($esminvoiceDetailArr) - 1)*33 + 20 ."px";

	            $str .=<<<EOT
	                        </table>
	                    </td>
		                <td valign="top">
	                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" style="height:$remarkHeight" class="txtlong">$v[remark]</textarea>
	                    </td>
	                </tr>
EOT;
            }else{
	            $str .=<<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]" title="通过审核的费用">
	                    <td valign="top"></td>
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
		                    <input type="hidden" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]"/>
							<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>

	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>

	                        <input type="hidden" id="notSelect$countI" value="1"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
							$v[costMoney]
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;
	            //获取发票信息
	            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
	            foreach($esminvoiceDetailArr as $thisK => $thisV){
	                $thisI = $countI . "_" .$thisK;
	                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
					$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
					$str .=<<<EOT
	                    <tr id="tr_$thisI">
		                    <td align="left" width="29.6%">
                                $billArr[name]
	                            <select id="select_$thisI" style="width:90px;display:none;">$billTypeStr</select>
                                <input type="hidden" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]"/>
	                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
                                <input type="hidden" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]"/>
	                        </td>
	                        <td align="left" width="27%" class="formatMoney">$thisV[invoiceMoney]</td>
	                        <td align="left" width="27%">$thisV[invoiceNumber]</td>
	                        <td></td>
	                    </tr>
EOT;
	            }
	            $str .=<<<EOT
	                        </table>
	                    </td>
		                <td valign="top" align="left">$v[remark]
	                    </td>
	                </tr>
EOT;
            }
        }

        return $str;
    }

    //返回对应的发票类型
    function getBillArr_d($object,$defaultVal = null){
		if($defaultVal){
			$rtArr = array();
	        foreach($object as $key => $val){
				if($val['id'] == $defaultVal){
					$rtArr = $val;
					break;
				}
	        }
	        return $rtArr;
		}else{
			return array(
				'name' => '',
				'id' => ''
			);
		}
    }

    //初始化模板信息
    //TODO - initTemplateAdd方法
    function initTemplateAdd_d($worklogObj,$modelType){
        //引入配置信息
        include (WEB_TOR."model/common/commonConfig.php");
        //测试卡费对应的费用类型
        $CARDCOSTTYPE = isset($CARDCOSTTYPE) ? $CARDCOSTTYPE['id'] : null;
        //人员租赁对应的费用类型
        $TEMPPERSONCOSTTYPE = isset($TEMPPERSONCOSTTYPE) ? $TEMPPERSONCOSTTYPE['id'] : null;
        //租车费对应的费用类型
        $CARTRAVELFEECOSTTYPE = isset($CARTRAVELFEECOSTTYPE) ? $CARTRAVELFEECOSTTYPE['id'] : null;
        //油费对应的费用类型
        $CARFUELFEECOSTTYPE = isset($CARFUELFEECOSTTYPE) ? $CARFUELFEECOSTTYPE['id'] : null;
        //路桥对应的费用类型
        $CARROADFEECOSTTYPE = isset($CARROADFEECOSTTYPE) ? $CARROADFEECOSTTYPE['id'] : null;
        //停车费的费用类型
        $CARPARKINGFEECOSTTYPE = isset($CARPARKINGFEECOSTTYPE) ? $CARPARKINGFEECOSTTYPE['id'] : null;
        //返回数组
        $rtArr = array();
        //模板Id
        $modelId = null;
        //费用模板类型
        $COSTMODEL = isset($COSTMODEL) ? $COSTMODEL : null;
        if(empty($COSTMODEL)){
            return null;
        }
        $rtArr['templateName'] = $COSTMODEL['name'];
        $rtArr['templateId'] = $COSTMODEL['id'];
        $modelId = $COSTMODEL['id'];

        //获取模板信息
        $sql = "select id,templateName,contentId from cost_customtemplate where id = $modelId";
        $rs = $this->_db->getArray($sql);
        $modelArr = $rs[0];

        //查询模板小类型
        $sql = "select
					c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
					c.invoiceTypeName,c.isReplace,c.isEqu
				from
					cost_type c
				where c.CostTypeID in(".$modelArr['contentId'].") and c.isNew = '1' order by c.ParentCostTypeID ";
        $costTypeArr = $this->_db->getArray($sql);

        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //模板实例化字符串
        $str = null;
        foreach( $costTypeArr as $k => $v){
            $countI = $v['CostTypeID'];
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

			//费用类型渲染
       		$billTypeStr = $this->initBillType_d($billTypeArr,null,$v['invoiceType'],$v['isReplace']);

            $str .=<<<EOT
                <tr class="$trClass">
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="esmcostdetail[$countI][parentCostType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="esmcostdetail[$countI][parentCostTypeId]" value="$v[ParentCostTypeID]"/>
						<input type="hidden" name="esmcostdetail[$countI][days]" id="days$countI" value="1"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="esmcostdetail[$countI][costType]" id="costType$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="esmcostdetail[$countI][costTypeId]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" id="projectCode" name="esmcostdetail[$countI][projectCode]" value="$worklogObj[projectCode]"/>
                        <input type="hidden" id="projectId" name="esmcostdetail[$countI][projectId]" value="$worklogObj[projectId]"/>
                        <input type="hidden" id="projectName" name="esmcostdetail[$countI][projectName]" value="$worklogObj[projectName]"/>
                        <input type="hidden" id="activityName" name="esmcostdetail[$countI][activityName]" value="$worklogObj[activityName]"/>
                        <input type="hidden" id="activityId" name="esmcostdetail[$countI][activityId]" value="$worklogObj[activityId]"/>
                        <input type="hidden" id="worklogId" name="esmcostdetail[$countI][worklogId]" value="$worklogObj[id]"/>
                        <input type="hidden" name="esmcostdetail[$countI][executionDate]" value="$worklogObj[executionDate]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$v[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$v[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$v[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$v[isEqu]"/>
                        <input type="hidden" id="showDays$countI" value="$v[showDays]"/>
                    </td>
                    <td valign="top" class="form_text_right">
EOT;

            switch($v['CostTypeID']){
                case $CARDCOSTTYPE ://测试卡
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCardrecords($countI)" readonly="readonly"/>
EOT;
                    break;
                case $TEMPPERSONCOSTTYPE ://临聘人员
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initTempPerson($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARTRAVELFEECOSTTYPE ://租车费
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARFUELFEECOSTTYPE ://油费
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARROADFEECOSTTYPE ://路桥费
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARPARKINGFEECOSTTYPE ://停车费
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                default :
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();"/>
EOT;
                    break;
            }

            $thisI = $countI . "_0";
            //发票部分
            $str .=<<<EOT
                    </td>
                    <td valign="top" colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
                            <tr id="tr_$thisI">
                                <td width="30%">
                                    <select id="select_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceTypeId]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
                                </td>
                                <td width="25%">
                                    <input type="text" id="invoiceMoney_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceMoney()" class="txtshort formatMoney"/>
                                </td>
                                <td width="25%">
                                    <input type="text" id="invoiceNumber_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
                                </td>
                                <td width="20%">
                                    <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">
                    	<textarea name="esmcostdetail[$countI][remark]" id="remark$countI" class="txtlong"></textarea>
                    </td>
                </tr>
EOT;
        }
        $rtArr['str'] = $str;
        return $rtArr;
    }


    //编辑初始化模板
    //TODO initTemplateEdit
    function initTemplateEdit_d($worklogObj,$isReedit = false){
        //引入配置信息
        include (WEB_TOR."model/common/commonConfig.php");
        //测试卡费对应的费用类型
        $CARDCOSTTYPE = isset($CARDCOSTTYPE) ? $CARDCOSTTYPE['id'] : null;
        //人员租赁对应的费用类型
        $TEMPPERSONCOSTTYPE = isset($TEMPPERSONCOSTTYPE) ? $TEMPPERSONCOSTTYPE['id'] : null;
        //租车费对应的费用类型
        $CARTRAVELFEECOSTTYPE = isset($CARTRAVELFEECOSTTYPE) ? $CARTRAVELFEECOSTTYPE['id'] : null;
        //油费对应的费用类型
        $CARFUELFEECOSTTYPE = isset($CARFUELFEECOSTTYPE) ? $CARFUELFEECOSTTYPE['id'] : null;
        //路桥对应的费用类型
        $CARROADFEECOSTTYPE = isset($CARROADFEECOSTTYPE) ? $CARROADFEECOSTTYPE['id'] : null;
        //停车费的费用类型
        $CARPARKINGFEECOSTTYPE = isset($CARPARKINGFEECOSTTYPE) ? $CARPARKINGFEECOSTTYPE['id'] : null;
        //返回数组
        $rtArr = array();
        //费用模板类型
        $COSTMODEL = isset($COSTMODEL) ? $COSTMODEL : null;
        if(empty($COSTMODEL)){
            return null;
        }

        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);
        $initBillStr = $this->initBillType_d($billTypeArr);

        //查询模板小类型
        $sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName from cost_type where isNew = '1'";
        $sysCostTypeArr = $this->_db->getArray($sql);

        //实例化发票对象
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

		//如果是重新编辑
		if($isReedit == true){
			$this->searchArr = array(
				'worklogId' => $worklogObj['id'],
				'statusArr' => '2'
			);
        	$costTypeArr = $this->list_d();
		}else{
        	$costTypeArr = $this->findAll(array('worklogId' => $worklogObj['id']));
		}
//        echo "<pre>";
//        print_r($costTypeArr);

        //模板实例化字符串
        $str = null;
        //单据总金额
        $countMoney = 0;
        foreach( $costTypeArr as $k => $v){
            //设置费用类型Id
            $countI = $v['costTypeId'];
            //查询本日志内的该项费用金额
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $countMoney = bcadd($countMoney,$v['costMoney']);

            //获取匹配费用类型
            $thisCostType = $this->initExpenseEdit_d($sysCostTypeArr,$v['costTypeId']);
//       	echo "<pre>";
//			print_r($costTypeArr);

            $str .=<<<EOT
                <tr class="$trClass">
                    <td valign="top" class="form_text_right">
                        $v[parentCostType]
                        <input type="hidden" name="esmcostdetail[$countI][parentCostType]" value="$v[parentCostType]"/>
                        <input type="hidden" name="esmcostdetail[$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
						<input type="hidden" name="esmcostdetail[$countI][days]" id="days$countI" value="1"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[costType]
                        <input type="hidden" name="esmcostdetail[$countI][costType]" id="costType$countI" value="$v[costType]"/>
                        <input type="hidden" name="esmcostdetail[$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
                        <input type="hidden" id="projectCode" name="esmcostdetail[$countI][projectCode]" value="$worklogObj[projectCode]"/>
                        <input type="hidden" id="projectId" name="esmcostdetail[$countI][projectId]" value="$worklogObj[projectId]"/>
                        <input type="hidden" id="projectName" name="esmcostdetail[$countI][projectName]" value="$worklogObj[projectName]"/>
                        <input type="hidden" id="activityName" name="esmcostdetail[$countI][activityName]" value="$worklogObj[activityName]"/>
                        <input type="hidden" id="activityId" name="esmcostdetail[$countI][activityId]" value="$worklogObj[activityId]"/>
                        <input type="hidden" id="worklogId" name="esmcostdetail[$countI][worklogId]" value="$worklogObj[id]"/>
                        <input type="hidden" name="esmcostdetail[$countI][executionDate]" value="$worklogObj[executionDate]"/>
                        <input type="hidden" name="esmcostdetail[$countI][id]" value="$v[id]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
                    </td>
                    <td valign="top" class="form_text_right">
EOT;
            //渲染不同的费用输入框
            switch($v['costTypeId']){
                case $CARDCOSTTYPE ://测试卡
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCardrecords($countI)" readonly="readonly"/>
EOT;
                    break;
                case $TEMPPERSONCOSTTYPE ://临聘人员
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initTempPerson($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARTRAVELFEECOSTTYPE ://租车费
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARFUELFEECOSTTYPE ://油费
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARROADFEECOSTTYPE ://路桥费
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARPARKINGFEECOSTTYPE ://停车费
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                default :
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();"/>
EOT;
                    break;
            }

            $str .=<<<EOT
					</td>
                    <td colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
EOT;
            $esmInvoiceRow = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
            if($esmInvoiceRow){
                foreach($esmInvoiceRow as $thisK => $thisV){
                    $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId']);
                    $thisI = $countI . "_" .$thisK;
                    //图片显示判定
                    $imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
                    //方法判定
                    $funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
                    //发票部分
                    $str .=<<<EOT
                        <tr id="tr_$thisI">
                            <td width="30%">
                                <select id="select_$thisI" name="esmcostdetail[$countI][invoiceDetail][$thisK][invoiceTypeId]"><option value="">请选择发票</option>$billTypeStr</select>
                            </td>
                            <td width="25%">
                                <input type="hidden" name="esmcostdetail[$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
                                <input type="text" id="invoiceMoney_$thisI" name="esmcostdetail[$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[invoiceMoney]" onblur="countInvoiceMoney()" class="txtshort formatMoney"/>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceNumber]" value="$thisV[invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
                            </td>
                            <td width="20">
                                <img style="cursor:pointer;" src="$imgUrl" title="添加行" onclick="$funClick"/>
                            </td>
                        </tr>
EOT;
                }
            }else{
                $thisI = $countI . "_0";
                //发票部分
                $str .=<<<EOT
                        <tr id="tr_$thisI">
                            <td width="30%">
                                <select id="select_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceTypeId]"><option value="">请选择发票</option>$initBillStr</select>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceMoney()" class="txtshort formatMoney"/>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
                            </td>
                            <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
                            </td>
                        </tr>
EOT;
            }

			//设置备注栏高度
            $remarkHeight = (count($esmInvoiceRow) - 1)*33 + 20 ."px";

            $str .=<<<EOT
                        </table>
                    </td>
                    <td valign="top">
                    	<textarea name="esmcostdetail[$countI][remark]" id="remark$countI" class="txtlong" style="height:$remarkHeight">$v[remark]</textarea>
                    </td>
                </tr>
EOT;
        }
        $rtArr['str'] = $str;
        $rtArr['countMoney'] = $countMoney;

        return $rtArr;
    }

    //编辑初始化模板
    //TODO initTemplateView_d
    function initTemplateView_d($worklogObj){
        //返回数组
        $rtArr = array();

        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //实例化发票对象
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

        $costTypeArr = $this->findAll(array('worklogId' => $worklogObj['id']));

		//标志位
		$markArr = array();

		//合同列计算
		$countArr = array();
		//相同费用计算
		foreach($costTypeArr as $key => $val){
            //获取发票信息
            $costTypeArr[$key]['invDetail'] = $esminvoiceDetailDao->findAll(array('costDetailId' => $val['id']));

			//发票信息长度
            $costTypeArr[$key]['invLength'] = count($costTypeArr[$key]['invDetail']);

			if(isset($countArr[$val['parentCostTypeId']])){
				$countArr[$val['parentCostTypeId']] += $costTypeArr[$key]['invLength'];
			}else{
				$countArr[$val['parentCostTypeId']] = $costTypeArr[$key]['invLength'];
			}
		}

//        echo "<pre>";
//        print_r($costTypeArr);

        //模板实例化字符串
        $str = null;
        //单据总金额
        $countMoney = 0;
        $invoiceMoney = 0;
        $invoiceNumber = 0;
        foreach( $costTypeArr as $k => $v){
            if($v['costMoney'] == 0){
                continue;
            }
//	    	echo "<pre>";
//			print_r($v);
            //跨行长度
    		$mailSize = $countArr[$v['parentCostTypeId']];

            //查询本日志内的该项费用金额
            $detailMoney = bcmul($v['costMoney'],$v['days'],2);
            $countMoney = bcadd($countMoney,$detailMoney,2);

            //设置费用类型Id
            $countI = $v['costTypeId'];
            //查询本日志内的该项费用金额
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

            //带天数金额显示
            if($v['days'] > 1){
            	$costMoneyHtm = "<span class='formatMoney green' title='单价:".$v['costMoney']." X 天数:".$v['days']."'>$detailMoney</span>";
            }else{
            	$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
            }

            //发票跨行设置
    		$invSize = $v['invLength'];

    		//确认结果显示
    		$confirmStatus = $this->rtConfirmStatus_d($v['status']);

            foreach($v['invDetail'] as $thisK => $thisV){
            	//发票合计
	            $invoiceMoney = bcadd($invoiceMoney,$thisV['invoiceMoney'],2);
	            $invoiceNumber = bcadd($invoiceNumber,$thisV['invoiceNumber']);
            	if($thisK == 0){
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);

            		if(!in_array($v['parentCostTypeId'],$markArr)){
           				$trClass = count($markArr)%2 == 0? 'tr_odd' : 'tr_even';

			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[parentCostType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
			                    <td valign="top" rowspan="$invSize">$confirmStatus</td>
				            </tr>
EOT;
					array_push($markArr,$v['parentCostTypeId']);
            		}else{
			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
			                    <td valign="top" rowspan="$invSize">$confirmStatus</td>
				            </tr>
EOT;
            		}
            	}else{
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
		            $str .=<<<EOT
		            	<tr class="$trClass">
			                <td>
								$billType
			                </td>
			                <td>
								<span class="formatMoney">$thisV[invoiceMoney]</span>
			                </td>
			                <td>$thisV[invoiceNumber]</td>
			            </tr>
EOT;
            	}
            }
        }
        $rtArr['str'] = $str;
        $rtArr['countMoney'] = $countMoney;
        $rtArr['invoiceMoney'] = $invoiceMoney;
        $rtArr['invoiceNumber'] = $invoiceNumber;

        return $rtArr;
    }

    /**
     * 确认费用页面
     */
    function initTemplateConfirm_d($worklogObj){
        //返回数组
        $rtArr = array();

        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //实例化发票对象
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

        $costTypeArr = $this->findAll(array('worklogId' => $worklogObj['id']));

		//标志位
		$markArr = array();

		//合同列计算
		$countArr = array();
		//相同费用计算
		foreach($costTypeArr as $key => $val){
            //获取发票信息
            $costTypeArr[$key]['invDetail'] = $esminvoiceDetailDao->findAll(array('costDetailId' => $val['id']));

			//发票信息长度
            $costTypeArr[$key]['invLength'] = count($costTypeArr[$key]['invDetail']);

			if(isset($countArr[$val['parentCostTypeId']])){
				$countArr[$val['parentCostTypeId']] += $costTypeArr[$key]['invLength'];
			}else{
				$countArr[$val['parentCostTypeId']] = $costTypeArr[$key]['invLength'];
			}
		}

//        echo "<pre>";
//        print_r($costTypeArr);

        //模板实例化字符串
        $str = null;
        //单据总金额
        $countMoney = 0;
        $invoiceMoney = 0;
        $invoiceNumber = 0;
        foreach( $costTypeArr as $k => $v){
            if($v['costMoney'] == 0){
                continue;
            }
//	    	echo "<pre>";
//			print_r($v);
            //跨行长度
    		$mailSize = $countArr[$v['parentCostTypeId']];

            //查询本日志内的该项费用金额
            $detailMoney = bcmul($v['costMoney'],$v['days'],2);
            $countMoney = bcadd($countMoney,$detailMoney,2);

            //设置费用类型Id
            $countI = $v['costTypeId'];
            //查询本日志内的该项费用金额
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

            //带天数金额显示
            if($v['days'] > 1){
            	$costMoneyHtm = "<span class='formatMoney green' title='单价:".$v['costMoney']." X 天数:".$v['days']."'>$detailMoney</span>";
            }else{
            	$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
            }

            //发票跨行设置
    		$invSize = $v['invLength'];
			//返回审核状态
    		$thisRs = $this->rtConfirmStatus_d($v['status']);

            foreach($v['invDetail'] as $thisK => $thisV){
            	//发票合计
	            $invoiceMoney = bcadd($invoiceMoney,$thisV['invoiceMoney'],2);
	            $invoiceNumber = bcadd($invoiceNumber,$thisV['invoiceNumber']);
            	if($thisK == 0){
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
            		if(!in_array($v['parentCostTypeId'],$markArr)){
           				$trClass = count($markArr)%2 == 0? 'tr_odd' : 'tr_even';

			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[parentCostType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
EOT;
						if($v['status'] == "0"){
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
	                                	<input type="hidden" name="esmcostdetail[detail][$k][id]" value="$v[id]"/>
										<select name="esmcostdetail[detail][$k][status]" class="txtshort"><option value="1">审核通过</option><option value="2">打回</option></select>
				                    </td>
					            </tr>
EOT;
						}else{
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
										$thisRs
				                    </td>
					            </tr>
EOT;
						}
					array_push($markArr,$v['parentCostTypeId']);
            		}else{
			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
EOT;
						if($v['status'] == "0"){
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
	                                	<input type="hidden" name="esmcostdetail[detail][$k][id]" value="$v[id]"/>
										<select name="esmcostdetail[detail][$k][status]" class="txtshort"><option value="1">审核通过</option><option value="2">打回</option></select>
				                    </td>
					            </tr>
EOT;
						}else{
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
										$thisRs
				                    </td>
					            </tr>
EOT;
						}
            		}
            	}else{
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
		            $str .=<<<EOT
		            	<tr class="$trClass">
			                <td>
								$billType
			                </td>
			                <td>
								<span class="formatMoney">$thisV[invoiceMoney]</span>
			                </td>
			                <td>$thisV[invoiceNumber]</td>
			            </tr>
EOT;
            	}
            }
        }
        $rtArr['str'] = $str;
        $rtArr['countMoney'] = $countMoney;
        $rtArr['invoiceMoney'] = $invoiceMoney;
        $rtArr['invoiceNumber'] = $invoiceNumber;

        return $rtArr;
    }

    //将数组初始化成option选项
    function initBillType_d($object,$thisVal = null,$defaultVal = null,$isReplace = 1){
        $str = null;
        $title = $isReplace ? '此费用允许替票' : '此费用不允许替票';
        foreach($object as $key => $val){
            if($thisVal == $val['id']){
            	$str.='<option value="'.$val['id'].'" selected="selected" title="'.$title.'">'.$val['name'] .'</option>';
            }elseif($defaultVal == $val['id']){
            	if($thisVal){
           			$str.='<option value="'.$val['id'].'" title="'.$title.'">'.$val['name'] .'</option>';
            	}else{
            		$str.='<option value="'.$val['id'].'" selected="selected" title="'.$title.'">'.$val['name'] .'</option>';
            	}
            }else{
            	if($isReplace){
               		$str.='<option value="'.$val['id'].'" title="'.$title.'">'.$val['name'] .'</option>';
            	}
            }
        }
        return $str;
    }

    //查看发票值
    function initBillView_d($object,$thisVal = null){
        $str = null;
        foreach($object as $key => $val){
        	if($thisVal == $val['id']){
				return $val['name'];
        	}
        }
        return null;
    }

    //返回费用类型名称
    function initExpenseEdit_d($object,$thisVal = null){
        $str = null;
        foreach($object as $key => $val){
        	if($thisVal == $val['id']){
				return array(
					'name' => $val['name'],
					'showDays' => $val['showDays'],
					'isReplace' => $val['isReplace'],
					'isEqu' => $val['isEqu'],
					'invoiceType' => $val['invoiceType'],
					'invoiceTypeName' => $val['invoiceTypeName'],
					'isSubsidy' => $val['isSubsidy']
				);
        	}
        }
        return null;
    }

    /******************** 项目经理管理费用 *********************/
    /**
     * 项目经理管理费用
     * TODO 项目经理管理费用
     */
    function projectManage_d($projectId,$showType,$beginDate,$endDate){
    	//获取项目中的成员
		$esmmemberDao = new model_engineering_member_esmmember();
		$memberArr = $esmmemberDao->getMemberInProject_d($projectId);

		//计算日期
		$days = (strtotime($endDate) - strtotime($beginDate))/86400 + 1;

		//当前金额合计
		$countArr = array();

		//日期数组渲染
		$dateArr = array();

		//头部信息渲染
		$thisDate = $beginDate;
    	for($i = 0;$i< $days;$i++){
    		if($i != 0){
				$thisDate = date('Y-m-d',strtotime($thisDate) + 86400);
    		}
    		$wday = $this->rtWeekDay_d(date('w',strtotime($thisDate)));
    		$rtArr['tr'] .=<<<EOT
				<th>$thisDate($wday)</th>
EOT;
			array_push($dateArr,$thisDate);
    	}

    	//渲染项目成员串
    	$memberIdArr = array();
		foreach($memberArr as $val){
			array_push($memberIdArr,$val['memberId']);
		}

    	//日志信息获取
    	$esmworklogDao = new model_engineering_worklog_esmworklog();
    	$worklogArr = $esmworklogDao->getWorklogInPeriod_d(implode($memberIdArr,','),$projectId,$beginDate,$endDate);
    	$newLogArr = $esmworklogDao->logArrDeal_d($worklogArr);

    	//项目费用查询
    	$projectCostArr = $this->getCostForProject_d($projectId);

//    	echo "<pre>";
//		print_r($newLogArr);

		//当前费用合计
		$showCostMoneyCount = 0;

		//列表信息
		$str = null;
		$i = 0;
		foreach($memberArr as $key => $val){
			$i++;
			$trClass = $i%2 == 0? 'tr_odd' : 'tr_even';

			//周报是否已经提交
			$logHandup = $newLogArr[$val['memberId']]['logStatus'] == 'YTJ' || $newLogArr[$val['memberId']]['logStatus'] == 'YQR' ? '<img src="images/icon/icon088.gif"/>' : '';

			//显示记录的金额合计
			$showCostMoney = isset($newLogArr[$val['memberId']]) ? $newLogArr[$val['memberId']]['costMoney'] : 0;
			$showCostMoneyCount = bcadd($showCostMoneyCount,$showCostMoney,2);

			//全部费用
			$thisAllCostMoney = isset($projectCostArr[$val['memberId']]) ? $projectCostArr[$val['memberId']]['allCostMoney'] : 0;

			//未审核费用
			$thisUnauditMoney = isset($projectCostArr[$val['memberId']]) ? $projectCostArr[$val['memberId']]['unauditMoney'] : 0;

			$str .=<<<EOT
				<tr class="$trClass">
					<td>$i</td>
					<td>$val[memberName]</td>
					<td>$logHandup</td>
					<td class="formatMoney">$thisAllCostMoney</td>
					<td class="formatMoney">$thisUnauditMoney</td>
					<td class="formatMoney">$showCostMoney</td>
EOT;
			foreach($dateArr as $k => $v){
//				if($val['memberId'] == 'peng.lei'){
//					echo "<pre>";
//					print_r($newLogArr[$val['memberId']]['dateInfo'][$v]);
//				}
				//用户当前录入费用
				$thisCostMoney = isset($newLogArr[$val['memberId']]['dateInfo'][$v]['costMoney']) ? $newLogArr[$val['memberId']]['dateInfo'][$v]['costMoney'] : '';

				//日志id
				$worklogId = isset($newLogArr[$val['memberId']]['dateInfo'][$v]['worklogId']) ? $newLogArr[$val['memberId']]['dateInfo'][$v]['worklogId'] : '';

				//如果金额未确认
				if($thisCostMoney == 0){
					if($thisCostMoney*1 === 0.00){
						$str .=<<<EOT
							<!--td title="有日志但无费用"><span class="formatMoney">$thisCostMoney</span></td-->
							<td title="有日志但无费用"><a href="javascript:void(0)" class="formatMoney" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
					}else{
						$str .=<<<EOT
							<td></td>
EOT;
					}
				}elseif($newLogArr[$val['memberId']]['dateInfo'][$v]['confirmStatus'] == '1' && $newLogArr[$val['memberId']]['dateInfo'][$v]['unconfirmMoney'] == 0){
					if($newLogArr[$val['memberId']]['dateInfo'][$v]['backMoney'] == 0){
						$thisCostMoney = $newLogArr[$val['memberId']]['dateInfo'][$v]['confirmMoney'];
						$str .=<<<EOT
							<td title="已确认的费用"><a href="javascript:void(0)" class="formatMoney" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
					}else{
						$thisCostMoney = $newLogArr[$val['memberId']]['dateInfo'][$v]['confirmMoney'];
						$str .=<<<EOT
							<td title="已确认但含有打回费用"><a href="javascript:void(0)" class="formatMoney" style="color:red" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
					}
				}else{//已确认
					$str .=<<<EOT
						<td title="未确认的费用"><a href="javascript:void(0)" class="formatMoney" style="color:green" onclick="confirmCost($worklogId);">$thisCostMoney</a></td>
EOT;
				}

				//如果存在录入的金额时，进度合计数组开启判断
				if($thisCostMoney !== ''){
					//计算合计数
					$countArr[$v] = isset($countArr[$v]) ? bcadd($countArr[$v],$thisCostMoney,2) : $thisCostMoney;
				}else if(!isset($countArr[$v])){
					$countArr[$v] = "";
				}
			}

			$str .=<<<EOT
				</tr>
EOT;
		}

		//合计部分
		$str .=<<<EOT
			<tr class="tr_count">
				<td></td>
				<td>合计</td>
				<td></td>
				<td class="formatMoney">$projectCostArr[allCostMoney]</td>
				<td class="formatMoney">$projectCostArr[unauditMoney]</td>
				<td class="formatMoney">$showCostMoneyCount</td>
EOT;
		foreach($dateArr as $key => $val){
			if(isset($countArr[$val])){
				$str .=<<<EOT
					<td class="formatMoney">$countArr[$val]</td>
EOT;
			}
		}
		//合计部分
		$str .=<<<EOT
			</tr>
EOT;

		$rtArr['list'] = $str;
		return $rtArr;
    }

    /**
     * 费用确认功能
     */
    function confirm_d($object){
//    	echo "<pre>";
//		print_r($object);
		//获取费用数组
		$feeArr = $object['detail'];

		//发票金额处理
		$esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

		try{
			$this->start_d();

			//更新确认的状态
			foreach($feeArr as $key => $val){
				$this->update(array('id' => $val['id']),array('status' => $val['status']));

				//更新发票状态
				$esminvoiceDetailDao->updateCostInvoice_d($val['id'],$val['status']);
			}

			//更新完成后获取已确认金额
			$moneyArr = $this->getWorklogMoney_d($object['id']);

			//更新日志信息
			$worklogDao = new model_engineering_worklog_esmworklog();
			$worklogDao->update(
				array('id' => $object['id']),
				array('confirmDate' => day_date,'confirmMoney' => $moneyArr['confirmMoney'],'backMoney' => $moneyArr['backMoney'],
					'confirmName' => $_SESSION['USERNAME'],'confirmId' => $_SESSION['USER_ID'],
					'confirmStatus' => 1
				)
			);

			//计算人员的项目费用
            if($object['projectId']){
				//获取当前项目的费用
				$projectCountArr = $this->getCostFormMember_d($object['projectId'],$object['memberId']);

				//更新人员费用信息
				$esmmemberDao = new model_engineering_member_esmmember();
				$esmmemberDao->update(
					array('projectId' => $object['projectId'] ,'memberId' => $object['memberId']),
					$projectCountArr
				);
            }

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
    }

    /**
     * 费用确认功能
     */
    function auditFee_d($worklogId,$isBack = 0){
		$rs = $this->findAll(array('worklogId' => $worklogId),null,'id');

		//不包含费用时直接返回true
		if(!$rs){
			return true;
		}

		try{
			$this->start_d();

			//发票金额处理
			$esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

			//判断更新状态
			if($isBack == 0){
				$status = '1';
			}else{
				$status = '2';
			}
			//自己的费用更新
			$this->update(array('worklogId' => $worklogId),array('status' => $status));

			//更新确认的状态
			foreach($rs as $key => $val){
				//更新发票状态
				$esminvoiceDetailDao->updateCostInvoice_d($val['id'],$status);
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
    }

    /**
     * 费用确认功能 - 撤销
     */
    function unauditFee_d($worklogId){
		$rs = $this->findAll(array('worklogId' => $worklogId),null,'id');

		//不包含费用时直接返回true
		if(!$rs){
			return true;
		}

		try{
			$this->start_d();

			//发票金额处理
			$esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();
			//自己的费用更新
			$this->update(array('worklogId' => $worklogId),array('status' => 0));

			//更新确认的状态
			foreach($rs as $key => $val){
				//更新发票状态
				$esminvoiceDetailDao->updateCostInvoice_d($val['id'],0);
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
    }

    /**
     * 获取日志审核通过的费用
     */
    function getWorklogMoney_d($worklogId){
		$this->searchArr = array(
			'worklogId' => $worklogId
		);
		$this->groupBy = 'c.worklogId';
		$rs = $this->list_d('count_membercount');
		if($rs){
			return $rs[0];
		}else{
			return 0;
		}
    }

    /**
     * 获取项目中的费用 - 根据人来分组
     */
    function getCostForProject_d($projectId){
		$this->searchArr = array(
			'projectId' => $projectId
		);
		$this->groupBy = 'c.createId';
		$rs = $this->list_d('count_project');
		if($rs){
			$allCostCount = 0;//全部费用合计
			$unauditCount = 0;//未审核费用合计

			foreach($rs as $key => $val){
				$projectCostArr[$val['createId']]['allCostMoney'] = $val['allCostMoney'];
				$projectCostArr[$val['createId']]['unauditMoney'] = $val['unauditMoney'];

				$allCostCount = bcadd($allCostCount,$val['allCostMoney'],2);
				$unauditCount = bcadd($unauditCount,$val['unauditMoney'],2);
			}
			$projectCostArr['allCostMoney'] = $allCostCount;
			$projectCostArr['unauditMoney'] = $unauditCount;

			return $projectCostArr;
		}else{
			return false;
		}
    }

    /**
     * 获取某人在某项目中的费用
     */
    function getCostFormMember_d($projectId,$memberId = null){
    	//如果没传入人，则自动获取$_SESSION
    	if(empty($memberId)){
			$memberId = $_SESSION['USER_ID'];
    	}
		$this->searchArr = array(
			'projectId' => $projectId,
			'createId' => $memberId
		);
		$this->groupBy = 'c.createId';
		$rs = $this->list_d('count_membercount');
		if($rs){
			return $rs[0];
		}else{
			return false;
		}
    }

    /**
     * 获取某人某项目某日期的可报销费用
     */
    function getCostForExpense_d($projectId,$days,$memberId){
		$this->searchArr = array(
			'projectId' => $projectId,
			'executionDates' => $days,
			'createId' => $memberId,
			'status' => 1
		);
		$this->groupBy = 'c.costTypeId';
		$this->sort = 'c.parentCostTypeId desc,c.costTypeId';
		$this->asc = false;
		$rs = $this->list_d('count_costType');
		if($rs){
			return $rs;
		}else{
			return false;
		}
    }

    /**
     * 项目经理管理费用
     * TODO 项目经理管理费用
     */
    function projectView_d($projectId,$showType,$beginDate,$endDate){
    	//获取项目中的成员
		$esmmemberDao = new model_engineering_member_esmmember();
		$memberArr = $esmmemberDao->getMemberInProject_d($projectId);

		//计算日期
		$days = (strtotime($endDate) - strtotime($beginDate))/86400 + 1;

		//当前金额合计
		$countArr = array();

		//日期数组渲染
		$dateArr = array();

		//头部信息渲染
		$thisDate = $beginDate;
    	for($i = 0;$i< $days;$i++){
    		if($i != 0){
				$thisDate = date('Y-m-d',strtotime($thisDate) + 86400);
    		}
    		$wday = $this->rtWeekDay_d(date('w',strtotime($thisDate)));
    		$rtArr['tr'] .=<<<EOT
				<th>$thisDate($wday)</th>
EOT;
			array_push($dateArr,$thisDate);
    	}

    	//渲染项目成员串
    	$memberIdArr = array();
		foreach($memberArr as $val){
			array_push($memberIdArr,$val['memberId']);
		}

    	//日志信息获取
    	$esmworklogDao = new model_engineering_worklog_esmworklog();
    	$worklogArr = $esmworklogDao->getWorklogInPeriod_d(implode($memberIdArr,','),$projectId,$beginDate,$endDate);
    	$newLogArr = $esmworklogDao->logArrDeal_d($worklogArr);

    	//项目费用查询
    	$projectCostArr = $this->getCostForProject_d($projectId);

//    	echo "<pre>";
//		print_r($newLogArr);

		//当前费用合计
		$showCostMoneyCount = 0;

		//列表信息
		$str = null;
		$i = 0;
		foreach($memberArr as $key => $val){
			$i++;
			$trClass = $i%2 == 0? 'tr_odd' : 'tr_even';

			//周报是否已经提交
			$logHandup = $newLogArr[$val['memberId']]['logStatus'] == 'YTJ' || $newLogArr[$val['memberId']]['logStatus'] == 'YQR' ? '<img src="images/icon/icon088.gif"/>' : '';

			//显示记录的金额合计
			$showCostMoney = isset($newLogArr[$val['memberId']]) ? $newLogArr[$val['memberId']]['costMoney'] : 0;
			$showCostMoneyCount = bcadd($showCostMoneyCount,$showCostMoney,2);

			//全部费用
			$thisAllCostMoney = isset($projectCostArr[$val['memberId']]) ? $projectCostArr[$val['memberId']]['allCostMoney'] : 0;

			//未审核费用
			$thisUnauditMoney = isset($projectCostArr[$val['memberId']]) ? $projectCostArr[$val['memberId']]['unauditMoney'] : 0;

			$str .=<<<EOT
				<tr class="$trClass">
					<td>$i</td>
					<td>$val[memberName]</td>
					<td>$logHandup</td>
					<td class="formatMoney">$thisAllCostMoney</td>
					<td class="formatMoney">$thisUnauditMoney</td>
					<td class="formatMoney">$showCostMoney</td>
EOT;
			foreach($dateArr as $k => $v){
				//用户当前录入费用
				$thisCostMoney = isset($newLogArr[$val['memberId']]['dateInfo'][$v]['costMoney']) ? $newLogArr[$val['memberId']]['dateInfo'][$v]['costMoney'] : '';

				//日志id
				$worklogId = isset($newLogArr[$val['memberId']]['dateInfo'][$v]['worklogId']) ? $newLogArr[$val['memberId']]['dateInfo'][$v]['worklogId'] : '';

				//如果金额未确认
				if($thisCostMoney == 0){
					if($thisCostMoney*1 === 0.00){
						$str .=<<<EOT
							<td title="有日志但无费用"><span class="formatMoney">$thisCostMoney</span></td>
EOT;
					}else{
						$str .=<<<EOT
							<td></td>
EOT;
					}
				}elseif($newLogArr[$val['memberId']]['dateInfo'][$v]['confirmStatus'] == '1'){
					$thisCostMoney = $newLogArr[$val['memberId']]['dateInfo'][$v]['confirmMoney'];
					$str .=<<<EOT
						<td title="已确认的费用"><a href="javascript:void(0)" class="formatMoney" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
				}else{//已确认
					$str .=<<<EOT
						<td title="未确认的费用"><a href="javascript:void(0)" class="formatMoney" style="color:green" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
				}

				//如果存在录入的金额时，进度合计数组开启判断
				if($thisCostMoney !== ''){
					//计算合计数
					$countArr[$v] = isset($countArr[$v]) ? bcadd($countArr[$v],$thisCostMoney,2) : $thisCostMoney;
				}else if(!isset($countArr[$v])){
					$countArr[$v] = "";
				}
			}

			$str .=<<<EOT
				</tr>
EOT;
		}

		//合计部分
		$str .=<<<EOT
			<tr class="tr_count">
				<td></td>
				<td>合计</td>
				<td></td>
				<td class="formatMoney">$projectCostArr[allCostMoney]</td>
				<td class="formatMoney">$projectCostArr[unauditMoney]</td>
				<td class="formatMoney">$showCostMoneyCount</td>
EOT;
		foreach($dateArr as $key => $val){
			if(isset($countArr[$val])){
				$str .=<<<EOT
					<td class="formatMoney">$countArr[$val]</td>
EOT;
			}
		}
		//合计部分
		$str .=<<<EOT
			</tr>
EOT;

		$rtArr['list'] = $str;
		return $rtArr;
    }

    /*********************** 票据整理部分 ************************/
    /**
     * 获取报销单信息
     */
    function getExpenseInfo_d($id){
		$expenseDao = new model_finance_expense_expense();
		$expenseObj = $expenseDao->find(array('id' => $id),null);
		return $expenseObj;
    }

    /**
     *  获取对应的费用明细
     */
    function getCostdetail_d($ids){
    	$this->searchArr = array('ids' => $ids);
    	$this->sort = "c.worklogId";
		$rs = $this->list_d();
		return $rs;
    }

    /**
     * 根据id获取报销费用
     */
    function getCostByIds_d($ids){
    	$this->searchArr = array('ids' => $ids);
    	$this->groupBy = 'costTypeId';
		$rs = $this->list_d('select_fee');
		return $rs;
    }

    /**
     * 渲染报销明细管理列表
     */
    function initCostdetail_d($costdetail){
		$rs = array('tr' => '','list' => '');

		//扩展表头 -- 主要是费用类型
		$titleArr = array();
		//内容数组
		$contentArr = array();

		foreach($costdetail as $key => $val){
			//扩展标题构建
			if(!isset($titleArr[$val['costTypeId']])){
				$titleArr[$val['costTypeId']] = $val['costType'];
			}

			//重构内容
			$contentArr[$val['worklogId']][$val['costTypeId']] = $val;
		}
//		echo "<pre>";
//		print_r($contentArr);

		$str = "";
		$countMoneyArr = array();
		$i = 0;
		foreach($contentArr as $key => $val){
			$head = "";
			$body = "";
			$countMoney = 0;
			$costdetailIdArr = array();
			$i++;
			foreach($titleArr as $tk => $tv){
				if(isset($val[$tk])){
					//将费用id缓存到数组中
					array_push($costdetailIdArr,$val[$tk]['id']);

					$costMoney = $val[$tk]['costMoney'];
					$countMoney = bcadd($countMoney,$costMoney,2);//行合计金额
					//统计列部分
					$countMoneyArr[$tk] = isset($countMoneyArr[$tk]) ? bcadd($costMoney ,$countMoneyArr[$tk],2) : $costMoney;

					$body .=<<<EOT
						<td class="formatMoney" style="text-align:right;padding:0 5px 0 0;">$costMoney</td>
EOT;
					if(empty($head)){
						$worklogDate = $costMoney = $val[$tk]['executionDate'];
						$head .=<<<EOT
							<td><a href="javascript:void(0)" onclick="showWorklog($key)" title="点击查看日志">$worklogDate</a></td>
							<td></td>
EOT;
					}
				}else{
					$body .=<<<EOT
						<td style="text-align:right;padding:0 5px 0 0;">0.00</td>
EOT;
				}
			}
			//全部合计
			$countMoneyArr['allMoney'] = isset($countMoneyArr['allMoney']) ?  bcadd($countMoney ,$countMoneyArr['allMoney'],2) : $countMoney;

			$costdetailId = implode(',',$costdetailIdArr);
			$bottom =<<<EOT
				<td class="formatMoney" style="text-align:right;padding:0 5px 0 0;">$countMoney</td>
				<td>
					<a href="javascript:void(0)" onclick="editWorklog($key)">修改明细</a>
					<input type="hidden" id="costdetailId$key" value="$costdetailId"/>
				</td>
EOT;
			$trClass = $i%2 == 1? 'tr_even' : 'tr_odd';
			$str .= "<tr class='$trClass'><td>$i</td>".$head . $body. $bottom."</tr>";

		}
		$rs['tr'] = $this->initCostTitle_d($titleArr);

		//生成合计行
		$countStr = $this->initCostCount_d($titleArr,$countMoneyArr);

		$rs['list'] = $str.$countStr;

		return $rs;
    }

    /**
     * 实例化标题
     */
    function initCostTitle_d($titleArr){
    	$str = "";
		foreach($titleArr as $key => $val){
			$str .=<<<EOT
				<td width="80px">$val</td>
EOT;
		}
		$str .="<td width='80px'>合计</td><td width='80px'>操作</td>";
		return $str;
    }

    /**
     * 实例化合计行
     */
    function initCostCount_d($titleArr,$countMoneyArr){
		$countStr = "<tr class='tr_count'><td></td><td>合计</td><td></td>";
		foreach($titleArr as $ctk => $ctv){
			$countStr .=<<<EOT
				<td class="formatMoney" style="text-align:right;padding:0 5px 0 0;">$countMoneyArr[$ctk]</td>
EOT;
		}
		$countStr.= "<td class='formatMoney' style='text-align:right;padding:0 5px 0 0;'>$countMoneyArr[allMoney]</td><td></td></tr>";
		return $countStr;
    }

    //编辑初始化模板
    function initCheckEdit_d($worklogId,$costdetailId){
    	$costdetailIdArr = explode(',',$costdetailId);

        //获取发票类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);
        $initBillStr = $this->initBillType_d($billTypeArr);

        //查询模板小类型
        $sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isReplace,isEqu,isSubsidy from cost_type where isNew = '1'";
        $sysCostTypeArr = $this->_db->getArray($sql);

        //实例化发票对象
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

    	//如果是重新编辑
		$costTypeArr = $this->findAll(array('worklogId' => $worklogId),'id asc');

        //模板实例化字符串
        $str = null;
        //单据总金额
        $countMoney = 0;
        foreach( $costTypeArr as $k => $v){
            //设置费用类型Id
            $countI = $v['costTypeId'];
            //查询本日志内的该项费用金额
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $countMoney = bcadd($countMoney,$v['costMoney']);

            //获取匹配费用类型
            $thisCostType = $this->initExpenseEdit_d($sysCostTypeArr,$v['costTypeId']);

			//可修改部分
            if(in_array($v['id'],$costdetailIdArr)){
	            $str .=<<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]">
	                    <td valign="top">
	                        <img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType($countI)"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
EOT;
				//如果需要显示天数，则显示
				if($thisCostType['showDays']){
					$str .=<<<EOT
							<span>
								<input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" value="$v[costMoney]" style="width:60px" onblur="detailSet($countI);countAll();"/>
								X
								天数
								<input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="$v[days]" readonly="readonly"/>
							</span>
EOT;
				}else{
					$str .=<<<EOT
		                    <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" value="$v[costMoney]" onblur="detailSet($countI);countAll();"/>
							<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
				}

	            $str .=<<<EOT
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;
	            //获取发票信息
	            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
	            foreach($esminvoiceDetailArr as $thisK => $thisV){
					// 是否不需要发票
					if($thisCostType['isSubsidy'] == 1){
						$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
						$str .=<<<EOT
		                    <tr id="tr_$thisI">
			                    <td width="30%">
	                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
	                                <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
		                        </td>
		                        <td width="25%">
	                                <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
		                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
		                        </td>
		                        <td width="25%">
	                                <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" readonly="readonly"/>
		                        </td>
		                        <td width="20%">
	                                <img style="cursor:pointer;" src="images/add_item.png" title="该类型不需要录入发票，不能进行新增操作" onclick="alert('该类型不需要录入发票，不能进行新增操作');"/>
		                        </td>
		                    </tr>
EOT;
					}else{
		                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
		                $thisI = $countI . "_" .$thisK;
		                //图片显示判定
		                $imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
		                //方法判定
		                $funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
		                $invTitle = $thisK == 0 ? "添加行" : "删除本行发票";
		                //发票部分
		                $str .=<<<EOT
		                    <tr id="tr_$thisI">
		                        <td width="30%">
		                            <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceTypeId]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
		                        </td>
		                        <td width="25%">
		                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
		                            <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
		                        </td>
		                        <td width="25%">
		                            <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
		                        </td>
		                        <td width="20%">
		                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
		                        </td>
		                    </tr>
EOT;
					}
	            }

				//设置备注栏高度
	            $remarkHeight = (count($esminvoiceDetailArr) - 1)*33 + 20 ."px";

	            $str .=<<<EOT
	                        </table>
	                    </td>
		                <td valign="top">
	                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" style="height:$remarkHeight" class="txtlong">$v[remark]</textarea>
	                    </td>
	                </tr>
EOT;
            }else{
	            $str .=<<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]" title="通过审核的费用">
	                    <td valign="top"></td>
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
		                    <input type="hidden" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]"/>
							<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>

	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>

	                        <input type="hidden" id="notSelect$countI" value="1"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
							$v[costMoney]
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;
	            //获取发票信息
	            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
	            foreach($esminvoiceDetailArr as $thisK => $thisV){
	                $thisI = $countI . "_" .$thisK;
	                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
					$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
					$str .=<<<EOT
	                    <tr id="tr_$thisI">
		                    <td align="left" width="29.6%">
                                $billArr[name]
	                            <select id="select_$thisI" style="width:90px;display:none;">$billTypeStr</select>
                                <input type="hidden" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]"/>
	                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
                                <input type="hidden" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]"/>
	                        </td>
	                        <td align="left" width="27%" class="formatMoney">$thisV[invoiceMoney]</td>
	                        <td align="left" width="27%">$thisV[invoiceNumber]</td>
	                        <td></td>
	                    </tr>
EOT;
	            }
	            $str .=<<<EOT
	                        </table>
	                    </td>
		                <td valign="top" align="left">$v[remark]
	                    </td>
	                </tr>
EOT;
            }
        }

        return $str;
    }
    /**
     * 详细费用报销
     */
    function listHtml_d($projectId,$status){
    	$sql = "SELECT
    				executionDate,
					parentCostType,
					costType,
					SUM(costMoney) AS costMoney
				FROM ".$this->tbl_name."
				WHERE
					projectId = '".$projectId."'
				AND createId = '".$_SESSION['USER_ID']."'
				AND status='".$status."'
				GROUP BY executionDate,costType";

    	$this->asc = false;
    	$this->sort = "executionDate";
    	$trStr="";
    	$arr = $this->listBySql($sql);
    	//重构数组
    	$new = array();
    	foreach ($arr as $key => $val) {
    		$new[$val['executionDate']]['executionDate'] = $val['executionDate'];
    		$new[$val['executionDate']][$val['parentCostType']][$val['costType']] = $val['costMoney'];
    		$new[$val['executionDate']]['countMoney'] = bcadd($new[$val['executionDate']]['countMoney'], $val['costMoney'],2);
    	}
    	$new = array_values($new);
    	//获取所有费用类型构造表头
    	$headArr = array();
    	foreach ($arr as $key => $val){
    		if(!in_array($val[costType],$headArr[$val['parentCostType']])){
    			$headArr[$val['parentCostType']][] = $val[costType];
    		}
    	}
    	//未报销状态，才显示checkbox
    	if($status == '1'){
    		$subCheck.=<<<EOT
				<td style="text-align: center;"><input type="checkbox" class="subCheck"/></td>
EOT;
    		$mainCheck =<<<EOT
				<th rowspan="2" width="30"><input type="checkbox" id="mainCheck" onclick="selectAllCheck();"/></th>
EOT;
    	}
    	foreach ($new as $key => $val){
    	    $td = '';
    		foreach ($headArr as $thisKey => $thisVal){
				foreach ($thisVal as $thisK =>$thisV){
					if (isset($val[$thisKey])){
						$td.=<<<EOT
    						<td style="text-align: center;" class="formatMoney">{$val[$thisKey][$thisV]}</td>
EOT;
					}else {
						$td.=<<<EOT
    						<td style="text-align: center;"></td>
EOT;
					}
				}
    		}
			$trStr.=<<<EOT
				<tr class="tr_even">
					$subCheck
					<td style="text-align: center;" class="executionDate">{$val['executionDate']}</td>
					<td style="text-align: center;" class="formatMoney">{$val['countMoney']}</td>
					$td
				</tr>
EOT;
		}
		if(empty($trStr)){
			$trStr = <<<EOT
				<tr class="tr_even" rownum="0"><td style="text-align: center;" colspan="3">------没有相关记录------</td></tr>
EOT;
			$mainCheck ='';
		}else{
			//查询到相关记录，且为未报销状态，才显示生成报销单按钮
			if($status == '1'){
				$button = <<<EOT
					<input type="button" class="txt_btn_a" value="生成报销单" onclick="toEsmExpenseAdd();"/>
EOT;
			}
		}		
		$thead1 =<<<EOT
				$mainCheck
				<th rowspan="2" style="width: 100px;"><div style="min-width: 70px;" class="divChangeLine">执行日期</div></th>
				<th rowspan="2" style="width: 100px;"><div style="min-width: 80px;" class="divChangeLine">小计</div></th>
EOT;
		$thead2 =<<<EOT
		
EOT;
		foreach($headArr as $k => $v){
			$thLength = count($v);
			$thead1 .=<<<EOT
				<th colspan="$thLength">$k</td>			
EOT;
			foreach($v as $ik){
				$thead2 .=<<<EOT
				<th>$ik</td>
EOT;
			}
		}
		$str = <<<EOT
			<thead>
				<tr class="main_tr_header">
					<td colspan="10" style="text-align: left;">
						<b>详细费用报销</b>
						&nbsp;&nbsp;<b>报销状态</b>
						<select id="status" onchange="changeStatus();">
						<option value="1">未报销</option>
						<option value="3">在报销</option>
						<option value="4">已报销</option>
						</select>
						$button
					</td>
				</tr>
				<tr class="main_tr_header">
					$thead1
				</tr>
				<tr class="main_tr_header">
					$thead2
				</tr>
			</thead>
			<tbody>
				$trStr
			</tbody>
EOT;
		return $str;
    }
}