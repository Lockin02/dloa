<?php
/**
 * @author Administrator
 * @Date 2012-11-14 11:06:15
 * @version 1.0
 * @description:项目设备任务单明细 Model层
 */
class model_engineering_resources_taskdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_taskdetail";
		$this->sql_map = "engineering/resources/taskdetailSql.php";
		parent :: __construct();
	}

	/**
	 * 获取设备待出库数量
	 * area => num
	 */
	function getAreaWaitNumArr_d($resourceId){
		$this->searchArr = array(
			'resourceId' => $resourceId
		);
		$this->sort = '';
		$this->groupBy = 'areaId';
		$rs = $this->list_d('select_area');
		if($rs[0]['areaName']){
			$newArr = array();
			foreach($rs as $v){
				$newArr[$v['areaId']] = $v['waitNum'];
			}
			return $newArr;
		}else{
			return false;
		}
	}

    /**
     * 更新执行数量
     */
    function updateExeNumber_d($id,$number){
        try{
            // 更新自身数量
            $sql = "UPDATE $this->tbl_name SET exeNumber = exeNumber + $number WHERE id = $id";
            $this->_db->query($sql);

            return true;
        }catch (Exception $e){
            throw $e;
        }
    }
    
    /**
     * 撤销任务时更新申请源单明细下达数量并删除下达任务明细
     */
    function updateApplyExeNumberAndDelete_d($taskId){
    	try{
    		$rs = $this->findAll(array('taskId' => $taskId),null,'id,applyDetailId,number');
    		if(!empty($rs)){
    			foreach ($rs as $v){
    				//更新申请源单明细下达数量
    				$sql = "UPDATE oa_esm_resource_applydetail SET exeNumber = exeNumber - {$v['number']} WHERE id = '{$v['applyDetailId']}'";
    				$this->_db->query($sql);
    				//删除自身
    				$this->deletes($v['id']);
    			}
    		}
    		return true;
    	}catch (Exception $e){
    		throw $e;
    	}
    }
    
    /**
     * 设备管理员确认发货分配数量时获取数据
     */
    function confirmTaskNumListJson_d($applyId){
    	$sql = "
    			SELECT
    				k.id as taskId,
					k.taskCode,
					l.id,
					l.resourceId,
					l.resourceTypeId,
					l.applyDetailId,
					l.resourceTypeName,
					l.resourceName,
					l.unit,
					l.number,
					l.awaitNumber,
					l.planBeginDate,
					l.planEndDate,
					l.useDays,
					l.remark
				FROM
					{$this->tbl_name} l
				LEFT JOIN oa_esm_resource_task k ON k.id = l.taskId
				WHERE
					k.applyId = '{$applyId}'
				AND k.status = 3;";
		return $this->_db->getArray($sql);
    }
}