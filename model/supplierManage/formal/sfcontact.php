<?php
/**
 *��Ӧ����ϵ��model����
 */
class model_supplierManage_formal_sfcontact extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_cont";
		$this->sql_map = "supplierManage/formal/sfcontactSql.php";
		parent::__construct ();
	}

	/**
	 * @desription ��Ӧ����ϸ--�鿴��ϵ��
	 * @param tags
	 * @date 2010-11-13 ����10:08:06
	 */
	function conInSupp ($parentId) {//����ID
		$this->searchArr['parentId'] = $parentId;
		return $this->pageBySqlId('readconInSupp');
	}

    function addcontact() {
		$con = $_POST [$this->objName];
        if($_GET['id']){
        	$linkman['createId'] = $_SESSION['USER_ID'];
        	$linkman['createName'] = $_SESSION['USERNAME'];
        	$linkman ['defaultContact'] = $_SESSION ['USERNAME'];
        }
		$id = $this->service->add_d ( $con, true );
	}


	/**
	 * ���������id�����ӱ��parentId����ȡ����
	 */
	function getByid_d($parentId) {
		$parentId = isset($parentId)?$parentId:'';
		if($parentId){
			$sql = "select c.id,c.parentId,c.name,c.mobile1,c.mobile2,c.fax,c.plane,c.email from  oa_supp_cont_temp c where c.parentId=" . "'" . $parentId . "'";
			$rows = $this->pageBySql($sql);
			return $rows;
		}else{
			return null;
		}


	}
	function showViewContact($parentId){
		$condiction = array('parentId' => $parentId);
		$rows = $this->findAll($condiction);
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$str .=<<<EOT
					<tr>
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td  nowrap align="center">
					 	   $val[name]
					 	</td>
					 	<td nowrap align="center">
					 		$val[position]
					 	</td>
					 	<td nowrap align="center">
					 		$val[mobile1]
					 	</td>
					 	<td nowrap align="center">
					 		$val[fax]
					 	</td>
					 </tr>
EOT;
			}
		}else{
			$str .="<tr><td colspan='4' align='center'>������ϵ����Ϣ<td></tr>";
		}
		return $str;

	}
}
?>
