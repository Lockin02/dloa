<?php

/**
 *
 * 统一变更处理model层类
 * 待完善功能：
 * 1.附件
 * 2.非审批变更
 * @author chengl
 *
 */
class model_common_changeLog extends model_base
{

    /**
     * 变更处理构造函数
     * @param $logObj 变更对象注册编码
     * @param boolean $isAudit 是否需要审批，如果需要审批，则要加入临时记录，否则只需加入变更记录
     */
    function __construct($logObj = null, $isAudit = true)
    {
        include("model/common/changeLogRegister.php");
        $this->logObjArr = isset($logObjArr) ? $logObjArr : array();
        $this->isAudit = $isAudit;
        if (!empty ($logObj)) {
            $this->getRegisterByLogObj($logObj);
        }
        $this->sql_map = "common/changeLogSql.php";
        parent::__construct();
    }

    /**
     * 根据注册编码获取注册信息
     * @param unknown_type $logObj
     */
    function getRegisterByLogObj($logObj)
    {
        $this->logObj = $logObj;
        $this->logObjInfo = $this->logObjArr[$logObj];
        $daoName = "model_" . $this->logObjArr[$logObj][objDao];
        $this->objDao = new $daoName ();
        $this->main_tbl_name = $this->logObjArr[$logObj]['mainTable'];
        $this->detail_tbl_name = $this->logObjArr[$logObj]['detailTable'];
        $this->register = $this->logObjArr[$logObj]['register'];
    }

    /**
     * 添加变更记录方法
     * 如果需要审批，加入变更记录及一条临时记录
     * 如果不需要审批，只加入变更记录
     * 主对象的changeTagField只有1变更修改
     * @param $newObj
     * @throws $e
     */
    function addLog($newObj)
    {
        try {
            //先找到变更对象原始记录
            $objId = $newObj['oldId'];
            $oldObj = $this->objDao->get_d($objId);
            //插入一条临时对象
            $this->tbl_name = $this->objDao->tbl_name;
            $oldObj['isTemp'] = 1;
            $oldObj['originalId'] = $objId;
            $oldObj['ExaStatus'] = WAITAUDIT;
            unset($oldObj['ExaDT']);
            if (isset($oldObj['id'])) unset ($oldObj['id']);
            if (isset($oldObj['ID'])) unset ($oldObj['ID']);

            $newObj = $this->objDao->processDatadict($newObj);
            //如果需要审批
            if ($this->isAudit) {
                $tempObjId = parent::add_d($oldObj);//加入临时记录
                $newObj['id'] = $tempObjId;
                parent::edit_d($newObj);//修改临时记录为新的变更纪录
            }
            //插入变更主表
            $changeMain['objId'] = $objId;
            $changeMain['objType'] = $this->logObj;
            $changeMain['changeManId'] = $_SESSION['USER_ID'];
            $changeMain['changeManName'] = $_SESSION['USERNAME'];
            $changeMain['changeTime'] = date("Y-m-d H:i:s");
            $changeMain['changeReason'] = $newObj['changeReason'];

            $changeMain['tempId'] = $tempObjId;
            $this->tbl_name = $this->main_tbl_name;
            $parentId = $this->add_d($changeMain);

            //该数组包含了所有出现在 $newObj 中并同时出现在$oldObj数组中的键名的值
            $inKeyObj = array_intersect_key($newObj, $oldObj);
            $isMainChange = false;//主对象是否变更标识
            if (count($inKeyObj) > 0) { //如果有差值
                foreach ($inKeyObj as $key => $val) {
                    //如果字段注册了
                    if (array_key_exists($key, $this->register) && $newObj[$key] != $oldObj[$key] && !is_array($val)) {
                        $isMainChange = true;
                        //变更明细操作
                        $changeDetail['detailType'] = $this->logObj;
                        $changeDetail['detailTypeCn'] = $this->logObjInfo[objName];
                        $changeDetail['objId'] = $objId;
                        $changeDetail['tempId'] = $tempObjId;
                        $changeDetail['parentId'] = $parentId;
                        $changeDetail['parentType'] = $this->logObj;
                        $changeDetail['changeField'] = $key;
                        $changeDetail['changeFieldCn'] = $this->register[$key];
                        $changeDetail['oldValue'] = $oldObj[$key];
                        $changeDetail['newValue'] = $newObj[$key];
                        $this->tbl_name = $this->detail_tbl_name;
                        $this->add_d($changeDetail);
                    }
                }
                //变更对象关联对象变更处理
                foreach ($this->register as $detailType => $detailArr) {
                    if (is_array($detailArr)) {
                        $daoName = "model_" . $detailArr[objDao];
                        $detailDao = new $daoName (); //明细dao
                        $relationField = $detailArr['relationField']; //明细表中关联主表的字段名称

                        //开始变更明细
                        foreach ($newObj[$detailType] as $key => $val) {
                            $isDetailChange = $this->addDetailLog($val, $objId, $tempObjId, $parentId, $detailDao, $detailType, $relationField);
                            if ($isDetailChange == true) {
                                $isMainChange = true;//只要有一条明细变更，则代表整个对象有变更
                            }
                        }
                    }
                }
                //如果有变更，则设置变更标识
                if ($isMainChange == true) {
                    $changeTagField = $this->logObjInfo['changeTagField'];
                    if (!empty($changeTagField)) {
                        $sql = "update " . $this->objDao->tbl_name . " set " . $changeTagField . "=1 where id=" . $objId;
                        $this->objDao->query($sql);
                    }
                }
                return $tempObjId;
            } else {
                throw new Exception ("变更主对象没有差异属性！");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * 明细对象变更记录:明细需要处理变更的新增跟删除两种情况
     * 新增情况处理：
     * 1.添加临时对象 isTemp=1
     * 2.变更记录：变更字段  变更前值【新增】，变更后值
     * 3.审批通过后直接更改临时对象属性 isTemp=0
     * 删除情况处理：
     * 1.添加临时对象 isTemp=2
     * 2.变更记录：变更字段 变更前值，变更后值【删除】
     * 3.审批通过后直接查找临时对象对应的原始对象,并删除（删除通过isTemp==2判断）
     * 4.如果是假删除，直接update isDel字段.
     * @param  $newObj 变更的明细对象
     * @param  $objId 变更的主对象id
     * @param  $parentId 变更记录的主id
     * @param  $tempObjId 产生的临时主对象id
     * @param  $detailDao 变更明细dao
     * @param  $detailType 明细类型（一般取在主表中的明细的键值）
     * @param  $relationField 明细表关联主表的字段名称
     * @throws $e
     * changeTagField 0：未变更  1：变更修改  2：变更新增  3：变更删除
     */
    function addDetailLog($newObj, $objId, $tempObjId, $parentId, $detailDao, $detailType, $relationField)
    {
        try {
            //echo $newObj['oldId']."<br>";
            $detailId = $newObj['oldId'];
            $newObj = $detailDao->processDatadict($newObj);
            $detailRegister = $this->register[$detailType];
            $op = "edit"; //变更操作标示 edit为修改,add为新增,del为删除
            $changeTagField = $this->logObjInfo['changeTagField'];
            //如果没有原始id，则为新增情况,构造一条原始记录字段值都为新增的虚拟记录
            if (empty ($detailId)) {
                foreach ($detailRegister as $key => $val) {
                    $oldObj[$key] = "【新增】";
                }
                $op = "add";
                $oldObj[$changeTagField] = 2;//变更新增
            } else {
                //先找到变更对象原始记录
                $oldObj = $detailDao->get_d($detailId);
                $oldObj[$changeTagField] = 0;
            }

            //插入一条临时对象
            $this->tbl_name = $detailDao->tbl_name;
            $oldObj[$relationField] = $tempObjId;
            //处理删除情况
            if ($newObj['isDel'] == 1 || $newObj['isDelTag'] == 1) {
                $oldObj['isDel'] = 1;
                $oldObj['isTemp'] = 2;
                $op = "del";
                $oldObj[$changeTagField] = 3;
            } else {
                $oldObj['isTemp'] = 1;
            }

            $oldObj['originalId'] = $detailId;
            unset ($oldObj['id']);
            //var_dump($oldObj);
            if ($this->isAudit) {
                $tempObjId = parent::add_d($oldObj);
                $newObj['id'] = $tempObjId;
                unset ($newObj['isTemp']);
                unset ($newObj['originalId']);
                unset ($newObj[$relationField]);
                parent::edit_d($newObj);
            }
            if ($op == "del") {
                //删除的时候需要记录所有字段，以便还原
                foreach ($oldObj as $key => $val) {
                    $newObj[$key] = "【删除】";
                }
                $inKeyObj = $oldObj;
            } else {
                //该数组包含了所有出现在 $newObj 中并同时出现在$oldObj数组中的键名的值
                $inKeyObj = array_intersect_key($newObj, $oldObj);
            }
            if (count($inKeyObj) > 0) { //如果有差值
                $isDetailChange = false;
//                print_r($detailRegister);
                foreach ($inKeyObj as $key => $val) {
                    //如果差异值在注册字段中存在并且变更后的值与变更前的值不等
                    //如果是删除情况，需要全部记录，以便还原。
//                    echo "field: ", $key, ",v：" ,trim($newObj[$key]), " ~ ", trim($oldObj[$key]), "-<br>";
                    if (trim($newObj[$key]) != trim($oldObj[$key]) && (array_key_exists($key, $detailRegister))) {
                        $isDetailChange = true;
                        //变更明细操作
                        if (!empty ($this->register[$detailType]['objField'])) {
                            $registerD = $this->register[$detailType];
                            $pname = $newObj[$registerD['objField']];
                            if ($op == "add") {
                                $pname = $newObj[$registerD['objField']];
                            } else if ($op == "del") {
                                $pname = $oldObj[$registerD['objField']];
                            }
                            $name = "【" . $registerD[$registerD['objField']] . "】" . $pname;
                            $changeDetail['objField'] = $name;
                        }
                        $changeDetail['detailType'] = $detailType;
                        if (!empty ($detailRegister[objName])) {
                            $changeDetail['detailTypeCn'] = $detailRegister[objName];
                        } else {
                            $changeDetail['detailTypeCn'] = '未注册';
                        }
                        $changeDetail['detailId'] = $detailId;
                        $changeDetail['tempId'] = $tempObjId;
                        $changeDetail['objId'] = $objId;
                        $changeDetail['parentId'] = $parentId;
                        $changeDetail['parentType'] = $this->logObj;
                        $changeDetail['changeField'] = $key;
                        $changeDetail['changeFieldCn'] = $this->register[$detailType][$key];
                        $changeDetail['oldValue'] = $oldObj[$key];
                        $changeDetail['newValue'] = $newObj[$key];
                        $this->tbl_name = $this->detail_tbl_name;
                        $this->add_d($changeDetail);

                    }
                }
                //设置变更明细的变更标识(变更修改)
                if ($isDetailChange == true && !empty($detailId) && $op != 'del') {
                    $changeTagField = $this->logObjInfo['changeTagField'];
                    if (!empty($changeTagField)) {
                        if (empty($tempObjId)) {
                            $tempId = $detailId;
                        } else {
                            $tempId = $tempObjId;
                        }
                        $sql = "update " . $detailDao->tbl_name . " set " . $changeTagField . "=1 where id=" . $tempId;
                        $this->objDao->query($sql);
                    }
                }
                return $isDetailChange;
            } else {
                throw new Exception ("变更明细没有差异属性！");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 变更记录主表数据
     * @param $obj
     * @return array
     */
    function page_d($obj)
    {
        $this->tbl_name = $this->logObjArr[$obj['logObj']]['mainTable'];
        $this->dtbl_name = $this->logObjArr[$obj['logObj']]['detailTable'];
        if (isset($obj['changeFieldCn'])) {
            $sqlStr = "sql: and c.id in (select d.parentId from " . $this->dtbl_name . " d where d.changeFieldCn ='" . $obj['changeFieldCn'] . "')";
            $this->searchArr['changeFieldCn'] = $sqlStr;
        }
        return parent::page_d();
    }

    /**
     * 变更记录明细表数据(合并新增及删除记录)
     * @param $logObj 变更对象注册key
     * @param bool $isLast 是否拿最新的变更记录
     * @param int $isTemp
     * @return array
     */
    function pageDetailMerge($logObj, $isLast = false, $isTemp = 0)
    {
        $main_tbl_name = $this->logObjArr[$logObj]['mainTable'];
        $this->tbl_name = $this->logObjArr[$logObj]['detailTable'];
        $sql = "select c.*,m.*,c.tempId as dTempId from " . $this->tbl_name . " c left join " . $main_tbl_name . " m on c.parentId=m.id";
        $this->sort = "c.id";
        $sqlplus = " and ExaStatus='" . AUDITED . "'";
        $isTemp = (!empty($isTemp) ? $isTemp : 0);
        if ($isTemp == 1) {
            $sqlplus = " and ExaStatus is null ";
        } else if ($isTemp == 9) {//9代表无需审批标识
            $sqlplus = "";
        }
        if ($isLast) {
            //获取最近一次变更主对象id
            $maxSql = "select max(id) as id from $main_tbl_name where objId=" . $this->searchArr['objId'] . $sqlplus;
            $rs = $this->_db->get_one($maxSql);
            $maxId = $rs['id'];
            $this->searchArr['parentId'] = $maxId;
        }
        $rows = $this->pageBySql($sql);
        //对删除及新增明细进行合并处理 add by chengl 2011-12-09
        $returnRow = array();
        $returnRowDel = array();
        $returnRowAdd = array();
        $tempRow = array();
        foreach ($rows as $key => $val) {
            if ($val['newValue'] == "【删除】") {
                if (!empty($val['changeFieldCn'])) {
                    $tempRow[$val['detailId']] = $val;
                    $tempRowDel[$val['detailId']][] = $val['changeFieldCn'] . ":" . $val['oldValue'] . "</br>";
                }
            } else if ($val['oldValue'] == "【新增】") {
                if (!empty($val['changeFieldCn'])) {
                    $tempRow[$val['objField']] = $val;
                    $tempRowAdd[$val['objField']][] = $val['changeFieldCn'] . ":" . $val['newValue'] . "</br>";
                }
            } else {
                $returnRow[] = $val;
            }

        }
        foreach ($tempRowDel as $key => $val) {
            $tempRow[$key]['oldValue'] = implode(" ", $val);
            $returnRowDel[] = $tempRow[$key];
        }
        foreach ($tempRowAdd as $key => $val) {
            $tempRow[$key]['newValue'] = implode(" ", $val);
            $returnRowAdd[] = $tempRow[$key];
        }

        $returnRow = array_merge($returnRow, $returnRowDel, $returnRowAdd);
        return $returnRow;
    }

    /**
     * 变更记录明细表数据
     * @param $logObj 变更对象注册key
     * @param bool $isLast 是否拿最新的变更记录
     * @param int $isTemp
     * @return array
     */
    function pageDetail_d($logObj, $isLast = false, $isTemp = 0)
    {
        $main_tbl_name = $this->logObjArr[$logObj]['mainTable'];
        $this->tbl_name = $this->logObjArr[$logObj]['detailTable'];
        $sql = "select c.*,m.*,c.tempId as dTempId from " . $this->tbl_name . " c left join " . $main_tbl_name . " m on c.parentId=m.id";
        $this->sort = "c.id";
        $sqlplus = " and ExaStatus='" . AUDITED . "'";
        $isTemp = (!empty($isTemp) ? $isTemp : 0);
        if ($isTemp == 1) {
            $sqlplus = " and ExaStatus is null ";
        } else if ($isTemp == 9) {//9代表无需审批标识
            $sqlplus = "";
        }
        if ($isLast) {
            //获取最近一次变更主对象id
            $maxSql = "select max(id) as id from $main_tbl_name where objId=" . $this->searchArr['objId'] . $sqlplus;
            $rs = $this->_db->get_one($maxSql);
            $maxId = $rs['id'];
            $this->searchArr['parentId'] = $maxId;
        }
        $rows = $this->pageBySql($sql);
        //对删除及新增明细进行合并处理 add by chengl 2011-12-09
        $lastTempId = "";
        $returnRow = array();
        $tempRow = array();
        $lastKey = "";//上一次删除/新增 key
        $lastOp = "";//上一次操作类型 add 新增 del 删除
        $i = 0;

        foreach ($rows as $key => $val) {
            if ($val['newValue'] == "【删除】") {
                if (!empty($val['changeFieldCn'])) {
                    $tempRow[$val['dTempId']][] = $val['changeFieldCn'] . ":" . $val['oldValue'] . "</br>";
                }
            } else if ($val['oldValue'] == "【新增】") {
                if (!empty($val['changeFieldCn'])) {
                    $tempRow[$val['dTempId']][] = $val['changeFieldCn'] . ":" . $val['newValue'] . "</br>";
                }
            }
            if ($val['dTempId'] == $lastTempId && ($val['newValue'] == "【删除】" || $val['oldValue'] == "【新增】")) {

            } else {
                if ($val['newValue'] == "【删除】" || $val['oldValue'] == "【新增】") {

                    if ($lastKey !== "") {
                        if ($lastOp == 'del') {
                            $returnRow[$lastKey]['oldValue'] = implode(" ", $tempRow[$lastTempId]);
                        } else if ($lastOp == 'add') {
                            $returnRow[$lastKey]['newValue'] = implode(" ", $tempRow[$lastTempId]);
                        }
                    }
                    //第一条删除或者新增的记录
                    if ($val['newValue'] == "【删除】") {
                        $val['changeFieldCn'] = "【删除】";
                        $val['newValue'] = "";
                        $val['oldValue'] = "";
                        $lastKey = $i;
                        $lastOp = "del";
                    } else if ($val['oldValue'] == "【新增】") {
                        $val['changeFieldCn'] = "【新增】";
                        $val['newValue'] = "";
                        $val['oldValue'] = "";
                        $lastKey = $i;
                        $lastOp = "add";
                    }

                    $i++;
                    $lastTempId = $val['dTempId'];
                }
                $returnRow[] = $val;

            }

        }
        if ($lastKey !== "") {
            if ($lastOp == 'del') {
                $returnRow[$lastKey]['oldValue'] = implode(" ", $tempRow[$lastTempId]);
            } else if ($lastOp == 'add') {
                $returnRow[$lastKey]['newValue'] = implode(" ", $tempRow[$lastTempId]);
            }
        }
        return $returnRow;
    }

    /**
     * 确认变更,拿到临时记录，覆盖原纪录
     * $obj 审批通过的临时记录
     * @param $obj
     * @param $logObj
     * @return int
     */
    function confirmChange_d($obj, $logObj = null)
    {
        $id = $obj['id'];
        $originalId = $obj['originalId'];
        if (empty ($logObj)) {
            $logObj = $this->logObj;
        }
        $this->getRegisterByLogObj($logObj);
        if ($obj['ExaStatus'] == BACK) { //打回情况
            $sql = "update " . $this->main_tbl_name . " set ExaStatus='" . BACK . "',ExaDT='" . $obj['ExaDT'] . "' where tempId=" . $id;
            $this->query($sql);
            //变更审批不通过原来记录审批状态变成完成
            $sql = "update " . $this->objDao->tbl_name . " set ExaStatus='" . AUDITED . "',ExaDT='" . $obj['ExaDT'] . "' where id=" . $originalId;
            $this->query($sql);
        } else if ($obj['ExaStatus'] == AUDITED) { //审批通过情况
            $baseDao = $this->newBase($this->objDao);
            $sql = "update " . $this->main_tbl_name . " set ExaStatus='" . AUDITED . "',ExaDT='" . $obj['ExaDT'] . "' where tempId=" . $id;
            //echo $sql;
            $this->query($sql);
            $sql = "update " . $this->objDao->tbl_name . " set ExaStatus='" . AUDITED . "',ExaDT='" . $obj['ExaDT'] . "' where id=" . $originalId;
            $this->query($sql);
            try {
                $this->start_d();

                $tempObj = $baseDao->get_d($id);
                //临时对象对应的原始对象
                if (empty ($tempObj['originalId'])) {
                    throw new Exception ("主对象对应原始记录为空！");
                }
                $originalId = $tempObj['originalId'];
                $originalObj = $baseDao->get_d($originalId);

                //判断是否是第一次变更审批通过，如果是，需要保存第一个版本信息
                $sql = "select count(id) as num from " . $this->main_tbl_name . " where objId=$originalId and ExaStatus='" . AUDITED . "'";
                $changeCount = $baseDao->queryCount($sql);
                if ($changeCount == 1) {
                    $firstObj = $originalObj;
                    unset($firstObj['id']);
                    if (isset($firstObj['ID'])) unset($firstObj['ID']);
                    $firstObj['isTemp'] = 3;//3代表第一个版本
                    $firstObj['originalId'] = $originalId;
                    $firstObjId = $baseDao->add_d($firstObj);
                }
                $register = $this->register;
                $logObjInfo = $this->logObjInfo;
                foreach ($register as $k => $v) {
                    if (!is_array($v)) {
                        //只更新注册了的字段
                        $originalObj[$k] = $tempObj[$k];
                    } else { //开始对注册的明细对象进行变更操作
                        $relationField = $v['relationField'];
                        $expendStr = '';
                        if (isset($v['relationFieldAsset'])) {
                            foreach ($v['relationFieldAsset'] as $ik => $iv) {
                                $expendStr .= " AND " . $ik . " = '" . $iv . "'";
                            }
                        }
                        $changeTagField = $logObjInfo['changeTagField'];
                        $daoName = "model_" . $v[objDao];
                        $detailDao = $this->newBase(new $daoName ()); //明细dao
                        //被逼用select *,有空调整下
                        $sql = "select * from " . $detailDao->tbl_name . " where " . $relationField . "=" . $id . $expendStr;
                        if ($k == "uploadFiles" && !empty($v['uploadFilesTypeArr'])) {
                            $sql .= " and serviceType in(" . $v['uploadFilesTypeArr'] . ")";
                        }
                        //echo $sql;
                        $details = $detailDao->_db->getArray($sql);
                        //print_r($details);
                        if ($changeCount == 1) {
                            //获取原始记录
                            $sql = "select * from " . $detailDao->tbl_name . " where " . $relationField . "=" . $originalId . $expendStr;
                            if ($k == "uploadFiles" && !empty($v['uploadFilesTypeArr'])) {
                                $sql .= " and serviceType in(" . $v['uploadFilesTypeArr'] . ")";
                            }
                            $oldDetails = $detailDao->_db->getArray($sql);
                            foreach ($oldDetails as $ok => $ov) {
                                unset($ov['id']);
                                if (isset($ov['ID'])) {
                                    unset($ov['ID']);
                                }
                                $ov[$relationField] = $firstObjId;
                                $ov['isTemp'] = 3;
                                $detailDao->add_d($ov);
                            }
                        }

                        //还原明细
                        foreach ($details as $dk => $dv) {
                            //如果是删除情况
                            if ($dv['isTemp'] == 2 || $dv['isDel'] == 1) {
                                if ($v['isFalseDel'] == true) { //如果是假删除
                                    $sql = "update " . $detailDao->tbl_name . " set isDel=1 where id=" . $dv['originalId'] . $expendStr; //先写死删除标志位字段
                                    $detailDao->query($sql);
                                    if (!empty($changeTagField)) {
                                        $sql = "update " . $detailDao->tbl_name . " set $changeTagField=3 where id=" . $dv['originalId'] . $expendStr; //变更删除标识
                                        $detailDao->query($sql);
                                    }
                                } else {
                                    //删除原始记录
                                    $detailDao->deletes_d($dv['originalId']);
                                }
                                //删除临时记录
                                //$detailDao->deletes ( $dv['id'] );
                                //$sql = "delete from " . $detailDao->tbl_name . " where id=" . $dv['id'];
                                //$detailDao->query ( $sql );
                            } else if (empty ($dv['originalId'])) { //如果是添加情况
                                //throw new Exception("明细对应原始记录为空！");
                                //变更添加情况
                                $dv['isTemp'] = 0;
                                $dv[$relationField] = isset($originalObj['id']) ? $originalObj['id'] : $originalObj['ID'];
                                $dv[$changeTagField] = $dv[$changeTagField];
                                if ($logObj == "contract") {//针对合同变更记录新增从表的临时ID 用于审批后更新物料的关联产品id
                                    $dv['tempId'] = $dv['id'];
                                }
                                unset($dv['id']);
                                if (isset($dv['ID'])) {
                                    unset($dv['ID']);
                                }
                                $detailDao->add_d($dv);//添加一条，原来的记录需要保存
                            } else { //修改情况
                                $originalDetailObj = $detailDao->get_d($dv['originalId']);
                                foreach ($v as $drk => $drv) {
                                    //echo $drk.":::".$originalDetailObj[$drk]."=>".$dv[$drk].'<br>';
                                    $originalDetailObj[$drk] = $dv[$drk];
                                }

                                $originalDetailObj[$changeTagField] = $dv[$changeTagField];

                                $detailDao->edit_d($originalDetailObj);
                            }
                        }
                    }
                }
                //print_r($originalObj);
                if (isset($originalObj['ID'])) {
                    $originalObj['id'] = $originalObj['ID'];
                    unset($originalObj['ID']);
                }// 解决借款单的id为大写ID的问题
                $baseDao->edit_d($originalObj);
                //$baseDao->deletes ( $id );
                $this->commit_d();
                return 1;
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->rollBack();
                return 0;
            }
        }
    }

    /**
     * @param $businessDao
     * @return model_base
     */
    function newBase($businessDao)
    {
        $baseDao = new model_base ();
        $baseDao->tbl_name = $businessDao->tbl_name;
        return $baseDao;
    }

    /**
     * add by chengl 2011-08-29
     * 根据tempId获取变更对象
     * @param  $tempId
     * @return array
     */
    function getObjByTempId($tempId)
    {
        $this->tbl_name = $this->logObjArr[$this->logObj]['mainTable'];
        $this->searchArr = array("tempId" => $tempId);
        return parent::list_d();
    }

    /**
     * add by chengl 2011-08-31
     * 判断业务对象是否处于变更中
     * @param $objId
     * @return boolean
     */
    function isChanging($objId)
    {
        $sql = "select count(id) as num from " . $this->objDao->tbl_name . " where originalId=" . $objId . " and isTemp=1 ";
        if ($this->isAudit) {
            $sql .= "and id in(select Pid from wf_task where code='" . $this->objDao->tbl_name . "' and Status='ok')";
        }
        $num = $this->objDao->queryCount($sql);
        if ($num > 0) {
            return true;
        }
        return false;
    }

    /**
     * 对象附件处理(暂时没有考虑多个属性附件情况)
     * 1.原对象附件复制处理 isTemp=1
     * 2.新增附件处理 isTemp=1
     * 4.删除附件isTemp=2
     * @param $obj
     * @param $servieType
     * @return array
     * @throws $e
     */
    function processUploadFile($obj, $servieType)
    {
        $oldId = $obj['oldId'];
        if (empty ($oldId)) {
            throw new Exception ("没有关联原始对象！");
        } else {
            $uploadFile = new model_file_uploadfile_management ();
            $files = $uploadFile->getFilesByObjId($oldId, $servieType);
            if (!$files) {
                $files = array();
            }
            $newFiles = array();
            $delFiles = array();
            //原对象附件处理
            foreach ($files as $key => $value) {
                $files[$key]['id'] = '';
                $files[$key]['oldId'] = $value['id'];
                //$files[$key]['isTemp'] = 1;
                $files[$key]['serviceId'] = ''; //清空的目的是为了等变更对象插入数据库的时候获取id更新关联
            }
            //新增附件处理
            if (!empty ($_POST['fileuploadIds'])) {
                $uploadFile->searchArr = array("ids" => implode(",", $_POST['fileuploadIds']));
                $newFiles = $uploadFile->list_d();
                //$newFiles[$key]['isTemp'] = 1;
                foreach ($newFiles as $key => $value) {
                    $newFiles[$key]['serviceId'] = '';
                }
            }
            //删除处理
            if (!empty ($_POST['delFilesId'])) {
                $uploadFile->searchArr = array("ids" => $_POST['delFilesId']);
                $delFiles = $uploadFile->list_d();
                //var_dump($delFiles);
                foreach ($delFiles as $key => $value) {
                    $delFiles[$key]['isDel'] = 1;
                    $delFiles[$key]['oldId'] = $value['id'];
                    $delFiles[$key]['serviceId'] = '';
                    foreach ($files as $k => $v) {
                        if ($value['id'] == $v['oldId']) {
                            unset ($files[$k]);
                            break;
                        }
                    }
                    foreach ($newFiles as $k => $v) {
                        if ($value['id'] == $v['oldId']) {
                            unset ($newFiles[$k]);
                            break;
                        }
                    }
                }
            }
            return array_merge($files, $newFiles, $delFiles);
        }
    }

    /**
     * 获取最近一次变更审批通过变更明细
     * @param $logObj
     * @param $objId
     * @param $objType
     * @param $detailType
     * @param $detailId
     * @return array
     */
    function getLastDetails($logObj, $objId, $objType, $detailType, $detailId)
    {
        $main_tbl_name = $this->logObjArr[$logObj]['mainTable'];
        $this->tbl_name = $this->logObjArr[$logObj]['detailTable'];
        $sql = "select c.*,m.* from " . $this->tbl_name . " c left join " . $main_tbl_name . " m on c.parentId=m.id";
        $this->sort = "c.id";
        //获取最近一次变更主对象id
        $maxSql = "select max(id) as id from $main_tbl_name where ExaStatus='完成' and objId=" . $objId;
        $rs = $this->_db->get_one($maxSql);
        $maxId = $rs['id'];
        $this->searchArr['parentId'] = $maxId;
        if (!empty($objType)) {
            $this->searchArr['parentType'] = $objType;
        }
        if (!empty($detailType)) {
            $this->searchArr['detailType'] = $detailType;
        }
        if (!empty($detailId)) {
            $this->searchArr['detailId'] = $detailId;
        }
        return $this->listBySql($sql);

    }

    /**
     * 获取变更对象数组
     * @param $tempId
     * @param $logObj
     * @return array
     */
    function getChangeObjs_d($tempId, $logObj)
    {
        $mainTable = $this->logObjArr[$logObj]['mainTable'];
        $detailTable = $this->logObjArr[$logObj]['detailTable'];

        $sql = "select c.changeField from " . $detailTable . " c left join " . $mainTable
            . " m on c.parentId=m.id where c.tempId = $tempId";
        $rs = $this->_db->getArray($sql);

        $rtArr = array();
        if (is_array($rs)) {
            foreach ($rs as $key => $val) {
                array_push($rtArr, $val['changeField']);
            }
        }
        return $rtArr;
    }

    /**
     * 获取对象的变更字段，变更前的值，变更后的值
     * @param $tempId
     * @param $logObj
     * @return array
     */
    function getChangeInformation_d($tempId, $logObj)
    {
        $mainTable = $this->logObjArr[$logObj]['mainTable'];
        $detailTable = $this->logObjArr[$logObj]['detailTable'];

        $sql = "select c.changeField,c.oldValue,c.newValue from " . $detailTable . " c left join " . $mainTable
            . " m on c.parentId=m.id where c.tempId = $tempId";
        return $this->_db->getArray($sql);
    }


    /**
     * 获取更新记录
     * @param $ids
     * @return array
     */
    function getUpdateInfo_d($ids)
    {
        $dataArr = array();
        foreach ($ids as $k => $v) {
            $sql = "select id,objId,changeTime from oa_chance_changlog where objId='$v' order by changeTime desc limit 1";
            $data = $this->_db->getArray($sql);
            array_push($dataArr, $data);
        }
        return $dataArr;
    }

    /**
     * 获取更新详细信息
     * @param $ids
     * @return array
     */
    function getUpdataInfo_d($ids)
    {
        $dataArr = array();
        foreach ($ids as $key => $val) {
            foreach ($val as $k => $v) {
                $sql = "select changeField,oldValue,newValue from oa_chance_changedetail where objId = $v[objId] and parentId = $v[id]";
                $data = $this->_db->getArray($sql);
                array_push($dataArr, $data);
            }
        }
        return $dataArr;
    }

    /**
     * 获取变更属性
     * @param $logObj
     * @param $objId
     * @return array
     */
    function getChangeFieldCnByObjId($logObj, $objId)
    {
        $this->tbl_name = $this->logObjArr[$logObj]['detailTable'];
        //不显示带ID和code的变更属性
        $sql = "select distinct changeFieldCn from " . $this->tbl_name . " where objId = '" . $objId
            . "' and (changeFieldCn not like '%I%' and changeFieldCn not like '%code%')";
        return $this->listBySql($sql);
    }
}