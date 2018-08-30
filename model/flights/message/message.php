<?php
/**
 * @author sony
 * @Date 2013年7月10日 17:29:50
 * @version 1.0
 * @description:订票信息 Model层
 */
class model_flights_message_message extends model_base {

	function __construct() {
		$this->tbl_name = "oa_flights_message";
		$this->sql_map = "flights/message/messageSql.php";
		parent::__construct ();
	}

	//返回状态
	function rtStatus_d($thisVal){
		switch($thisVal){
			case 1 : return '改签';break;
			case 2 : return '退票';break;
			case 10 : return '单程';break;
			case 11 : return '往返';break;
			case 12 : return '联程';break;
			default : return '正常';
		}
	}

	//返回yn
	function rtYesOrNo_d($thisVal){
		if($thisVal == '1'){
			return '是';
		}else{
			return '否';
		}
	}
	/*--------------------------------------------业务操作--------------------------------------------*/

	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try{
			$this->start_d();

			//判断requireId是否有值，进行生成订票信息
			if ($object['requireId'] != "") {
				$requireDao = new model_flights_require_require ();
				$requireDao->updateMsgState_d($object['requireId']);
			}
			//剔除主表无关信息
			$items = $object ['items'];
			unset ( $object ['items'] );
            $idArr = array(); // 缓存id
			foreach ( $items as $val ) {
				$mergerArr = array_merge ( $object, $val );
				$mergerArr['costPay'] = $mergerArr['actualCost'];
				$newId = parent::add_d ( $mergerArr, true );

				$newObj = array('id' => $newId,'businessNo' => $newId);
				parent::edit_d($newObj);

                array_push($idArr,$newId); // 获取新插入的数据Id
			}

            //特别处理 -- 更新项目费用到项目上
            $ids = implode(',',$idArr);
            $projectFeeArr = $this->getProjectFeeArr_d($ids);
            //如果确实有费用，那么去更新
            if($projectFeeArr){
                $esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
                foreach($projectFeeArr as $v){
                    $esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
                }
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
            }

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
        try{
            $this->start_d();
            // 更新
            parent::edit_d ( $object, true );

            //特别处理 -- 更新项目费用到项目上
            $projectFeeArr = $this->getProjectFeeArr_d($object['id']);
			//如果确实有费用，那么去更新
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

            $this->commit_d();
            return true;
        }catch (Exception $e){
            $this->rollBack();
            return false;
        }
	}

	/**
	 * 删除方法
	 */
	function deletes_d($id){
		try {
			$this->start_d();

			//更新订票需求
			$obj = $this->get_d($id);

			//删除
			$this->deletes ( $id );

			//查询是否还存在对应的订票信息
			$requireMsgArr = $this->find(array('requireId' => $obj['requireId']));
			if(!$requireMsgArr){
				//将订票需求状态更新回未生成
				$requireDao = new model_flights_require_require ();
				$requireDao->updateMsgState_d($obj['requireId'],0);
			}

            //特别处理 -- 更新项目费用到项目上
            $projectFeeArr = $this->getProjectFeeArr_d($id);
			//如果确实有费用，那么去更新
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack();
            return false;
		}
	}

	/**
	 * 改签机票
	 */
	function change_d($object){
		try{
			$this->start_d();

			//获取源生数组
			$obj = $this->get_d($object['orgId']);
			unset($obj['id']); //去除id
			unset($obj['auditState']); //去除核单状态
			$newId = parent::add_d($obj,true); //新增

			//将改签内容载入到新数据中
			$object['id'] = $newId;
			$object['businessState'] = '3';
			$object['msgType'] = '1';
			$object['costPay'] = bcadd($object['costDiff'],$object['feeChange'],2);
			parent::edit_d($object,true);

			//更新核单状态
			if($obj['auditState'] != "2"){
				//如果未结算，则将订票信息更新为不需核算
				$newObj = array('id' => $object['orgId'],'businessState' => '1');
				parent::edit_d($newObj,true);
			}

            //特别处理 -- 更新项目费用到项目上
            $projectFeeArr = $this->getProjectFeeArr_d($newId);
			//如果确实有费用，那么去更新
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 修改改签
	 */
	function changeEdit_d($object){
		try{
			$this->start_d();

			$object['costPay'] = bcadd($object['costDiff'],$object['feeChange'],2);
			parent::edit_d($object,true);

            //特别处理 -- 更新项目费用到项目上
            $projectFeeArr = $this->getProjectFeeArr_d($object['id']);
			//如果确实有费用，那么去更新
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	//更改业务
	function back_d($object) {
		try{
			$this->start_d();

			//获取源生数组
			$obj = $this->get_d($object['orgId']);
			unset($obj['id']); //去除id
			unset($obj['feeChange']); //去除改签手续费
			unset($obj['costDiff']); //去除票价差额
			unset($obj['auditState']); //去除核单状态
			$obj['beforeCost'] = $obj['actualCost']; //原票价设置为当前票价
			$newId = parent::add_d($obj,true); //新增

			//将退票内容载入到新数据中
			$object['id'] = $newId;
			$object['businessState'] = '4';
			$object['msgType'] = '2';
			$object['costPay'] = -$object['costBack'];
			parent::edit_d($object,true);

			//更新核单状态
			if($obj['auditState'] != "2"){
				//如果未结算，则将订票信息更新为不需核算
				$newObj = array('id' => $object['orgId'],'businessState' => '2');
				parent::edit_d($newObj,true);
			}

            //特别处理 -- 更新项目费用到项目上
            $projectFeeArr = $this->getProjectFeeArr_d($newId);
			//如果确实有费用，那么去更新
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	//更改业务
	function editBack_d($object) {
		try{
			$this->start_d();

			//将退票内容载入到新数据中
			$object['businessState'] = '4';
			$object['msgType'] = '2';
			$object['costPay'] = -$object['costBack'];
			parent::edit_d($object,true);

            //特别处理 -- 更新项目费用到项目上
            $projectFeeArr = $this->getProjectFeeArr_d($object['id']);
			//如果确实有费用，那么去更新
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

    //审核
    function confirm_d($ids){
        try{
            $this->start_d();

            //审核主表
            $this->_db->query ( "update ".$this->tbl_name . " set auditState = 1 where id in ($ids)" );

            //特别处理 -- 更新项目费用到项目上
            $projectFeeArr = $this->getProjectFeeArr_d($ids);
			//如果确实有费用，那么去更新
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }
    }

    //反核单
    function unconfirm_d($ids){
        try{
            $this->start_d();

            //反主表
            $this->_db->query ( "update ".$this->tbl_name . " set auditState = 0 where id in ($ids)" );

            //特别处理 -- 更新项目费用到项目上
            $projectFeeArr = $this->getProjectFeeArr_d($ids);
			//如果确实有费用，那么去更新
			if($projectFeeArr){
				$esmprojectDao = new model_engineering_project_esmproject();
				$projectIdArr = array();
				foreach($projectFeeArr as $v){
					$esmprojectDao->updateFeeFlights_d($v['projectId'],$v['money']);
					$projectIdArr[] = $v['projectId'];
				}
				$esmprojectDao->calProjectFee_d(null, implode(',', $projectIdArr));
			}

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }
    }

    /**
     * 项目费用查询
     * @param $ids 订票记录的id
     */
    function getProjectFeeArr_d($ids){
        //查询关联的项目id
        $arr = $this->_db->getArray("SELECT projectId FROM oa_flights_message WHERE id IN ($ids) AND projectType = 'esm' GROUP BY projectId");
        if($arr){
            $rtProjectFeeArr = array();//实际需要返回的数据
            $projectIdArr = array();//缓存项目id
            foreach($arr as $v){
                array_push($projectIdArr,$v['projectId']);//查询费用的项目id
                $rtProjectFeeArr[$v['projectId']] = array('projectId' => $v['projectId'], 'money' => 0);//构建返回数据
            }
            $projectIds = implode(',',$projectIdArr);//项目id串
            //项目费用
            $projectFeeArr = $this->_db->getArray("SELECT projectId,SUM(costPay) as costPay FROM oa_flights_message WHERE projectId IN ($projectIds) AND projectType = 'esm' GROUP BY projectId");
            //如果项目有费用,那么就加载
            if($projectFeeArr){
                foreach($projectFeeArr as $v){
                    $rtProjectFeeArr[$v['projectId']]['money'] = $v['costPay'];
                }
            }
            return $rtProjectFeeArr;
        }
        return false;
    }

	/**
	 * 过滤查询
	 */
	function filterMes_d($ids){
		//循环数组
		$this->searchArr = array(
            'ids' => $ids
		);
		//查找条数据
		return $this->list_d();
	}
	/**
	 * 更新订单状态
	 */
	function updateAuditState_d($id,$auditState){
		$object = array(
			'id' => $id,
			'auditState' => $auditState
		);
		return parent::edit_d($object);
	}

	//更改需求页面订票状态
	function deleteRquery($requireId){
		$requireDao=new model_flights_require_require();
        return $requireDao->updateMsgState_d($requireId,0);
	}

	/**
	 * 获取订单信息
	 * @param unknown $param
	 * @param unknown $sort
	 */
	function getlistHtml_d($param){
		$trStr="";
		$i=0;
		$j=1;
		$this->getParam($param);
		$arr = $this->list_d();
		foreach ($arr as $val){//判断订票信息类型
			if($val['msgType'] == "0"){
				$msgType = '<span class="green">正常</span>';
			}else if($val['msgType'] == '1'){
				$msgType = '<span class="blue">改签</span>';
			}else{
				$msgType = '<span class="red">退票</span>';
			}
			$trStr.=<<<EOT
			<tr class="tr_even" rownum="$i">
				<td><span class="removeBn"><img src="images/removeline.png" onclick="removeRow($i);"></span>
				<input	type="hidden" name="balance[items][$i][rowNum_]" value="$i"></td>
				<td type="rowNum">$j</td>
				<td style="text-align: center;"><div class="divChangeLine"
						id="itemTable_cmp_msgType$i" name="balance[items][$i][msgType]"
						style="width: 70px;">		
						$msgType
					</div></td>
				<td style="text-align: center;">
				<input type="text" id="itemTable_cmp_auditDate$i" class="readOnlyTxtMiddle" name="balance[items][$i][auditDate]" readonly="" style="width: 80px;" value="{$val['auditDate']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_airName$i" class="readOnlyTxtMiddle" name="balance[items][$i][airName]" readonly="" style="width: 90px;"  value="{$val['airName']}"></td>
				<td style="display: none; text-align: center;"><input type="hidden"  id="itemTable_cmp_airId$i" class="txtmiddle" name="balance[items][$i][airId]" value="{$val['airId']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_airline$i" class="readOnlyTxtNormal" name="balance[items][$i][airline]" readonly="" style="width: 140px;" value="{$val['airline']}"></td>
				<td style="display: none; text-align: center;"><input type="hidden"  id="itemTable_cmp_airlineId$i" class="txtmiddle" name="balance[items][$i][airlineId]" value="{$val['airlineId']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_flightNumber$i" class="readOnlyTxtMiddle" name="balance[items][$i][flightNumber]" readonly="" value="{$val['flightNumber']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_flightTime$i" class="readOnlyTxtMiddle" name="balance[items][$i][flightTime]" readonly="" style="width: 130px;" value="{$val['flightTime']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_arrivalTime$i" class="readOnlyTxtMiddle" name="balance[items][$i][arrivalTime]" readonly="" style="width: 130px;" value="{$val['arrivalTime']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_departPlace$i" class="readOnlyTxtShort" name="balance[items][$i][departPlace]" readonly="" value="{$val['departPlace']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_arrivalPlace$i" class="readOnlyTxtShort" name="balance[items][$i][arrivalPlace]" readonly="" value="{$val['arrivalPlace']}"></td>
				<td style="text-align: center;"><input type="text" id="itemTable_cmp_costPay$i" class="readOnlyTxtMiddle" name="balance[items][$i][costPay]" readonly="" value="{$val['costPay']}"></td>
				<td style="display: none; text-align: center;"><input type="hidden" id="itemTable_cmp_msgType$i" class="txtmiddle" name="balance[items][$i][msgType]" readonly="" value="{$val['msgType']}"></td>
				<td style="display: none; text-align: center;"><input type="hidden" id="itemTable_cmp_msgId$i" class="txtmiddle" name="balance[items][$i][msgId]" readonly="" value="{$val['msgId']}"></td>
			</tr>
EOT;
			$i++;
			$j++;
		}
		
		$str = <<<EOT
		<div id="itemTable" style="overflow-x: auto; overflow-y: auto; height: 500px;">
		<table class="form_in_table">
			<thead>
				<tr class="main_tr_header">
					<th width="10"></th>
					<th width="30">序号</th>
					<th style="width: 70px;"><div style="min-width: 70px;" class="divChangeLine">结算类型</div></th>
					<th style="width: 80px;"><div style="min-width: 80px;" class="divChangeLine">核算日期</div></th>
					<th style="width: 90px;"><div style="min-width: 90px;" class="divChangeLine">乘机人</div></th>
					<th style="display: none;"><div style="min-width: 100px;" class="divChangeLine">airId</div></th>
					<th style="width: 140px;"><div style="min-width: 140px;" class="divChangeLine">航空公司</div></th>
					<th style="display: none;"><div style="min-width: 100px;" class="divChangeLine">airlineId</div></th>
					<th><div style="min-width: 100px;" class="divChangeLine">车次/航班号</div></th>
					<th style="width: 130px;"><div style="min-width: 130px;" class="divChangeLine">乘机时间</div></th>
					<th style="width: 130px;"><div style="min-width: 130px;" class="divChangeLine">到达时间</div></th>
					<th><div style="min-width: 100px;" class="divChangeLine">出发地点</div></th>
					<th><div style="min-width: 100px;" class="divChangeLine">到达地点</div></th>
					<th><div style="min-width: 100px;" class="divChangeLine">实际付款金额</div></th>
					<th style="display: none;"><div style="min-width: 100px;" class="divChangeLine">msgType</div></th>
					<th style="display: none;"><div style="min-width: 100px;" class="divChangeLine">msgId</div></th>
				</tr>
			</thead>
			<tbody>
				$trStr
			</tbody>
		</table>
		</div>
EOT;
		return $str;
	
	}

    /**
     * @param 根据传入条件获取所有费用
     * @return int|string
     */
    function getAllCost_d($param){
		$this->getParam($param);
		$arr=$this->list_d();
		$result = 0;
		foreach ($arr as $val){
			$result = bcadd($result,$val['costPay'],2);
		}
		return $result;
	}
}