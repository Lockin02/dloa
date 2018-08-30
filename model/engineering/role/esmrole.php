<?php
/**
 * @author Show
 * @Date 2012年7月13日 星期五 10:48:12
 * @version 1.0
 * @description:项目角色(oa_esm_project_role) Model层
 */
class model_engineering_role_esmrole extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_role";
		$this->sql_map = "engineering/role/esmroleSql.php";
		parent :: __construct();
	}

	/****************************获取外部信息方法***************************/

	/**
     * 获取项目信息
     */
    function getEsmprojectInfo_d($projectId){
    	$esmprojectDao = new model_engineering_project_esmproject();
    	return $esmprojectDao->find(array('id' => $projectId),null,'projectCode,projectName');
    }

    /**
     * 获取活动信息
     */
    function getActivityInfo_d($activityId){
		$esmactivityDao = new model_engineering_activity_esmactivity();
		return $esmactivityDao->find(array('id'=>$activityId),null,'activityName,planBeginDate,planEndDate,days,workContent,remark');
    }

    /**
     * 获取活动信息
     */
    function getActivityArr_d($projectId){
		$esmactivityDao = new model_engineering_activity_esmactivity();
		return $esmactivityDao->findAll(array('projectId'=>$projectId),'id ASC','id,activityName,planBeginDate,planEndDate,lft,rgt,days,workContent,remark');
    }

    /**
     * 获取项目信息
     */
    function getObjInfo_d($projectId){
    	$projectDao = new model_engineering_project_esmproject();
    	return $projectDao->get_d($projectId);
    }
    /******************************* 增删改查 *************************/
	/**
	 * 重写add_d 方法
	 */
	function add_d($object){
		try{
			$this->start_d();
			//新增方法
			$newId = parent::add_d($object,true);

			if($object['memberId']){
				//更新项目成员
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
	 * 重写add_d 方法
	 */
	function addRoleAndMember_d($object){
		try{
			$this->start_d();
			//新增方法
			$newId = parent::add_d($object,true);

			//添加一个项目成员
			$memberDao = new model_engineering_member_esmmember();
			//查询成员的人员等级信息
			$personnelDao = new model_hr_personnel_personnel();
			//处理多个项目经理
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
	 * @desription 编辑
	 */
	function edit_d($object) {
		try {
			$this->start_d ();

			parent::edit_d ( $object, true );

			if($object['memberId'] || $object['orgMemberId']){
				//更新项目成员
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
     * 批量删除树节点
     * @param $id
     * @return bool
     * @throws Exception
     */
    function deletes($id) {
		try {
			$this->start_d ();

			//添加一个项目成员
			$memberDao = new model_engineering_member_esmmember();

			//获取子节点
			$node=$this->get_d($id);
			$childNodes=$this->getChildrenByNode ( $node );
            if($childNodes){
                foreach($childNodes as $key=>$val){
                    //如果角色有人，则取消人员角色
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
            //如果角色有人，则取消人员角色
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

			//重新更新项目成员数
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
	 * 修改项目经理
	 */
	function changeManager_d($object){
		try {
			$this->start_d ();
			$orgManagerIdArr = explode(',',$object['orgManagerId']);
			$orgManagerNameArr = explode(',',$object['orgManagerName']);
			$managerIdArr = explode(',',$object['managerId']);
			$managerNameArr = explode(',',$object['managerName']);

			//实例化成员对象
			$esmmemberDao = new model_engineering_member_esmmember();

			//查询对应项目经理角色
			$managerRoleObj = $this->find(array('projectId' => $object['id'],'isManager' => 1));

			//更新显示的项目经理
			if($managerRoleObj){//如果没有存在角色，那么新增一份
				$this->update(array('id' => $managerRoleObj['id']),array('memberId' => $object['managerId'],'memberName' => $object['managerName']));
			}else{
				//初始化一份空角色
				$managerRoleObj = array(
					'roleName' => '项目经理','projectId' => $object['id'],'projectCode' => $object['projectCode'],
					'projectName' => $object['projectName'],'memberId' => $object['managerId'],
					'memberName' => $object['managerName'],'isManager' => 1,'isCanEdit' => 1,'parentId'=> -1
				);
				parent::add_d($managerRoleObj,true);
				$managerRoleObj = $this->find(array('projectId' => $object['id'],'isManager' => 1));
			}

			//删除的项目经理
			$delIdArr = array_diff($orgManagerIdArr,$managerIdArr);
			if($delIdArr){
				foreach($delIdArr as $val){
					if(empty($val)) continue;
					//判断是否已经存在项目中，存在则更新，不存在则新增
					$esmmemberObj = $esmmemberDao->find(array('projectId' => $object['id'],'memberId' => $val),null,'id');

					//取消角色关联
					if($esmmemberObj) $esmmemberDao->cancelRole_d($esmmemberObj['id']);
				}
			}

			//新增的项目经理
			$addIdArr = array_diff($managerIdArr,$orgManagerIdArr);
			if($addIdArr){
				$addNameArr = array_diff($managerNameArr,$orgManagerNameArr);
				foreach($addIdArr as $key => $val){
					if(empty($val)) continue;
					//判断是否已经存在项目中，存在则更新，不存在则新增
					$esmmemberObj = $esmmemberDao->find(array('projectId' => $object['id'],'memberId' => $val));
					//如果项目已经已经是项目组里的人，则抹掉相关角色信息
					$this->clearRoleMember_d($esmmemberObj['roleId'],$val);

					if($esmmemberObj){
						//重新建立角色关联
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
						//新建角色
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

    /******************************** 逻辑控制方法 *******************************/
    /**
     * 验证是否存在根节点，不存在则新增
     */
    function checkParent_d(){
    	$this->searchArr['id'] = -1;
    	$rs = $this->list_d('select_default');
		if(is_array($rs)){
			return true;
		}else{
			$this->create(array('id' => -1,'roleName' => '项目','lft'=> 1 , 'rgt' =>2));
			return false;
		}
    }

    /**
     * 抹掉人员角色信息
     */
    function clearRoleMember_d($id,$memberId){
		try {
			$this->start_d ();

			$obj = $this->get_d($id);

			//循环抹掉数据
			$memberIdArr = explode(',',$obj['memberId']);
			$memberNameArr = explode(',',$obj['memberName']);

			foreach($memberIdArr as $key => $val){
				if($val == $memberId){
					unset($memberIdArr[$key]);
					unset($memberNameArr[$key]);
					break;
				}
			}
			//项目成员为空，将固定投入比例置0
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

    //初始化项目角色已经成员
    function initProjectRoleAneMember_d(){
    	//获取没有生成项目角色的项目
		$sql = "select * from oa_esm_project where id not in (select projectId from oa_esm_project_role where isManager = 1)";
		$projectArr = $this->_db->getArray($sql);
		foreach($projectArr as $key => $val){
			$object = array(
				'roleName' => '项目经理',
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

	//导入Excel表
	function importExcel($roleArr, $projectId, $projectCode, $projectName) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();//结果数组
			$addArr = array();//正确信息数组

			foreach ( $roleArr as $key => $obj ) {
				$memberName = $obj['memberName'];//获取memberName
				$members = explode(',',$memberName);
				$memberIds = NULL;//存放正确的成员
				$memberError = NULL;//存放已存在成员
				$memberNameError = NULL;//存放不存在的memberName

				if(!empty($members)){
					foreach($members as $k => $val){
						$linkSql = "select USER_ID from user where USER_NAME = '$members[$k]'";
						$memberIdArr =  $this->_db->getArray($linkSql); //获取memberId
						$memberId = $memberIdArr[0]["USER_ID"];

						if(!empty($memberId)){  //判断USER表里是否存在成员ID
						$linkSql = "select id from oa_esm_project_member where projectId = '$projectId' and memberId = '$memberId' " ;
						$checkMemberId = $this->_db->getArray($linkSql);

							if(!empty($checkMemberId) ) {   //判断该项目是否存在该成员
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
				if($roleName == '项目经理'){ //判断是否为项目经理
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
					array_push ( $resultArr, array ("docCode" => $obj['roleName'], "result" => "导入成功！" ) );
				}else if(empty($obj['roleName'])){
					array_push ( $resultArr, array ("docCode" => $obj['roleName'], "result" => "失败！角色名称为空" ) );
				}else if(!empty($memberError)){
					array_push ( $resultArr, array ("docCode" => $obj['roleName'], "result" => "失败！'$memberError' 该成员在项目中已存在" ) );
				}else if(!empty($memberNameError)){
					array_push ( $resultArr, array ("docCode" => $obj['roleName'], "result" => "失败！不存在 '$memberNameError' 该成员" ) );
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
	 * 获取去除的有加入项目时间的项目成员id
	 */
	function getLeaveMember_d($object){
		$leaveMember = array_diff(explode(",",$object['orgMemberId']),explode(",", $object['memberId']));//识别离开人员
		$memberDao = new model_engineering_member_esmmember();
		$esmworklogDao = new model_engineering_worklog_esmworklog();
	    $esmentryDao = new model_engineering_member_esmentry();
		$idArr = array();
		foreach ($leaveMember as $val){
			if($esmworklogDao->checkExistLogUsers_d($val, $object['projectId'])){//判断人员是否已经填写日志
				if($esmentryDao->checkExistRangeLog_d($val, $object['projectId'])){
					$memberObj = $memberDao->find(array('projectId' => $object['projectId'],'memberId' =>$val));
					array_push($idArr, $memberObj['id']);
				}
			}
		}
		return $idArr;
	}

	private $childrenArr = array();//缓存全部的根节点

	/**
	 * 获取下级节点
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
	 * 验证项目角色是否可编辑固定投入比例
	 * 是返回0，否返回1
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
	 * 返回可填最大固定投入比例
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