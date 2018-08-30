<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 10:02:07
 * @version 1.0
 * @description:服务合同配置清单 Model层
 */
 class model_engineering_serviceContract_servicelist  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_service_list";
		$this->sql_map = "engineering/serviceContract/servicelistSql.php";
		parent::__construct ();
	}


	/**
	 * 渲染查看页面内从表
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
 * 渲染编辑从表
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
			         <img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行"/>
			      </td>
 			   </tr>
EOT;
 		}
 	}
 	return array($i,$str);
 }

 /**变更列表
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
			         <img src="images/closeDiv.gif" onclick="mydel(this,'List','servicelist')" title="删除行"/>
			      </td>
 			   </tr>
EOT;
 		}
 	}
 	return array($i,$str);
 }



	/*******************************页面显示层*********************************/

	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($orderId){
		$this->searchArr['orderId'] = $orderId;
		return $this->list_d();
	}
 }
?>