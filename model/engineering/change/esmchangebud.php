<?php
/**
 * @author Show
 * @Date 2012年12月15日 星期六 15:21:23
 * @version 1.0
 * @description:项目费用预算变更表 Model层
 */
class model_engineering_change_esmchangebud extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_change_budget";
		$this->sql_map = "engineering/change/esmchangebudSql.php";
		parent :: __construct();
	}

	/**
	 * 添加预算
	 */
	function add_d($object,$esmbudgetDao = null) {
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
            $newId = parent::add_d($object,true);

            //编辑完后从新计算变更后项目内容
            $this->updateChange_d($object['projectId']);

            $this->commit_d();
            return $newId;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
	}

    //变更原任务
    function edit_d($object,$esmbudgetDao = null){
        try{
            $this->start_d();

            //原任务状态更新
            $esmbudgetDao->changeInfoSet_d($object['id'],'edit');

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

            //编辑完后从新计算变更后项目内容
            $this->updateChange_d($object['projectId']);

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
    }

    //变更原任务
    function editOrg_d($object){
        try{
            $this->start_d();

            parent::edit_d($object,true);

            //编辑完后从新计算变更后项目内容
            $this->updateChange_d($object['projectId']);

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
    }

	/**
	 * @desription 删除树节点及下属节点
	 *
	 */
	function deletes_d($id,$esmbudgetDao,$projectId,$changeId) {
		//判断是否第一次变更,第一次变更不会有changeId
		$isChanging = $changeId ? true : false;
		try {
			$this->start_d ();

			$idArr = explode(',',$id);

            //判断是否已经存在变更申请
            if(!$changeId){
	            $esmchangeDao = new model_engineering_change_esmchange();
	            $changeId = $esmchangeDao->getChangeId_d($projectId);
            }

			//循环处理
			if($isChanging){//如果是已经存在变更申请,就根据变更id查找
				foreach($idArr as $v){
					$obj = $this->find(array('id' => $v,'changeId' => $changeId),null,'orgId');
					if($obj['orgId']){
			            //原任务状态更新
			            $esmbudgetDao->changeInfoSet_d($obj['orgId'],'delete');

			            //编辑任务内容
			            $updateObj = array(
							'id' => $v,'isChanging' => 1,'changeAction' => 'delete'
			            );
			            parent::edit_d($updateObj,true);
					}else{
						//直接删除
						$this->deletes($id);
					}
				}
			}else{//如果是第一次变更,就根据原id查找
				foreach($idArr as $v){
					$obj = $this->find(array('orgId' => $v,'changeId' => $changeId),null,'id');
		            //原任务状态更新
		            $esmbudgetDao->changeInfoSet_d($v,'delete');
		            //编辑任务内容
		            $updateObj = array(
						'id' => $obj['id'],'isChanging' => 1,'changeAction' => 'delete'
		            );
		            parent::edit_d($updateObj,true);
				}
			}

            //编辑完后从新计算变更后项目内容
            $this->updateChange_d($projectId);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}
	}

    //获取变更的项目任务信息
    function getChangeInfo_d($orgId,$changeId){
		return $this->find(array('orgId' => $orgId ,'changeId' => $changeId));
    }

	/**
	 * 获取变更的费用信息
	 */
	function getChangeBudget_d($changeactId,$isChanging = null){
		$condition = array('changeId' => $changeactId);
		if($isChanging !== null){
			$condition['isChanging'] = $isChanging ;
		}
		return $this->findAll($condition);
	}

    //创建原始任务记录
    function createBudget_d($arr,$changeId){
        try{
            $this->start_d();

            //循环新增
            foreach($arr as $val){
				$val['changeId'] = $changeId;
				$val['orgId'] = $val['id'];
				unset($val['id']);

				parent::add_d($val,true);
            }

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 获取变更数据
     */
	function getProjectBudgetOnce_d($projectId,$changeId){
		$sql = "select $changeId as id,sum(if(c.budgetType = 'budgetField',c.amount,0)) as newBudgetField,
				sum(if(c.budgetType = 'budgetOutsourcing',c.amount,0)) as newBudgetOutsourcing,
				sum(if(c.budgetType = 'budgetPerson',c.amount,0)) as newBudgetPerson,
				sum(if(c.budgetType = 'budgetPerson',c.budgetDay,0)) as newBudgetDay,
				sum(if(c.budgetType = 'budgetPerson',c.budgetPeople,0)) as newBudgetPeople,
				sum(if(c.budgetType = 'budgetOther',c.amount,0)) as newBudgetOther
			 from
				oa_esm_change_budget c
			where c.projectId = '$projectId' and c.changeId = '$changeId' and c.changeAction <> 'delete' group by c.projectId";
		$rs = $this->_db->getArray($sql);
		if($rs[0]['newBudgetField'] === null){
			return array(
				'id' => $changeId,
				'newBudgetField' => 0,
				'newBudgetOutsourcing' => 0,
				'newBudgetPerson' => 0,
				'newBudgetDay' => 0,
				'newBudgetPeople' => 0,
				'newBudgetOther' => 0
			);
		}else{
			return $rs[0];
		}
	}

    /**
     * 更新项目信息 - 统一调用方法
     * 数组务必包含 projectId
     */
    function updateChange_d($projectId){
    	$esmchangeDao = new model_engineering_change_esmchange();
		try{
			$this->start_d();

            //获取changeId
            $changeId = $esmchangeDao->getChangeId_d($projectId,false);
			//更新项目费用
			$updateArr = $this->getProjectBudgetOnce_d($projectId,$changeId);

			$esmchangeDao->updateChangeBudget_d($updateArr);

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
		}
    }

    /**
     * 还原变更状态
     */
    function rollBackChangeInfo_d($changeId){
		return $this->update(array('changeId' => $changeId),array('isChanging' => 0));
    }

    /**
     * 判断数据是否处于变更状态
     */
    function budgetIsChanging_d($id){
		return $this->find(array('id' => $id,'ischanging' => 1));
    }
}