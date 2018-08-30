<?php
/**
 * @author Administrator
 * @Date 2012-11-14 11:05:47
 * @version 1.0
 * @description:��Ŀ�豸���� Model��
 */
class model_engineering_resources_task extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_task";
		$this->sql_map = "engineering/resources/taskSql.php";
		parent :: __construct();
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object) {
        $applydet = $object['applydet'];//ȡ������������Ϣ
        unset($object['applydet']);

		$taskdetail = $object['taskdetail'];//ȡ���´�������Ϣ
		unset($object['taskdetail']);
        //������������������������
        $areaTaskArr = array();
        foreach($taskdetail as $v){
            //������������
            if(!isset($areaTaskArr[$v['areaId']])){
                $areaTaskArr[$v['areaId']] = $object;
                $areaTaskArr[$v['areaId']]['areaId'] = $v['areaId'];
                $areaTaskArr[$v['areaId']]['areaName'] = $v['areaName'];
                $areaTaskArr[$v['areaId']]['taskdetail'] = array();
            }
            array_push($areaTaskArr[$v['areaId']]['taskdetail'],$v);
        }
        if(empty($taskdetail)){
            echo '�������ʧ��';
        }
		try {
			$this->start_d();
            foreach($areaTaskArr as $v){
                //����������Ϣ
                $codeRuleDao = new model_common_codeRule();
                $v['taskCode'] = $codeRuleDao->commonCode ('���̷�������',$this->tbl_name,'GCFH');
                $newId = parent :: add_d($v,true);

                //����
                $taskdetailDao = new model_engineering_resources_taskdetail();
                $v['taskdetail'] = util_arrayUtil::setArrayFn(array('taskId' => $newId),$v['taskdetail']);
                $taskdetailDao->saveDelBatch($v['taskdetail']);
            }

			//�����豸��Ϣ����
			$applyDetailDao = new model_engineering_resources_resourceapplydet();
			$applyDetailDao->updateBatch($applydet);
			//��д���뵥 ����״̬
			$applyDao = new model_engineering_resources_resourceapply();
			$applyDao->updateSatusAuto_d($v['applyId'],$applyDetailDao);//����������뵥��״̬

            //��������沿�ֵĵ��� TODO
            
			//��¼������־
			$logDao = new model_engineering_baseinfo_resourceapplylog();
			$logDao->addLog_d($object['applyId'],'�´﷢��');

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
		}
	}

    /**
     * ��ʼ���豸��Ϣ,�����ֳ��ش���
     */
    function initInfo_d($object){
    	$id = $object['id'];
    	$item = $object['item']; 	
        $obj = $this->get_d($id);
        //��ʼ����ص���
        $taskdetailDao = new model_engineering_resources_taskdetail();
        $resourceapplyDao = new model_engineering_resources_resourceapply();
        $resourceapplydetDao = new model_engineering_resources_resourceapplydet();
        $isBack = false;//���ر�־
        
        try{
        	$this->start_d();
        	
        	foreach ($item as $k => $v){
        		if(!isset($v['isDelTag'])){//δɾ����������,��ѯ����Ϊ�յ�,������ѯ����
        			if(empty($v['searchText'])){
        				unset($item[$k]);
        			}
        		}else{//ɾ��������,��Ҫ������������ϸ�����豸������ϸ��Ϣ
        			$backNumber = $v['number'] - $v['exeNumber'];
        			$taskdetailDao->update(array('id'=>$v['id']),array('backNumber'=>$backNumber));
        			$resourceapplydetDao->update(array('id'=>$v['applyDetailId']), array('backNumber'=>$backNumber,'status'=>2));
        			$isBack = true;
        			unset($item[$k]);
        		}
        	}
        	if($isBack){//���ش���
        		$rs = $this->find(array('id'=>$id),null,'applyId,createId');
        		if(!empty($rs)){
        			//�����豸���뵥����״̬Ϊ�����ش�ȷ�ϡ�
        			$applyId = $rs['applyId'];
        			$resourceapplyDao->update(array('id'=>$applyId), array('confirmStatus'=>7));
        			//��¼������־
        			$logDao = new model_engineering_baseinfo_resourceapplylog();
        			$logDao->addLog_d($applyId, '���ش�ȷ��');
        			//�ʼ�֪ͨ
					$this->mailDeal_d('resourceBackConfirm',$rs['createId'],array('id'=>$applyId));
        		}
        		//��������״̬
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
     * ��ʼ���ӱ�
     */
    function getEqu_d($object,$areaId){
        $html = '<table class="form_in_table"><thead><tr class="main_tr_header"><th>���</th><th>�豸����</th><th>�豸����</th><th>������</th>'.
            '<th>������</th><th>��λ</th><th>���</th><th>�������</th><th>�������</th><th>ʣ���������</th><th>��������</th><th>Ԥ�ƹ黹����</th>'.
            '<th>��ע</th></tr></thead><tbody>';
        $str = '';
        $i = 0;
        foreach($object as $v){
        	$equInfo = $this->getEquInfo_d($v['searchKey'],$v['searchText'],$v['resourceId'],$areaId);
        	if($equInfo){
        		$num = $v['number'] - $v['exeNumber']; //ʣ���������
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
     * ��ȡ�豸
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
     * �ַ����з�
     */
    function strBuild_d($str){
        if(!$str) return ''; // �յ�ʱ��ֱ�ӷ��ؿ��ַ���
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
     * ���ս������
     */
    function outStockFinal_d($object){
//        var_dump($object);die();
		if(!is_array($object['item'])){
			msg("������Ϣ������");
		}
        try{
            $this->start_d();

            //���±�����
            $this->updateById($object);
            //��ȡ�����ܽ������
            $amount = 0;
            foreach($object['item'] as $v){
                $amount = $amount + $v['amount'];
            }

            //��Ŀidת��
            $esmprojectDao = new model_engineering_project_esmproject();
            $projectId = $esmprojectDao->getOldProjectId_d($object['projectId']);
            //�������ݴ���
            $sql = "INSERT INTO device_borrow_order (userid,dept_id,project_id,operatorid,manager,area,targetdate,date,rand_key,amount)VALUES('".
                $object['userId']."','".$object['deptId']."','".$projectId."','".$_SESSION['USER_ID']."','".$object['managerId']."','".$object['area']."',".
                "UNIX_TIMESTAMP(),UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()),$amount)";
            $this->_db->query($sql);
            $id = $this->_db->insert_id();

            $listIdArr = array(); // ����list_id������
            $infoIdArr = array(); // ����info_id������
            $taskDetailDao = new model_engineering_resources_taskdetail();
            //�ӱ����ݴ���
            foreach($object['item'] as $v){
                // �����ӱ�
                $itemSql = "INSERT INTO device_borrow_order_info (orderid,info_id,typeid,list_id,amount,targetdate,notse,date,rand_key)VALUES('".
                    $id."','".$v['infoId']."','".$v['typeid']."','".$v['listId']."','".$v['amount']."',".
                    "'".strtotime($v['target_date'])."','".$v['notse']."',UNIX_TIMESTAMP(),MD5(UNIX_TIMESTAMP()))";
                $this->_db->query($itemSql);

                // ����device_info
                $sql = "UPDATE device_info SET borrow_num = borrow_num + $v[amount],state = IF(amount = borrow_num,1,0) WHERE id = ".$v['infoId'];
                $this->_db->query($sql);

                // ������������
                $taskDetailDao->updateExeNumber_d($v['taskDetailId'],$v['amount']);
                // ����listId
                array_push($listIdArr,$v['listId']);
                // ����infoId
                array_push($infoIdArr,$v['infoId']);
            }

            $listIds = implode(',',$listIdArr);
            $infoIds = implode(',',$infoIdArr);
            // ����ʵ�ʿ������
            $sql = "update
                    device_list as a,
                    (select sum(amount)  as num ,sum(borrow_num)as borrow_num, avg(price) as average ,list_id  from device_info where quit=0 AND list_id in ($listIds) group by list_id) as b
                set
                    a.total=b.num,a.average=b.average,a.borrow=b.borrow_num, a.surplus=b.num-b.borrow_num,
                    a.rate=(CAST((b.borrow_num/b.num) AS DECIMAL(11,2)))
                where a.id=b.list_id AND a.id in($listIds)";
            $this->_db->query($sql);
            //��������״̬
            $this->updateStatus_d($object['id']);
            //�������뵥 ����״̬
            $applyDao = new model_engineering_resources_resourceapply();
            $applyDao->updateConfirmStatus_d($object['applyId']);
            //��ȡ�ʲ���Ƭid
            $sql = "SELECT GROUP_CONCAT(CAST(assetCardId AS CHAR)) AS assetCardIds FROM device_info WHERE id IN ($infoIds)";
            $rs = $this->findSql($sql);
            $assetCardIds = $rs[0]['assetCardIds'];
            //�����ڹ����ʲ���Ƭid,�������ʹ���˼�������Ϣ
            if(!empty($assetCardIds)){
            	$assetcardDao = new model_asset_assetcard_assetcard();
            	$assetcardDao->updateByEsmDevice($object['userId'], $assetCardIds);
            }
			//�ʼ�֪ͨ������,������,��Ŀ����
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
     * ���µ���״̬
     */
    function updateStatus_d($id){
        $sql = "SELECT SUM(number) AS number ,SUM(exeNumber) as exeNumber ,SUM(backNumber) as backNumber FROM oa_esm_resource_taskdetail WHERE taskId = '$id'";
        $rs = $this->_db->getArray($sql);
        //�Զ��ж�״̬
        if($rs[0]['number'] == $rs[0]['exeNumber'] + $rs[0]['backNumber']){
            $status = '2';
        }elseif($rs[0]['exeNumber'] == 0){
            $status = '0';
        }else{
            $status = '1';
        }
        try{
            $this->update(array('id' => $id),array('status' => $status)); // ����״̬
            return true;
        }catch (Exception $e){
            throw $e;
        }
    }
    
    /**
     * �������񷽷�
     */
    function deletes_d($ids) {
    	try {
    		$this->start_d ();
    		
    		//ͳһʵ����
    		$taskdetailDao = new model_engineering_resources_taskdetail();//����������ϸ
    		$resourceapplyDao = new model_engineering_resources_resourceapply();//��Ŀ�豸����� 
    		if(strstr($ids,',') != false){//��������
    			$idArr = explode(',',$ids);
    			foreach ($idArr as $id){
    				//��ȡ�豸����id
    				$rs = $this->find(array('id' => $id),null,'applyId,createId');
    				$applyId = $rs['applyId'];
    				$createId = $rs['createId'];
    				//������ϸ
    				$rs = $taskdetailDao->findAll(array('taskId'=>$id),null,'id,applyDetailId,number');
    				if(!empty($rs)){
    					foreach ($rs as $v){
    						$taskdetailDao->update(array('id'=>$v['id']),array('backNumber'=>$v['number']));
    						$sql = "UPDATE oa_esm_resource_applydetail SET backNumber = backNumber + ".$v['number'].",status=2 WHERE id = ".$v['applyDetailId'];
							$this->_db->query($sql);
    					}
    				}
    				//��������״̬
    				$this->updateStatus_d($id);
					//����Դ������״̬
    				$resourceapplyDao->update(array('id'=>$applyId), array('confirmStatus'=>7));
    				//��¼������־
    				$logDao = new model_engineering_baseinfo_resourceapplylog();
    				$logDao->addLog_d($applyId, '���ش�ȷ��');
    				//�ʼ�֪ͨ
    				$this->mailDeal_d('resourceBackConfirm',$createId,array('id'=>$applyId));
    			}
    		}else{//��������
    				//��ȡ�豸����id
    				$rs = $this->find(array('id' => $ids),null,'applyId,createId');
    				$applyId = $rs['applyId'];
    				$createId = $rs['createId'];
    				//������ϸ
    				$rs = $taskdetailDao->findAll(array('taskId'=>$ids),null,'id,applyDetailId,number');
    				if(!empty($rs)){
    					foreach ($rs as $v){
    						$taskdetailDao->update(array('id'=>$v['id']),array('backNumber'=>$v['number']));
    						$sql = "UPDATE oa_esm_resource_applydetail SET backNumber = backNumber + ".$v['number'].",status=2 WHERE id = ".$v['applyDetailId'];
							$this->_db->query($sql);
    					}
    				}
    				//��������״̬
    				$this->updateStatus_d($ids);
					//����Դ������״̬
    				$resourceapplyDao->update(array('id'=>$applyId), array('confirmStatus'=>7));
    				//��¼������־
    				$logDao = new model_engineering_baseinfo_resourceapplylog();
    				$logDao->addLog_d($applyId, '���ش�ȷ��');
    				//�ʼ�֪ͨ
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
     * �޸ķ������������������
     */
    function editNumber_d($object) {
    	try {
    		$this->start_d ();
    		
    		$id = $object['id'];
    		//�����ݵ�״̬��Ϊ��ȷ��
    		$this->update(array('id' => $id),array('status' => 3));
    		//��ϸ����
    		$detail = $object['detail'];
    		if(is_array($detail)){
    			//ͳһʵ����
	    		$taskdetailDao = new model_engineering_resources_taskdetail();//����������ϸ
	    		$resourceapplyDao = new model_engineering_resources_resourceapply();//��Ŀ�豸����� 
    			foreach ($detail as $val){
    				//���´�ȷ�Ϸ����������豸��Ϣ
    				$taskdetailDao->update(array('id' => $val['id']),
    					array('awaitNumber' => $val['number'],
    					'resourceId' => $val['resourceId'],
    					'resourceName' => $val['resourceName'],
    					'unit' => $val['unit'],
    					'remark' => $val['remark']));
    			}
    			//��ȡ�豸����id
    			$rs = $this->find(array('id' => $id),null,'applyId');
    			$applyId = $rs['applyId'];
    			//����Դ���´�״̬Ϊ��ȷ��
    			$resourceapplyDao->update(array('id' => $applyId),array('status' => 3));
    			//��¼������־
    			$logDao = new model_engineering_baseinfo_resourceapplylog();
    			$logDao->addLog_d($applyId, '�����޸Ĵ�ȷ��');
    			//�ʼ�֪ͨ
    			if($object['mailInfo']['issend'] == 'y'){
    				$this->mailDeal_d('esmResourcesTaskChangeNum',$object['mailInfo']['TO_ID'],array('id' => $id));
    			}
    		}else {
				throw new Exception ( "������Ϣ������!" );
			}

    		$this->commit_d ();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack ();
    		return false;
    	}
    }
}