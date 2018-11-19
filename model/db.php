<?php

class model_db
{
    public $_db;

    //缓存DB对象
    static $instance = array();

    //注册表配置
    static function getInstance($config = 'DEFAULT')
    {
        if (empty(self::$instance[$config])) {
            self::$instance[$config] = new mysql();
        }
        return self::$instance[$config];
    }

    public $tbl_name;
    public $pk = 'id';
    //标志数据库事务 - 新添加字段
    static $transaction_tag = 0;

    //公司权限处理 TODO
    protected $_isSetCompany = 0; # 单据是否要区分公司,1为区分,0为不区分
    protected $_comLocal = null; # 归属公司过滤部分坐标 一般情况下为空,格式为 array('公司过滤位置' => '过滤业务key'); 表中相应添加 #公司过滤位置


    public $isBlankSearch = false;

    //=========================
    function __construct()
    {
        $this->_db = self::getInstance(); //加载MYSQL
        $this->page = isset ($_GET ['page']) ? $_GET ['page'] : (isset ($_POST ['page']) ? $_POST ['page'] : 1); //获得分页

        /************************************************************************************
         * --------------------------@@以下为合同采购及研发新框架新加代码@@-----------------------------------
         *********************************************************************************/
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * $this->pageSize; //分页开始数
        //如果设置了sql配置路径
        if (isset ($this->sql_map) && !empty ($this->sql_map)) {
            if (file_exists('D:/wamp2/www/dloa/model/' . $this->sql_map)) {
                include('D:/wamp2/www/dloa/model/' . $this->sql_map);
            }
            if (isset ($sql_arr)) {
                $this->sql_arr = $sql_arr;
            }
            if (isset ($sql_map)) {
                $this->sql_map = $sql_map;
            }
        }

        //获取权限值
        global $func_limit;
        $this->this_limit = $func_limit;

        //公司过滤
        if ($this->_isSetCompany == 1) {
            include(WEB_TOR . '/model/common/belongRegister.php');
            # 默认指向表的别称是 c
            $defaultBelongArr = isset($defaultBelongArr) ? $defaultBelongArr : null;
            $this->_comLocal['c'] = isset($belongArr[$this->tbl_name]) ? $belongArr[$this->tbl_name] : $defaultBelongArr;
        }
    }

    /**
     * 关闭MYSQL连接
     */
    function db_close($config = 'DEFAULT')
    {
        // 关闭数据库连接
        $this->_db->close();

        // 清空对应的注册表
        if (isset(self::$instance[$config])) {
            unset(self::$instance[$config]);
        }
    }

    /**
     * 从数据表中查找一条记录
     *
     * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
     * 请注意在使用字符串时请自行使用__val_escape来对输入值进行过滤
     * @param sort    排序，等同于“ORDER BY ”
     * @param fields    返回的字段范围，默认为返回全部字段的值
     */
    public function find($conditions = null, $sort = null, $fields = null)
    {
        if (($record = $this->findAll($conditions, $sort, $fields, 1)) != false) {
            return array_pop($record);
        } else {
            return FALSE;
        }
    }

    /**
     * 查找表中一个字段的单条数据
     *
     * @param string $tbl_name 要查找的表名
     * @param string $conditions 查找条件
     * @param string $fields 返回字段名
     * @return string
     */
    public function get_table_fields($tbl_name, $conditions, $fields)
    {
        $rs = $this->_db->get_one("select $fields from $tbl_name where $conditions");
        return $rs[$fields];
    }

    /**
     * 从数据表中查找记录
     *
     * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
     * 请注意在使用字符串时请自行使用__val_escape来对输入值进行过滤
     * @param sort    排序，等同于“ORDER BY ”
     * @param fields    返回的字段范围，默认为返回全部字段的值
     * @param limit    返回的结果数量限制，等同于“LIMIT ”，如$limit = " 3, 5"，即是从第3条记录开始获取，共获取5条记录
     */
    public function findAll($conditions = null, $sort = null, $fields = null, $limit = null)
    {
        $where = "";
        $fields = empty($fields) ? "*" : $fields;
        if (is_array($conditions)) {
            $join = array();
            foreach ($conditions as $key => $condition) {
                $condition = $this->__val_escape($condition);
                $join[] = " `{$key}` = '{$condition}' ";
            }
            $where = "WHERE " . join(" AND ", $join);
        } else {
            if (null != $conditions) $where = "WHERE " . $conditions;
        }
        if (null != $sort) $sort = "ORDER BY {$sort}";
        if (null != $limit) $limit = "LIMIT {$limit}";
        $sql = "SELECT {$this->tbl_name}.{$fields} FROM {$this->tbl_name} {$where} {$sort} {$limit}";
        return $this->_db->getArray($sql);
    }

    /**
     * 过滤转义字符
     *
     * @param value 需要进行过滤的值
     */
    public function __val_escape($value)
    {
        return $this->_db->__val_escape($value);
    }


    /**
     * 使用SQL语句进行查找操作，等于进行find，findAll等操作
     *
     * @param sql 字符串，需要进行查找的SQL语句
     */
    public function findSql($sql)
    {
        return $this->_db->getArray($sql);
    }

    /**
     * 在数据表中新增一行数据
     *
     * @param row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
     */
    public function create($row)
    {
        if (!is_array($row)) return FALSE;
        $row = $this->__prepera_format($row);
        if (empty($row)) return FALSE;
        foreach ($row as $key => $value) {
            $cols[] = "`" . $key . "`";
            $vals[] = "'" . $this->__val_escape($value) . "'";
        }
        $col = join(',', $cols);
        $val = join(',', $vals);

        $sql = "INSERT INTO {$this->tbl_name} ({$col}) VALUES ({$val})";

        if (FALSE != $this->_db->query($sql)) { // 获取当前新增的ID
            $newinserid = $this->_db->insert_id();
            //file_put_contents('xxxx',$newinserid);
            if ($newinserid) {
                return $newinserid;
            } else {
                return array_pop($this->find($row, "{$this->pk} DESC", $this->pk));
            }
        }
        return FALSE;
    }

    /**
     * 在数据表中新增多条记录
     *
     * @param rows 数组形式，每项均为create的$row的一个数组
     */
    public function createAll($rows)
    {
        foreach ($rows as $row) $this->create($row);
    }

    /**
     * 按条件删除记录
     *
     * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
     */
    public function delete($conditions)
    {
        $where = "";
        if (is_array($conditions)) {
            $join = array();
            foreach ($conditions as $key => $condition) {
                $condition = $this->__val_escape($condition);
                $join[] = "`{$key}` = '{$condition}'";
            }
            $where = "WHERE ( " . join(" AND ", $join) . ")";
        } else {
            if (null != $conditions) $where = "WHERE ( " . $conditions . ")";
        }
        $sql = "DELETE FROM {$this->tbl_name} {$where}";
        return $this->_db->query($sql);
    }

    /**
     * 按字段值查找一条记录
     *
     * @param field 字符串，对应数据表中的字段名
     * @param value 字符串，对应的值
     */
    public function findBy($field, $value)
    {
        return $this->find(array($field => $value));
    }

    /**
     * 按字段值修改一条记录
     *
     * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
     * @param field 字符串，对应数据表中的需要修改的字段名
     * @param value 字符串，新值
     */
    public function updateField($conditions, $field, $value)
    {
        return $this->update($conditions, array($field => $value));
    }

    /**
     * 执行SQL语句，相等于执行新增，修改，删除等操作。
     *
     * @param sql 字符串，需要执行的SQL语句
     */
    public function query($sql)
    {
        return $this->_db->query($sql);
    }

    public function query_e($sql)
    {
        return $this->_db->query_exc($sql);
    }

    public function fetch_array($query, $result_type = MYSQL_ASSOC)
    {
        return $this->_db->fetch_array($query, $result_type);
    }

    public function get_one($query)
    {
        return $this->_db->get_one($query);
    }

    /**
     * 返回最后执行的SQL语句供分析
     */
    public function dumpSql()
    {
        return array_pop($this->_db->arrSql);
    }

    /**
     * 计算符合条件的记录数量
     *
     * @param conditions 查找条件，数组array("字段名"=>"查找值")或字符串，
     * 请注意在使用字符串时将需要开发者自行使用__val_escape来对输入值进行过滤
     */
    public function findCount($conditions = null)
    {
        $where = "";
        if (is_array($conditions)) {
            $join = array();
            foreach ($conditions as $key => $condition) {
                $condition = $this->__val_escape($condition);
                $join[] = "`{$key}` = '{$condition}'";
            }
            $where = "WHERE " . join(" AND ", $join);
        } else {
            if (null != $conditions) $where = "WHERE " . $conditions;
        }
        $sql = "SELECT COUNT({$this->pk}) as sp_counter FROM {$this->tbl_name} {$where}";
        $result = $this->_db->getArray($sql);
        return $result[0]['sp_counter'];
    }

    /**
     * 魔术函数，执行模型扩展类的自动加载及使用
     */
    public function newclass($classname, $args = '')
    {
        $classpath = WEB_TOR . str_replace('_', '/', $classname) . '.php';
        if (include($classpath)) {
            if ($args) {
                return new $classname($args);
            } else {
                return new $classname();
            }
        } else {
            throw new Exception('加载类库失败，请检查类名 "' . $classname . '" 是否和目录位置一样！');
        }
    }

    /**
     * 修改数据，该函数将根据参数中设置的条件而更新表中数据
     *
     * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
     * @param row    数组形式，修改的数据，
     *  此参数的格式用法与create的$row是相同的。在符合条件的记录中，将对$row设置的字段的数据进行修改。
     */
    public function update($conditions, $row)
    {
        $where = "";
        $row = $this->__prepera_format($row);
        if (isset($row[$this->pk]) && isset($conditions[$this->pk]) && $row[$this->pk] == $conditions[$this->pk]) unset($row[$this->pk]); // 如果更新数据中含有主键，并且跟条件中的主键值相同，则去掉该更新内容
        if (empty($row)) return FALSE;
        if (is_array($conditions)) {
            $join = array();
            foreach ($conditions as $key => $condition) {
                $condition = $this->__val_escape($condition);
                $join[] = "`{$key}` = '{$condition}'";
            }
            $where = "WHERE " . join(" AND ", $join);
        } else {
            if (null != $conditions) $where = "WHERE " . $conditions;
        }
        foreach ($row as $key => $value) {
            $value = $this->__val_escape($value);
            $vals[] = "`{$key}` = '{$value}'";
        }
        $values = join(", ", $vals);
        $sql = "UPDATE {$this->tbl_name} SET {$values} {$where}";
        return $this->_db->query($sql);
    }

    /**
     * 按给定的数据表的主键删除记录
     *
     * @param pk    字符串或数字，数据表主键的值。
     */
    public function deleteByPk($pk)
    {
        return $this->delete(array($this->pk => intval($pk)));
    }

    /**
     * 按表字段调整适合的字段
     * @param rows    输入的表字段
     */
    public function __prepera_format($rows)
    {
        $columns = $this->_db->getTable($this->tbl_name);
        $newcol = array();
        foreach ($columns as $col) {
            $newcol[$col['Field']] = $col['Field'];
        }
        return array_intersect_key($rows, $newcol);
    }
    //======================================================================
    /**
     * 群发邮件任务
     * @param string $title
     * @param string $content
     * @param string or array $address
     * @param string $time
     */
    public function  EmialTask($title, $content, $address, $time = null, $attPath = '', $attFileStr = '')
    {
        $time = $time ? $time : date('Y-m-d H:i:s');
        if ($title && $content && $address) {
            $address = is_array($address) ? implode(',', $address) : $address;
            return $this->_db->query("insert into email_task(title,content,address,send_time,attPath,attFileStr)values('$title','$content','$address','$time','$attPath','$attFileStr')");
        } else {
            return false;
        }
    }

    public function EmailTask($title, $content, $address, $time = null, $attPath = '', $attFileStr = '')
    {
        return $this->EmialTask($title, $content, $address, $time, $attPath, $attFileStr);
    }

    /**
     *测试记录
     * @param <type> $db
     */
    function pf($db, $flag = true)
    {
        if ($flag) {
            file_put_contents('x.txt', $db);
        } else {
            $txt = file_get_contents('x.txt');
            file_put_contents('x.txt', $txt . $db);
        }
    }

    /**
     * 读取公共类库数据
     */
    function get_include_contents($filename)
    {
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
     * --------------------------@@以下为合同采购及研发新框架结合新加属性及函数@@-----------------------------------
     *********************************************************************************/
    // 排序字段（属性）名
    public $sort = "id";

    // 排序顺序（true为降序，false为升序）
    public $asc = true;

    // group by语句列名
    public $groupBy;

    //记录总数
    public $count;

    // 查询列值数组 key为列名，value为列值
    public $searchArr;

    // Page每页显示条数
    public $pageSize = pagenum;

    //=======分页参数结束===========


    //存放在配置文件里面的sql数组
    private $sql_arr;

    //存放在配置文件里面的sql条件
    private $condition_arr;

    /**
     * 数据库配置文件路径
     */
    protected $sql_map;

    /**
     * 权限
     */
    public $this_limit;

    //对象关联类
    private $objass = null;

    /**
     * @desription 设置关联对象
     * @param tags
     * @date 2010-12-20 下午07:44:20
     */
    function setObjAss()
    {
        $this->objass = new model_common_objass ();
    }

    /**
     * 功能权限控制:通常用于增删改查
     * 作用是对访问的页面进行权限限制
     */
    function filterFunc($key)
    {
        if ($this->this_limit [$key] == 0) {
            msg('权限不足');
            die ();
        }
    }

    /**
     * 字段权限控制:用于对字段的过滤 - 包括列表和表单
     * 第一个参数是权限名称
     * 第二个参数是需要过滤的数组
     * 第三个参数是过滤类型： form => 表单(默认) ，list => 列表
     * 第四个参数：需要过滤的字段
     * 第五个参数：获取其他模块的权限
     */
    function filterField($key, $rows, $type = 'form', $modelName = '')
    {
        //update by chengl 2012-02-01 加入另外模块权限判断
        $limit = $this->this_limit;
        if (!empty($modelName)) {
            $otherdatasDao = new model_common_otherdatas();
            $limit = $otherdatasDao->getUserPriv($modelName, $_SESSION ['USER_ID']);
        }
        if (isset ($limit [$key])) {
            $limitarr = explode(',', $limit [$key]);
            //			print_r($limitarr);
            $rs = array();
            if ($type == 'form') {
                foreach ($rows as $k => $v) {
                    if (in_array($k, $limitarr)) {
                        $rs [$k] = '<span class="red">******</span>';
                    } else {
                        $rs [$k] = $v;
                    }
                }
            } elseif ($type == 'list') {
                $i = 0;
                foreach ($rows as $k => $v) {
                    foreach ($v as $myKey => $myVal) {
                        if (in_array($myKey, $limitarr)) {
                            $rs [$i] [$myKey] = '<font color="red">******</font>';
                        } else {
                            $rs [$i] [$myKey] = $myVal;
                        }
                    }
                    $i++;
                }
            }
            return $rs;
        } else {
            return $rows;
        }
    }

    /**
     * 自定义函数权限控制:通常用于大类型过滤
     * 如：限制某角色只能查看某类型的项目
     * 第一个参数是权限名称
     * 第二个参数是组合SQL的CODE
     * 第三个参数是数组
     */
    function filterCustom($key, $code, $object)
    {
        //		print_r($key);
        //		print_r($code);
        //		print_r($this->this_limit);
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                if (isset ($this->this_limit [$v])) {
                    $object [$code [$k]] = $this->this_limit [$v];
                }
            }
        } else {
            if (isset ($this->this_limit [$key])) {
                $object [$code] = $this->this_limit [$key];
            }
        }
        //		print_r($object);
        return $object;
    }

    function __GET($name)
    {
        if ($name == 'start' && !isset ($this->start)) {
            $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * $this->pageSize;
        }
        return $this->$name; //注意，设置的时候name前要加$符号
    }

    function __SET($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * 在数据表中批量新增数据
     *
     * @param $row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
     * @param $addObjs 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
     * @param $keyword 字符串类型,拼装SQL时,会检验数组中的$val[$keyword]中是否有值,没有则忽略该条数据
     */
    public function createBatch($rows, $addObjs = null, $keyword = null)
    {
        if (!is_array($rows) || count($rows) == 0)
            return FALSE;
        $valArr = array();
        $cols = array();
        $row_frist = reset($rows);
//		foreach ( $row_frist as $key => $value ) {
//			$cols [] = $key;
//		}
        $cols = array();
        foreach ($rows as $key => $row) {
            $row = $this->__prepera_format($row);
            $rows[$key] = $row;
        }
        foreach ($rows as $key => $value) {
            $keyarr = array_keys($value);
            $cols = array_merge($cols, $keyarr);//合并所有数据列
        }
        $cols = array_unique($cols);
        $col = join(',', $cols);

        //判断是否有额外增加数组，有则加上
        if (!empty ($addObjs)) {
            foreach ($addObjs as $key => $value) {
                $col .= ',' . $key;
            }
        }

        foreach ($rows as $row) {
            if (!empty ($keyword) && empty ($row [$keyword])) {
                continue;
            }
            $vals = array();

            //按输入字段来生成数组,不存在则生成空字符串(主要应对checkbox)
            foreach ($cols as $value) {
                if (isset ($row [$value])) {
                    $vals [] = "'" . $this->__val_escape($row [$value]) . "'";
                } else {
                    $vals [] = "''";
                }
            }
            //			foreach ( $row as $key => $value ) {
            //				$vals [] = "'" . $this->__val_escape ( $value ) . "'";
            //			}
            //判断是否有额外数组，有则加上
            if (!empty ($addObjs)) {
                foreach ($addObjs as $key => $value) {
                    $vals [] = "'" . $this->__val_escape($value) . "'";
                }
            }
            $valArr [] = "(" . join(',', $vals) . ")";
        }
        if (empty ($valArr)) {
            return FALSE;
        }
        $val = implode(",", $valArr);
        $sql = "INSERT INTO {$this->tbl_name} ({$col}) VALUES {$val}";
        //		echo $sql;
        return $this->_db->query($sql);
    }

    /*
     * 重置分页所需参数，在action或者service里面调用service list 或者page方法后，再调用list或page方法需要清空参数信息再进行赋值
     */
    public function resetParam()
    {
        $this->page = 1;
        $this->start = 0;
        $this->sort = "id";
        $this->asc = true;
        $this->groupBy = '';
        $this->count = 0;
        $this->searchArr = array();
    }

    /*
     * 统一获取页面参数方法
     */
    function getParam($param)
    {

        if (isset ($param ['limit'])) {
            $this->pageSize = $param ['limit'];
            unset ($param ['limit']);
        }
        if (isset ($param ['pageSize'])) {
            $this->pageSize = $param ['pageSize'];
            unset ($param ['pageSize']);
        }
        if (isset ($param ['page'])) {
            $this->page = $param ['page'];
            unset ($param ['page']);
            $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * $this->pageSize;
        }

        if (isset ($param ['start'])) {
            $this->start = $param ['start'];
            unset ($param ['start']);
        }
        if (isset ($param ['sort'])) {
            $this->sort = $param ['sort'];
            unset ($param ['sort']);
        }
        if (isset ($param ['asc'])) {
            $this->asc = $param ['asc'];
            unset ($param ['asc']);
        }
        if (isset ($param ['dir'])) {
            $this->asc = $param ['dir'] == 'ASC' ? false : true;
            unset ($param ['dir']);
        }
        if (isset ($param ['groupBy'])) {
            $this->groupBy = $param ['groupBy'];
            unset ($param ['groupBy']);
        }
        if (isset ($param ['action'])) {
            unset ($param ['action']);
        }
        if (isset ($param ['model'])) {
            unset ($param ['model']);
        }
        if ($param) {
            foreach ($param as $key => $val) {
                if ($val === null || $val === '') {
                    unset ($param [$key]);
                }
            }
        }
        $this->searchArr = $param;
        //处理高级搜索
        if (is_array($param['advArr'])) {
            $this->processAdvsearch($param['advArr']);
            unset ($param ['advArr']);
            unset ($this->searchArr ['advArr']);
        }
        //处理排序
        if (is_array($param['sortArr'])) {
            $this->processAdvsort($param['sortArr']);
            unset ($param ['sortArr']);
        }
        //print_r($this->searchArr);
        return $param;
    }

    /**
     * 处理多字段排序
     */
    function processAdvsort($sortArr)
    {
        $sortPlus = "";
        foreach ($sortArr as $key => $valArr) {
            $sortField = $valArr['sortField'];
            $sort = $valArr['sort'];
            $sortPlus .= $sortField . " " . $sort . ",";
        }
        $this->sort = substr($sortPlus, 0, strlen($sortPlus) - 5);
        $asc = trim(substr($sortPlus, strlen($sortPlus) - 5, 4));
        $this->asc = ($asc == 'ASC' ? false : true);
        return $sortPlus;
    }
    /***************************** 增加部分 ***********************************/
    //高级搜索条件，用于传回前台
    public $advSql = '';


    /***************************** 修改部分 ***********************************/
    /**
     * 处理高级搜索
     */
    function processAdvsearch($advArr)
    {
        $advSql = "sql:";
        $i = 0;
        foreach ($advArr as $key => $valArr) {
            $i++;
            $leftK = $valArr['leftK'];//左括号
            $rightK = $valArr['rightK'];//右括号
            $logic = $valArr['logic'];//逻辑
            if ($i == 1) {
                $logic = "and";
                $leftK .= "(";
            }
            $searchField = $valArr['searchField'];//查询字段
            //为解决同一字段在高级搜索中多次应用（如合同属性），在定义高级搜索时，字段后加 “*”后在加两位唯一标识
            $tempStr = substr($searchField, -3, 1);
            if ($tempStr == "*") {
                $searchField = substr($searchField, 0, -3);
            }
            $compare = $valArr['compare'];//比较关系
            $value = $valArr['value'];//数值
            if (util_jsonUtil::is_utf8($value)) {
                $value = util_jsonUtil::iconvUTF2GB($value);
            }
            if (strpos($compare, "like") !== false) {
                $value = "%" . $value . "%";
            }
            $advSql .= $logic . " " . $leftK . $searchField . " " . $compare . " '" . $value . "'" . $rightK;
        }
        //echo $advSql;
        $advSql .= ")";
        $this->advSql = $advSql;
        $this->searchArr['advSql'] = $advSql;
        //echo $advSql;
    }

    /**
     * 描述：动态构建某一页select sql并执行
     * 参数：
     * 1.$sql:传入的基本select语句
     * 2.$param:构造sql所需参数
     * 返回： Array 二维数组.第一维是所有记录，第二维是某条记录的字段数组
     */
    function pageBySql($sql)
    {
        //构建group by
        $groupBy = $this->groupBy;
        $countPos = strpos($sql, "@");
        //拼装搜索条件
        $sql = $this->createQuery($sql, $this->searchArr);
        if (empty($countPos)) {
            $countPos = strpos($sql, "from");
        } else {
            $sql = str_replace("@", "", $sql);
        }
        if (isset ($groupBy) && $groupBy != "" && $groupBy != "id") {
            $groupBy = " group By $groupBy ";
            $countsql = "select 0 " . substr($sql, $countPos);
            $countsql = "select count(0) as num from ( " . $countsql . " " . $groupBy . " ) as t";
        } else {
            //构造获取记录数sql
            $countsql = "select count(0) as num " . substr($sql, $countPos);
        }
        $this->count = $this->queryCount($countsql);
        //如果查询出来的记录数大于 （当前分页 - 1）*页记录条数，那么定位到第一页 2014-01-21 kuangzw
        /*
        if($this->count < ($this->page - 1)* $this->pageSize){
            $this->page = 1;
            $this->start = 0;
        }*/

        //拼装聚合函数
        $sql .= $groupBy;

        //构建排序信息
        if (!empty($this->sort)) {
            $asc = $this->asc ? "DESC" : "ASC";
            //echo $this->asc;
            $sql .= " order by " . $this->sort . " " . $asc;
        }
        $this->listSql = $sql;
        //print($sql);
        //构建获取记录数
        $sql .= " limit " . $this->start . "," . $this->pageSize;
//        header("Content-type:text/html;charset=gbk");
//        print_r($sql);die();
        return $this->_db->getArray($sql);
    }

    /**
     * 描述：动态构建select sql并执行
     * 参数：
     * 1.$sql:传入的基本select语句
     * 2.$param:构造sql所需参数
     * 返回： Array 二维数组.第一维是所有记录，第二维是某条记录的字段数组
     */
    function listBySql($sql)
    {
        $sql = str_replace("@", "", $sql);
        //拼装搜索条件
        $sql = $this->createQuery($sql, $this->searchArr);
        //构建group by
        $groupBy = $this->groupBy;
        if (isset ($groupBy) && !empty ($groupBy) && $groupBy != "id") {
            $sql .= " group By $groupBy ";
        }
        //构建排序信息
        if (!empty($this->sort)) {
            $asc = $this->asc ? "DESC" : "ASC";
            //echo $this->asc;
            $sql .= " order by " . $this->sort . " " . $asc;
        }
        $this->listSql = $sql;
        return $this->_db->getArray($sql);
    }

    /**
     * 动态构建count sql并执行
     */
    function queryCount($countSql)
    {
        $rs = $this->_db->get_one($countSql);
        return $rs ['num'];
    }

    /**
     * 通过配置文件sql id执行sql返回对象分页信息
     */
    function pageBySqlId($sqlId = "")
    {
        if (!$sqlId) {
            $sql = reset($this->sql_arr); //如果不传入$sqlId，默认取配置文件数组第一个成员sql
        } else {
            $sql = $this->sql_arr [$sqlId];
        }
        return $this->pageBySql($sql);
    }

    /**
     * 通过配置文件sql id执行sql返回对象列表信息
     */
    function listBySqlId($sqlId = "")
    {
        if (!$sqlId) {
            $sql = reset($this->sql_arr); //如果不传入$sqlId，默认取配置文件数组第一个成员sql
        } else {
            $sql = $this->sql_arr [$sqlId];
        }
        return $this->listBySql($sql);
    }


    /**
     * 通过取配置文件动态构建sql语句
     */
    function createQuery($sql, $searchArr)
    {
        if (stripos($sql, 'where') == false) {
            $sql .= " where 1 ";
        } else {
            $sql .= " "; //防止传入的sql语句最后不是空格
        }
        //加入公司处理
        if ($this->_isSetCompany == 1) {
            $limitStr = isset($this->this_limit['公司权限']) && !empty($this->this_limit['公司权限']) ? $this->this_limit['公司权限'] : $_SESSION['Company'];
            if (strpos($limitStr, ';;') === false) {# 如果含有所有权限,跳过此处理
                $innerLimit = util_jsonUtil::strBuild($limitStr);
                if (strpos($sql, '#') === false) { # 含有需要替换的部分
                    foreach ($this->_comLocal as $k => $v) {
                        $sql .= " and ($k." . $v['businessBelong'] . " in($innerLimit))";
                        break;
                    }
                } else {
                    foreach ($this->_comLocal as $k => $v) {
                        $comSql = " and ($k." . $v['formBelong'] . " in($innerLimit))";
                        $sql = str_replace("#" . $k, $comSql, $sql);
                    }
                }
            }
        }
        if (is_array($searchArr)) {
            include("D:/wamp2/www/dloa/model/".$this->sql_map);
            //include(CONFIG_SQL . $this->sql_map);
            if (isset ($condition_arr) && is_array($condition_arr)) {
                array_push($condition_arr, array("name" => "advSql", "sql" => "$"));
//				$isSearchTag=false;
//				//如果是搜索标识，加上拼音搜索
//				if($searchArr['isSearchTag_']==true){
//					$isSearchTag=true;
//				}
                foreach ($searchArr as $key => $val) {
                    $isSqlVal = false;
                    //echo $val;
                    //if ($val) {
                    if (!is_array($val)) {
                        if (substr($val, 0, 4) != 'sql:') {//如果是整段sql，则无需转义 add by chengl 2011-10-25
                            $val = $this->__val_escape($val); //转义字符
                        } else {
                            $val = substr($val, 4);
                            $isSqlVal = true;
                        }
                        if (util_jsonUtil::is_utf8($val)) {
                            $val = util_jsonUtil::iconvUTF2GB($val);
                        }
                    }
                    $isFindAll = false;
                    if (strpos($key, ',')) {
                        $isFindAll = true;
                    }
                    //搜索所有的情况
                    if ($isFindAll == true) {
                        $keyArr = explode(",", $key);
                        $sqlPlus = "";
                        if ($isSqlVal == false) {
                            //add by chengl 加入空格搜索判断
                            if (!$this->isBlankSearch && !is_array($val)) {
                                $valArr = preg_split("/\s+/", $val);
                            } else {
                                $valArr = array($val);
                            }
                            foreach ($valArr as $vv) {
                                $sqlPlus_ = "";
                                foreach ($condition_arr as $k => $v) {
                                    if (!is_array($vv)) $vv = trim($vv);
                                    if (in_array($v ['name'], $keyArr)) {
                                        $sqlCondition = $v ['sql'];
                                        $condition = $this->replaceSqlCondition($vv, $sqlCondition);
                                        $condition = substr_replace(trim($condition), " or ", 0, 3);
                                        $sqlPlus_ .= $condition;
                                        //加上拼音
//										if($isSearchTag==true){
//											$sqlCondition1=$this->addPingyin($sqlCondition);
//											if(!empty($sqlCondition1)){
//												$condition=$this->replaceSqlCondition ( $vv, $sqlCondition1 );
//												$condition=substr_replace(trim($condition)," or ",0,3);
//												$sqlPlus_ .= $condition;
//											}
//										}
                                    }
                                }
                                $sqlPlus_ = substr(trim($sqlPlus_), 2);
                                $sqlPlus .= " or(" . $sqlPlus_ . ")";
                            }
                            $sql = $sql . "and (" . substr(trim($sqlPlus), 2) . ")";
                        } else {
                            foreach ($condition_arr as $k => $v) {
                                if (in_array($v ['name'], $keyArr)) {
                                    $sqlCondition = $v ['sql'];
                                    $condition = $this->replaceSqlCondition($val, $sqlCondition);
                                    $condition = substr_replace(trim($condition), " or ", 0, 3);
                                    $sqlPlus_ .= $condition;
                                }
                            }
                            $sqlPlus = substr(trim($sqlPlus), 2);
                            $sqlPlus = " and(" . $sqlPlus . ")";
                            $sql = $sql . $sqlPlus;
                        }
                    } else {
                        foreach ($condition_arr as $k => $v) {
                            if (strcmp($key, $v ['name']) === 0) {
                                //add by chengl 加入空格搜索判断
                                if ($isSqlVal == false) {
                                    if (!$this->isBlankSearch && !is_array($val)) {
                                        $valArr = preg_split("/\s+/", $val);
                                    } else {
                                        $valArr = array($val);
                                    }
                                    $sqlPlus = "";
                                    //print_r($valArr);
                                    foreach ($valArr as $vv) {
                                        if (!is_array($vv)) $vv = trim($vv);
                                        $sqlPlus_ = $this->replaceSqlCondition($vv, $v ['sql']) . " ";
//										if($isSearchTag==true){
//											$sqlCondition1=$this->addPingyin($v ['sql']);
//											if(!empty($sqlCondition1)){
//												$condition=" ".$this->replaceSqlCondition ( $vv, $sqlCondition1 ) . " ";
//												$condition=substr_replace(trim($condition)," or ",0,3);
//												$sqlPlus_ .= $condition;
//											}
//										}
                                        $sqlPlus_ = substr(trim($sqlPlus_), 3);
                                        $sqlPlus .= " or(" . $sqlPlus_ . ")";
                                    }
                                    //echo $sql;
                                    $sqlPlus = substr(trim($sqlPlus), 2);
                                    $sql .= " and(" . $sqlPlus . ")";
                                } else {
                                    $sql .= $this->replaceSqlCondition($val, $v ['sql']) . " ";
                                }
                                //echo $sqlPlus_;
                                break;
                            }
                        }
                    }
                }
            }
        }
        //如果是完全匹配，替换like为=
        if (isset($searchArr['isEqualSearch'])) {
            if ($searchArr['isEqualSearch'] == true) {
                $sql = str_replace(" like ", " = ", $sql);
                $sql = str_replace("'%'", "''", $sql);
                //某些百分号不能替换的，要替换回来
                $sql = str_replace("'Y-m-d'", "'%Y-%m-%d'", $sql);
            }
        }
//		echo $sql;
        return $sql;
    }

    /*
     * 替换sql条件方法并返回
     * val:替换的值
     * condition：待替换的sql语句
     * 返回：替换后的sql语句
     */
    function replaceSqlCondition($val, $condition)
    {
        if (strpos($condition, 'in(arr)') > 0) {
            if (is_array($val)) {
                $arr = $val;
            } else {
                $arr = explode(',', $val); //如果是字符串转换成数组
            }
            $val = "";
            foreach ($arr as $v) {
                $val .= "'" . $v . "',";
            }
            $val = substr($val, 0, -1);
            $conditionSql = str_replace("#", "'" . $val . "'", $condition);
            $conditionSql = str_replace("in(arr)", " in (" . $val . ")", $condition);
        } else {
            $conditionSql = str_replace("#", "'" . $val . "'", $condition); //替换的时候加上引号
            $conditionSql = str_replace("$", $val, $conditionSql); //替换的时候不加上引号
        }
        return $conditionSql;
    }

    /**
     *根据主键批量删除表记录,根据所传id字符中","符号个数进行单或多条记录的删除
     */
    function deletes($ids)
    {
        if (!$this->_db->query("delete from " . $this->tbl_name . " where id in(" . $ids . ")")) {
            throw new Exception (mysql_error());
        }
        return true;

    }

    /**
     * 根据主键修改记录
     */
    function updateById($object)
    {
        $condition = array("id" => $object ['id']);
        return $this->update($condition, $object);
    }

    /**
     * 事务回退
     */
    function rollBack()
    {
        $this->_db->query("ROLLBACK");
    }

    /**
     * 开启事务
     */
    function start_d()
    {
        //echo "START".self::$transaction_tag."</br>";
        if (self::$transaction_tag == 0) {
            $this->_db->query("START TRANSACTION");
        };
        self::$transaction_tag++;
    }

    /**
     * 提交事务
     */
    function commit_d()
    {
        //echo "COMMIT".self::$transaction_tag."</br>";
        if (self::$transaction_tag == 1) {
            $this->_db->query("COMMIT");
        }
        self::$transaction_tag--;
    }

    //以下为model基本的增删改操作方法，业务上需要可以进行重写


    /**
     * 获取对象分页列表数组
     */
    function page_d($sqlId = '')
    {
        //$this->echoSelect();
        if (!isset ($this->sql_arr)) {
            return $this->pageBySql("select * from " . $this->tbl_name . " c");
        } else {
            //var_dump($this->pageBySqlId ());
            return $this->pageBySqlId($sqlId);
        }

    }

    /**
     * 获取对象列表数组
     */
    function list_d($sqlId = '')
    {
        if (!isset ($this->sql_arr)) {
            return $this->listBySql("select * from " . $this->tbl_name . " c");
        } else {
            return $this->listBySqlId($sqlId);
        }

    }

    /**
     * 批量添加对象
     * function addBatch_d($objArr, $isAddInfo = false) {
     * if ($isAddInfo) {
     * //$object = $this->addCreateInfo ( $objArr );
     * }
     * return $this->createBatch ( $objArr );
     * }
     */


    /**
     * 添加对象
     */
    function add_d($object, $isAddInfo = false)
    {
        if ($isAddInfo) {
            $object = $this->addCreateInfo($object);
        }

        //加入公司处理 TODO
        //add by kuangzw 2014-01-03
        if ($this->_isSetCompany == 1) {
            if (!isset($object[current(current($this->_comLocal))])) {//如果启用了公司而且新增时公司属性不存在，则系统自动添加
                foreach (current($this->_comLocal) as $v) {
                    $object[$v] = $_SESSION['USER_COM'];
                    $object[$v . 'Name'] = $_SESSION['USER_COM_NAME'];
                }
            }
        }

        //加入数据字典处理 add by chengl 2011-05-15
        $newId = $this->create($object);
        return $newId;
    }

    /**
     * 批量删除对象
     */
    function deletes_d($ids)
    {
        try {
            $this->deletes($ids);
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }

    /**
     * 根据主键获取对象
     */
    function get_d($id)
    {
        //return $this->getObject($id);
        $condition = array("id" => $id);
        return $this->find($condition);
    }

    /**
     * 根据主键修改对象
     */
    function edit_d($object, $isEditInfo = false)
    {
        if ($isEditInfo) {
            $object = $this->addUpdateInfo($object);
        }
        //加入数据字典处理 add by chengl 2011-05-15
        //delete xgq 20110715 $this->processDatadict ( $object );
        return $this->updateById($object);
    }

    /**
     * 检查对象是否重复
     * @新增检查重复无需传入checkId
     * @修改检查重复需要排除修改对象id
     */
    function isRepeat($searchArr, $checkId)
    {
        $countsql = "select count(id) as num  from " . $this->tbl_name . " c";
        $this->isBlankSearch = true;
        $countsql = $this->createQuery($countsql, $searchArr);
        if ($checkId != '') {
            $countsql .= " and c.id!=" . $checkId;
        }
        //echo $countsql;
        $num = $this->queryCount($countsql);
//		echo $num;
        return ($num == 0 ? false : true);
    }

    /**
     * 通过表格字段打印表格select语句
     */
    function echoSelect()
    {
        $columns = $this->_db->getTable($this->tbl_name);
        $sql = "select ";
        $plus = "";
        foreach ($columns as $k => $v) {
            $plus .= 'c.' . $v ['Field'] . ',';
        }
        $sql .= substr($plus, 0, -1);
        $sql .= " from " . $this->tbl_name . " c where 1=1 ";
        echo $sql;
    }

    /*
     * 为传入的对象添加添加人，添加时间，修改人，修改时间并返回新对象，一般用于添加对象的时候使用
     */
    function addCreateInfo($obj)
    {
        $obj ['createId'] = $_SESSION ['USER_ID'];
        $obj ['createName'] = $_SESSION ['USERNAME'];
        $obj ['createTime'] = date("Y-m-d H:i:s");
        $obj ['updateId'] = $_SESSION ['USER_ID'];
        $obj ['updateName'] = $_SESSION ['USERNAME'];
        $obj ['updateTime'] = date("Y-m-d H:i:s");
        return $obj;
    }

    /*
     * 为传入的对象添加修改人，修改时间并返回新对象，一般用于修改对象的时候使用
     */
    function addUpdateInfo($obj)
    {
        $obj ['updateId'] = $_SESSION ['USER_ID'];
        $obj ['updateName'] = $_SESSION ['USERNAME'];
        $obj ['updateTime'] = date("Y-m-d H:i:s");
        return $obj;
    }

    /**
     * @exclude 通过Id返回审批数据列表
     * @author ouyang
     * @param
     * @return
     * @version 2010-8-12 下午07:31:59
     */
    function arrExa_d($id)
    {
        $code = $this->__GET("tbl_name");
        //测试数据
        //		$code = "equipment_borrow";
        //		$id = "12";
        $sql = "select  " . "f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task " . "from " . "flow_step f , wf_task w  " . "where  " . "w.code='$code' and " . "w.Pid='$id' and " . "f.Wf_task_ID=w.task ";
        $arr = $this->findSql($sql);
        if ($arr) {
            foreach ($arr as $key => $val) {
                $childSql = "select " . " p.Content, p.Result, p.Endtime ,p.ID ,p.Flag , u.USER_NAME as User  " . "from " . "flow_step_partent p left join user u on (p.User=u.USER_ID)" . "where " . "p.Wf_task_ID='" . $val ["task"] . "' and " . "p.SmallID='" . $val ["SmallID"] . "' ";
                $arr [$key] ["childArr"] = $this->findSql($childSql);
                $strExp = substr($arr [$key] ["User"], 0, -1);
                if (strstr($strExp, ",")) {
                    $strExp = str_replace(",", "','", $strExp);
                }
                $sqlUser = " select USER_NAME from user where USER_ID in ('" . $strExp . "') ";
                $arrUser = $this->findSql($sqlUser);
                $userNames = "";
                foreach ($arrUser as $userKey => $userVal) {
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
     * 根据code数组获取数据字典项
     * 传入的数组key为页面上设置的模板字符串，value为后台设置的父亲code
     */
    function getDatadicts($parentCodeStr)
    {
        //根据上级编码获取数据字典信息
        $datadictDao = new model_system_datadict_datadict ();
        $datadictArr = $datadictDao->getDatadictsByParentCodes($parentCodeStr);
        return $datadictArr;

    }

    /*
     * 设置页面数据字典项
     *
     */
    function getDatadictsStr($datadictArr, $valueCode = null)
    {
        if (!empty ($datadictArr)) {
            $reStrArr = array();
            $str = "";
            if ($valueCode) {
                foreach ($datadictArr as $key => $value) {
                    if ($value ['dataCode'] == $valueCode)
                        $str .= '<option value="' . $value ['dataCode'] . '" selected>';
                    else
                        $str .= '<option value="' . $value ['dataCode'] . '">';
                    $str .= $value ['dataName'];
                    $str .= '</option>';
                }
            } else {
                foreach ($datadictArr as $key => $value) {
                    $str .= '<option value="' . $value ['dataCode'] . '">' . $value ['dataName'] . '</option>';
                }
            }
            return $str;
        }
    }

    /**
     * 分解字符串获取对应值
     */
    function cutStr($str, $sign, $location)
    {
        $arr = explode($sign, $str);
        return $arr [$location];
    }

    /*
     * 根据业务对象id获取附件列表字符串
     */
    function getFilesByObjId($objId, $isShowDel = true, $serviceType = '')
    {
        $uploadFile = new model_file_uploadfile_management ();
        if (empty ($serviceType)) {
            $serviceType = $this->tbl_name;
        }
        $files = $uploadFile->getFilesByObjId($objId, $serviceType);
        return $uploadFile->showFilelist($files, $isShowDel);
    }

    /*
     * 根据业务对象编码获取附件列表字符串
     */
    function getFilesByObjNo($objNo, $isShowDel = true, $serviceType = '')
    {
        $uploadFile = new model_file_uploadfile_management ();
        if (empty ($serviceType)) {
            $serviceType = $this->tbl_name;
        }
        $files = $uploadFile->getFilesByObjNo($objNo, $serviceType);
        return $uploadFile->showFilelist($files, $isShowDel);
    }

    /*
     * 新增业务对象时更新业务对象及附件关联关系
     */
    function updateObjWithFile($objId, $objCode = '')
    {
        //delete xgq 20110715 附件处理
        //		if($_POST['delFilesId']){
        //			foreach ($_POST['delFilesId'] as $value) {
        //				$uploadFile->deletes_d ( $value );
        //			}
        //		}
        if (isset ($_POST ['fileuploadIds']) && is_array($_POST ['fileuploadIds'])) {
            $uploadFile = new model_file_uploadfile_management ();
            $uploadFile->updateFileAndObj($_POST ['fileuploadIds'], $objId, $objCode);
        }
    }

    function saveBatch($addObjs, $editObjs)
    {
        try {
            $service = $this->service;
            $this->start_d();
            $this->saveBatch($addObjs, $editObjs);
            $this->createAll($addObjs);
            foreach ($editObjs as $key => $value) {
                $this->edit_d($value, true);
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * 根据传入的对象数组自动进行新增，修改，删除(主要用于解决主从表中对从表对象的批量操作)
     * 判断规则：
     * 1.如果id为空且isDelTag属性为1（这种情况属于如界面上添加后删除情况,后台啥都不做）
     * 2.如果id为空，则新增
     * 3.如果isDelTag属性为1，则删除
     * 4.否则修改
     * @param Array $objs
     */
    function saveDelBatch($objs)
    {
        if (!is_array($objs)) {
            throw new Exception ("传入对象不是数组！");
        }
        $returnObjs = array();
        foreach ($objs as $key => $val) {
            $isDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
            if (empty ($val ['id']) && $isDelTag == 1) {

            } else if (empty ($val ['id'])) {
                $id = $this->add_d($val);
                $val ['id'] = $id;
                array_push($returnObjs, $val);
            } else if ($isDelTag == 1) {
                $this->deletes($val ['id']);
            } else {
                $this->edit_d($val);
                array_push($returnObjs, $val);
            }
        }
        return $returnObjs;
    }

    /**
     *
     * 数据字典处理（加上冗余名称） add by chengl 2011-05-15
     * @param unknown_type $object
     */
    function processDatadict($object)
    {
        //进行数据字典自动处理
        if (isset ($this->datadictFieldArr) && is_array($this->datadictFieldArr)) {
            $datadictDao = new model_system_datadict_datadict ();
            foreach ($object as $key => $value) {
                if (in_array($key, $this->datadictFieldArr)) {
                    $object [$key . "Name"] = $datadictDao->getDataNameByCode($value);
                }
            }
        }
        return $object;
    }

    /**
     * 字段权限控制:用于对字段的过滤 - 包括列表和表单
     * 第一个参数是权限名称
     * 第二个参数是需要过滤的数组
     * 第三个参数是过滤类型： form => 表单(默认:权限类型为表字段) ，list => 列表(权限类型为表字段) , 'keyForm' => 表单(权限类型为0或者1)
     * 第四个参数：需要过滤的字段
     * 第五个参数：获取其他模块的权限
     */
    function filterWithoutField($key, $rows, $type = 'form', $filterArr, $modelName)
    {
        //update by chengl 2012-02-01 加入另外模块权限判断
        $limit = $this->this_limit;
        if (!empty($modelName)) {
            $otherdatasDao = new model_common_otherdatas();
            $limit = $otherdatasDao->getUserPriv($modelName, $_SESSION ['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        }

        if ($type == 'form') {
            $limitArr = isset($limit [$key]) ? explode(',', $limit [$key]) : array();

            foreach ($rows as $k => $v) {
                if (in_array($k, $filterArr)) {
                    if (!in_array($k, $limitArr)) {
                        $rows [$k] = '******';
                    }
                }
            }
        } elseif ($type == 'list') {
            $limitArr = isset($limit [$key]) ? explode(',', $limit [$key]) : array();
            $i = 0;
            foreach ($rows as $k => $v) {
                foreach ($v as $myKey => $myVal) {
                    if (in_array($myKey, $filterArr)) {
                        if (!in_array($myKey, $limitArr)) {
                            $rows [$i] [$myKey] = '******';
                        }
                    }
                }
                $i++;
            }
        } elseif ($type == 'keyForm') {
            $limitArr = isset($limit [$key]) && !empty($limit [$key]) ? 1 : 0;
//			print_r($key);
            foreach ($rows as $k => $v) {
                if (in_array($k, $filterArr)) {
                    if ($limitArr == 0) {
                        $rows [$k] = '******';
                    }
                }
            }
        } elseif ($type == 'keyList') {
            $limitArr = isset($limit [$key]) && !empty($limit [$key]) ? 1 : 0;
            $i = 0;
            foreach ($rows as $k => $v) {
                foreach ($v as $myKey => $myVal) {
                    if (in_array($myKey, $filterArr)) {
                        if ($limitArr == 0) {
                            $rows [$i] [$myKey] = '******';
                        }
                    }
                }
                $i++;
            }
        }
        return $rows;
    }

    /**
     * 批量添加对象 2011-09-26
     */
    function addBatch_d($objArr, $isAddInfo = false)
    {
        if ($isAddInfo) {
            $object = array();
            $object = $this->addCreateInfo($object);
        }
        return $this->createBatch($objArr, $object);
    }

    /**
     * 新框架页面跳转返回
     * 有URL则刷新至URL页面
     * 没有则跳回当前页
     */
    function msg($title, $url = '')
    {
        if (empty($url)) {
            echo "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>";
            echo "<script>alert('" . $title . "');if (parent.$('#TB_window').length == 1){parent.show_page();parent.tb_remove();}else {if(window.opener != undefined ){window.opener.show_page();}window.close();}</script>";
            return;
        } else if ($url == 'debug') {
            $url = '<input type="button" onclick="self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);" value=" 返回 " />';
        } else {
            $url = '<input type="button" onclick="self.parent.tb_remove();self.parent.location=\'' . $url . '\'" value=" 返回 " />';
        }
        $html = file_get_contents(TPL_DIR . '/showmsg.htm');
        $html = str_replace('{title}', $title, $html);
        $html = str_replace('{url}', $url, $html);
        echo $html;
        exit ();
    }

    /**
     * 弹出窗口关闭刷新页面
     */
    function msgRf($title, $url = '')
    {
        if (empty($url)) {
            echo "<script>alert('" . $title . "');if( typeof(window.opener.document)!='unknown'&&typeof(window.opener.document) != 'undefined' ){self.opener.show_page(1);} self.close();</script>";
        } else {
            echo "<script>if(confirm('" . $title . ",是否继续录入?')){ self.location='" . $url . "';if( typeof(window.opener.document)!='unknown'&&typeof(window.opener.document) != 'undefined' ){self.opener.show_page(1);}}else{if(  typeof(window.opener.document)!='unknown'&&typeof(window.opener.document) != 'undefined' ){self.opener.show_page(1);}self.close();}</script>";
        }
        exit ();
    }

    /**
     * 变更公司权限的方法
     * param $v 0/1,动态配置是否启用公司权限过滤
     */
    public function setCompany($v = 0)
    {
        $this->_isSetCompany = $v;
    }

    /**
     * 设置公司权限过滤
     * 配置需要设置的数组
     * $arr = array(
     *     'c' => 'k'
     * );
     */
    public function setComLocal($arr = null)
    {
        if ($arr) {
            include(WEB_TOR . '/model/common/belongRegister.php');
            $defaultBelongArr = isset($defaultBelongArr) ? $defaultBelongArr : null;
            foreach ($arr as &$v) {
                $v = isset($belongArr[$v]) ? $belongArr[$v] : $defaultBelongArr;
            }
            $this->_comLocal = $arr;
        }
    }
}

?>