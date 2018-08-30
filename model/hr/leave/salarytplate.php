<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 15:29:40
 * @version 1.0
 * @description:工资清单模板 Model层
 */
class model_hr_leave_salarytplate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_salarytplate";
		$this->sql_map = "hr/leave/salarytplateSql.php";
		parent::__construct ();
	}
	/**
	 *
	 * 在选择模板是渲染对应模板信息
	 */
	function showItemListAtChoose($rows){
		$i=0;
		foreach($rows as $k => $v){
			$seCode=$i+1;
			$list .= "<tr class='clearClass'><td>$seCode</td>
             			<td colspan='2'>
             		     <input type ='text' class='rimless_textB'  name='salarydoc[items][$i][salaryContent]' value='{$v[salaryContent]}'/>
             		    </td>
             		     <td colspan='3'><input type ='text'  class='rimless_textB'  name='salarydoc[items][$i][remark]' value='{$v[remark]}' >
             		  </tr>";
		}
		return $list;
	}
	/*--------------------------------------------业务操作--------------------------------------------*/

	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {

			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				$id = parent::add_d ( $object, true );
				$salarytplateitemDao = new model_hr_leave_salarytplateitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id, $object ['items'] );
				$itemsObj = $salarytplateitemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				$editResult = parent::edit_d ( $object, true );
				$salarytplateitemDao = new model_hr_leave_salarytplateitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $salarytplateitemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $editResult;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/**
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$salarytplateitemDao = new model_hr_leave_salarytplateitem();
		$salarytplateitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $salarytplateitemDao->listBySqlId ();
		return $object;

	}
}
?>