<?php
/**
 * @description: 项目组合Model
 * @date 2010-9-11 下午12:03:10
 * @author oyzx
 * @version V1.0
 */
class model_enginering_group_rdgroup extends model_base {

	/**
	 * @desription 构造函数
	 * @date 2010-9-11 下午12:46:46
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_group";
		$this->sql_map = "enginering/group/rdgroupSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

	/***************************************************************************************************
	 * ------------------------------以下为接口方法,可以为其他模块所调用--------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 通过组合Id获取组合详细信息
	 * @param tags
	 * @return return_type
	 * @date 2010-9-16 下午03:07:53
	 */
	function rgArrById_d($id) {
		$searchArr = array ('rgid' => $id );
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ( 'select_readAll' );
		return $rows;
	}
	/**
	 * @desription 通过组合Id获取组合部分信息
	 * @param tags
	 * @return return_type
	 * @date 2010-9-16 下午03:07:53
	 */
	function rgParentById_d($id) {
		$searchArr = array ('rgid' => $id );
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ( 'select_parent' );
		return $rows;
	}
	/**
	 * @desription 返回列表数据方法
	 * @param tags
	 * @return return_type
	 * @date 2010-9-26 下午03:55:39
	 */
	function rgPage_d() {
		$rows = $this->page_d ();
		//		echo "<pre>";
		//		print_r($rows);
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$rows [$key] ['pid'] = $rows [$key] ['id'];
				$rows [$key] ['oid'] = "g_" . $rows [$key] ['id']; //以g-为前缀表明为组合
				$rows [$key] ['oParentId'] = "g_" . $rows [$key] ['parentId'];
			}
		}
		return $rows;
	}

	/**
	 * @desription 返回列表数据方法
	 * @param tags
	 * @return return_type
	 * @date 2010-9-26 下午04:15:43
	 */
	function rgList_d() {
		$arr = $this->listBySqlId ( "select_page" );
		if (is_array ( $arr )) {
			foreach ( $arr as $key => $val ) {
				$arr [$key] ['pid'] = $arr [$key] ['id'];
				$arr [$key] ['oid'] = "g_" . $arr [$key] ['id']; //以g-为前缀表明为组合
				$arr [$key] ['oParentId'] = "g_" . $arr [$key] ['parentId'];
			}
		}
		return $arr;
	}

	/**
	 * @desription 递归项目组合
	 * @return Array 每次返回一层项目组合数组
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
	 * @desription 1.获取某一页组合  2.获取该页组合下的所有子孙组合  3.获取所有获取到的组合下的项目
	 * @param searchArr 项目跟项目组合的搜索条件，key为搜索条件名称，value为搜索值
	 * @return Array 一个混合项目组合跟项目的数组
	 */
	function pageAll_d($searchArr) {
		//获取组合第一级分页
		$this->searchArr = $searchArr;
		$this->searchArr ["parentId"] = PARENT_ID;
		$firstGroups = $this->page_d ();

		$sunNodes = $this->getChildrenByNodes ( $firstGroups );
		$mergeGroups = model_common_util::yx_array_merge ( $firstGroups, $sunNodes );

		//获取合并后组合下所有项目信息
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
			//合并项目到组合数组中
			$mergeGroups = model_common_util::yx_array_merge ( $mergeGroups, $projects );
		}

		return $mergeGroups;
	}

	/**
	 * @desription 删除方法
	 * @param tags
	 * @date 2010-10-4 下午05:04:41
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
	 * @desription 添加组合
	 * @param tags
	 * @date 2010-10-7 下午04:36:18
	 */
	function rgAdd_d ($node) {
		try {
			$this->start_d ();
			$sql = "select rgt from oa_rd_group where id=" . $node ['parentId'];
			$rs = $this->_db->get_one ( $sql );
			$parentNodeRgt = $rs ['rgt'] - 1;
			//所有左值大于父节点右值的左值加2
			$sql = "update oa_rd_project set lft=lft+2 where lft>" . $parentNodeRgt;
			$this->query ( $sql );
			$sql = "update oa_rd_group set lft=lft+2 where lft>" . $parentNodeRgt;
			$this->query ( $sql );
			//所有右值大于父节点右值的右值加2
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
			echo "异常*********************";
			$this->rollBack ();
			return null;
		}
	}


}

?>
