<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:22:42
 * @version 1.0
 * @description:��Ʒ������ϸ�� Model��
 */
 class model_stockup_application_applicationMatter  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stockup_application_matter";
		$this->sql_map = "stockup/application/applicationMatterSql.php";
		parent::__construct ();
	}

	function add_d($object) {
		try {
			$this->start_d();
			parent::add_d ( $object,true );
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ����״̬
	 */
	function updateObjStatus($id, $statusType,$statusValue) {
		if ($id && $statusType&&$statusValue) {
			$sql = "UPDATE  oa_stockup_application_matter SET $statusType='$statusValue' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}
	function getProductInfo($listId){
		$sqlStr="SELECT * FROM  oa_stockup_application_matter a
				 WHERE a.appId='$listId'";
		$rs = $this->_db->getArray($sqlStr);
		return $rs;
	}

	/**
	 * ����DATA
	 *
	 */
	function showAppList() {
		$keyType=$_POST['keyType']?$_POST['keyType']:$_GET['keyType'];
		$keyWords=$_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords'];
		$keyWords=trim($keyWords);
		$keyTypeI=explode(',',$keyType);
		$sqlStr='';
		$sqlKeyStr='';
		if(strpos($keyType,',')){
			$this->searchArr['allS'] = $keyWords;
		}
		else if($keyType)
		{
			$this->searchArr[$keyType.'S'] = $keyWords;
		}
		$this->searchArr['bid']="1";
		$rs = $this->pageBySqlId('select_pageAll');
		$str = "";
		$i = 0;
		//$rs = $this->sconfig->md5Rows ( $rs );
		if ($rs&&is_array($rs)) {
			$dataInfo=array();
			foreach ( $rs as $key => $val ) {
			   if($val){
			   		$dataInfo[$val['productId']][]=$val;
			   }
			}
				foreach ( $dataInfo as $key => $_dataInfo ) {
					if($_dataInfo&&is_array($_dataInfo)){
						$i ++;
						$n=0;
						$productNum=0;
						$productAmount=0;
						$rowspan=count($_dataInfo);
						foreach ( $_dataInfo as $_key => $val ) {
							$productNum+=$val[stockupNum];
							$productAmount+=$val[expectAmount];
							$k ++;
							$iClass = (($k % 2) == 0) ? "tr_even" : "tr_odd";
							$str .="<tr class='$iClass'>";
							$n ++;
							if($val){
								$str.="<td >$k</td>";
								if($n==1){
									$str.="<td rowspan='$rowspan'>$val[productName]</td>";
								}
								$links='?model=stockup_application_application&action=toView&id='.$val[pid];
						$str .= <<<EOT
							<td><a  title="�鿴��ϸ" href="javascript:showLink('$links','stockup_application_application',$val[pid])">$val[listNo]</a></td>
							<td>$val[createTime]</td>
							<td>$val[stockupNum]</td>
							<td>$val[ExaDT]</td>
							<td class="formatMoney">$val[expectAmount]</td>

EOT;
							}
							$str .="</tr>";

						}
						$str .="<tr><td></td><td><span style='color:blue;font-size:14px'>����</span></td><td></td><td></td><td><span style='color:blue'>$productNum</span></td><td></td><td  class='formatMoney'><span style='color:blue'>$productAmount</span></td></tr>";
					}
				}
		}else {
			$str = "<tr><td colspan='8'>���޲�Ʒ����������Ϣ</td></tr>";
		}

		return $str;
	}

 }
?>