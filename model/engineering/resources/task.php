<?php
/**
 * @author Administrator
 * @Date 2012-11-14 11:05:47
 * @version 1.0
 * @description:项目设备任务单 Model层
 */
class model_engineering_resources_task extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_task";
		$this->sql_map = "engineering/resources/taskSql.php";
		parent :: __construct();
	}

	/**
	 * 添加对象
	 */
	function add_d($object) {
        $applydet = $object['applydet'];//取出申请物料信息
        unset($object['applydet']);

		$taskdetail = $object['taskdetail'];//取出下达任务信息
		unset($object['taskdetail']);
        //根据任务的区域生成相关任务单
        $areaTaskArr = array();
        foreach($taskdetail as $v){
            //构建区域数组
            if(!isset($areaTaskArr[$v['areaId']])){
                $areaTaskArr[$v['areaId']] = $object;
                $areaTaskArr[$v['areaId']]['areaId'] = $v['areaId'];
                $areaTaskArr[$v['areaId']]['areaName'] = $v['areaName'];
                $areaTaskArr[$v['areaId']]['taskdetail'] = array();
            }
            array_push($areaTaskArr[$v['areaId']]['taskdetail'],$v);
        }
        if(empty($taskdetail)){
            echo '任务分配失败';
        }
		try {
			$this->start_d();
            foreach($areaTaskArr as $v){
                //插入主表信息
                $codeRuleDao = new model_common_codeRule();
                $v['taskCode'] = $codeRuleDao->commonCode ('工程发货任务',$this->tbl_name,'GCFH');
                $newId = parent :: add_d($v,true);

                //物料
                $taskdetailDao = new model_engineering_resources_taskdetail();
                $v['taskdetail'] = util_arrayUtil::setArrayFn(array('taskId' => $newId),$v['taskdetail']);
                $taskdetailDao->saveDelBatch($v['taskdetail']);
            }

			//申请设备信息更新
			$applyDetailDao = new model_engineering_resources_resourceapplydet();
			$applyDetailDao->updateBatch($applydet);
			//反写申请单 单据状态
			$applyDao = new model_engineering_resources_resourceapply();
			$applyDao->updateSatusAuto_d($v['applyId'],$applyDetailDao);//这里更新申请单的状态

            //这里接入库存部分的调整 TODO
            
			//记录操作日志
			$logDao = new model_engineering_baseinfo_resourceapplylog();
			$logDao->addLog_d($object['applyId'],'下达发货');

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
		}
	}

    /**
     * 初始化设备信息,含部分撤回处理
     */
    function initInfo_d($object){
    	$id = $object['id'];
    	$item = $object['item']; 	
        $obj = $this->get_d($id);
        //初始化相关的类
        $taskdetailDao = new model_engineering_resources_taskdetail();
        $resourceapplyDao = new model_engineering_resources_resourceapply();
        $resourceapplydetDao = new model_engineering_resources_resourceapplydet();
        $isBack = false;//撤回标志
        
        try{
        	$this->start_d();
        	
        	foreach ($item as $k => $v){
        		if(!isset($v['isDelTag'])){//未删除的数据中,查询内容为空的,不作查询处理
        			if(empty($v['searchText'])){
        				unset($item[$k]);
        			}
        		}else{//删掉的数据,需要处理发货任务明细还有设备申请明细信息
        			$backNumber = $v['number'] - $v['exeNumber'];
        			$taskdetailDao->update(array('id'=>$v['id']),array('backNumber'=>$backNumber));
        			$resourceapplydetDao->update(array('id'=>$v['applyDetailId']), array('backNumber'=>$backNumber,'status'=>2));
        			$isBack = true;
        			unset($item[$k]);
        		}
        	}
        	if($isBack){//撤回处理
        		$rs = $this->find(array('id'=>$id),null,'applyId,createId');
        		if(!empty($rs)){
        			//更新设备申请单单据状态为【撤回待确认】
        			$applyId = $rs['applyId'];
        			$resourceapplyDao->update(array('id'=>$applyId), array('confirmStatus'=>7));
        			//记录操作日志
        			$logDao = new model_engineering_baseinfo_resourceapplylog();
        			$logDao->addLog_d($applyId, '撤回待确认');
        			//邮件通知
					$this->mailDeal_d('resourceBackConfirm',$rs['createId'],array('id'=>$applyId));
        		}
        		//更新任务单状态
        		$this->updateStatus_d($id);
        	}
        	
        	$this->commit_d ();
        	$flag = true;
        } catch (Exception $e) {
        	$this->rollBack ();
        	$flag = false;
        }
		if($flag && !empty($item)){
			$obj['item'] = $this->getEqu_d($item,$obj['areaId']);
			$obj['itemCount'] = count($obj['item']);
			return $obj;
		}else{
			return "";
		}
    }

    /**
     * 初始化从表
     */
    function getEqu_d($object,$areaId){
        $html = '<table class="form_in_table"><thead><tr class="main_tr_header"><th>序号</th><th>设备分类</th><th>设备名称</th><th>机身码</th>'.
            '<th>部门码</th><th>单位</th><th>配件</th><th>库存数量</th><th>借出数量</th><th>剩余分配数量</th><th>借用数量</th><th>预计归还日期</th>'.
            '<th>备注</th></tr></thead><tbody>';
        $str = '';
        $i = 0;
        foreach($object as $v){
        	$equInfo = $this->getEquInfo_d($v['searchKey'],$v['searchText'],$v['resourceId'],$areaId);
        	if($equInfo){
        		$num = $v['number'] - $v['exeNumber']; //剩余分配数量
        		foreach($equInfo as $val){
        			$i++;
        			if($val['surplus'] > 1 && $num > 1){
        				$canBorrow = min($val['surplus'],$num);
        				$td = '<td><input type="text" class="txtmiddle" id="amount'.$i.'" name="task[item]['.$i.'][amount]" value="'.$canBorrow.'"/>
        						<input type="hidden" id="canBorrow'.$i.'" value="'.$canBorrow.'"/></td>'; 
        			}else{
        				$td = '<td>1<input type="hidden" name="task[item]['.$i.'][amount]" value="1"/></td>
        						<input type="hidden" id="canBorrow'.$i.'" value="1"/></td>';
        			}
        			$str .=<<<E
                    	<tr class="tr_even">
                        <td>$i</td>
                        <td>$val[typename]</td>
                        <td>$val[device_name]</td>
                        <td>$val[coding]</td>
                        <td>$val[dpcoding]</td>
                        <td>$val[unit]</td>
                        <td>$val[fitting]</td>
                        <td>$val[total]</td>
                        <td>$val[borrow]</td>
                        <td>$num</td>
						$td
                        <td><input type="text" class="txtmiddle Wdate" name="task[item][$i][target_date]" value="$v[planEndDate]" onfocus="WdatePicker();"/></td>
                        <td><input type="text" class="txt" name="task[item][$i][notse]" value="$v[remark]"/></td>
                       	<input type="hidden" name="task[item][$i][taskDetailId]" value="$v[id]"/>
                        <input type="hidden" name="task[item][$i][infoId]" value="$val[id]"/>
                        <input type="hidden" name="task[item][$i][listId]" value="$val[list_id]"/>
                        <input type="hidden" name="task[item][$i][typeid]" value="$val[typeid]"/>
                    </tr>
E;
        		}
        	}
        }
        return $html.$str.'</tbody></table>';
    }

    /**
     * 获取设备
     */
    function getEquInfo_d($searchKey,$searchText,$listId,$areaId){
        switch($searchKey){
            case 'dpcoding' : $searchSql = 'i.dpcoding' ; break;
            case 'coding' : $searchSql = 'i.coding' ; break;
            case 'id' : $searchSql = 'i.id' ; break;
        }
        $searchText = $this->strBuild_d($searchText);
        $searchSql .= " in($searchText)";
        $sql = "SELECT
                i.id,i.coding,i.dpcoding,i.amount as total,i.borrow_num as borrow,(i.amount - i.borrow_num) as surplus,i.fitting,
                l.id as list_id,l.device_name,l.unit,t.typename,t.id as typeid
            FROM
                device_info i
                LEFT JOIN device_list l on i.list_id = l.id
                LEFT JOIN device_type t ON t.id = l.typeid 
        	WHERE 
        		i.state = 0 AND i.quit = 0 AND i.list_id = $listId AND i.area = $areaId AND $searchSql";
        return $this->_db->getArray($sql);
    }

    /**
     * 字符串切分
     */
    function strBuild_d($str){
        if(!$str) return ''; // 空的时候直接返回空字符串
        $strArr = explode("\n",$str);
        $newStr = "'";
        foreach($strArr as $key => $val){
            $val = trim($val);
            if($key){
                $newStr .= "','".$val;
            }else{
                $newStr .= $val;
            }
        }
        $newStr .= "'";
        return $newStr;
    }

    /**
     * 最终借出操作
     */
    function outStockFinal_d($object){
//        var_dump($object);die();
		if(!is_array($object['item'])){
			msg("单据信息不完整");
		}
        try{
            $this->start_d();

            //更新本单据
            $this->updateById($object);
            //获取单据总借出数量
            $amount = 0;
            foreach($object['item'] as $v){
                $amount = $amount + $v['amount'];
            }

            //项目id转义
            $esmprojectDao = new model_engineering_project_esmproject();
            $projectId = $esmprojectDao->getOldProjectId_d($object['projectId']);
            //主表数据处理
            $sql = "INSERT INTO device_borrow_order (userid,dept_id,project_id,operatorid,manager,area,targetdate,date,rand_key,amount)VALUES('".
                $object['userId']."','".$object['deptId']."','".$projectId."','".$_SESSION['USER_ID']."','".$object['managerId']."','".$object['area']."',".
                "UNIX_TIMESTAMP(),UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()),$amount)";
            $this->_db->query($sql);
            $id = $this->_db->insert_id();

            $listIdArr = array(); // 缓存list_id的数组
            $infoIdArr = array(); // 缓存info_id的数组
            $taskDetailDao = new model_engineering_resources_taskdetail();
            //从表数据处理
            foreach($object['item'] as $v){
                // 新增从表
                $itemSql = "INSERT INTO device_borrow_order_info (orderid,info_id,typeid,list_id,amount,targetdate,notse,date,rand_key)VALUES('".
                    $id."','".$v['infoId']."','".$v['typeid']."','".$v['listId']."','".$v['amount']."',".
                    "'".strtotime($v['target_date'])."','".$v['notse']."',UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
                $this->_db->query($itemSql);

                // 更新device_info
                $sql = "UPDATE device_info SET borrow_num = borrow_num + $v[amount],state = IF(amount = borrow_num,1,0) WHERE id = ".$v['infoId'];
                $this->_db->query($sql);

                // 更新任务单数量
                $taskDetailDao->updateExeNumber_d($v['taskDetailId'],$v['amount']);
                // 缓存listId
                array_push($listIdArr,$v['listId']);
                // 缓存infoId
                array_push($infoIdArr,$v['infoId']);
            }

            $listIds = implode(',',$listIdArr);
            $infoIds = implode(',',$infoIdArr);
            // 更新实际库存数量
            $sql = "update
                    device_list as a,
                    (select sum(amount)  as num ,sum(borrow_num)as borrow_num, avg(price) as average ,list_id  from device_info where quit=0 AND list_id in ($listIds) group by list_id) as b
                set
                    a.total=b.num,a.average=b.average,a.borrow=b.borrow_num, a.surplus=b.num-b.borrow_num,
                    a.rate=(CAST((b.borrow_num/b.num) AS DECIMAL(11,2)))
                where a.id=b.list_id AND a.id in($listIds)";
            $this->_db->query($sql);
            //更新任务单状态
            $this->updateStatus_d($object['id']);
            //更新申请单 单据状态
            $applyDao = new model_engineering_resources_resourceapply();
            $applyDao->updateConfirmStatus_d($object['applyId']);
            //获取资产卡片id
            $sql = "SELECT GROUP_CONCAT(CAST(assetCardId AS CHAR)) AS assetCardIds FROM device_info WHERE id IN ($infoIds)";
            $rs = $this->findSql($sql);
            $assetCardIds = $rs[0]['assetCardIds'];
            //若存在关联资产卡片id,则更新其使用人及部门信息
            if(!empty($assetCardIds)){
            	$assetcardDao = new model_asset_assetcard_assetcard();
            	$assetcardDao->updateByEsmDevice($object['userId'], $assetCardIds);
            }
			//邮件通知申请人,接收人,项目经理
			if($object['email']['issend'] == 'y'){
				$this->mailDeal_d('esmResourcesTaskOutStock',$object['email']['TO_ID'],array('id'=>$object['id'],'orderid'=>$id));
			}
            
            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }
    }

    /**
     * 更新单据状态
     */
    function updateStatus_d($id){
        $sql = "SELECT SUM(number) AS number ,SUM(exeNumber) as exeNumber ,SUM(backNumber) as backNumber FROM oa_esm_resource_taskdetail WHERE taskId = '$id'";
        $rs = $this->_db->getArray($sql);
        //自动判断状态
        if($rs[0]['number'] == $rs[0]['exeNumber'] + $rs[0]['backNumber']){
            $status = '2';
        }elseif($rs[0]['exeNumber'] == 0){
            $status = '0';
        }else{
            $status = '1';
        }
        try{
            $this->update(array('id' => $id),array('status' => $status)); // 更新状态
            return true;
        }catch (Exception $e){
            throw $e;
        }
    }
    
    /**
     * 撤销任务方法
     */
    function deletes_d($ids) {
    	try {
    		$this->start_d ();
    		
    		//统一实例化
    		$taskdetailDao = new model_engineering_resources_taskdetail();//发货任务明细
    		$resourceapplyDao = new model_engineering_resources_resourceapply();//项目设备申请表 
    		if(strstr($ids,',') != false){//批量撤销
    			$idArr = explode(',',$ids);
    			foreach ($idArr as $id){
    				//获取设备申请id
    				$rs = $this->find(array('id' => $id),null,'applyId,createId');
    				$applyId = $rs['applyId'];
    				$createId = $rs['createId'];
    				//处理明细
    				$rs = $taskdetailDao->findAll(array('taskId'=>$id),null,'id,applyDetailId,number');
    				if(!empty($rs)){
    					foreach ($rs as $v){
    						$taskdetailDao->update(array('id'=>$v['id']),array('backNumber'=>$v['number']));
    						$sql = "UPDATE oa_esm_resource_applydetail SET backNumber = backNumber + ".$v['number'].",status=2 WHERE id = ".$v['applyDetailId'];
							$this->_db->query($sql);
    					}
    				}
    				//更新任务单状态
    				$this->updateStatus_d($id);
					//更新源单单据状态
    				$resourceapplyDao->update(array('id'=>$applyId), array('confirmStatus'=>7));
    				//记录操作日志
    				$logDao = new model_engineering_baseinfo_resourceapplylog();
    				$logDao->addLog_d($applyId, '撤回待确认');
    				//邮件通知
    				$this->mailDeal_d('resourceBackConfirm',$createId,array('id'=>$applyId));
    			}
    		}else{//单独撤销
    				//获取设备申请id
    				$rs = $this->find(array('id' => $ids),null,'applyId,createId');
    				$applyId = $rs['applyId'];
    				$createId = $rs['createId'];
    				//处理明细
    				$rs = $taskdetailDao->findAll(array('taskId'=>$ids),null,'id,applyDetailId,number');
    				if(!empty($rs)){
    					foreach ($rs as $v){
    						$taskdetailDao->update(array('id'=>$v['id']),array('backNumber'=>$v['number']));
    						$sql = "UPDATE oa_esm_resource_applydetail SET backNumber = backNumber + ".$v['number'].",status=2 WHERE id = ".$v['applyDetailId'];
							$this->_db->query($sql);
    					}
    				}
    				//更新任务单状态
    				$this->updateStatus_d($ids);
					//更新源单单据状态
    				$resourceapplyDao->update(array('id'=>$applyId), array('confirmStatus'=>7));
    				//记录操作日志
    				$logDao = new model_engineering_baseinfo_resourceapplylog();
    				$logDao->addLog_d($applyId, '撤回待确认');
    				//邮件通知
    				$this->mailDeal_d('resourceBackConfirm',$createId,array('id'=>$applyId));
    		}

    		$this->commit_d ();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack ();
    		return false;
    	}
    }
    
    /**
     * 修改发货任务分配数量方法
     */
    function editNumber_d($object) {
    	try {
    		$this->start_d ();
    		
    		$id = $object['id'];
    		//将单据的状态改为待确认
    		$this->update(array('id' => $id),array('status' => 3));
    		//明细处理
    		$detail = $object['detail'];
    		if(is_array($detail)){
    			//统一实例化
	    		$taskdetailDao = new model_engineering_resources_taskdetail();//发货任务明细
	    		$resourceapplyDao = new model_engineering_resources_resourceapply();//项目设备申请表 
    			foreach ($detail as $val){
    				//更新待确认分配数量或设备信息
    				$taskdetailDao->update(array('id' => $val['id']),
    					array('awaitNumber' => $val['number'],
    					'resourceId' => $val['resourceId'],
    					'resourceName' => $val['resourceName'],
    					'unit' => $val['unit'],
    					'remark' => $val['remark']));
    			}
    			//获取设备申请id
    			$rs = $this->find(array('id' => $id),null,'applyId');
    			$applyId = $rs['applyId'];
    			//更新源单下达状态为待确认
    			$resourceapplyDao->update(array('id' => $applyId),array('status' => 3));
    			//记录操作日志
    			$logDao = new model_engineering_baseinfo_resourceapplylog();
    			$logDao->addLog_d($applyId, '发货修改待确认');
    			//邮件通知
    			if($object['mailInfo']['issend'] == 'y'){
    				$this->mailDeal_d('esmResourcesTaskChangeNum',$object['mailInfo']['TO_ID'],array('id' => $id));
    			}
    		}else {
				throw new Exception ( "单据信息不完整!" );
			}

    		$this->commit_d ();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack ();
    		return false;
    	}
    }
}