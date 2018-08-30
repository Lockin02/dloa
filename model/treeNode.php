<?php
/**
 * @description: 树节点Model（改进前序遍历树）
 * @date 2010-9-23
 * @author chengl
 * @version V1.0
 */
class model_treeNode extends model_base {

	private $tabName;

	public $treeCondFields; //根据不同条件字段（条件之间用and）分组形成形成多颗树，如果没有设置此字段，则数据库里只有一棵树


	/**
	 * @desription 构造函数
	 */
	function __construct() {
		//$this->tbl_name = "oa_rd_group";
		//$this->sql_map = "rdproject/group/rdgroupSql.php";
		parent::__construct ();
		$tabName = $this->tbl_name;

	}

	/***************************************************************************************************
	 * ------------------------------以下为接口方法,可以为其他模块所调用--------------------------*
	 **************************************************************************************************/

	public function setTabName($tabName = "") {
		if ($tabName) {
			$this->tabName = $tabName;
		} else {
			$this->tabName = $this->tbl_name;
		}
	}

	/**
	 * @desription 构造树条件
	 * @param $node 传入树节点
	 */
	private function getTreeSqlCondition($node) {
		$conditionStr = "";
		if (is_array ( $this->treeCondFields )) {
			foreach ( $this->treeCondFields as $value ) {
				if ($node [$value] == null) {
					$conditionStr .= " and " . $value . " is null ";
				} else {
					$conditionStr .= " and " . $value . " = '" . $node [$value] . "'";
				}
			}
		}
//		if (! empty ( $conditionStr )) {
//			$conditionStr .= " and parentId!=" . PARENT_ID; //如果是多棵树情况，不对根节点左右值做任何操作。
//		}
		return $conditionStr;
	}

	/**
	 * @desription 保留baseModel里新增的接口
	 * @param $node 传入保存的树节点
	 */
	function addBase_d($node) {
		return parent::add_d ( $node, true );
	}

	/**
	 * @desription 保存树节点
	 * @param $node 传入保存的树节点
	 */
	public function add_d($node) {
		try {
			$this->start_d ();
			//调用树工具产生左右节点id
			$node = $this->createNode ( $node );
			$newId = parent::add_d ( $node, true );
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * @desription 编辑树节点
	 * @param $node:编辑的树节点
	 * @return $node:编辑后的树节点
	 */
	function edit_d($node) {
		try {
			$this->start_d ();
			//调用树工具产生左右节点id
			$node = $this->moveNode ( $node );
			$node = parent::edit_d ( $node, true );
			$this->commit_d ();
			return $node;
		} catch ( Exception $e ) {
			msg ( $e->getMessage () );
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * @desription 批量删除树节点
	 * @param $ids:树节点id集合，以,隔开
	 */
	function deletes($ids) {
		try {
			$this->start_d ();
			$this->deleteNodes ( $ids );
			parent::deletes ( $ids );
			//toDO 删除节点下得子节点
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception ( $e->getMessage () );
			return false;
		}
	}

	/**
	 * @desription 创建节点：主要为树节点创建左右值，一般用于添加节点时使用
	 * @param $node 传入添加的节点对象
	 * @return $node 产生左右值后的节点对象
	 */
	public function createNode($node) {
		$sqlPlus = $this->getTreeSqlCondition ( $node );
		/**
		 * 1-1.如果是多棵树并且父亲是根节点，判断是否是根节点下的第一个节点
		 * 1-1-1.如果是根节点下的第一个节点，左值为2，右值为3
		 * 1-1-2.否则：获取同级最大的兄弟右值，左值为其+1，右值+2（这里适应多棵树情况）
		 * 2-1.否则：获取父亲节点的右值-1，左值为其+1，右值+2
		 */
		//多棵树并且父亲为根节点情况
		if (! empty ( $sqlPlus ) && $node ['parentId'] == PARENT_ID) {
			$sql = "select count(id) as num from " . $this->tbl_name . " where parentId=" . $node ['parentId'] . $sqlPlus;
			//echo $sql;
			$nodeNum = $this->_db->get_one ( $sql );
			if ($nodeNum ['num'] == 0) { //如果是根节点下第一个节点
				$node ['lft'] = 2;
				$node ['rgt'] = 3;
			} else {
				$sql = "select max(rgt) as rgt from " . $this->tbl_name . " where parentId=" . $node ['parentId'] . $sqlPlus;
				$maxNode = $this->_db->get_one ( $sql );
				$node ['lft'] = $maxNode ['rgt'] + 1;
				$node ['rgt'] = $maxNode ['rgt'] + 2;
			}
		} else {
			//获取其父节点的右值
			$sql = "select rgt from " . $this->tbl_name . " where id=" . $node ['parentId'] . $sqlPlus;
			$parentNode = $this->_db->get_one ( $sql );
			if (is_array ( $parentNode )) {
				$parentNodeRgt = $parentNode ['rgt'] - 1;
				//所有左值大于父节点右值的左值加2
				$sql = "update " . $this->tbl_name . " set lft=lft+2 where lft>" . $parentNodeRgt . $sqlPlus;
				$this->query ( $sql );
				//所有右值大于父节点右值的右值加2
				$sql = "update " . $this->tbl_name . " set rgt=rgt+2 where rgt>" . $parentNodeRgt . $sqlPlus;
				$this->query ( $sql );

				$node ['lft'] = $parentNodeRgt + 1;
				$node ['rgt'] = $parentNodeRgt + 2;
			}
		}
		return $node;
	}

	/**
	 * @desription 移动/修改节点时调用的方法:相当于新增一个节点后删除原节点
	 * @param $node 传入移动/修改的节点对象
	 * @return $node 产生新左右值后的节点对象
	 */
	public function moveNode($node) {
		//获取原节点数据
		$oldNode = $this->get_d ( $node ['id'] );

		//如果移动父亲节点不变，直接返回修改节点
		if ($node ['parentId'] == $oldNode ['parentId']) {
			unset($node['lft']);
			unset($node['rgt']);
			return $node;
		}

		//判断移动到的父亲节点是否为移动节点的子孙节点，如果是抛出异常
		$parentNode = $this->get_d ( $node ['parentId'] );
//		echo $oldNode ['lft']."==>".$parentNode ['lft'];
//		echo $oldNode ['rgt']."==>".$parentNode ['rgt'];
		if ($oldNode ['lft'] <= $parentNode ['lft'] && $oldNode ['rgt'] >= $parentNode ['rgt']) {
			throw new Exception ( "操作失败，父节点不能为该节点的子孙节点或者本身节点!" );
		}
		//删除原节点
		$this->deleteNode ( $oldNode );
		//创建新节点
		$node = $this->createNode ( $node );
		return $node;
	}

	/**
	 * @desription 删除节点时调用方法
	 * @param $node 传入删除的节点对象
	 */
	public function deleteNode($node) {
		if (! isset ( $node ['lft'] )) {
			$node = $this->get_d ( $node ['id'] );
		}
		$sqlPlus = $this->getTreeSqlCondition ( $node );
		if (isset ( $node ['lft'] )) {
			//所有大于删除节点的左值减2
			$sql = "update " . $this->tbl_name . " set lft=lft-2 where lft>" . $node ['lft'] . $sqlPlus;
			$this->query ( $sql );
			//所有大于删除节点的右值减2
			$sql = "update " . $this->tbl_name . " set rgt=rgt-2 where rgt>" . $node ['rgt'] . $sqlPlus;
			$this->query ( $sql );
		}
	}

	/**
	 * @desription 批量删除节点时调用方法
	 * @param $node 传入删除的节点对象
	 */
	public function deleteNodes($nodeIds) {
		//这里需要用*，因为可能会有多棵树的情况存在，需要检索出条件字段给予动态拼装
		$sql = "select * from " . $this->tbl_name . " where id in(" . $nodeIds . ")";
		$nodes = $this->_db->getArray ( $sql );
		foreach ( $nodes as $node ) {
			$this->deleteNode ( $node );
		}
	}

	/**
	 * @desription 获取某个节点的子孙节点(未测试)
	 * @param $node 传入节点对象
	 */
	public function getChildrenByNode($node,$getType=false) {
		$sqlPlus = $this->getTreeSqlCondition ( $node );
		$sql="";
		if($getType){
				$sql = "select * from " . $this->tbl_name . " where (lft>=" . $node ['lft'] . " and rgt<=" . $node ['rgt'] . ")" . $sqlPlus;
		}else{
			$sql = "select * from " . $this->tbl_name . " where (lft>" . $node ['lft'] . " and rgt<" . $node ['rgt'] . ")" . $sqlPlus;
		//echo $sql;
		}
		$nodes = $this->_db->getArray ( $sql );
		return $nodes;
	}

	/**
	 * @desription 获取某些节点下的子孙节点
	 * @param $node 传入节点数组
	 */
	public function getChildrenByNodes($nodes) {
		if (! is_array ( $nodes )) {
			throw new Exception ( "传入的参数不是数组！" );
		}
		$sqlPlus = "";
		$sqlPlusExclude .="";
		foreach ( $nodes as $value ) {
			$sqlPlus .= "or (lft>" . $value ['lft'] . " and rgt<" . $value ['rgt'] . ")";
			$sqlPlusExclude .=" and (id !=".$value['id'].")";
		}
		if (! empty ( $sqlPlus )) {
			$sqlPlus = " and (" . substr ( $sqlPlus, 3 ) . ")";
		}
		$sqlPlus .= $sqlPlusExclude;
		$sqlPlus .= $this->getTreeSqlCondition ( $nodes [0] );
		//获取传入节点组合的所有子孙组合
		//$this->searchArr = array ("children" => $sqlPlus );
		$sql = reset ( $this->sql_arr );
		$sql .= $sqlPlus;
		//echo $sql;
		return $this->_db->getArray ( $sql );
	}

	/**
	 * @desription 产生/更新整颗树的左右值
	 */
	public function createTreeLRValue() {
		//清空所有左右值
		$sql = "update " . $this->tbl_name . " set lft=null,rgt=null";
		$this->query ( $sql );
		//更新根节点左右值为1及2
		$sql = "update " . $this->tbl_name . " set lft=1,rgt=2 where id=-1";
		$this->query ( $sql );
		//获取根节点的子节点集合
		$this->asc=false;
		$this->searchArr = array ("parentId" => - 1 );
		$firstNodes = $this->list_d ();
		if (is_array ( $firstNodes )) {
			foreach ( $firstNodes as $key => $value ) {
				$parentSql = "select rgt from " . $this->tbl_name . " where id=" . $value ['parentId'];
				$parentArr = $this->findSql ( $parentSql );
				if (is_array ( $parentArr )) {
					$this->updateNodes ( $parentArr [0] ['rgt'], $parentArr [0] ['rgt'] + 1 );
					$sql = "update " . $this->tbl_name . " set lft=" . $parentArr [0] ['rgt'] . ",rgt=" . ($parentArr [0] ['rgt'] + 1) . " where id=" . $value ['id'];
					$this->query ( $sql );
					
					$this->searchArr = array ("parentId" => $value ['id'] );
					$nodes = $this->list_d ();
					if (is_array ( $nodes )) {
						$this->createNodesLRValue ( $nodes );
					}
				}
			}
		}
	}
	
	/**
	 * @desription 更改节点数组的左右值
	 * @param $nodes 更改的节点数组
	 */
	private function createNodesLRValue($nodes) {
		foreach ( $nodes as $key => $value ) {
			//			$value = $this->moveNode ( $value );
			$parentSql = "select rgt from " . $this->tbl_name . " where id=" . $value ['parentId'];
			$parentArr = $this->findSql ( $parentSql );
			if (is_array ( $parentArr )) {
				$sql = "update " . $this->tbl_name . " set lft=" . ($parentArr [0] ['rgt']) . ",rgt=" . ($parentArr [0] ['rgt'] + 1) . " where id=" . $value ['id'];
				$this->updateNodes ( $parentArr [0] ['rgt'], $parentArr [0] ['rgt'] + 1 );
				$this->query ( $sql );
				
				$this->searchArr = array ("parentId" => $value ['id'] );
				$ns = $this->list_d ();
				if (is_array ( $ns )) {
					$this->createNodesLRValue ( $ns );
				}
			}
		}
	}
	
	/**
	 * 新增节点时更新树的左右节点信息
	 * 
	 * @param  $lft
	 * @param  $rgt
	 */
	private function updateNodes($lft, $rgt) {
		//		$lftSql = "update " . $this->tbl_name . " set lft=lft+2 where lft>=" . $lft;
		$rgtSql = "update " . $this->tbl_name . " set rgt=rgt+2 where rgt>=" . $lft;
		//		echo "<br>";
		$this->query ( $rgtSql );
	}

}

?>
