<?php
/**
 * model����
 * @author chengl
 *
 */
class model_rdproject_uploadfile_template extends model_base {
	function __construct() {
		$this->tbl_name = "oa_rd_project_uploadfile_template";
		$this->sql_map = "rdproject/uploadfile/templateSql.php";
		parent::__construct ();
	}

	/**
	 * �������ͣ��������ݿ������Ҽ�¼������ҵ�����༭����������
	 */
	function saveType_d($type) {
		try {
			$this->start_d ();
			//$oldType = $this->get_d ( $type ['id'] );
			//print_r($oldType);
			if (empty ( $type ['id'] )) {
				if($type['parentId']==PARENT_ID){
					$root=$this->get_d ( PARENT_ID );
					if(empty($root)){
						$root=array("id"=>PARENT_ID,"name"=>"���ڵ�");
						$this->add_d ( $root );
					}
				}
				$id = $this->add_d ( $type );
			} else {
				$this->edit_d ( $type );
				$id = $type ['id'];
			}
			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
		}
	}

	/**
	 * ������Ŀ���͸�ĳ����Ŀ
	 * @param unknown_type $project
	 */
	function copyUploadFileTemplate($project) {
		try {
			$this->start_d ();
			$this->searchArr = array ("projectType" => $project ['projectType'] );
			$this->sort = "id";
			$this->asc = false;
			$temps = $this->list_d ();
			$uploadfiletypeDao = new model_rdproject_uploadfile_uploadfiletype ();
			$map = array ();
			foreach ( $temps as $key => $value ) {
				$value ['projectId'] = $project ['id'];
				foreach ( $map as $k => $v ) {
					if ($value ['parentId'] == $v ['id']) {
						$value ['parentId'] = $k;
						break;
					}
				}
				$temp_id=$value['id'];
				unset($value['id']);
				$id = $uploadfiletypeDao->add_d ( $value );
				$value['id']=$temp_id;
				$map [$id] = $value;
			}
			$this->commit_d ();
		} catch ( Exception $e ) {
			echo $e->getMessage();
			$this->rollBack ();
			throw $e;
		}
	}

}
?>
