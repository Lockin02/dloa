<?php
/**
 * @author Show
 * @Date 2011年12月10日 星期六 13:45:07
 * @version 1.0
 * @description:项目里程碑(oa_esm_project_milestone) Model层
 */
class model_engineering_milestone_esmmilestone extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_milestone";
		$this->sql_map = "engineering/milestone/esmmilestoneSql.php";
		parent::__construct ();
    }

	/****************************业务方法***************************/
    /**
     * 获取里程碑所需信息
     */
    function getObjInfo_d($projectId){
    	$serviceesmMilestoneDao = new model_engineering_project_esmproject();
    	$serviceMilestone = $serviceesmMilestoneDao->get_d($projectId);
    	return $serviceMilestone;
    }

	/**
	 * 重写新增方法
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * 插入新的里程碑点
	 */
	function milestoneChange_d($object){
//		echo "<pre>";
//		print_r($object);
		//还存在的id数组
		$idsArr = array();
		//变更缓存数据
		$changeIdsArr = array();
		//项目id
		$projectId = null;
		try{
			$this->start_d();

			//循环处理数组
			foreach($object as $key => $val){
				if(!empty($val['milestoneId'])){
					array_push($idsArr,$val['milestoneId']);
				}
				if(empty($projectId)){
					$projectId = $val['projectId'];
				}

				if(empty($val['milestoneId'])){//如果是新产生的数据，直接新增
					unset($val['id']);
					$val['preMilestoneId'] = $changeIdsArr[$val['changePreId']];
//					print_r($val);

					//处理实际开始日期
					if(empty($val['actBeginDate'] ) || $val['actBeginDate'] == '0000-00-00'){
						unset($val['actBeginDate']);
					}
					//处理实际结束日期
					if(empty($val['actEndDate'] ) || $val['actEndDate'] == '0000-00-00'){
						unset($val['actEndDate']);
					}
					//处理实际结束日期
					if(empty($val['confirmStatus'] )){
						unset($val['confirmStatus']);
					}

					$newId = $this->add_d($val);

					array_push($idsArr,$newId);

				}else{//如果是从原来里程碑产生的数据，编辑
					$changeIdsArr[$val['id']] = $val['milestoneId'];
					$val['id'] = $val['milestoneId'];
					unset($val['milestoneId']);
					$val['preMilestoneId'] = $changeIdsArr[$val['changePreId']];
					$this->edit_d($val);
				}
			}

			//删除已不存在的数据
			$ids = implode($idsArr,',');
			$conditionStr = "projectId = $projectId and id not in($ids)";
			$this->delete($conditionStr);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}

	}

	/**
	 * 重写编辑方法
	 */
	function edit_d($object){
		return parent::edit_d($object,true);
	}

	/**
	 *修改项目首个里程碑点的实际开始时间
	 *@param $object 项目信息
	 */
	function  editFirstStone_d($object){
	 	//获取首个里程碑信息
	 	$condition=array('projectId'=>$object['id'],'preMilestoneId'=>0);
	 	$row=$this->find($condition);
	 	if($row){
	 		$row['actBeginDate'] = $object['actBeginDate'];
	 		$row['status']='LCBZTB';//状态改为“执行中”
	 		$this->edit_d($row,true);
	 	}
	}

	/**
	 * 完成里程碑
	 *@param $projectId 项目ID
	 *@param $milestoneId 里程碑ID
	 *@param $endDate 完成时间
	 */
	function endStone_d($projectId,$milestoneId,$endDate){
		//修改已完成的里程碑
		$object = array(
			'id' => $milestoneId,
			'status' => 'LCBZTC',
			'actEndDate' => $endDate

		);
		$this->edit_d($object,true);

	 	//获取下一个里程碑信息,如果存在,启用,不存在则返回
	 	$condition=array('projectId'=>$projectId,'preMilestoneId'=>$milestoneId);
	 	$row = $this->find($condition,null,'id');
	 	if(is_array($row)){
	 		$row['actBeginDate']=$endDate;
	 		$row['status']='LCBZTB';
	 		$this->edit_d($row,true);
	 	}
	}

	/**
	 * 获取项目中的里程碑列表
	 */
	function getProjectMilestone_d($projectId){
		$this->searchArr = array('projectId'=>$projectId);
		$this->sort = 'c.planBeginDate';
		$this->asc = false;
		return $this->list_d();
	}
}
?>