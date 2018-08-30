<?php
/*
 * Created on 2010-9-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_rdproject_personfocus_personfocus extends model_base{
	function __construct() {
		$this->tbl_name = "oa_rd_personfocus";
		$this->sql_map = "rdproject/personfocus/personfocusSql.php";
		parent::__construct ();
	}

	function showlist($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str.=<<<EOT
					<tr class="$classCss" title="$val[weekId]" id="$val[id]">
						<td>
							$i
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=logList&weekId=$val[weekId]">$val[weekTitle]</a>
						</td>
						<td>
							$val[depName]
						</td>
						<td>
							$val[updateTime]
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdweeklog&action=view&id=$val[weekId]">打开</a> |
							<a href="?model=rdproject_personfocus_personfocus&action=makeSureCancl&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=300" class="thickbox" title="取消关注">取消关注</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">暂无相关日志</td></tr>';
		}
		return $str;
	}

	/**
	 * 关注人员列表
	 */
	function showfocuslist($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str.=<<<EOT
					<tr class="$classCss" id="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[focusName]
						</td>
						<td>
							$val[depName]
						</td>
						<td>
							$val[updateTime]
						</td>
						<td>
							<a href="?model=rdproject_personfocus_personfocus&action=makeSureCancl&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=300" class="thickbox" title="取消关注">取消关注</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="10">暂无相关记录</td></tr>';
		}
		return $str;
	}

	/*****************************************业务接口**********************************/

	/**
	 * 添加关注
	 */
	function addFocus($beFocusId,$user_id=null,$user_name=null){
//		$object['beFocusId'] = $beFocusId;
		$object['focusId'] = $user_id;
		$object['focusName'] = $user_name;
		$object['isUsing'] = '1';
		return $this->add_d($object,true);
	}

	/**
	 * 取消关注
	 */
	function canclFocus($id){
		$object['id'] = $id;
		$object['isUsing'] = '0';
		$object = $this->addUpdateInfo($object);
		return $this->updateById($object);
	}

	/**
	 * 是否已存在此关注
	 */
	function isFocused($user_id,$createId){
		return $this->findAll(array('focusId'=>$user_id,'createId' => $createId,'isUsing' => '1'),null,'id');
	}
}
?>
