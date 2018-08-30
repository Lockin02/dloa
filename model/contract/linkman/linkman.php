<?php
/*
 * Created on 2011-3-2 By Zeng ZX
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_linkman_linkman extends model_base{
	function __construct(){
		parent::__construct();
		$this->tbl_name = "oa_contract_sales_linkman";
		$this->sql_map = "contract/linkman/linkmanSql.php";
	}

	/**
	 * ��������ͻ���ϵ��-��ͬ
	 */
	function batchInsert($id,$contNumber,$contName,$rows){
		if($rows){
			$strdate = "";
			$str="insert into ".$this->tbl_name." (contId,contNumber,contName,linkmanId,linkman,telephone,Email,remark) values ";
			foreach($rows as $key => $val){
				if($val['linkmanId']!=""||$val['linkman']!=""||$val['telephone']!=""||$val['Email']!=""||$val['remark']!=""){
					$strdate.=" ( '$id','$contNumber','$contName','$val[linkmanId]','$val[linkman]','$val[telephone]','$val[Email]','$val[remark]' ) ,";
				}
				else{
					continue;
				}
			}
		}
		if($strdate!=""){
			$str.=$strdate;
			$str = substr($str,0,-1);
			return $this->query($str);
		}else{
			return true;
		}
	}
	/**
	 * ���ݺ�ͬID�ͱ��ɾ���ͻ���ϵ��
	 */
	function delectByIdAndNumber($id,$contNumber){
		try{
			$rows = $this->delete(array( 'contId' => $id, 'contNumber' => $contNumber ));
			return $rows;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	/**
	 * �ͻ���ϵ��
	 * ���ݺ�ͬ��Ż�ȡ�ͻ���ϵ��
	 */
	function showLinkmanList($id){
		return $this->findAll(array( 'contId' => $id),'','id,linkmanId,linkman,telephone,Email,remark');
	}

	/**
	 * ��ʾ��ͬ�ͻ���ϵ���б�
	 */
	function showlist($rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $val) {
				$i++;
				$str .= '
					<tr align="center">
						<td>' . $i. '</td>
						<td width="10%">' . $val['linkman'] . '</td>
						<td width="10%">' . $val['telephone'] . '</td>
						<td width="12%">' . $val['Email'] . '</td>
						<td>' . $val['remark'] . '</td>
					</tr>
					';
			}
		}else{
			return '<tr align="center"><td colspan="6">�����������</td></tr>';
		}
		return $str;
	}

	/**
	 * �����༭��ͬʱ��Ҫ�Ŀͻ���ϵ��
	 */
	function showlistInEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td align="center">
					 		<input type="hidden" name="sales[linkman][$i][linkmanId]" id="linkmanId$i" value="$val[linkmanId]"/>
					 		<input type="text" name="sales[linkman][$i][linkman]" id="linkman$i" value="$val[linkman]" onclick="reloadLinkman('linkman$i');" class="txt"/>
					 	</td>
					 	<td align="center">
					 		<input type="text" name="sales[linkman][$i][telephone]" id="telephone$i" value="$val[telephone]" class="txt"/>
					 	</td>
					 	<td>
					 		<input type="text" name="sales[linkman][$i][Email]" id="Email$i" value="$val[Email]" class="txt"/>
					 	</td>
					 	<td align="center">
					 		<input type="text" name="sales[linkman][$i][remark]" id="remark$i" value="$val[remark]" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mylink')" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
}
?>
