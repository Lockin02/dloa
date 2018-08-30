<?php
/**
 * @author Show
 * @Date 2012��7��13�� ������ 10:48:12
 * @version 1.0
 * @description:��Ŀ��ɫ(oa_esm_project_role) Model��
 */
class model_engineering_role_esmrole extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_role";
		$this->sql_map = "engineering/role/esmroleSql.php";
		parent :: __construct();
	}

	/****************************��ȡ�ⲿ��Ϣ����***************************/

	/**
     * ��ȡ��Ŀ��Ϣ
     */
    function getEsmprojectInfo_d($projectId){
    	$esmprojectDao = new model_engineering_project_esmproject();
    	return $esmprojectDao->find(array('id' => $projectId),null,'projectCode,projectName');
    }

    /**
     * ��ȡ���Ϣ
     */
    function getActivityInfo_d($activityId){
		$esmactivityDao = new model_engineering_activity_esmactivity();
		return $esmactivityDao->find(array('id'=>$activityId),null,'activityName,planBeginDate,planEndDate,days,workContent,remark');
    }

    /**
     * ��ȡ���Ϣ
     */
    function getActivityArr_d($projectId){
		$esmactivityDao = new model_engineering_activity_esmactivity();
		return $esmactivityDao->findAll(array('projectId'=>$projectId),'id ASC','id,activityName,planBeginDate,planEndDate,lft,rgt,days,workContent,remark');
    }

    /**
     * ��ȡ��Ŀ��Ϣ
     */
    function getObjInfo_d($projectId){
    	$projectDao = new model_engineering_project_esmproject();
    	return $projectDao->get_d($projectId);
    }
    /******************************* ��ɾ�Ĳ� *************************/
	/**
	 * ��дadd_d ����
	 */
	function add_d($object){
		try{
			$this->start_d();
			//��������
			$newId = parent::add_d($object,true);

			if($object['memberId']){
				//������Ŀ��Ա
				$memberDao = new model_engineering_member_esmmember();
				$managerArr = array(
					'projectCode' => $object['projectCode'],
					'projectName' => $object['projectName'],
					'projectId' => $object['projectId'],
					'memberId' => $object['memberId'],
					'memberName' => $object['memberName'],
					'roleName' => $object['roleName'],
					'roleId' => $newId,
					'isManager' => '0',
					'isCanEdit' => '0'
				);
				$memberDao->updateMember_d($managerArr,$this);
			}

			$this->commit_d();
			return $newId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��дadd_d ����
	 */
	function addRoleAndMember_d($object){
		try{
			$this->start_d();
			//��������
			$newId = parent::add_d($object,true);

			//���һ����Ŀ��Ա
			$memberDao = new model_engineering_member_esmmember();
			//��ѯ��Ա����Ա�ȼ���Ϣ
			$personnelDao = new model_hr_personnel_personnel();
			//��������Ŀ����
			$memberIdArr = explode(',',$object['memberId']);
			$memberNameArr = explode(',',$object['memberName']);
			foreach($memberIdArr as $key => $val){
				if(empty($val)){
					continue;
				}
				$managerArr = array(
					'projectCode' => $object['projectCode'],
					'projectName' => $object['projectName'],
					'projectId' => $object['projectId'],
					'memberId' => $val,
					'memberName' => $memberNameArr[$key],
					'roleName' => $object['roleName'],
					'roleId' => $newId,
					'isManager' => '1',
					'isCanEdit' => '1'
				);
				$personnelInfo = $personnelDao->getPersonnelAndLevel_d($val);
//				if($personnelInfo){
					$managerArr['personLevel'] = $personnelInfo['personLevel'];
					$managerArr['personLevelId'] = $personnelInfo['personLevelId'];
					$managerArr['price'] = $personnelInfo['price'];
					$managerArr['coefficient'] = $personnelInfo['coefficient'];
//				}
				$memberDao->addAlong_d($managerArr);
			}

			$this->commit_d();
			return $newId;
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * @desription �༭
	 */
	function edit_d($object) {
		try {
			$this->start_d ();

			parent::edit_d ( $object, true );

			if($object['memberId'] || $object['orgMemberId']){
				//������Ŀ��Ա
				$memberDao = new model_engineering_member_esmmember();
				$managerArr = array(
					'projectCode' => $object['projectCode'],
					'projectName' => $object['projectName'],
					'projectId' => $object['projectId'],
					'memberId' => $object['memberId'],
					'memberName' => $object['memberName'],
					'orgMemberId' => $object['orgMemberId'],
					'orgMemberName' => $object['orgMemberName'],
					'roleName' => $object['roleName'],
					'roleId' => $object['id'],
					'fixedRate' => $object['fixedRate'],
					'isManager' => '0',
					'isCanEdit' => '0'
				);
				$memberDao->updateMember_d($managerArr,$this);
			}

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}
	}

    /**
     * ����ɾ�����ڵ�
     * @param $id
     * @return bool
     * @throws Exception
     */
    function deletes($id) {
		try {
			$this->start_d ();

			//���һ����Ŀ��Ա
			$memberDao = new model_engineering_member_esmmember();

			//��ȡ�ӽڵ�
			$node=$this->get_d($id);
			$childNodes=$this->getChildrenByNode ( $node );
            if($childNodes){
                foreach($childNodes as $key=>$val){
                    //�����ɫ���ˣ���ȡ����Ա��ɫ
                    if($val['memberId']){
						$managerArr = array(
							'projectId' => $val['projectId'],
							'memberId' => '',
							'memberName' => '',
							'orgMemberId' => $val['memberId']
						);
						$memberDao->updateMember_d($managerArr,$this);
                    }
                    parent::deletes ($val['id']);
                }
            }
            //�����ɫ���ˣ���ȡ����Ա��ɫ
            if($node['memberId']){
				$managerArr = array(
					'projectId' => $node['projectId'],
					'memberId' => '',
					'memberName' => '',
					'orgMemberId' => $node['memberId']
				);
				$memberDao->updateMember_d($managerArr,$this);
            }
			parent::deletes ( $id );

			//���¸�����Ŀ��Ա��
			$esmmemberDao = new model_engineering_member_esmmember();
			$esmmemberDao->updateProject_d($node['projectId']);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}
	}

	/**
	 * �޸���Ŀ����
	 */
	function changeManager_d($object){
		try {
			$this->start_d ();
			$orgManagerIdArr = explode(',',$object['orgManagerId']);
			$orgManagerNameArr = explode(',',$object['orgManagerName']);
			$managerIdArr = explode(',',$object['managerId']);
			$managerNameArr = explode(',',$object['managerName']);

			//ʵ������Ա����
			$esmmemberDao = new model_engineering_member_esmmember();

			//��ѯ��Ӧ��Ŀ�����ɫ
			$managerRoleObj = $this->find(array('projectId' => $object['id'],'isManager' => 1));

			//������ʾ����Ŀ����
			if($managerRoleObj){//���û�д��ڽ�ɫ����ô����һ��
				$this->update(array('id' => $managerRoleObj['id']),array('memberId' => $object['managerId'],'memberName' => $object['managerName']));
			}else{
				//��ʼ��һ�ݿս�ɫ
				$managerRoleObj = array(
					'roleName' => '��Ŀ����','projectId' => $object['id'],'projectCode' => $object['projectCode'],
					'projectName' => $object['projectName'],'memberId' => $object['managerId'],
					'memberName' => $object['managerName'],'isManager' => 1,'isCanEdit' => 1,'parentId'=> -1
				);
				parent::add_d($managerRoleObj,true);
				$managerRoleObj = $this->find(array('projectId' => $object['id'],'isManager' => 1));
			}

			//ɾ������Ŀ����
			$delIdArr = array_diff($orgManagerIdArr,$managerIdArr);
			if($delIdArr){
				foreach($delIdArr as $val){
					if(empty($val)) continue;
					//�ж��Ƿ��Ѿ�������Ŀ�У���������£�������������
					$esmmemberObj = $esmmemberDao->find(array('projectId' => $object['id'],'memberId' => $val),null,'id');

					//ȡ����ɫ����
					if($esmmemberObj) $esmmemberDao->cancelRole_d($esmmemberObj['id']);
				}
			}

			//��������Ŀ����
			$addIdArr = array_diff($managerIdArr,$orgManagerIdArr);
			if($addIdArr){
				$addNameArr = array_diff($managerNameArr,$orgManagerNameArr);
				foreach($addIdArr as $key => $val){
					if(empty($val)) continue;
					//�ж��Ƿ��Ѿ�������Ŀ�У���������£�������������
					$esmmemberObj = $esmmemberDao->find(array('projectId' => $object['id'],'memberId' => $val));
					//�����Ŀ�Ѿ��Ѿ�����Ŀ������ˣ���Ĩ����ؽ�ɫ��Ϣ
					$this->clearRoleMember_d($esmmemberObj['roleId'],$val);

					if($esmmemberObj){
						//���½�����ɫ����
						$newManagerArr = array(
							'id' => $esmmemberObj['id'],
							'roleName' => $managerRoleObj['roleName'],
							'roleId' => $managerRoleObj['id'],
							'isManager' => '1',
							'isCanEdit' => '1',
							'status' => 0
						);
						$esmmemberDao->edit_d($newManagerArr);
					}else{
						//�½���ɫ
						$newManagerArr = array(
							'projectCode' => $object['projectCode'],
							'projectName' => $object['projectName'],
							'projectId' => $object['id'],
							'memberId' => $val,
							'memberName' => $addNameArr[$key],
							'roleName' => $managerRoleObj['roleName'],
							'roleId' => $managerRoleObj['id'],
							'isManager' => '1',
							'isCanEdit' => '1'
						);
						$esmmemberDao->add_d($newManagerArr);
					}
				}
			}

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}
	}

    /******************************** �߼����Ʒ��� *******************************/
    /**
     * ��֤�Ƿ���ڸ��ڵ㣬������������
     */
    function checkParent_d(){
    	$this->searchArr['id'] = -1;
    	$rs = $this->list_d('select_default');
		if(is_array($rs)){
			return true;
		}else{
			$this->create(array('id' => -1,'roleName' => '��Ŀ','lft'=> 1 , 'rgt' =>2));
			return false;
		}
    }

    /**
     * Ĩ����Ա��ɫ��Ϣ
     */
    function clearRoleMember_d($id,$memberId){
		try {
			$this->start_d ();

			$obj = $this->get_d($id);

			//ѭ��Ĩ������
			$memberIdArr = explode(',',$obj['memberId']);
			$memberNameArr = explode(',',$obj['memberName']);

			foreach($memberIdArr as $key => $val){
				if($val == $memberId){
					unset($memberIdArr[$key]);
					unset($memberNameArr[$key]);
					break;
				}
			}
			//��Ŀ��ԱΪ�գ����̶�Ͷ�������0
			if(empty($memberIdArr)){
				$this->update(array('id' => $id),array('memberId' => implode(',',$memberIdArr),'memberName' => implode(',',$memberNameArr),'fixedRate' => 0));
			}else{
				$this->update(array('id' => $id),array('memberId' => implode(',',$memberIdArr),'memberName' => implode(',',$memberNameArr)));
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
		}
    }

    //��ʼ����Ŀ��ɫ�Ѿ���Ա
    function initProjectRoleAneMember_d(){
    	//��ȡû��������Ŀ��ɫ����Ŀ
		$sql = "select * from oa_esm_project where id not in (select projectId from oa_esm_project_role where isManager = 1)";
		$projectArr = $this->_db->getArray($sql);
		foreach($projectArr as $key => $val){
			$object = array(
				'roleName' => '��Ŀ����',
				'projectId' => $val['id'],
				'projectCode' => $val['projectCode'],
				'projectName' => $val['projectName'],
				'memberId' => $val['managerId'],
				'memberName' => $val['managerName'],
				'isManager' => 1,
				'isCanEdit' => 1,
				'parentId'=> -1
			);
			$this->add_d($object);
		}
		$sql = "INSERT into oa_esm_project_member(projectId,projectCode,projectName,memberName,memberId,isManager,isCanEdit) select id,projectCode,projectName,managerName,managerId,1,1 from oa_esm_project";
		$this->_db->query($sql);

		$sql = "update oa_esm_project_member c INNER JOIN oa_esm_project_role r on c.projectId = r.projectId set c.roleId = r.id,c.roleName = r.roleName where c.roleId is null and c.isManager = 1 and r.isManager";
		$this->_db->query($sql);

		return true;
    }

	//����Excel��
	function importExcel($roleArr, $projectId, $projectCode, $projectName) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();//�������
			$addArr = array();//��ȷ��Ϣ����

			foreach ( $roleArr as $key => $obj ) {
				$memberName = $obj['memberName'];//��ȡmemberName
				$members = explode(',',$memberName);
				$memberIds = NULL;//�����ȷ�ĳ�Ա
				$memberError = NULL;//����Ѵ��ڳ�Ա
				$memberNameError = NULL;//��Ų����ڵ�memberName

				if(!empty($members)){
					foreach($members as $k => $val){
						$linkSql = "select USER_ID from user where USER_NAME = '$members[$k]'";
						$memberIdArr =  $this->_db->getArray($linkSql); //��ȡmemberId
						$memberId = $memberIdArr[0]["USER_ID"];

						if(!empty($memberId)){  //�ж�USER�����Ƿ���ڳ�ԱID
						$linkSql = "select id from oa_esm_project_member where projectId = '$projectId' and memberId = '$memberId' " ;
						$checkMemberId = $this->_db->getArray($linkSql);

							if(!empty($checkMemberId) ) {   //�жϸ���Ŀ�Ƿ���ڸó�Ա
								if(empty($memberError))
								$memberError = $members[$k];
								else
								$memberError .= (",".$members[$k]);
							}
							else {
								if(empty($memberIds))
								$memberIds = $memberId;
								else
								$memberIds .= (",".$memberId);
							}
						}else{
							if(empty($memberNameError))
							$memberNameError = $members[$k];
							else
							$memberNameError .= (",".$members[$k]);
						}
					}
				}
				$roleName = $obj['roleName'];
				if($roleName == '��Ŀ����'){ //�ж��Ƿ�Ϊ��Ŀ����
					$isManager = 1;
				}
				else {
					$isManager = 0;
				}
				if(!empty($obj['roleName']) && empty($memberError) && empty($memberNameError)){
					$addArr[$key]['roleName'] = $obj['roleName'];
					$addArr[$key]['memberName'] = $obj['memberName'];
					$addArr[$key]['jobDescription'] = $obj['jobDescription'];
					$addArr[$key]['activityName'] = $obj['activityName'];
					$addArr[$key]['memberId'] = $memberIds;
					$addArr[$key]['projectId'] = $projectId;
					$addArr[$key]['projectCode'] = $projectCode;
					$addArr[$key]['projectName'] = $projectName;
					$addArr[$key]['parentId'] = -1;
					$addArr[$key]['isManager'] = $isManager;

					$this->add_d($addArr[$key]);
					array_push ( $resultArr, array ("docCode" => $obj['roleName'], "result" => "����ɹ���" ) );
				}else if(empty($obj['roleName'])){
					array_push ( $resultArr, array ("docCode" => $obj['roleName'], "result" => "ʧ�ܣ���ɫ����Ϊ��" ) );
				}else if(!empty($memberError)){
					array_push ( $resultArr, array ("docCode" => $obj['roleName'], "result" => "ʧ�ܣ�'$memberError' �ó�Ա����Ŀ���Ѵ���" ) );
				}else if(!empty($memberNameError)){
					array_push ( $resultArr, array ("docCode" => $obj['roleName'], "result" => "ʧ�ܣ������� '$memberNameError' �ó�Ա" ) );
				}
			}
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * ��ȡȥ�����м�����Ŀʱ�����Ŀ��Աid
	 */
	function getLeaveMember_d($object){
		$leaveMember = array_diff(explode(",",$object['orgMemberId']),explode(",", $object['memberId']));//ʶ���뿪��Ա
		$memberDao = new model_engineering_member_esmmember();
		$esmworklogDao = new model_engineering_worklog_esmworklog();
	    $esmentryDao = new model_engineering_member_esmentry();
		$idArr = array();
		foreach ($leaveMember as $val){
			if($esmworklogDao->checkExistLogUsers_d($val, $object['projectId'])){//�ж���Ա�Ƿ��Ѿ���д��־
				if($esmentryDao->checkExistRangeLog_d($val, $object['projectId'])){
					$memberObj = $memberDao->find(array('projectId' => $object['projectId'],'memberId' =>$val));
					array_push($idArr, $memberObj['id']);
				}
			}
		}
		return $idArr;
	}

	private $childrenArr = array();//����ȫ���ĸ��ڵ�

	/**
	 * ��ȡ�¼��ڵ�
	 */
	function getChildrenByNode($node){
		$sql = "SELECT * FROM `oa_esm_project_role` WHERE projectId = '".$node['projectId']."' AND parentId = '".$node['id']."'";
		$nodes = $this->_db->getArray ( $sql );
		if($nodes){
			foreach($nodes as $v){
				array_push($this->childrenArr,$v);
				$this->getChildrenByNode($v);
			}
			return $this->childrenArr;
		}
	}
	/**
	 * ��֤��Ŀ��ɫ�Ƿ�ɱ༭�̶�Ͷ�����
	 * �Ƿ���0���񷵻�1
	 */
	function isEditFixedRate_d($id){
		$sql = "
				SELECT
					p.isWithoutLog
				FROM
					oa_esm_project_role r
				LEFT JOIN oa_esm_project p ON r.projectId = p.id
				WHERE
					r.id = '".$id."'
				";
		$rs = $this->findSql($sql);
		return $rs[0]['isWithoutLog'];
	}
	/**
	 * ���ؿ������̶�Ͷ�����
	 */
	function getMaxFixedRate_d($projectId,$memberIdStr){
		$sql = "
				SELECT
					(100 - SUM(m.fixedRate)) AS minFixedRate
				FROM
					oa_esm_project_member m
				LEFT JOIN (
					SELECT
						id
					FROM
						oa_esm_project
					WHERE
						STATUS <> 'GCXMZT00'
					AND STATUS <> 'GCXMZT03'
				) AS p ON p.id = m.projectId
				WHERE
					m.memberId in (".$memberIdStr.")
				AND m.projectId <> '".$projectId."'
				AND m.projectId = p.id
				GROUP BY
					m.memberId
				ORDER BY
					minFixedRate
				";
		$rs = $this->findSql($sql);
		return $rs[0]['minFixedRate'];
	}
}