<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 20:07:06
 * @version 1.0
 * @description:销售合同联系人信息表 Model层
 */
 class model_projectmanagent_order_linkman  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_order_linkman";
		$this->sql_map = "projectmanagent/order/linkmanSql.php";
		parent::__construct ();
	}


	/**
	 * 渲染查看页面内从表
	 */
	function initTableView($object){

		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;

				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[linkman]</td>
						<td>$val[telephone]</td>
						<td>$val[email]</td>
						<td>$val[remark]</td>

					</tr>
EOT;
		}
		return $str;
	}


	/**
	 * 构建编辑合同时需要的客户联系人
	 */
	function initTableEdit($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$str .=<<<EOT
					<tr>
					 	<td>$i</td>
					 	<td><input type="text" name="order[linkman][$i][linkman]" id="linkman$i" value="$val[linkman]"  class="txt"/>
					 	    <input type="hidden" name="order[linkman][$i][linkmanId]" id="linkmanId$i" value="$val[linkmanId]"/></td>
					 	<td ><input type="text" name="order[linkman][$i][telephone]" id="telephone$i" value="$val[telephone]" class="txt"/></td>
					 	<td><input type="text" name="order[linkman][$i][Email]" id="Email$i" value="$val[email]" class="txt"/></td>
					 	<td><input type="text" name="order[linkman][$i][remark]" id="Lremark$i" value="$val[remark]" class="txtlong"/></td>
					 	<td><img src="images/closeDiv.gif" onclick="mydel(this,'mylink')" title="删除行"></td>
					 </tr>
EOT;
			}
		}

		return array($i,$str);
	}

	/**变更动态列表
	*author can
	*2011-6-1
	*/
	function initTableChange($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="order[linkman]['.$i.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="order[linkman]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td align="center">
					 		<input type="hidden" name="order[linkman][$i][linkmanId]" id="linkmanId$i" value="$val[linkmanId]"/>
					 		<input type="text" name="order[linkman][$i][linkman]" id="linkman$i" value="$val[linkman]" onclick="reloadLinkman('linkman$i');" class="txt"/>
					 	</td>
					 	<td align="center">
					 		<input type="text" name="order[linkman][$i][telephone]" id="telephone$i" value="$val[telephone]" class="txt"/>
					 	</td>
					 	<td>
					 		<input type="text" name="order[linkman][$i][Email]" id="Email$i" value="$val[email]" class="txt"/>
					 	</td>
					 	<td align="center">
					 		<input type="text" name="order[linkman][$i][remark]" id="Lremark$i" value="$val[remark]" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mylink','linkman')" title="删除行">
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