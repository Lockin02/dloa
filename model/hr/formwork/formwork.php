<?php
/**
 * @author Administrator
 * @Date 2012-07-12 14:04:29
 * @version 1.0
 * @description:����ģ������ Model��
 */
 class model_hr_formwork_formwork  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_formwork";
		$this->sql_map = "hr/formwork/formworkSql.php";
		parent::__construct ();
	}

	/**
	 * ����id ��ȡģ������
	 */
	function formworkContent_d($id){
        $sql="select formworkContent from oa_hr_formwork where id=".$id."";
        $content=$this->_db->getArray($sql);
        return $content[0]['formworkContent'];
	}
	/**
	 *ģ������--�洢��ѡģ��id
	 */
	function formworkdeployEdit_d($ids,$type){
        try {
        	$findSql="select count(id) as num from oa_hr_formwork_deploy where type='".$type."'";
        	$flag=$this->_db->getArray($findSql);
        	$ids=implode(",",$ids);
        	if($flag[0]['num'] == '0'){
        		$sql="insert into oa_hr_formwork_deploy(ids,type) values ('".$ids."','".$type."')";
        	}else{
        		$sql="update oa_hr_formwork_deploy set ids='".$ids."' where type='".$type."' ";
        	}
            $this->query($sql);
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
	/*8
	 * ģ���ж�
	 */
	function formworkLimit_d($type){
		 $sql="select ids from oa_hr_formwork_deploy where type='".$type."'";
		 $idsArr = $this->_db->getArray($sql);
		 return $idsArr[0]['ids'];
	}
 }
?>