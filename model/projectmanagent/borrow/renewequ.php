<?php
/**
 * @author Administrator
 * @Date 2011��12��12�� 15:14:45
 * @version 1.0
 * @description:����ӱ�������Ϣ Model��
 */
 class model_projectmanagent_borrow_renewequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_renew_equ";
		$this->sql_map = "projectmanagent/borrow/renewequSql.php";
		parent::__construct ();
	}


	/**
	 * ��Ⱦ�鿴ҳ���ڴӱ�
	 */
	function renewTableview($object){
		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict ();
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
               if(empty($val['license'] )){
               		$license = "";
               }else{
               		$license = "<input type='button' class='txt_btn_a' value='����' onclick='" .
               				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=".$val['license']."" .
               						"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
               }
				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td>$val[productName]</td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[warrantyPeriod]</td>
						<td>$license</td>

					</tr>
EOT;
		}

		return $str;
	}
/*******************************ҳ����ʾ��*********************************/

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($renewId){
		$this->searchArr['renewId'] = $renewId;
		$this->searchArr['isDel'] = '0';
		$this->asc = false;
		return $this->list_d();
	}
 }
?>