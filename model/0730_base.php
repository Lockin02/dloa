<?php
class model_base
{
	public $_db;
	public $tbl_name;
	public $pk;
	//=======��ҳ����===========
	public $page;
	public $start;
	public $num;
	//=========================
	function __construct()
	{
		$this->_db = new mysql(); //����MYSQL
		
		$this->page = $_GET['page'] ? $_GET['page'] : 1; //��÷�ҳ
		$this->start = ($this->page==1) ? 0 : ($this->page - 1) * pagenum; //��ҳ��ʼ��
		$this->num = $_GET['num'] ? $_GET['num'] : '';
		/**���**/
		//���������sql����·��
		if (isset ( $this->sql_map ) && ! empty ( $this->sql_map )) {
			include (CONFIG_SQL . $this->sql_map);
			if (isset ( $sql_arr )) {
				$this->sql_arr = $sql_arr;
			}
			if (isset ( $sql_map )) {
				$this->sql_map = $sql_map;
			}
		}

		//��ȡȨ��ֵ
		global $func_limit;
		$this->this_limit = $func_limit;
	}
	/**
	 * �ر�MYSQL����
	 */
	function db_close()
	{
		$this->_db->close();
	}
	/**
	 * �����ݱ��в���һ����¼
	 *
	 * @param conditions    ��������������array("�ֶ���"=>"����ֵ")���ַ�����
	 * ��ע����ʹ���ַ���ʱ������ʹ��__val_escape��������ֵ���й���
	 * @param sort    ���򣬵�ͬ�ڡ�ORDER BY ��
	 * @param fields    ���ص��ֶη�Χ��Ĭ��Ϊ����ȫ���ֶε�ֵ
	 */
	public function find($conditions = null, $sort = null, $fields = null)
	{
		if( ($record = $this->findAll($conditions, $sort, $fields, 1))!=false ){
			return array_pop($record);
		}else{
			return FALSE;
		}
	}
	
	/**
	 * ���ұ���һ���ֶεĵ�������
	 *
	 * @param string $tbl_name Ҫ���ҵı���
	 * @param string $conditions ��������
	 * @param string $fields �����ֶ���
	 * @return string
	 */
	public function get_table_fields($tbl_name,$conditions,$fields)
	{
		$rs = $this->_db->get_one("select $fields from $tbl_name where $conditions");
		return $rs[$fields];
	}
	/**
	 * �����ݱ��в��Ҽ�¼
	 *
	 * @param conditions    ��������������array("�ֶ���"=>"����ֵ")���ַ�����
	 * ��ע����ʹ���ַ���ʱ������ʹ��__val_escape��������ֵ���й���
	 * @param sort    ���򣬵�ͬ�ڡ�ORDER BY ��
	 * @param fields    ���ص��ֶη�Χ��Ĭ��Ϊ����ȫ���ֶε�ֵ
	 * @param limit    ���صĽ���������ƣ���ͬ�ڡ�LIMIT ������$limit = " 3, 5"�����Ǵӵ�3����¼��ʼ��ȡ������ȡ5����¼
	 */
	public function findAll($conditions = null, $sort = null, $fields = null, $limit = null)
	{
		$where = "";
		$fields = empty($fields) ? "*" : $fields;
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->__val_escape($condition);
				$join[] = "{$key} = '{$condition}'";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		if(null != $sort)$sort = "ORDER BY {$sort}";
		if(null != $limit)$limit = "LIMIT {$limit}";
		$sql = "SELECT {$this->tbl_name}.{$fields} FROM {$this->tbl_name} {$where} {$sort} {$limit}";
		return $this->_db->getArray($sql);
	}
	/**
	 * ����ת���ַ�
	 *
	 * @param value ��Ҫ���й��˵�ֵ
	 */
	public function __val_escape($value)
	{
		return $this->_db->__val_escape($value);
	}


	/**
	 * ʹ��SQL�����в��Ҳ��������ڽ���find��findAll�Ȳ���
	 *
	 * @param sql �ַ�������Ҫ���в��ҵ�SQL���
	 */
	public function findSql($sql)
	{
		return $this->_db->getArray($sql);
	}

	/**
	 * �����ݱ�������һ������
	 *
	 * @param row ������ʽ������ļ������ݱ��е��ֶ���������Ӧ��ֵ����Ҫ���������ݡ�
	 */
	public function create($row)
	{
		if(!is_array($row))return FALSE;
		$row = $this->__prepera_format($row);
		if(empty($row))return FALSE;
		foreach($row as $key => $value){
			$cols[] = $key;
			$vals[] = "'".$this->__val_escape($value)."'";
		}
		$col = join(',', $cols);
		$val = join(',', $vals);

		$sql = "INSERT INTO {$this->tbl_name} ({$col}) VALUES ({$val})";
		if( FALSE != $this->_db->query($sql) ){ // ��ȡ��ǰ������ID
			$newinserid = $this->_db->insert_id();
			if( $newinserid ){
				return $newinserid;
			}else{
				return array_pop( $this->find($row, "{$this->pk} DESC",$this->pk) );
			}
		}
		return FALSE;
	}

	/**
	 * �����ݱ�������������¼
	 *
	 * @param rows ������ʽ��ÿ���Ϊcreate��$row��һ������
	 */
	public function createAll($rows)
	{
		foreach($rows as $row)$this->create($row);
	}

	/**
	 * ������ɾ����¼
	 *
	 * @param conditions ������ʽ�������������˲����ĸ�ʽ�÷���find/findAll�Ĳ���������������ͬ�ġ�
	 */
	public function delete($conditions)
	{
		$where = "";
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->__val_escape($condition);
				$join[] = "{$key} = '{$condition}'";
			}
			$where = "WHERE ( ".join(" AND ",$join). ")";
		}else{
			if(null != $conditions)$where = "WHERE ( ".$conditions. ")";
		}
		$sql = "DELETE FROM {$this->tbl_name} {$where}";
		return $this->_db->query($sql);
	}

	/**
	 * ���ֶ�ֵ����һ����¼
	 *
	 * @param field �ַ�������Ӧ���ݱ��е��ֶ���
	 * @param value �ַ�������Ӧ��ֵ
	 */
	public function findBy($field, $value)
	{
		return $this->find(array($field=>$value));
	}

	/**
	 * ���ֶ�ֵ�޸�һ����¼
	 *
	 * @param conditions ������ʽ�������������˲����ĸ�ʽ�÷���find/findAll�Ĳ���������������ͬ�ġ�
	 * @param field �ַ�������Ӧ���ݱ��е���Ҫ�޸ĵ��ֶ���
	 * @param value �ַ�������ֵ
	 */
	public function updateField($conditions, $field, $value)
	{
		return $this->update($conditions, array($field=>$value));
	}

	/**
	 * ִ��SQL��䣬�����ִ���������޸ģ�ɾ���Ȳ�����
	 *
	 * @param sql �ַ�������Ҫִ�е�SQL���
	 */
	public function query($sql)
	{
		return $this->_db->query($sql);
	}
        
        public function query_e($sql)
	{
		return $this->_db->query_exc($sql);
	}
	
	public function fetch_array($query,$result_type=MYSQL_ASSOC)
	{
		return $this->_db->fetch_array($query,$result_type);
	}
	public function get_one($query)
	{
		return $this->_db->get_one($query);
	}
	/**
	 * �������ִ�е�SQL��乩����
	 */
	public function dumpSql()
	{
		return array_pop( $this->_db->arrSql );
	}

	/**
	 * ������������ļ�¼����
	 *
	 * @param conditions ��������������array("�ֶ���"=>"����ֵ")���ַ�����
	 * ��ע����ʹ���ַ���ʱ����Ҫ����������ʹ��__val_escape��������ֵ���й���
	 */
	public function findCount($conditions = null)
	{
		$where = "";
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->__val_escape($condition);
				$join[] = "{$key} = '{$condition}'";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		$sql = "SELECT COUNT({$this->pk}) as sp_counter FROM {$this->tbl_name} {$where}";
		$result = $this->_db->getArray($sql);
		return $result[0]['sp_counter'];
	}

	/**
	 * ħ��������ִ��ģ����չ����Զ����ؼ�ʹ��
	 */
	public function newclass($classname,$args='')
	{
		$classpath = WEB_TOR.str_replace('_','/',$classname).'.php';
		if (include($classpath))
		{
			if ($args)
			{
				return new $classname($args);
			}else{
				return new $classname();
			}
		}else{
			throw new Exception('�������ʧ�ܣ��������� "'.$classname.'" �Ƿ��Ŀ¼λ��һ����');
		}
	}

	/**
	 * �޸����ݣ��ú��������ݲ��������õ����������±�������
	 * 
	 * @param conditions    ������ʽ�������������˲����ĸ�ʽ�÷���find/findAll�Ĳ���������������ͬ�ġ�
	 * @param row    ������ʽ���޸ĵ����ݣ�
	 *  �˲����ĸ�ʽ�÷���create��$row����ͬ�ġ��ڷ��������ļ�¼�У�����$row���õ��ֶε����ݽ����޸ġ�
	 */
	public function update($conditions, $row)
	{
		$where = "";
		$row = $this->__prepera_format($row);
		if(empty($row))return FALSE;
		if(is_array($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->__val_escape($condition);
				$join[] = "{$key} = '{$condition}'";
			}
			$where = "WHERE ".join(" AND ",$join);
		}else{
			if(null != $conditions)$where = "WHERE ".$conditions;
		}
		foreach($row as $key => $value){
			$value = $this->__val_escape($value);
			$vals[] = "{$key} = '{$value}'";
		}
		$values = join(", ",$vals);
		$sql = "UPDATE {$this->tbl_name} SET {$values} {$where}";
		return $this->_db->query($sql);
	}

	/**
	 * �����������ݱ������ɾ����¼
	 *
	 * @param pk    �ַ��������֣����ݱ�������ֵ��
	 */
	public function deleteByPk($pk)
	{
		return $this->delete(array($this->pk=>intval($pk)));
	}

	/**
	 * �����ֶε����ʺϵ��ֶ�
	 * @param rows    ����ı��ֶ�
	 */
	public function __prepera_format($rows)
	{
		$columns = $this->_db->getTable($this->tbl_name);
		$newcol = array();
		foreach( $columns as $col ){
			$newcol[$col['Field']] = $col['Field'];
		}
		return array_intersect_key($rows,$newcol);
	}
	//======================================================================
	/**
	 * Ⱥ���ʼ�����
	 * @param string $title
	 * @param string $content
	 * @param string or array $address
	 * @param string $time
	 */
	public function  EmialTask($title,$content,$address,$time=null)
	{
		$time = $time ? $time : date('Y-m-d H:i:s');
		if ($title && $content && $address)
		{
			$address = is_array($address) ? implode(',',$address) : $address;
			return $this->_db->query("insert into email_task(title,content,address,send_time)values('$title','$content','$address','$time')");
		}else{
			return false;
		}
	}
    /**
     *���Լ�¼
     * @param <type> $db 
     */
    function pf($db,$flag=true){
        if($flag){
            file_put_contents('x.txt', $db);
        }else{
            $txt=file_get_contents('x.txt');
            file_put_contents('x.txt', $txt.$db);
        }
    }
    /**
     * ��ȡ�����������
     */
    function get_include_contents($filename) {
        if (is_file($filename)) {
            ob_start();
            include $filename;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }
    /************************************************************************************
	 * --------------------------@@����Ϊ��ͬ�ɹ����з��¿�ܽ���¼����Լ�����@@-----------------------------------
	 *********************************************************************************/
	// �����ֶΣ����ԣ���
	public $sort = "id";

	// ����˳��trueΪ����falseΪ����
	public $asc = true;

	// group by�������
	public $groupBy;

	//��¼����
	public $count;

	// ��ѯ��ֵ���� keyΪ������valueΪ��ֵ
	public $searchArr;

	// Pageÿҳ��ʾ����
	public $pageSize = pagenum;

	//=======��ҳ��������===========


	//����������ļ������sql����
	private $sql_arr;

	//����������ļ������sql����
	private $condition_arr;

	/**
	 * ���ݿ������ļ�·��
	 */
	protected $sql_map;

	/**
	 * Ȩ��
	 */
	public $this_limit;

	//���������
	private $objass = null;

	/**
	 * @desription ���ù�������
	 * @param tags
	 * @date 2010-12-20 ����07:44:20
	 */
	function setObjAss() {
		$this->objass = new model_common_objass ();
	}

	/**
	 * ����Ȩ�޿���:ͨ��������ɾ�Ĳ�
	 * �����ǶԷ��ʵ�ҳ�����Ȩ������
	 */
	function filterFunc($key) {
		if ($this->this_limit [$key] == 0) {
			msg ( 'Ȩ�޲���' );
			die ();
		}
	}

	/**
	 * �ֶ�Ȩ�޿���:���ڶ��ֶεĹ��� - �����б�ͱ�
	 * ��һ��������Ȩ������
	 * �ڶ�����������Ҫ���˵�����
	 * �����������ǹ������ͣ� form => ��(Ĭ��) ��list => �б�
	 */
	function filterField($key, $rows, $type = 'form') {
		if (isset ( $this->this_limit [$key] )) {
			$limitarr = explode ( ',', $this->this_limit [$key] );
			//			print_r($limitarr);
			$rs = array ();
			if ($type == 'form') {
				foreach ( $rows as $k => $v ) {
					if (in_array ( $k, $limitarr )) {
						$rs [$k] = '<font color="red">Ȩ�޿�������</font>';
					} else {
						$rs [$k] = $v;
					}
				}
			} elseif ($type == 'list') {
				$i = 0;
				foreach ( $rows as $k => $v ) {
					foreach ( $v as $myKey => $myVal ) {
						if (in_array ( $myKey, $limitarr )) {
							$rs [$i] [$myKey] = '<font color="red">Ȩ�޿�������</font>';
						} else {
							$rs [$i] [$myKey] = $myVal;
						}
					}
					$i ++;
				}
			}
			return $rs;
		} else {
			return $rows;
		}
	}

	/**
	 * �Զ��庯��Ȩ�޿���:ͨ�����ڴ����͹���
	 * �磺����ĳ��ɫֻ�ܲ鿴ĳ���͵���Ŀ
	 * ��һ��������Ȩ������
	 * �ڶ������������SQL��CODE
	 * ����������������
	 */
	function filterCustom($key, $code, $object) {
		//		print_r($key);
		//		print_r($code);
		//		print_r($this->this_limit);
		if (is_array ( $key )) {
			foreach ( $key as $k => $v ) {
				if (isset ( $this->this_limit [$v] )) {
					$object [$code [$k]] = $this->this_limit [$v];
				}
			}
		} else {
			if (isset ( $this->this_limit [$key] )) {
				$object [$code] = $this->this_limit [$key];
			}
		}
		//		print_r($object);
		return $object;
	}

	function __GET($name) {
		if ($name == 'start' && ! isset ( $this->start )) {
			$this->start = ($this->page == 1) ? 0 : ($this->page - 1) * $this->pageSize;
		}
		return $this->$name; //ע�⣬���õ�ʱ��nameǰҪ��$����
	}
	function __SET($name, $value) {
		$this->$name = $value;
	}
	/**
	 * �����ݱ���������������
	 *
	 * @param $row ������ʽ������ļ������ݱ��е��ֶ���������Ӧ��ֵ����Ҫ���������ݡ�
	 * @param $addObjs ������ʽ������ļ������ݱ��е��ֶ���������Ӧ��ֵ����Ҫ���������ݡ�
	 * @param $keyword �ַ�������,ƴװSQLʱ,����������е�$val[$keyword]���Ƿ���ֵ,û������Ը�������
	 */
	public function createBatch($rows, $addObjs = null, $keyword = null) {
		if (! is_array ( $rows ) || count ( $rows ) == 0)
			return FALSE;
		$valArr = array ();
		$cols = array ();
		$row_frist = reset ( $rows );
		foreach ( $row_frist as $key => $value ) {
			$cols [] = $key;
		}
		$col = join ( ',', $cols );

		//�ж��Ƿ��ж����������飬�������
		if (! empty ( $addObjs )) {
			foreach ( $addObjs as $key => $value ) {
				$col .= ',' . $key;
			}
		}

		foreach ( $rows as $row ) {
			if (! empty ( $keyword ) && empty ( $row [$keyword] )) {
				continue;
			}
			$vals = array ();
			$row = $this->__prepera_format ( $row );
			//�������ֶ�����������,�����������ɿ��ַ���(��ҪӦ��checkbox)
			foreach ( $cols as $value ) {
				if (isset ( $row [$value] )) {
					$vals [] = "'" . $this->__val_escape ( $row [$value] ) . "'";
				} else {
					$vals [] = "''";
				}
			}
			//			foreach ( $row as $key => $value ) {
			//				$vals [] = "'" . $this->__val_escape ( $value ) . "'";
			//			}
			//�ж��Ƿ��ж������飬�������
			if (! empty ( $addObjs )) {
				foreach ( $addObjs as $key => $value ) {
					$vals [] = "'" . $this->__val_escape ( $value ) . "'";
				}
			}
			$valArr [] = "(" . join ( ',', $vals ) . ")";
		}
		if (empty ( $valArr )) {
			return FALSE;
		}
		$val = implode ( ",", $valArr );
		$sql = "INSERT INTO {$this->tbl_name} ({$col}) VALUES {$val}";
		//		echo $sql;
		return $this->_db->query ( $sql );
	}

	/*
	 * ���÷�ҳ�����������action����service�������service list ����page�������ٵ���list��page������Ҫ��ղ�����Ϣ�ٽ��и�ֵ
	 */
	public function resetParam() {
		$this->page = 1;
		$this->start = 0;
		$this->sort = "id";
		$this->asc = true;
		$this->groupBy = '';
		$this->count = 0;
		$this->searchArr = array ();
	}

	/*
	 * ͳһ��ȡҳ���������
	 */
	function getParam($param) {
		if (isset ( $param ['limit'] )) {
			$this->pageSize = $param ['limit'];
			unset ( $param ['limit'] );
		}
		if (isset ( $param ['pageSize'] )) {
			$this->pageSize = $param ['pageSize'];
			unset ( $param ['pageSize'] );
		}
		if (isset ( $param ['page'] )) {
			$this->page = $param ['page'];
			unset ( $param ['page'] );
			$this->start = ($this->page == 1) ? 0 : ($this->page - 1) * $this->pageSize;
		}

		if (isset ( $param ['start'] )) {
			$this->start = $param ['start'];
			unset ( $param ['start'] );
		}
		if (isset ( $param ['sort'] )) {
			$this->sort = $param ['sort'];
			unset ( $param ['sort'] );
		}
		if (isset ( $param ['asc'] )) {
			$this->asc = $param ['asc'];
			unset ( $param ['asc'] );
		}
		if (isset ( $param ['dir'] )) {
			$this->asc = $param ['dir'] == 'ASC' ? false : true;
			unset ( $param ['dir'] );
		}
		if (isset ( $param ['groupBy'] )) {
			$this->groupBy = $param ['groupBy'];
			unset ( $param ['groupBy'] );
		}
		if (isset ( $param ['action'] )) {
			unset ( $param ['action'] );
		}
		if (isset ( $param ['model'] )) {
			unset ( $param ['model'] );
		}
		if ($param) {
			foreach ( $param as $key => $val ) {
				if ($val === null || $val === '') {
					unset ( $param [$key] );
				}
			}
		}
		$this->searchArr = $param;
		return $param;
	}

	/**
	 * ��������̬����ĳһҳselect sql��ִ��
	 * ������
	 * 1.$sql:����Ļ���select���
	 * 2.$param:����sql�������
	 * ���أ� Array ��ά����.��һά�����м�¼���ڶ�ά��ĳ����¼���ֶ�����
	 */
	function pageBySql($sql) {
		//����group by
		$groupBy = $this->groupBy;
		if (isset ( $groupBy ) && $groupBy != "" && $groupBy != "id") {
			$groupBy = " group By $groupBy ";
			$countsql = "select 0 " . substr ( $sql, strpos ( $sql, "from" ) );
			$countsql = $this->createQuery ( $countsql, $this->searchArr );
			$countsql = "select count(0) as num from ( " . $countsql . " " . $groupBy . " ) as t";
		} else {
			//�����ȡ��¼��sql
			$countsql = "select count(0) as num " . substr ( $sql, strpos ( $sql, "from" ) );
			$countsql = $this->createQuery ( $countsql, $this->searchArr ); //TODO;���Ż�������ִ������createQuery
		}
		//print($countsql);
		$this->count = $this->queryCount ( $countsql );
		//ƴװ��������
		$sql = $this->createQuery ( $sql, $this->searchArr );
		//print($sql);
		//����������Ϣ
		$asc = $this->asc ? "DESC" : "ASC";
		//echo $this->asc;
		$sql .= " $groupBy order by " . $this->sort . " " . $asc;
		//������ȡ��¼��
		$sql .= " limit " . $this->start . "," . $this->pageSize;
//		echo $sql;
		return $this->_db->getArray ( $sql );
	}

	/**
	 * ��������̬����select sql��ִ��
	 * ������
	 * 1.$sql:����Ļ���select���
	 * 2.$param:����sql�������
	 * ���أ� Array ��ά����.��һά�����м�¼���ڶ�ά��ĳ����¼���ֶ�����
	 */
	function listBySql($sql) {
		//ƴװ��������
		$sql = $this->createQuery ( $sql, $this->searchArr );
		//����group by
		$groupBy = $this->groupBy;
		if (isset ( $groupBy ) && ! empty ( $groupBy ) && $groupBy != "id") {
			$sql .= " group By $groupBy ";
		}
		//����������Ϣ
		//if (! empty ( $this->asc )) {
		$asc = $this->asc ? "DESC" : "ASC";
		$sql .= " order by " . $this->sort . " " . $asc;
		//}
		//				echo $sql."<br><br>";
		return $this->_db->getArray ( $sql );
	}

	/**
	 * ��̬����count sql��ִ��
	 */
	function queryCount($countSql) {
		$rs = $this->_db->get_one ( $countSql );
		return $rs ['num'];
	}

	/**
	 * ͨ�������ļ�sql idִ��sql���ض����ҳ��Ϣ
	 */
	function pageBySqlId($sqlId = "") {
		if (! $sqlId) {
			$sql = reset ( $this->sql_arr ); //���������$sqlId��Ĭ��ȡ�����ļ������һ����Աsql
		} else {
			$sql = $this->sql_arr [$sqlId];
		}
		return $this->pageBySql ( $sql );
	}

	/**
	 * ͨ�������ļ�sql idִ��sql���ض����б���Ϣ
	 */
	function listBySqlId($sqlId = "") {
		if (! $sqlId) {
			$sql = reset ( $this->sql_arr ); //���������$sqlId��Ĭ��ȡ�����ļ������һ����Աsql
		} else {
			$sql = $this->sql_arr [$sqlId];
		}
		return $this->listBySql ( $sql );
	}

	/**
	 * ͨ��ȡ�����ļ���̬����sql���
	 */
	function createQuery($sql, $searchArr) {
		if (stripos ( $sql, 'where' ) == false) {
			$sql .= " where 1=1 ";
		} else {
			$sql .= " "; //��ֹ�����sql�������ǿո�
		}
		if (is_array ( $searchArr )) {
			//$fileUtil = $this->commonFactory->getBean("fileUtil");
			include (CONFIG_SQL . $this->sql_map);
			//$condition_arr = util_fileUtil :: readJsonToArr($this->sql_map);


			//if (is_array($condition_arr) && is_array($searchArr)) {
			if (isset ( $condition_arr ) && is_array ( $condition_arr )) {
				foreach ( $searchArr as $key => $val ) {
					//echo $val;
					//if ($val) {
					if (! is_array ( $val )) {
						$val = $this->__val_escape ( $val ); //ת���ַ�
						if (util_jsonUtil::is_utf8 ( $val )) {
							$val = util_jsonUtil::iconvUTF2GB ( $val );
						}
					}
					foreach ( $condition_arr as $k => $v ) {
						//print($key."==".$v['name']);
						if (strcmp ( $key, $v ['name'] ) === 0) {
							//�ж��Ƿ���if��֧
							//print_r($v[0]);
							if (isset ( $v ['if'] )) {
							/**����չ
										foreach ( $v ['if'] as $kif => $vif ) {
											switch ($kif) {
												case 'equal' :
													{
														if ($val == $vif) {
															$sql .= str_replace ( "#", "'" . $val . "'", $v ['if'] ['sql'] );
															break 3;
														} else {
															if ($v ['else']) {
																$sql .= str_replace ( "#", "'" . $val . "'", $v ['else'] ['sql'] );
															}
														}
													}
											}
							 **/
							} else {
								//$sql .= str_replace ( "#", "'" . $val . "'", $v ['sql'] );
								$sql .= $this->replaceSqlCondition ( $val, $v ['sql'] ) . " ";
							}

							break;
						}
					}

		//}
				}
			}
		}
		//print($sql);
		return $sql;
	}
	/*
	 * �滻sql��������������
	 * val:�滻��ֵ
	 * condition�����滻��sql���
	 * ���أ��滻���sql���
	 */
	function replaceSqlCondition($val, $condition) {
		if (strpos ( $condition, 'in(arr)' ) > 0) {
			if (is_array ( $val )) {
				$arr = $val;
			} else {
				$arr = explode ( ',', $val ); //������ַ���ת��������
			}
			$val = "";
			foreach ( $arr as $v ) {
				$val .= "'" . $v . "',";
			}
			$val = substr ( $val, 0, - 1 );
			$conditionSql = str_replace ( "#", "'" . $val . "'", $condition );
			$conditionSql = str_replace ( "in(arr)", " in (" . $val . ")", $condition );
		} else {
			$conditionSql = str_replace ( "#", "'" . $val . "'", $condition ); //�滻��ʱ���������
			$conditionSql = str_replace ( "$", $val, $conditionSql ); //�滻��ʱ�򲻼�������
		}
		return $conditionSql;
	}

	/**
	 *������������ɾ�����¼,��������id�ַ���","���Ÿ������е��������¼��ɾ��
	 */
	function deletes($ids) {
		if (! mysql_query ( "delete from " . $this->tbl_name . " where id in(" . $ids . ")" )) {
			throw new Exception ( mysql_error () );
		}
		return true;

	}

	/**
	 * ���������޸ļ�¼
	 */
	function updateById($object) {
		$condition = array ("id" => $object ['id'] );
		return $this->update ( $condition, $object );
	}
	/**
	 * �������
	 */
	function rollBack() {
		$this->_db->query ( "ROLLBACK" );
	}
	/**
	 * ��������
	 */
	function start_d() {
		//mysql_query ( "set autocommit=0" );
		$this->_db->query ( "START TRANSACTION" );

		//mysql_query("BEGIN");//��ʼһ������
	}

	/**
	 * �ύ����
	 */
	function commit_d() {
		$this->_db->query ( "COMMIT" );
	}

	//����Ϊmodel��������ɾ�Ĳ���������ҵ������Ҫ���Խ�����д


	/**
	 * ��ȡ�����ҳ�б�����
	 */
	function page_d($sqlId = '') {
		//$this->echoSelect();
		if (! isset ( $this->sql_arr )) {
			return $this->pageBySql ( "select * from " . $this->tbl_name . " c" );
		} else {
			//var_dump($this->pageBySqlId ());
			return $this->pageBySqlId ( $sqlId );
		}

	}

	/**
	 * ��ȡ�����б�����
	 */
	function list_d($sqlId = '') {
		if (! isset ( $this->sql_arr )) {
			return $this->listBySql ( "select * from " . $this->tbl_name . " c" );
		} else {
			return $this->listBySqlId ( $sqlId );
		}

	}

	/**
	 * ������Ӷ���
	 */
	function addBatch_d($objArr, $isAddInfo = false) {
		if ($isAddInfo) {
			//$object = $this->addCreateInfo ( $objArr );
		}
		return $this->createBatch ( $objArr );
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		//���������ֵ䴦�� add by chengl 2011-05-15
		//delete xgq 20110715 $this->processDatadict ( $object );
		$newId = $this->create ( $object );
		return $newId;
	}

	/**
	 * ����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$this->deletes ( $ids );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
	/**
	 * ����������ȡ����
	 */
	function get_d($id) {
		//return $this->getObject($id);
		$condition = array ("id" => $id );
		return $this->find ( $condition );
	}
	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		//���������ֵ䴦�� add by chengl 2011-05-15
		//delete xgq 20110715 $this->processDatadict ( $object );
		return $this->updateById ( $object );
	}

	/**
	 * �������Ƿ��ظ�
	 * @��������ظ����贫��checkId
	 * @�޸ļ���ظ���Ҫ�ų��޸Ķ���id
	 */
	function isRepeat($searchArr, $checkId) {
		$countsql = "select count(id) as num  from " . $this->tbl_name . " c";
		$countsql = $this->createQuery ( $countsql, $searchArr );
		if ($checkId != '') {
			$countsql .= " and c.id!=" . $checkId;
		}
		//echo $countsql;
		$num = $this->queryCount ( $countsql );
//		echo $num;
		return ($num == 0 ? false : true);
	}

	/**
	 * ͨ������ֶδ�ӡ���select���
	 */
	function echoSelect() {
		$columns = $this->_db->getTable ( $this->tbl_name );
		$sql = "select ";
		$plus = "";
		foreach ( $columns as $k => $v ) {
			$plus .= 'c.' . $v ['Field'] . ',';
		}
		$sql .= substr ( $plus, 0, - 1 );
		$sql .= " from " . $this->tbl_name . " c where 1=1 ";
		echo $sql;
	}

	/*
	 * Ϊ����Ķ����������ˣ����ʱ�䣬�޸��ˣ��޸�ʱ�䲢�����¶���һ��������Ӷ����ʱ��ʹ��
	 */
	function addCreateInfo($obj) {
		$obj ['createId'] = $_SESSION ['USER_ID'];
		$obj ['createName'] = $_SESSION ['USERNAME'];
		$obj ['createTime'] = date ( "Y-m-d H:i:s" );
		$obj ['updateId'] = $_SESSION ['USER_ID'];
		$obj ['updateName'] = $_SESSION ['USERNAME'];
		$obj ['updateTime'] = date ( "Y-m-d H:i:s" );
		return $obj;
	}

	/*
	 * Ϊ����Ķ�������޸��ˣ��޸�ʱ�䲢�����¶���һ�������޸Ķ����ʱ��ʹ��
	 */
	function addUpdateInfo($obj) {
		$obj ['updateId'] = $_SESSION ['USER_ID'];
		$obj ['updateName'] = $_SESSION ['USERNAME'];
		$obj ['updateTime'] = date ( "Y-m-d H:i:s" );
		return $obj;
	}

	/**
	 * @exclude ͨ��Id�������������б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����07:31:59
	 */
	function arrExa_d($id) {
		$code = $this->__GET ( "tbl_name" );
		//��������
		//		$code = "equipment_borrow";
		//		$id = "12";
		$sql = "select  " . "f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task " . "from " . "flow_step f , wf_task w  " . "where  " . "w.code='$code' and " . "w.Pid='$id' and " . "f.Wf_task_ID=w.task ";
		$arr = $this->findSql ( $sql );
		if ($arr) {
			foreach ( $arr as $key => $val ) {
				$childSql = "select " . " p.Content, p.Result, p.Endtime ,p.ID ,p.Flag , u.USER_NAME as User  " . "from " . "flow_step_partent p left join user u on (p.User=u.USER_ID)" . "where " . "p.Wf_task_ID='" . $val ["task"] . "' and " . "p.SmallID='" . $val ["SmallID"] . "' ";
				$arr [$key] ["childArr"] = $this->findSql ( $childSql );
				$strExp = substr ( $arr [$key] ["User"], 0, - 1 );
				if (strstr ( $strExp, "," )) {
					$strExp = str_replace ( ",", "','", $strExp );
				}
				$sqlUser = " select USER_NAME from user where USER_ID in ('" . $strExp . "') ";
				$arrUser = $this->findSql ( $sqlUser );
				$userNames = "";
				foreach ( $arrUser as $userKey => $userVal ) {
					$userNames .= $userVal ["USER_NAME"] . ",";
				}
				$arr [$key] ["User"] = $userNames;
			}
		} else {
			$arr = false;
		}
		return $arr;
	}

	/*
	 * ����code�����ȡ�����ֵ���
	 * ���������keyΪҳ�������õ�ģ���ַ�����valueΪ��̨���õĸ���code
	 */
	function getDatadicts($parentCodeStr) {
		//�����ϼ������ȡ�����ֵ���Ϣ
		$datadictDao = new model_system_datadict_datadict ();
		$datadictArr = $datadictDao->getDatadictsByParentCodes ( $parentCodeStr );
		return $datadictArr;

	}

	/*
	 * ����ҳ�������ֵ���
	 *
	 */
	function getDatadictsStr($datadictArr, $valueCode = null) {
		if (! empty ( $datadictArr )) {
			$reStrArr = array ();
			$str = "";
			if ($valueCode) {
				foreach ( $datadictArr as $key => $value ) {
					if ($value ['dataCode'] == $valueCode)
						$str .= '<option value="' . $value ['dataCode'] . '" selected>';
					else
						$str .= '<option value="' . $value ['dataCode'] . '">';
					$str .= $value ['dataName'];
					$str .= '</option>';
				}
			} else {
				foreach ( $datadictArr as $key => $value ) {
					$str .= '<option value="' . $value ['dataCode'] . '">' . $value ['dataName'] . '</option>';
				}
			}
			return $str;
		}
	}

	/**
	 * �ֽ��ַ�����ȡ��Ӧֵ
	 */
	function cutStr($str, $sign, $location) {
		$arr = explode ( $sign, $str );
		return $arr [$location];
	}

	/*
	 * ����ҵ�����id��ȡ�����б��ַ���
	 */
	function getFilesByObjId($objId, $isShowDel = true, $serviceType = '') {
		$uploadFile = new model_file_uploadfile_management ();
		if (empty ( $serviceType )) {
			$serviceType = $this->tbl_name;
		}
		$files = $uploadFile->getFilesByObjId ( $objId, $serviceType );
		return $uploadFile->showFilelist ( $files, $isShowDel );
	}

	/*
	 * ����ҵ���������ȡ�����б��ַ���
	 */
	function getFilesByObjNo($objNo, $isShowDel = true, $serviceType = '') {
		$uploadFile = new model_file_uploadfile_management ();
		if (empty ( $serviceType )) {
			$serviceType = $this->tbl_name;
		}
		$files = $uploadFile->getFilesByObjNo ( $objNo, $serviceType );
		return $uploadFile->showFilelist ( $files, $isShowDel );
	}

	/*
	 * ����ҵ�����ʱ����ҵ����󼰸���������ϵ
	 */
	function updateObjWithFile($objId, $objCode = '') {
		//delete xgq 20110715 ��������
		//		if($_POST['delFilesId']){
		//			foreach ($_POST['delFilesId'] as $value) {
		//				$uploadFile->deletes_d ( $value );
		//			}
		//		}
		if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
			$uploadFile = new model_file_uploadfile_management ();
			$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $objId, $objCode );
		}
	}

	function saveBatch($addObjs, $editObjs) {
		try {
			$service = $this->service;
			$this->start_d ();
			$this->saveBatch ( $addObjs, $editObjs );
			$this->createAll ( $addObjs );
			foreach ( $editObjs as $key => $value ) {
				$this->edit_d ( $value, true );
			}
			$this->commit_d ();
		} catch ( Exception $e ) {
			$this->rollBack ();
		}
	}

	/**
	 * ���ݴ���Ķ��������Զ������������޸ģ�ɾ��(��Ҫ���ڽ�����ӱ��жԴӱ�������������)
	 * �жϹ���
	 * 1.���idΪ����isDelTag����Ϊ1����������������������Ӻ�ɾ�����,��̨ɶ��������
	 * 2.���idΪ�գ�������
	 * 3.���isDelTag����Ϊ1����ɾ��
	 * 4.�����޸�
	 * @param Array $objs
	 */
	function saveDelBatch($objs) {
		if (! is_array ( $objs )) {
			throw new Exception ( "������������飡" );
		}
		$returnObjs = array ();
		foreach ( $objs as $key => $val ) {
			$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;
			if (empty ( $val ['id'] ) && $isDelTag== 1) {

			} else if (empty ( $val ['id'] )) {
				$id = $this->add_d ( $val );
				$val ['id'] = $id;
				array_push ( $returnObjs, $val );
			} else if ($isDelTag == 1) {
				$this->deletes ( $val ['id'] );
			} else {
				$this->edit_d ( $val );
				array_push ( $returnObjs, $val );
			}
		}
		return $returnObjs;
	}

	/**
	 *
	 * �����ֵ䴦�������������ƣ� add by chengl 2011-05-15
	 * @param unknown_type $object
	 */
	function processDatadict($object) {
		//���������ֵ��Զ�����
		if (isset ( $this->datadictFieldArr ) && is_array ( $this->datadictFieldArr )) {
			$datadictDao = new model_system_datadict_datadict ();
			foreach ( $object as $key => $value ) {
				if (in_array ( $key, $this->datadictFieldArr )) {
					$object [$key . "Name"] = $datadictDao->getDataNameByCode ( $value );
				}
			}
		}
		return $object;
	}
}
?>