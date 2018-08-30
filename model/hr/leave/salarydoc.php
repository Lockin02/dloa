<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 16:33:08
 * @version 1.0
 * @description:工资交接单 Model层
 */
class model_hr_leave_salarydoc  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_salarydoc";
		$this->sql_map = "hr/leave/salarydocSql.php";
		parent::__construct ();
	}
	/**
	 *
	 * 显示清单模板在编辑
	 */
	function showItemAtEdit($rows){
		$list="<tr id='appendHtml'><td>序号<td colspan='2'>工资内容</td><td colspan='3'>备注</td></tr>";
		$i=0;
		 foreach($rows as $k => $v){
			$seCode=$i+1;
			$list .= "<tr class='clearClass'><td>$seCode</td>
             			<td colspan='2'>
             		     <input type ='text' class='rimless_textB'  name='salarydoc[items][$i][salaryContent]' value='{$v[salaryContent]}'/>
             		     <input type='hidden' name='salarydoc[items][$i][id]' value='{$v[id]}' />
             		     <input type='hidden' name='salarydoc[items][$i][mainId]' value='{$v[mainId]}' />
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
				$salarydocitemDao = new model_hr_leave_salarydocitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id, $object ['items'] );
				$itemsObj = $salarydocitemDao->saveDelBatch ( $itemsArr );
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
				$salarydocitemDao = new model_hr_leave_salarydocitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $salarydocitemDao->saveDelBatch ( $itemsArr );
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
		$salarydocitemDao = new model_hr_leave_salarydocitem();
		$salarydocitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $salarydocitemDao->listBySqlId ();
		return $object;

	}
}
?>