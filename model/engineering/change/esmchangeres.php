<?php
/**
 * @author Show
 * @Date 2013年6月6日 星期四 15:38:39
 * @version 1.0
 * @description:项目资源计划变更表 Model层
 */
class model_engineering_change_esmchangeres extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_change_resources";
		$this->sql_map = "engineering/change/esmchangeresSql.php";
		parent :: __construct();
	}

	/**
	 * 添加预算
	 */
	function add_d($object,$esmresourcesDao = null) {
        try{
            $this->start_d();

            //判断是否已经存在变更申请
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($object['projectId']);

            //编辑任务内容
            if($object['orgId'] == -1){
                $object['orgId'] == 0;
            }
            $object['changeId'] = $changeId;
            $object['isChanging'] = '1';
            $object['changeAction'] = 'add';
            unset($object['activityId']);
            unset($object['activityName']);
            $newId = parent::add_d($object,true);

//            print_r($object);

            //编辑完后从新计算变更后项目内容
            $this->updateChange_d($object['projectId']);

//            $this->rollBack();
            $this->commit_d();
            return $newId;
        }catch(exception $e){
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
	}

    //变更原任务
    function edit_d($object,$esmresourcesDao = null){
        try{
            $this->start_d();

            //原任务状态更新
            $esmresourcesDao->changeInfoSet_d($object['id'],'edit');

            //判断是否已经存在变更申请
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($object['projectId']);

            //编辑任务内容
            $changObj = $this->getChangeInfo_d($object['id'],$changeId);
            $object['orgId'] = $object['id'];
            $object['id'] = $changObj['id'];
            $object['isChanging'] = '1';
            $object['changeAction'] = 'edit';
            parent::edit_d($object,true);

//            print_r($object);

            //编辑完后从新计算变更后项目内容
            $this->updateChange_d($object['projectId']);

//            $this->rollBack();
            $this->commit_d();
            return true;
        }catch(exception $e){
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    //变更原任务
    function editOrg_d($object,$esmresourcesDao = null){
        try{
            $this->start_d();

            parent::edit_d($object,true);

            //编辑完后从新计算变更后项目内容
            $this->updateChange_d($object['projectId']);

            $this->commit_d();
            return true;
        }catch(exception $e){
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

	/**
	 * @desription 删除树节点及下属节点
	 *
	 */
	function deletes_d($id,$esmresourcesDao = null,$projectId) {
		try {
			$this->start_d ();

			$idArr = explode(',',$id);

            //判断是否已经存在变更申请
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($projectId);

			//循环处理
			foreach($idArr as $val){
	            //原任务状态更新
	            $esmresourcesDao->changeInfoSet_d($val,'delete');

	            //编辑任务内容
	            $changObj = $this->getChangeInfo_d($val,$changeId);
	            $updateObj = array(
					'id' => $changObj['id'],'isChanging' => 1,'changeAction' => 'delete'
	            );
	            parent::edit_d($updateObj,true);
			}

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception ( $e->getMessage () );
			return false;
		}
	}

	/**
	 * 删除变更的内容
	 */
	function deletesChange_d($id,$esmresourcesDao = null,$projectId){
		try{
			$this->start_d ();

			//删除数据
			$this->deletes($id);

            //编辑完后从新计算变更后项目内容
            $this->updateChange_d($projectId);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception ( $e->getMessage () );
			return false;
		}
	}

    /**
     * 更新项目信息 - 统一调用方法
     * 数组务必包含 projectId
     * 数组务必包含 activityId
     */
    function updateChange_d($projectId){
    	$esmchangeDao = new model_engineering_change_esmchange();
		try{
			$this->start_d();

			//更新项目费用
			$updateArr = $this->getProjectBudgetOnce_d($projectId);
			$esmchangeDao->updateChangeBudget_d($updateArr);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
		}
    }

    /**
     * 获取变更数据
     */
	function getProjectBudgetOnce_d($projectId){
		$sql = "select max(c.changeId) as id,sum(c.amount) as newBudgetEqu
			 from
			(select c.id ,'' as changeId ,c.resourceId ,c.resourceCode ,c.resourceName ,
				c.resourceTypeId ,c.resourceTypeCode ,c.resourceTypeName ,c.number ,c.unit ,c.amount,
				c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,c.projectCode ,
				c.projectName ,c.activityId ,c.activityCode ,c.activityName ,c.workContent ,c.remark ,c.createId ,
				c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,'' as orgId ,'' as isChanging ,c.changeAction
			from
				oa_esm_project_resources c
			where c.isChanging = 0 and c.projectId = '$projectId'
			union all
			select concat('change',cast(id as char(20))) as id ,c.changeId ,c.resourceId ,c.resourceCode ,c.resourceName ,
				c.resourceTypeId ,c.resourceTypeCode ,c.resourceTypeName ,c.number ,c.unit ,c.amount,
				c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,c.projectCode ,
				c.projectName ,c.activityId ,c.activityCode ,c.activityName ,c.workContent ,c.remark ,c.createId ,
				c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.orgId ,c.isChanging ,c.changeAction
			from oa_esm_change_resources c
			where isChanging = 1 and c.projectId = '$projectId' and c.changeAction <> 'delete') c
			group by c.projectId";
		$rs = $this->_db->getArray($sql);
		if($rs[0]['newBudgetEqu'] === null){
			return array(
				'id' => 0,
				'newBudgetEqu' => 0
			);
		}else{
			return $rs[0];
		}
	}

    //获取变更的项目任务信息
    function getChangeInfo_d($orgId,$changeId){
		return $this->find(array('orgId' => $orgId ,'changeId' => $changeId));
    }

	/**
	 * 获取变更的费用信息
	 */
	function getChangeArr_d($changeactId,$isChanging = null){
		$condition = array('changeId' => $changeactId);
		if($isChanging !== null){
			$condition['isChanging'] = $isChanging ;
		}
		$rs = $this->findAll($condition);
		return $rs;
	}

    /**
     * 还原变更状态
     */
    function rollBackChangeInfo_d($changeId){
		return $this->update(array('changeId' => $changeId),array('isChanging' => 0));
    }

    /**
     * 创建变更记录信息
     */
    function createResources_d($arr,$changeId){
        try{
            $this->start_d();

            //循环新增
            foreach($arr as $key => $val){
				$val['changeId'] = $changeId;
				$val['orgId'] = $val['id'];
				unset($val['id']);

				parent::add_d($val,true);
            }

            $this->commit_d();
            return true;
        }catch(exception $e){
            throw $e;
            echo $e->getMessage();
            $this->rollBack();
            return false;
        }
    }
}
?>