<?php
/**
 *供应商联系人model层类
 */
class model_supplierManage_formal_sfcontact extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_cont";
		$this->sql_map = "supplierManage/formal/sfcontactSql.php";
		parent::__construct ();
	}

	/**
	 * @desription 供应商详细--查看联系人
	 * @param tags
	 * @date 2010-11-13 上午10:08:06
	 */
	function conInSupp ($parentId) {//任务ID
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
	 * 根据主表的id（即从表的parentId）获取对象
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
			$str .="<tr><td colspan='4' align='center'>暂无联系人信息<td></tr>";
		}
		return $str;

	}
}
?>
