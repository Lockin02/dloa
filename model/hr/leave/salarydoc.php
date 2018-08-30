<?php
/**
 * @author Administrator
 * @Date 2013��4��25�� ������ 16:33:08
 * @version 1.0
 * @description:���ʽ��ӵ� Model��
 */
class model_hr_leave_salarydoc  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_salarydoc";
		$this->sql_map = "hr/leave/salarydocSql.php";
		parent::__construct ();
	}
	/**
	 *
	 * ��ʾ�嵥ģ���ڱ༭
	 */
	function showItemAtEdit($rows){
		$list="<tr id='appendHtml'><td>���<td colspan='2'>��������</td><td colspan='3'>��ע</td></tr>";
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
	/*--------------------------------------------ҵ�����--------------------------------------------*/
	 
	/**
	 * ��������
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
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	 
	/**
	 * �޸ı���
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
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	 

	/**
	 * ͨ��id��ȡ��ϸ��Ϣ
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