<?php
/**
 * @author Administrator
 * @Date 2012年7月9日 星期一 14:15:37
 * @version 1.0
 * @description:职位说明书 Model层
 */
 class model_hr_position_positiondescript  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_position_description";
		$this->sql_map = "hr/position/positiondescriptSql.php";
		parent::__construct ();
	}

	/**
	 * 添加职位说明书信息
	 */
	 function add_d($object){
	 	try{
			$this->start_d();
			$dictDao = new model_system_datadict_datadict();
			$object['rewardGrade'] = $dictDao->getDataNameByCode($object['rewardGradeCode']);
			$object['education'] = $dictDao->getDataNameByCode($object['education']);
			$jobsDao=new model_system_organizer_jobs();
			$jobobject = $jobsDao->db->get_one("select * from user_jobs where name = '".$object['positionName']."' and dept_id = ".$object['deptId']);
			if($jobobject)
				$flag = true;
			else
				$flag = false;
			//var_dump($jobobject);
			$object['formCode'] = date ( "YmdHis" );
		 	if(!is_array($object['ability'])){
				msg ( '请填写好能力及个性要求的信息！' );
				throw new Exception('职位说明书信息不完整，保存失败！');
			}else if(!is_array($object['work'])){
				msg ( '请填写好工作职责的信息！' );
				throw new Exception('职位说明书信息不完整，保存失败！');
			}
			$id=parent::add_d($object,true);
			$abilityDao=new model_hr_position_ability();
			$workDao=new model_hr_position_work();
			if(is_array($object['ability'])){
				foreach ($object['ability'] as $value) {
					$value['parentId'] = $id;
					$value['parentCode'] = $object['formCode'];
					$value['positionName'] = $object['positionName'];
					$abilityDao->add_d($value);
				}
			}
			if(is_array($object['work'])){
				foreach ($object['work'] as $value) {
					$value['parentId'] = $id;
					$value['parentCode'] = $object['formCode'];
					$value['positionName'] = $object['positionName'];
					$workDao->add_d($value);
				}
			}
			if(!$flag){
					$jobsDao=new model_deptuser_jobs_jobs();
					$jobArr['name']=$object['positionName'];
					$jobArr['dept_id']=$object['deptId'];
					$jobsId=$jobsDao->add_d($jobArr);
			}else{
				$jobsId=$jobobject['id'];
			}
			$this->commit_d();
			return $jobsId;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
 	}

	/**
	 * 删除数据
	 * @see model_base::deletes_d()
	 */
	function deletes_d($ids) {
		try {
			$abilityDao=new model_hr_position_ability();
			$workDao=new model_hr_position_work();
			$abilityDao->delete(array('parentId'=>$ids));
			$workDao->delete(array('parentId'=>$ids));
			$this->deletes ( $ids );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}


	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			$dictDao = new model_system_datadict_datadict();
			$object['rewardGrade'] = $dictDao->getDataNameByCode($object['rewardGradeCode']);
			$object['education'] = $dictDao->getDataNameByCode($object['education']);
			$jobsDao=new model_system_organizer_jobs();
			//echo "select * from user_jobs where name = '".$object['name']."' and dept_id = ".$object['dept_id'];
			$jobobject = $jobsDao->db->get_one("select * from user_jobs where name = '".$object['name']."' and dept_id = ".$object['deptId']);
			if($jobobject)
				$flag = true;
			else
				$flag = false;
			//var_dump($jobobject);

			//var_dump(is_array ( $object ['ability'])&&is_array ( $object ['work'] ));
			if (is_array ( $object ['work'] ) && is_array ( $object ['ability'] )){
				$id = parent::edit_d ( $object, true );
				$abilityDao=new model_hr_position_ability();
				$workDao=new model_hr_position_work();
				$mainArr=array(
					"parentId"=>$object ['id'],
					"parentCode"=> $object['positionCode'],
					"positionName"=> $object['positionName']
				);
				$abilityArr=util_arrayUtil::setArrayFn($mainArr,$object ['ability']);
				$workArr=util_arrayUtil::setArrayFn($mainArr,$object ['work']);
				//var_dump($abilityArr);var_dump($workArr);
				$abilityDao->saveDelBatch ( $abilityArr );
				$workDao->saveDelBatch ( $workArr );
				if($flag){
					//echo "update user_jobs set name ='".$object['positionName']."',dept_id=".$object['deptCode']." where name = '".$object['name']."' and dept_id = ".$object['dept_id'];
					$jobsDao->db->query("update user_jobs set name ='".$object['positionName']."',dept_id=".$object['deptId']." where name = '".$object['name']."' and dept_id = ".$object['dept_id']);
				}
				else {
					//echo "insert into user_jobs(name,dept_id)  VALUES('".$object['positionName']."',".$object['deptCode'].")";
					$jobsDao->db->query("insert into user_jobs(name,dept_id)  VALUES('".$object['positionName']."',".$object['deptId'].")");
				}
				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ("职位说明书信息不全，请确认！");
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
 	
 }
?>