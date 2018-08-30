<?php
/**
 * @author Administrator
 * @Date 2011��5��5�� 10:02:07
 * @version 1.0
 * @description:�����ͬ�����嵥 Model��
 */
 class model_engineering_serviceContract_servicelist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_list";
		$this->sql_map = "engineering/serviceContract/servicelistSql.php";
		parent::__construct ();
	}


	/**
	 * ��Ⱦ�鿴ҳ���ڴӱ�
	 */
	function initTableView($object){

		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict ();
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;

				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[serviceItem]</td>
						<td>$val[serviceNo]</td>
						<td>$val[serviceRemark]</td>

					</tr>
EOT;
		}

		return $str;
	}


/**
 * ��Ⱦ�༭�ӱ�
 */
 function initTableEdit($rows) {
 	$i = 0;
 	$srt = "";
 	if ($rows){
 		foreach ($rows as $val){
 			$i++;
 			$str .=<<<EOT
 			   <tr>
 			      <td nowrap align="center" width="5%">$i</td>
 			      <td nowrap align="center" >
 			         <input type="text" size="55" name="serviceContract[servicelist][$i][serviceItem]" id="servieItem$i" value="$val[serviceItem]" />
 			      </td>
 			      <td nowrap align="center">
 			         <input type="text" class="txt" name="serviceContract[servicelist][$i][serviceNo]" id="number$i" value="$val[serviceNo]" />
 			      </td>
 			      <td nowrap align="center">
 			         <input type="text" size="45" name="serviceContract[servicelist][$i][serviceRemark]" id="serviceremark$i" value="$val[serviceRemark]" />
 			      </td>

 			      <td>
			         <img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����"/>
			      </td>
 			   </tr>
EOT;
 		}
 	}
 	return array($i,$str);
 }

 /**����б�
*author can
*2011-6-2
*/
 function initTableChange($rows) {
 	$i = 0;
 	$srt = "";
 	if ($rows){
 		foreach ($rows as $val){
 			$i++;
			if(empty($val['originalId'])){
				$str.='<input type="hidden" name="serviceContract[servicelist]['.$i.'][oldId]" value="'.$val['id'].'" />';
			}else{
				$str.='<input type="hidden" name="serviceContract[servicelist]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
			}
 			$str .=<<<EOT
 			   <tr>
 			      <td nowrap align="center" width="5%">$i</td>
 			      <td nowrap align="center" >
 			         <input type="text" size="55" name="serviceContract[servicelist][$i][serviceItem]" id="servieItem$i" value="$val[serviceItem]" />
 			      </td>
 			      <td nowrap align="center">
 			         <input type="text" class="txt" name="serviceContract[servicelist][$i][serviceNo]" id="number$i" value="$val[serviceNo]" />
 			      </td>
 			      <td nowrap align="center">
 			         <input type="text" size="45" name="serviceContract[servicelist][$i][serviceRemark]" id="serviceremark$i" value="$val[serviceRemark]" />
 			      </td>
 			      <td>
			         <img src="images/closeDiv.gif" onclick="mydel(this,'List','servicelist')" title="ɾ����"/>
			      </td>
 			   </tr>
EOT;
 		}
 	}
 	return array($i,$str);
 }



	/*******************************ҳ����ʾ��*********************************/

	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($orderId){
		$this->searchArr['orderId'] = $orderId;
		return $this->list_d();
	}
 }
?>