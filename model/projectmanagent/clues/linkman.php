<?php
/**
 * @author suxc
 * @version 1.0
 * @description:线索联系人 Model层
 */
 class model_projectmanagent_clues_linkman  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_clues_linkman";
		$this->sql_map = "projectmanagent/clues/linkmanSql.php";
		parent::__construct ();
	}
		/**
	 * 显示联系人列表
	 */
	function viewList_d($rows) {
		$str = "";
		if ($rows) {
			$i = $n = 0;
			foreach ($rows as $key => $val) {
				if($val['isKeyMan']=="on"){
					$isKeyMan='是';
				}else{
					$isKeyMan='否';
				}
				$i++;
				$n = ($i%2)+1;
				$str .=<<<EOT
					<tr id="tr_$val[id]" class="TableLine$n">
						<td align="center">$i</td>
							<td align="center">$val[linkmanName]</td>
							<td align="center">$val[mobileTel]</td>
							<td align="center">$val[email]</td>
							<td align="center">$val[roleName]</td>
							<td align="center">$isKeyMan</td>
					</tr>
EOT;
			}

		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str ;
	}
	 /**
	 * 编辑时显示联系人列表
	 */
	function showEditList_d($rows) {
		$str = "";
		if ($rows) {
			$i = $n = 0;
			foreach ($rows as $key => $val) {
			if(!empty($val['isKeyMan'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}
				$i++;
				$n = ($i%2)+1;
				$str .=<<<EOT
					<tr id="linkTab_1">
						<td>$i</td>
						<td>
							<input class="text" type="hidden" name="clues[linkman][$i][linkmanId]" id="linkmanId$i" value="$val[linkmanId]"/>
							<input class="text" type="hidden" name="clues[linkman][$i][customerId]" id="customerId$i" value="$val[customerId]"/>
							<input class="txt" type="text" name="clues[linkman][$i][linkmanName]" id="linkmanName$i" title="双击可以添加联系人" value="$val[linkmanName]" >
						</td>
						<td>
							<input class="txt" type="text" name="clues[linkman][$i][mobileTel]" id="mobileTel$i" value="$val[mobileTel]" onchange="tel($i)"/>
						</td>
						<td>
							<input class="txt" type="text" name="clues[linkman][$i][email]" id="email$i" value="$val[email]" onchange="Email($i);"/>
						</td>
						<td>
							<select class="" type="text" name="clues[linkman][$i][roleCode]" id="roleCode$i">{roleCode$i}</select>
						</td>
						<td>
							<input class="" type="checkbox" name="clues[linkman][$i][isKeyMan]" id="isKeyMan$i" $checked/>
						</td>
						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this,'mylink')" title="删除行"/>
						</td>
					</tr>
EOT;
			}

		}
		return $str ;
	}

	/**
	 * 根据线索ID获取客户联系人
	 * @param  $cluesId   线索ID
	 *
	 */
	function getLinkmanByCluesId_d($cluesId){
		$conditions = array (
					"cluesId" => $cluesId
			);
	   return parent :: findAll($conditions);
	}

 }
?>