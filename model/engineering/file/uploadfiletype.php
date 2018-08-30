<?php
/**
 * model����
 * @author chengl
 *
 */
class model_engineering_file_uploadfiletype extends model_base {
	function __construct() {
		$this->tbl_name = "oa_esm_uploadfile_type";
		$this->sql_map = "engineering/file/uploadfiletypeSql.php";
		parent::__construct ();
	}

	/**
	 * �������ͣ��������ݿ������Ҽ�¼������ҵ�����༭����������
	 */
	function saveType_d($type) {
		try {
			$this->start_d ();
			$oldType = $this->get_d ( $type ['id'] );
			if (empty ( $oldType )) {
				$id = $this->add_d ( $type );
			} else {
				$this->edit_d ( $type );
				//������������
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
	 * ��������
	 */
	function add_d($type){
		try {
			$this->start_d ();
			$root = $this->get_d ( PARENT_ID );
			if(empty($root)){
				$root=array("id"=>PARENT_ID,"name"=>'���ڵ�');
				parent::add_d($root);
			}
			$id=parent::add_d($type);
			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
		}
	}

	/**
	 * �ݹ��ȡĳ�������µ���������id����
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
	 * ɾ������
	 */
	function deletes_d($id) {
		try {
			//ɾ�����ͼ��������¸���
			$idArr = array ($id );
			$idArr = array_merge ( $idArr, $this->getChildrenIds ( $id ) );
			$ids = implode ( ",", $idArr );
			$sql = 'delete from oa_uploadfile_manage where typeId in (' . $ids . ')';
			parent::deletes ( $id );
			//ɾ�����������ݿ⼶��ɾ��
			$this->query ( $sql );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ��һ����Ŀ����ļ����ڵ�
	 */
	function addEsmRoot($projectId,$projectName = '���ڵ�'){
		$esmroot=array("parentId"=>PARENT_ID,"parentName"=> '���ڵ�',"name"=>$projectName,"projectId"=>$projectId);
		$this->add_d($esmroot);
	}

}
?>
