<?php
class model_engineering_serviceTrain_servicetrain extends model_base {
	function __construct(){
		$this->tbl_name = "oa_contract_service_training";
		$this->sql_map = "engineering/serviceTrain/servicetrainSql.php";
		parent::__construct();
	}


/****************************************************以下是模板调用显示方法***************************************************/

	/*
	 * @desription 动态构建培训计划的编辑列表
	 * @param tags
	 * @author qian
	 * @date 2010-12-14 下午02:47:38
	 */
	function showTrainEditList ($contractId) {
		$condiction = array('contractID' => $contractId);
		$trainCount = $this->findCount($condiction);
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
					 	<td nowrap align="center">
					 		<input type="text" name="serviceContract[train][$i][beginDT]" id="TraDT$i" size="10" onfocus="WdatePicker()" value="$val[beginDT]"/>
					 	</td>
					 	<td nowrap align="center">
					 		<input type="text" name="serviceContract[train][$i][endDT]" id="TraEndDT$i" size="10" onfocus="WdatePicker()" value="$val[endDT]" />
					 	</td>
					 	<td align="center">
					 		<input type="text" name="serviceContract[train][$i][traNum]" value="$val[traNum]" size="8" maxlength="40"/>
					    </td>
					 	<td nowrap align="center">
					 		<textarea name="serviceContract[train][$i][adress]" rows="2" class="all">$val[adress]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="serviceContract[train][$i][content]" rows="2" class="all">$val[content]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<textarea name="serviceContract[train][$i][trainer]" rows="2" class="all">$val[trainer]</textarea>
					 	</td>
					 	<td nowrap align="center">
					 		<img src="images/closeDiv.gif" onclick="mydel(this,mytra.id)" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return $str;

	}

	/*
	 * @desription 培训计划的查看页面
	 * @param tags
	 * @author qian
	 * @date 2010-12-17 上午11:40:01
	 */
	function showTrainViewList ($contractId) {
		$condiction = array('contractID' => $contractId);
		$rows = $this->findAll($condiction);
		$str = "";
		$i = 0;
		if($rows){
			$str .=<<<EOT
			<table class="main_table">
				<tr class="main_tr_header">
					<th width="4%">序号</th>
					<th width="14%">开始日期</th>
					<th width="14%">结束日期</th>
					<th>参与人数</th>
					<th width="20%">培训地点</th>
					<th width="20%">培训内容</th>
					<th width="20%">培训工程师要求</th>
				</tr>
EOT;
			foreach($rows as $key => $val){
				$i++;
				$iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
				<tr class="$iClass">
					<td>$i</td>
					<td>$val[beginDT]</td>
					<td>$val[endDT]</td>
					<td>$val[traNum]</td>
					<td><textarea class="textarea_read">$val[adress]</textarea></td>
					<td><textarea class="textarea_read">$val[content]</textarea></td>
					<td><textarea class="textarea_read">$val[trainer]</textarea></td>
				</tr>
EOT;

			}
			$str .=<<<EOT
			</table>
EOT;
		}else{
			$str = "暂无相关培训计划";
		}
		return $str;
	}

/****************************************************以上是模板调用显示****************************************************/

/****************************************************以下是外部接口类方法****************************************************/
	/*
	 * @desription 培训计划的增加方法
	 * @param tags
	 * @author qian
	 * @date 2010-12-13 下午04:42:43
	 */
	function addTrain_d ($trainArr) {
		$rows = array();
		try{
			$this->start_d();
			if($trainArr){
				foreach($trainArr['train'] as $key1 => $val1){
					if(!empty($val['beginDT'])&&!empty($val['endDT'])){
						foreach($val1 as $key => $val){
							$rows[$key1][$key] = $val1[$key];
							$rows[$key1]['contractID'] = $trainArr['id'];
							$rows[$key1]['contractName'] = $trainArr['cusName'];
							$rows[$key1]['contractNo'] = $trainArr['contractNo'];
						}
					}
				}
				foreach($rows as $key => $val){
					parent::add_d($val,true);
				}
				foreach($trainArr['train'] as $key=>$val){
					if(!empty($val['beginDT'])&&!empty($val['endDT'])){
						$val['contractID'] = $trainArr['id'];
						$val['contractName'] = $trainArr['name'];
						$val['contractNo'] = $trainArr['contractNo'];

						$this->add_d($val,true);
					}
				}
			}
			else{
				return null;
			}
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}

	}

/****************************************************以下是外部接口类方法****************************************************/
}
?>
