<?php
/**
 * @author Administrator
 * @Date 2011年5月8日 14:16:25
 * @version 1.0
 * @description:研发合同培训计划 Model层
 */
 class model_rdproject_yxrdproject_trainingplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_rdproject_trainingplan";
		$this->sql_map = "rdproject/yxrdproject/trainingplanSql.php";
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
						<td>$val[beginDT]</td>
						<td>$val[endDT]</td>
						<td>$val[traNum]</td>
						<td>$val[adress]</td>
						<td>$val[content]</td>
						<td>$val[trainer]</td>



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
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$str .=<<<EOT
					<tr>
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txtshort" name="rdproject[trainingplan][$i][beginDT]" id="TraDT$i" size="10" onfocus="WdatePicker()" value="$val[beginDT]"/>
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txtshort" name="rdproject[trainingplan][$i][endDT]" id="TraEndDT$i" size="10" onfocus="WdatePicker()" value="$val[endDT]" />
					 	</td>
					 	<td align="center">
					 		<input type="text" class="txtshort" name="rdproject[trainingplan][$i][traNum]" value="$val[traNum]" size="8" maxlength="40"/>
					    </td>
					 	<td nowrap align="center">
					 		<textarea name="rdproject[trainingplan][$i][adress]" rows="3" style="width: 100%">$val[adress]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="rdproject[trainingplan][$i][content]" rows="3" style="width: 100%">$val[content]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="rdproject[trainingplan][$i][trainer]" rows="3" style="width: 100%">$val[trainer]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<img src="images/closeDiv.gif" onclick="mydel(this,mytra.id)" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}

	/**变更动态列表
	*author can
	*2011-6-3
	*/
 function initTableChange($rows) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="rdproject[trainingplan]['.$i.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="rdproject[trainingplan]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$str .=<<<EOT
					<tr>
					 	<td nowrap align="center" width="5%">$i
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txtshort" name="rdproject[trainingplan][$i][beginDT]" id="TraDT$i" size="10" onfocus="WdatePicker()" value="$val[beginDT]"/>
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" class="txtshort" name="rdproject[trainingplan][$i][endDT]" id="TraEndDT$i" size="10" onfocus="WdatePicker()" value="$val[endDT]" />
					 	</td>
					 	<td align="center">
					 		<input type="text" class="txtshort" name="rdproject[trainingplan][$i][traNum]" value="$val[traNum]" size="8" maxlength="40"/>
					    </td>
					 	<td nowrap align="center">
					 		<textarea name="rdproject[trainingplan][$i][adress]" rows="3" style="width: 100%">$val[adress]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="rdproject[trainingplan][$i][content]" rows="3" style="width: 100%">$val[content]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="rdproject[trainingplan][$i][trainer]" rows="3" style="width: 100%">$val[trainer]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<img src="images/closeDiv.gif" onclick="mydel(this,mytra.id,'trainingplan')" title="删除行">
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