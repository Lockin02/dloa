<?php
/**
 * @author Show
 * @Date 2012��12��15�� ������ 15:21:23
 * @version 1.0
 * @description:��Ŀ����Ԥ������ Model��
 */
class model_engineering_change_esmchangebud extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_change_budget";
		$this->sql_map = "engineering/change/esmchangebudSql.php";
		parent :: __construct();
	}

	/**
	 * ���Ԥ��
	 */
	function add_d($object,$esmbudgetDao = null) {
        try{
            $this->start_d();

            //�ж��Ƿ��Ѿ����ڱ������
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($object['projectId']);

            //�༭��������
            if($object['orgId'] == -1){
                $object['orgId'] == 0;
            }
            $object['changeId'] = $changeId;
            $object['isChanging'] = '1';
            $object['changeAction'] = 'add';
            $newId = parent::add_d($object,true);

            //�༭�����¼���������Ŀ����
            $this->updateChange_d($object['projectId']);

            $this->commit_d();
            return $newId;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
	}

    //���ԭ����
    function edit_d($object,$esmbudgetDao = null){
        try{
            $this->start_d();

            //ԭ����״̬����
            $esmbudgetDao->changeInfoSet_d($object['id'],'edit');

            //�ж��Ƿ��Ѿ����ڱ������
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($object['projectId']);

            //�༭��������
            $changObj = $this->getChangeInfo_d($object['id'],$changeId);
            $object['orgId'] = $object['id'];
            $object['id'] = $changObj['id'];
            $object['isChanging'] = '1';
            $object['changeAction'] = 'edit';
            parent::edit_d($object,true);

            //�༭�����¼���������Ŀ����
            $this->updateChange_d($object['projectId']);

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
    }

    //���ԭ����
    function editOrg_d($object){
        try{
            $this->start_d();

            parent::edit_d($object,true);

            //�༭�����¼���������Ŀ����
            $this->updateChange_d($object['projectId']);

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
    }

	/**
	 * @desription ɾ�����ڵ㼰�����ڵ�
	 *
	 */
	function deletes_d($id,$esmbudgetDao,$projectId,$changeId) {
		//�ж��Ƿ��һ�α��,��һ�α��������changeId
		$isChanging = $changeId ? true : false;
		try {
			$this->start_d ();

			$idArr = explode(',',$id);

            //�ж��Ƿ��Ѿ����ڱ������
            if(!$changeId){
	            $esmchangeDao = new model_engineering_change_esmchange();
	            $changeId = $esmchangeDao->getChangeId_d($projectId);
            }

			//ѭ������
			if($isChanging){//������Ѿ����ڱ������,�͸��ݱ��id����
				foreach($idArr as $v){
					$obj = $this->find(array('id' => $v,'changeId' => $changeId),null,'orgId');
					if($obj['orgId']){
			            //ԭ����״̬����
			            $esmbudgetDao->changeInfoSet_d($obj['orgId'],'delete');

			            //�༭��������
			            $updateObj = array(
							'id' => $v,'isChanging' => 1,'changeAction' => 'delete'
			            );
			            parent::edit_d($updateObj,true);
					}else{
						//ֱ��ɾ��
						$this->deletes($id);
					}
				}
			}else{//����ǵ�һ�α��,�͸���ԭid����
				foreach($idArr as $v){
					$obj = $this->find(array('orgId' => $v,'changeId' => $changeId),null,'id');
		            //ԭ����״̬����
		            $esmbudgetDao->changeInfoSet_d($v,'delete');
		            //�༭��������
		            $updateObj = array(
						'id' => $obj['id'],'isChanging' => 1,'changeAction' => 'delete'
		            );
		            parent::edit_d($updateObj,true);
				}
			}

            //�༭�����¼���������Ŀ����
            $this->updateChange_d($projectId);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}
	}

    //��ȡ�������Ŀ������Ϣ
    function getChangeInfo_d($orgId,$changeId){
		return $this->find(array('orgId' => $orgId ,'changeId' => $changeId));
    }

	/**
	 * ��ȡ����ķ�����Ϣ
	 */
	function getChangeBudget_d($changeactId,$isChanging = null){
		$condition = array('changeId' => $changeactId);
		if($isChanging !== null){
			$condition['isChanging'] = $isChanging ;
		}
		return $this->findAll($condition);
	}

    //����ԭʼ�����¼
    function createBudget_d($arr,$changeId){
        try{
            $this->start_d();

            //ѭ������
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
     * ��ȡ�������
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
     * ������Ŀ��Ϣ - ͳһ���÷���
     * ������ذ��� projectId
     */
    function updateChange_d($projectId){
    	$esmchangeDao = new model_engineering_change_esmchange();
		try{
			$this->start_d();

            //��ȡchangeId
            $changeId = $esmchangeDao->getChangeId_d($projectId,false);
			//������Ŀ����
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
     * ��ԭ���״̬
     */
    function rollBackChangeInfo_d($changeId){
		return $this->update(array('changeId' => $changeId),array('isChanging' => 0));
    }

    /**
     * �ж������Ƿ��ڱ��״̬
     */
    function budgetIsChanging_d($id){
		return $this->find(array('id' => $id,'ischanging' => 1));
    }
}