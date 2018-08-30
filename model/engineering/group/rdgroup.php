<?php
/**
 * @description: ��Ŀ���Model
 * @date 2010-9-11 ����12:03:10
 * @author oyzx
 * @version V1.0
 */
class model_enginering_group_rdgroup extends model_base {

	/**
	 * @desription ���캯��
	 * @date 2010-9-11 ����12:46:46
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_group";
		$this->sql_map = "enginering/group/rdgroupSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/

	/***************************************************************************************************
	 * ------------------------------����Ϊ�ӿڷ���,����Ϊ����ģ��������--------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ͨ�����Id��ȡ�����ϸ��Ϣ
	 * @param tags
	 * @return return_type
	 * @date 2010-9-16 ����03:07:53
	 */
	function rgArrById_d($id) {
		$searchArr = array ('rgid' => $id );
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ( 'select_readAll' );
		return $rows;
	}
	/**
	 * @desription ͨ�����Id��ȡ��ϲ�����Ϣ
	 * @param tags
	 * @return return_type
	 * @date 2010-9-16 ����03:07:53
	 */
	function rgParentById_d($id) {
		$searchArr = array ('rgid' => $id );
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ( 'select_parent' );
		return $rows;
	}
	/**
	 * @desription �����б����ݷ���
	 * @param tags
	 * @return return_type
	 * @date 2010-9-26 ����03:55:39
	 */
	function rgPage_d() {
		$rows = $this->page_d ();
		//		echo "<pre>";
		//		print_r($rows);
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$rows [$key] ['pid'] = $rows [$key] ['id'];
				$rows [$key] ['oid'] = "g_" . $rows [$key] ['id']; //��g-Ϊǰ׺����Ϊ���
				$rows [$key] ['oParentId'] = "g_" . $rows [$key] ['parentId'];
			}
		}
		return $rows;
	}

	/**
	 * @desription �����б����ݷ���
	 * @param tags
	 * @return return_type
	 * @date 2010-9-26 ����04:15:43
	 */
	function rgList_d() {
		$arr = $this->listBySqlId ( "select_page" );
		if (is_array ( $arr )) {
			foreach ( $arr as $key => $val ) {
				$arr [$key] ['pid'] = $arr [$key] ['id'];
				$arr [$key] ['oid'] = "g_" . $arr [$key] ['id']; //��g-Ϊǰ׺����Ϊ���
				$arr [$key] ['oParentId'] = "g_" . $arr [$key] ['parentId'];
			}
		}
		return $arr;
	}

	/**
	 * @desription �ݹ���Ŀ���
	 * @return Array ÿ�η���һ����Ŀ�������
	 */
	private function getGroups($parentId) {
		$idArr = array ();
		$this->searchArr = array ("parentId" => $parentId );
		$gs = $this->list_d ();
		if (is_array ( $gs )) {
			foreach ( $gs as $value ) {
				array_push ( $idArr, $value ['id'] );
				$childArr = $this->getGroups ( $value ['id'] );
				$idArr = array_merge ( $idArr, $childArr );
			}
		}
		return $idArr;
	}

	/**
	 * @desription 1.��ȡĳһҳ���  2.��ȡ��ҳ����µ������������  3.��ȡ���л�ȡ��������µ���Ŀ
	 * @param searchArr ��Ŀ����Ŀ��ϵ�����������keyΪ�����������ƣ�valueΪ����ֵ
	 * @return Array һ�������Ŀ��ϸ���Ŀ������
	 */
	function pageAll_d($searchArr) {
		//��ȡ��ϵ�һ����ҳ
		$this->searchArr = $searchArr;
		$this->searchArr ["parentId"] = PARENT_ID;
		$firstGroups = $this->page_d ();

		$sunNodes = $this->getChildrenByNodes ( $firstGroups );
		$mergeGroups = model_common_util::yx_array_merge ( $firstGroups, $sunNodes );

		//��ȡ�ϲ��������������Ŀ��Ϣ
		$groupIds = array ();
		if (is_array ( $mergeGroups )) {
			foreach ( $mergeGroups as $value ) {
				array_push ( $groupIds, $value ['id'] );
			}
		}
		//echo $groupIds;
		if (count ( $groupIds ) > 0) {
			$projectDao = new model_enginering_project_enginering ();
			$searchArr ["groupIds"] = $groupIds;
			$projectDao->searchArr = $searchArr;
			$projects = $projectDao->list_d ();
			//�ϲ���Ŀ�����������
			$mergeGroups = model_common_util::yx_array_merge ( $mergeGroups, $projects );
		}

		return $mergeGroups;
	}

	/**
	 * @desription ɾ������
	 * @param tags
	 * @date 2010-10-4 ����05:04:41
	 */
	function rgDel_d ($id){
		try{
			if( $this->deletes($id) )
			{
				return true;
			}
			else{
				return false;
			}
		}catch( Exception $e ) {
			return false;
		}
	}

	/**
	 * @desription ������
	 * @param tags
	 * @date 2010-10-7 ����04:36:18
	 */
	function rgAdd_d ($node) {
		try {
			$this->start_d ();
			$sql = "select rgt from oa_rd_group where id=" . $node ['parentId'];
			$rs = $this->_db->get_one ( $sql );
			$parentNodeRgt = $rs ['rgt'] - 1;
			//������ֵ���ڸ��ڵ���ֵ����ֵ��2
			$sql = "update oa_rd_project set lft=lft+2 where lft>" . $parentNodeRgt;
			$this->query ( $sql );
			$sql = "update oa_rd_group set lft=lft+2 where lft>" . $parentNodeRgt;
			$this->query ( $sql );
			//������ֵ���ڸ��ڵ���ֵ����ֵ��2
			$sql = "update oa_rd_project set rgt=rgt+2 where rgt>" . $parentNodeRgt;
			$this->query ( $sql );
			$sql = "update oa_rd_group set rgt=rgt+2 where rgt>" . $parentNodeRgt;
			$this->query ( $sql );
			$node ['lft'] = $parentNodeRgt + 1;
			$node ['rgt'] = $parentNodeRgt + 2;
			$newId =$this->add_d($node,true);
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			echo "�쳣*********************";
			$this->rollBack ();
			return null;
		}
	}


}

?>
