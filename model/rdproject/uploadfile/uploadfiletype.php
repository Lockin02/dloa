<?php
/**
 * model层类
 * @author chengl
 *
 */
class model_rdproject_uploadfile_uploadfiletype extends model_base {
	function __construct() {
		$this->tbl_name = "oa_rd_project_uploadfile_type";
		$this->sql_map = "rdproject/uploadfile/uploadfiletypeSql.php";
		parent::__construct ();
	}

	/**
	 * 保存类型，现在数据库里面找记录，如果找到，则编辑，否则新增
	 */
	function saveType_d($type) {
		try {
			$this->start_d ();
			$oldType = $this->get_d ( $type ['id'] );
			if (empty ( $oldType )) {
				$root=$this->get_d(PARENT_ID);
				if(empty($root)){
					$this->add_d ( array("id"=>PARENT_ID,"name"=>"根节点") );
				}
				$id = $this->add_d ( $type );
			} else {
				$this->edit_d ( $type );
				//更改类型名称
				$sql = "update oa_uploadfile_manage set typeName='" . $type ['name'] . "' where typeId=" . $type ['id'];
				$this->query ( $sql );
				$id = $type ['id'];
			}
			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
		}
	}

	/**
	 * 递归获取某个类型下的子孙类型id集合
	 */
	function getChildrenIds($typeId) {
		$idArr = array ();
		$this->searchArr = array ("parentId" => $typeId );
		$children = $this->list_d ();
		if (count ( $children ) > 0) {
			foreach ( $children as $key => $value ) {
				array_push ( $idArr, $value ['id'] );
				$cIdArr = $this->getChildrenIds ( $value ['id'] );
				if (count ( $cIdArr ) > 0) {
					$idArr = array_merge ( $idArr, $cIdArr );
				}
			}
		}
		//print_r($idArr);
		return $idArr;
	}

	/**
	 * 删除类型
	 */
	function deletes_d($id) {
		try {
			//删除类型及子类型下附件
			$idArr = array ($id );
			$idArr = array_merge ( $idArr, $this->getChildrenIds ( $id ) );
			$ids = implode ( ",", $idArr );
			$sql = 'delete from oa_uploadfile_manage where typeId in (' . $ids . ')';
			parent::deletes ( $id );
			//删除子类型数据库级联删除
			$this->query ( $sql );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * 复制模板到项目中
	 */
	function copyTemplateToType($projectId,$projectType){
		try {
			$templateDao=new model_rdproject_uploadfile_template();
			$templateDao->searchArr=array("projectType"=>$projectType);
			$templateDao->asc=false;//根据id升序，保证高层级的在前
			$templates=$templateDao->list_d();
			$type=array();
			$oldNewParent=array();
			foreach($templates as $key=>$val){
				$type['name']=$val['name'];
				$type['projectId']=$projectId;
				if($val['parentId']==-1){
					$type['parentId']=-1;
				}else{
					$type['parentId']=$oldNewParent[$val['parentId']];
				}
				$id=$this->add_d($type);
				$oldNewParent[$val['id']]=$id;
			}
			return true;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			throw $e;
			return false;
		}

	}

}
?>
