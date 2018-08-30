<?php
/**
 * @description: ���ڵ�Model���Ľ�ǰ���������
 * @date 2010-9-23
 * @author chengl
 * @version V1.0
 */
class model_treeNode extends model_base {

	private $tabName;

	public $treeCondFields; //���ݲ�ͬ�����ֶΣ�����֮����and�������γ��γɶ���������û�����ô��ֶΣ������ݿ���ֻ��һ����


	/**
	 * @desription ���캯��
	 */
	function __construct() {
		//$this->tbl_name = "oa_rd_group";
		//$this->sql_map = "rdproject/group/rdgroupSql.php";
		parent::__construct ();
		$tabName = $this->tbl_name;

	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ�ӿڷ���,����Ϊ����ģ��������--------------------------*
	 **************************************************************************************************/

	public function setTabName($tabName = "") {
		if ($tabName) {
			$this->tabName = $tabName;
		} else {
			$this->tabName = $this->tbl_name;
		}
	}

	/**
	 * @desription ����������
	 * @param $node �������ڵ�
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
//			$conditionStr .= " and parentId!=" . PARENT_ID; //����Ƕ������������Ը��ڵ�����ֵ���κβ�����
//		}
		return $conditionStr;
	}

	/**
	 * @desription ����baseModel�������Ľӿ�
	 * @param $node ���뱣������ڵ�
	 */
	function addBase_d($node) {
		return parent::add_d ( $node, true );
	}

	/**
	 * @desription �������ڵ�
	 * @param $node ���뱣������ڵ�
	 */
	public function add_d($node) {
		try {
			$this->start_d ();
			//���������߲������ҽڵ�id
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
	 * @desription �༭���ڵ�
	 * @param $node:�༭�����ڵ�
	 * @return $node:�༭������ڵ�
	 */
	function edit_d($node) {
		try {
			$this->start_d ();
			//���������߲������ҽڵ�id
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
	 * @desription ����ɾ�����ڵ�
	 * @param $ids:���ڵ�id���ϣ���,����
	 */
	function deletes($ids) {
		try {
			$this->start_d ();
			$this->deleteNodes ( $ids );
			parent::deletes ( $ids );
			//toDO ɾ���ڵ��µ��ӽڵ�
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception ( $e->getMessage () );
			return false;
		}
	}

	/**
	 * @desription �����ڵ㣺��ҪΪ���ڵ㴴������ֵ��һ��������ӽڵ�ʱʹ��
	 * @param $node ������ӵĽڵ����
	 * @return $node ��������ֵ��Ľڵ����
	 */
	public function createNode($node) {
		$sqlPlus = $this->getTreeSqlCondition ( $node );
		/**
		 * 1-1.����Ƕ�������Ҹ����Ǹ��ڵ㣬�ж��Ƿ��Ǹ��ڵ��µĵ�һ���ڵ�
		 * 1-1-1.����Ǹ��ڵ��µĵ�һ���ڵ㣬��ֵΪ2����ֵΪ3
		 * 1-1-2.���򣺻�ȡͬ�������ֵ���ֵ����ֵΪ��+1����ֵ+2��������Ӧ����������
		 * 2-1.���򣺻�ȡ���׽ڵ����ֵ-1����ֵΪ��+1����ֵ+2
		 */
		//��������Ҹ���Ϊ���ڵ����
		if (! empty ( $sqlPlus ) && $node ['parentId'] == PARENT_ID) {
			$sql = "select count(id) as num from " . $this->tbl_name . " where parentId=" . $node ['parentId'] . $sqlPlus;
			//echo $sql;
			$nodeNum = $this->_db->get_one ( $sql );
			if ($nodeNum ['num'] == 0) { //����Ǹ��ڵ��µ�һ���ڵ�
				$node ['lft'] = 2;
				$node ['rgt'] = 3;
			} else {
				$sql = "select max(rgt) as rgt from " . $this->tbl_name . " where parentId=" . $node ['parentId'] . $sqlPlus;
				$maxNode = $this->_db->get_one ( $sql );
				$node ['lft'] = $maxNode ['rgt'] + 1;
				$node ['rgt'] = $maxNode ['rgt'] + 2;
			}
		} else {
			//��ȡ�丸�ڵ����ֵ
			$sql = "select rgt from " . $this->tbl_name . " where id=" . $node ['parentId'] . $sqlPlus;
			$parentNode = $this->_db->get_one ( $sql );
			if (is_array ( $parentNode )) {
				$parentNodeRgt = $parentNode ['rgt'] - 1;
				//������ֵ���ڸ��ڵ���ֵ����ֵ��2
				$sql = "update " . $this->tbl_name . " set lft=lft+2 where lft>" . $parentNodeRgt . $sqlPlus;
				$this->query ( $sql );
				//������ֵ���ڸ��ڵ���ֵ����ֵ��2
				$sql = "update " . $this->tbl_name . " set rgt=rgt+2 where rgt>" . $parentNodeRgt . $sqlPlus;
				$this->query ( $sql );

				$node ['lft'] = $parentNodeRgt + 1;
				$node ['rgt'] = $parentNodeRgt + 2;
			}
		}
		return $node;
	}

	/**
	 * @desription �ƶ�/�޸Ľڵ�ʱ���õķ���:�൱������һ���ڵ��ɾ��ԭ�ڵ�
	 * @param $node �����ƶ�/�޸ĵĽڵ����
	 * @return $node ����������ֵ��Ľڵ����
	 */
	public function moveNode($node) {
		//��ȡԭ�ڵ�����
		$oldNode = $this->get_d ( $node ['id'] );

		//����ƶ����׽ڵ㲻�䣬ֱ�ӷ����޸Ľڵ�
		if ($node ['parentId'] == $oldNode ['parentId']) {
			unset($node['lft']);
			unset($node['rgt']);
			return $node;
		}

		//�ж��ƶ����ĸ��׽ڵ��Ƿ�Ϊ�ƶ��ڵ������ڵ㣬������׳��쳣
		$parentNode = $this->get_d ( $node ['parentId'] );
//		echo $oldNode ['lft']."==>".$parentNode ['lft'];
//		echo $oldNode ['rgt']."==>".$parentNode ['rgt'];
		if ($oldNode ['lft'] <= $parentNode ['lft'] && $oldNode ['rgt'] >= $parentNode ['rgt']) {
			throw new Exception ( "����ʧ�ܣ����ڵ㲻��Ϊ�ýڵ������ڵ���߱���ڵ�!" );
		}
		//ɾ��ԭ�ڵ�
		$this->deleteNode ( $oldNode );
		//�����½ڵ�
		$node = $this->createNode ( $node );
		return $node;
	}

	/**
	 * @desription ɾ���ڵ�ʱ���÷���
	 * @param $node ����ɾ���Ľڵ����
	 */
	public function deleteNode($node) {
		if (! isset ( $node ['lft'] )) {
			$node = $this->get_d ( $node ['id'] );
		}
		$sqlPlus = $this->getTreeSqlCondition ( $node );
		if (isset ( $node ['lft'] )) {
			//���д���ɾ���ڵ����ֵ��2
			$sql = "update " . $this->tbl_name . " set lft=lft-2 where lft>" . $node ['lft'] . $sqlPlus;
			$this->query ( $sql );
			//���д���ɾ���ڵ����ֵ��2
			$sql = "update " . $this->tbl_name . " set rgt=rgt-2 where rgt>" . $node ['rgt'] . $sqlPlus;
			$this->query ( $sql );
		}
	}

	/**
	 * @desription ����ɾ���ڵ�ʱ���÷���
	 * @param $node ����ɾ���Ľڵ����
	 */
	public function deleteNodes($nodeIds) {
		//������Ҫ��*����Ϊ���ܻ��ж������������ڣ���Ҫ�����������ֶθ��趯̬ƴװ
		$sql = "select * from " . $this->tbl_name . " where id in(" . $nodeIds . ")";
		$nodes = $this->_db->getArray ( $sql );
		foreach ( $nodes as $node ) {
			$this->deleteNode ( $node );
		}
	}

	/**
	 * @desription ��ȡĳ���ڵ������ڵ�(δ����)
	 * @param $node ����ڵ����
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
	 * @desription ��ȡĳЩ�ڵ��µ�����ڵ�
	 * @param $node ����ڵ�����
	 */
	public function getChildrenByNodes($nodes) {
		if (! is_array ( $nodes )) {
			throw new Exception ( "����Ĳ����������飡" );
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
		//��ȡ����ڵ���ϵ������������
		//$this->searchArr = array ("children" => $sqlPlus );
		$sql = reset ( $this->sql_arr );
		$sql .= $sqlPlus;
		//echo $sql;
		return $this->_db->getArray ( $sql );
	}

	/**
	 * @desription ����/����������������ֵ
	 */
	public function createTreeLRValue() {
		//�����������ֵ
		$sql = "update " . $this->tbl_name . " set lft=null,rgt=null";
		$this->query ( $sql );
		//���¸��ڵ�����ֵΪ1��2
		$sql = "update " . $this->tbl_name . " set lft=1,rgt=2 where id=-1";
		$this->query ( $sql );
		//��ȡ���ڵ���ӽڵ㼯��
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
	 * @desription ���Ľڵ����������ֵ
	 * @param $nodes ���ĵĽڵ�����
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
	 * �����ڵ�ʱ�����������ҽڵ���Ϣ
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
