<?php

/**
 *
 * ͳһ�������model����
 * �����ƹ��ܣ�
 * 1.����
 * 2.���������
 * @author chengl
 *
 */
class model_common_changeLog extends model_base
{

    /**
     * ��������캯��
     * @param $logObj �������ע�����
     * @param boolean $isAudit �Ƿ���Ҫ�����������Ҫ��������Ҫ������ʱ��¼������ֻ���������¼
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
     * ����ע������ȡע����Ϣ
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
     * ��ӱ����¼����
     * �����Ҫ��������������¼��һ����ʱ��¼
     * �������Ҫ������ֻ��������¼
     * �������changeTagFieldֻ��1����޸�
     * @param $newObj
     * @throws $e
     */
    function addLog($newObj)
    {
        try {
            //���ҵ��������ԭʼ��¼
            $objId = $newObj['oldId'];
            $oldObj = $this->objDao->get_d($objId);
            //����һ����ʱ����
            $this->tbl_name = $this->objDao->tbl_name;
            $oldObj['isTemp'] = 1;
            $oldObj['originalId'] = $objId;
            $oldObj['ExaStatus'] = WAITAUDIT;
            unset($oldObj['ExaDT']);
            if (isset($oldObj['id'])) unset ($oldObj['id']);
            if (isset($oldObj['ID'])) unset ($oldObj['ID']);

            $newObj = $this->objDao->processDatadict($newObj);
            //�����Ҫ����
            if ($this->isAudit) {
                $tempObjId = parent::add_d($oldObj);//������ʱ��¼
                $newObj['id'] = $tempObjId;
                parent::edit_d($newObj);//�޸���ʱ��¼Ϊ�µı����¼
            }
            //����������
            $changeMain['objId'] = $objId;
            $changeMain['objType'] = $this->logObj;
            $changeMain['changeManId'] = $_SESSION['USER_ID'];
            $changeMain['changeManName'] = $_SESSION['USERNAME'];
            $changeMain['changeTime'] = date("Y-m-d H:i:s");
            $changeMain['changeReason'] = $newObj['changeReason'];

            $changeMain['tempId'] = $tempObjId;
            $this->tbl_name = $this->main_tbl_name;
            $parentId = $this->add_d($changeMain);

            //��������������г����� $newObj �в�ͬʱ������$oldObj�����еļ�����ֵ
            $inKeyObj = array_intersect_key($newObj, $oldObj);
            $isMainChange = false;//�������Ƿ�����ʶ
            if (count($inKeyObj) > 0) { //����в�ֵ
                foreach ($inKeyObj as $key => $val) {
                    //����ֶ�ע����
                    if (array_key_exists($key, $this->register) && $newObj[$key] != $oldObj[$key] && !is_array($val)) {
                        $isMainChange = true;
                        //�����ϸ����
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
                //��������������������
                foreach ($this->register as $detailType => $detailArr) {
                    if (is_array($detailArr)) {
                        $daoName = "model_" . $detailArr[objDao];
                        $detailDao = new $daoName (); //��ϸdao
                        $relationField = $detailArr['relationField']; //��ϸ���й���������ֶ�����

                        //��ʼ�����ϸ
                        foreach ($newObj[$detailType] as $key => $val) {
                            $isDetailChange = $this->addDetailLog($val, $objId, $tempObjId, $parentId, $detailDao, $detailType, $relationField);
                            if ($isDetailChange == true) {
                                $isMainChange = true;//ֻҪ��һ����ϸ�������������������б��
                            }
                        }
                    }
                }
                //����б���������ñ����ʶ
                if ($isMainChange == true) {
                    $changeTagField = $this->logObjInfo['changeTagField'];
                    if (!empty($changeTagField)) {
                        $sql = "update " . $this->objDao->tbl_name . " set " . $changeTagField . "=1 where id=" . $objId;
                        $this->objDao->query($sql);
                    }
                }
                return $tempObjId;
            } else {
                throw new Exception ("���������û�в������ԣ�");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * ��ϸ��������¼:��ϸ��Ҫ��������������ɾ���������
     * �����������
     * 1.�����ʱ���� isTemp=1
     * 2.�����¼������ֶ�  ���ǰֵ���������������ֵ
     * 3.����ͨ����ֱ�Ӹ�����ʱ�������� isTemp=0
     * ɾ���������
     * 1.�����ʱ���� isTemp=2
     * 2.�����¼������ֶ� ���ǰֵ�������ֵ��ɾ����
     * 3.����ͨ����ֱ�Ӳ�����ʱ�����Ӧ��ԭʼ����,��ɾ����ɾ��ͨ��isTemp==2�жϣ�
     * 4.����Ǽ�ɾ����ֱ��update isDel�ֶ�.
     * @param  $newObj �������ϸ����
     * @param  $objId �����������id
     * @param  $parentId �����¼����id
     * @param  $tempObjId ��������ʱ������id
     * @param  $detailDao �����ϸdao
     * @param  $detailType ��ϸ���ͣ�һ��ȡ�������е���ϸ�ļ�ֵ��
     * @param  $relationField ��ϸ�����������ֶ�����
     * @throws $e
     * changeTagField 0��δ���  1������޸�  2���������  3�����ɾ��
     */
    function addDetailLog($newObj, $objId, $tempObjId, $parentId, $detailDao, $detailType, $relationField)
    {
        try {
            //echo $newObj['oldId']."<br>";
            $detailId = $newObj['oldId'];
            $newObj = $detailDao->processDatadict($newObj);
            $detailRegister = $this->register[$detailType];
            $op = "edit"; //���������ʾ editΪ�޸�,addΪ����,delΪɾ��
            $changeTagField = $this->logObjInfo['changeTagField'];
            //���û��ԭʼid����Ϊ�������,����һ��ԭʼ��¼�ֶ�ֵ��Ϊ�����������¼
            if (empty ($detailId)) {
                foreach ($detailRegister as $key => $val) {
                    $oldObj[$key] = "��������";
                }
                $op = "add";
                $oldObj[$changeTagField] = 2;//�������
            } else {
                //���ҵ��������ԭʼ��¼
                $oldObj = $detailDao->get_d($detailId);
                $oldObj[$changeTagField] = 0;
            }

            //����һ����ʱ����
            $this->tbl_name = $detailDao->tbl_name;
            $oldObj[$relationField] = $tempObjId;
            //����ɾ�����
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
                //ɾ����ʱ����Ҫ��¼�����ֶΣ��Ա㻹ԭ
                foreach ($oldObj as $key => $val) {
                    $newObj[$key] = "��ɾ����";
                }
                $inKeyObj = $oldObj;
            } else {
                //��������������г����� $newObj �в�ͬʱ������$oldObj�����еļ�����ֵ
                $inKeyObj = array_intersect_key($newObj, $oldObj);
            }
            if (count($inKeyObj) > 0) { //����в�ֵ
                $isDetailChange = false;
//                print_r($detailRegister);
                foreach ($inKeyObj as $key => $val) {
                    //�������ֵ��ע���ֶ��д��ڲ��ұ�����ֵ����ǰ��ֵ����
                    //�����ɾ���������Ҫȫ����¼���Ա㻹ԭ��
//                    echo "field: ", $key, ",v��" ,trim($newObj[$key]), " ~ ", trim($oldObj[$key]), "-<br>";
                    if (trim($newObj[$key]) != trim($oldObj[$key]) && (array_key_exists($key, $detailRegister))) {
                        $isDetailChange = true;
                        //�����ϸ����
                        if (!empty ($this->register[$detailType]['objField'])) {
                            $registerD = $this->register[$detailType];
                            $pname = $newObj[$registerD['objField']];
                            if ($op == "add") {
                                $pname = $newObj[$registerD['objField']];
                            } else if ($op == "del") {
                                $pname = $oldObj[$registerD['objField']];
                            }
                            $name = "��" . $registerD[$registerD['objField']] . "��" . $pname;
                            $changeDetail['objField'] = $name;
                        }
                        $changeDetail['detailType'] = $detailType;
                        if (!empty ($detailRegister[objName])) {
                            $changeDetail['detailTypeCn'] = $detailRegister[objName];
                        } else {
                            $changeDetail['detailTypeCn'] = 'δע��';
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
                //���ñ����ϸ�ı����ʶ(����޸�)
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
                throw new Exception ("�����ϸû�в������ԣ�");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * �����¼��������
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
     * �����¼��ϸ������(�ϲ�������ɾ����¼)
     * @param $logObj �������ע��key
     * @param bool $isLast �Ƿ������µı����¼
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
        } else if ($isTemp == 9) {//9��������������ʶ
            $sqlplus = "";
        }
        if ($isLast) {
            //��ȡ���һ�α��������id
            $maxSql = "select max(id) as id from $main_tbl_name where objId=" . $this->searchArr['objId'] . $sqlplus;
            $rs = $this->_db->get_one($maxSql);
            $maxId = $rs['id'];
            $this->searchArr['parentId'] = $maxId;
        }
        $rows = $this->pageBySql($sql);
        //��ɾ����������ϸ���кϲ����� add by chengl 2011-12-09
        $returnRow = array();
        $returnRowDel = array();
        $returnRowAdd = array();
        $tempRow = array();
        foreach ($rows as $key => $val) {
            if ($val['newValue'] == "��ɾ����") {
                if (!empty($val['changeFieldCn'])) {
                    $tempRow[$val['detailId']] = $val;
                    $tempRowDel[$val['detailId']][] = $val['changeFieldCn'] . ":" . $val['oldValue'] . "</br>";
                }
            } else if ($val['oldValue'] == "��������") {
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
     * �����¼��ϸ������
     * @param $logObj �������ע��key
     * @param bool $isLast �Ƿ������µı����¼
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
        } else if ($isTemp == 9) {//9��������������ʶ
            $sqlplus = "";
        }
        if ($isLast) {
            //��ȡ���һ�α��������id
            $maxSql = "select max(id) as id from $main_tbl_name where objId=" . $this->searchArr['objId'] . $sqlplus;
            $rs = $this->_db->get_one($maxSql);
            $maxId = $rs['id'];
            $this->searchArr['parentId'] = $maxId;
        }
        $rows = $this->pageBySql($sql);
        //��ɾ����������ϸ���кϲ����� add by chengl 2011-12-09
        $lastTempId = "";
        $returnRow = array();
        $tempRow = array();
        $lastKey = "";//��һ��ɾ��/���� key
        $lastOp = "";//��һ�β������� add ���� del ɾ��
        $i = 0;

        foreach ($rows as $key => $val) {
            if ($val['newValue'] == "��ɾ����") {
                if (!empty($val['changeFieldCn'])) {
                    $tempRow[$val['dTempId']][] = $val['changeFieldCn'] . ":" . $val['oldValue'] . "</br>";
                }
            } else if ($val['oldValue'] == "��������") {
                if (!empty($val['changeFieldCn'])) {
                    $tempRow[$val['dTempId']][] = $val['changeFieldCn'] . ":" . $val['newValue'] . "</br>";
                }
            }
            if ($val['dTempId'] == $lastTempId && ($val['newValue'] == "��ɾ����" || $val['oldValue'] == "��������")) {

            } else {
                if ($val['newValue'] == "��ɾ����" || $val['oldValue'] == "��������") {

                    if ($lastKey !== "") {
                        if ($lastOp == 'del') {
                            $returnRow[$lastKey]['oldValue'] = implode(" ", $tempRow[$lastTempId]);
                        } else if ($lastOp == 'add') {
                            $returnRow[$lastKey]['newValue'] = implode(" ", $tempRow[$lastTempId]);
                        }
                    }
                    //��һ��ɾ�����������ļ�¼
                    if ($val['newValue'] == "��ɾ����") {
                        $val['changeFieldCn'] = "��ɾ����";
                        $val['newValue'] = "";
                        $val['oldValue'] = "";
                        $lastKey = $i;
                        $lastOp = "del";
                    } else if ($val['oldValue'] == "��������") {
                        $val['changeFieldCn'] = "��������";
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
     * ȷ�ϱ��,�õ���ʱ��¼������ԭ��¼
     * $obj ����ͨ������ʱ��¼
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
        if ($obj['ExaStatus'] == BACK) { //������
            $sql = "update " . $this->main_tbl_name . " set ExaStatus='" . BACK . "',ExaDT='" . $obj['ExaDT'] . "' where tempId=" . $id;
            $this->query($sql);
            //���������ͨ��ԭ����¼����״̬������
            $sql = "update " . $this->objDao->tbl_name . " set ExaStatus='" . AUDITED . "',ExaDT='" . $obj['ExaDT'] . "' where id=" . $originalId;
            $this->query($sql);
        } else if ($obj['ExaStatus'] == AUDITED) { //����ͨ�����
            $baseDao = $this->newBase($this->objDao);
            $sql = "update " . $this->main_tbl_name . " set ExaStatus='" . AUDITED . "',ExaDT='" . $obj['ExaDT'] . "' where tempId=" . $id;
            //echo $sql;
            $this->query($sql);
            $sql = "update " . $this->objDao->tbl_name . " set ExaStatus='" . AUDITED . "',ExaDT='" . $obj['ExaDT'] . "' where id=" . $originalId;
            $this->query($sql);
            try {
                $this->start_d();

                $tempObj = $baseDao->get_d($id);
                //��ʱ�����Ӧ��ԭʼ����
                if (empty ($tempObj['originalId'])) {
                    throw new Exception ("�������Ӧԭʼ��¼Ϊ�գ�");
                }
                $originalId = $tempObj['originalId'];
                $originalObj = $baseDao->get_d($originalId);

                //�ж��Ƿ��ǵ�һ�α������ͨ��������ǣ���Ҫ�����һ���汾��Ϣ
                $sql = "select count(id) as num from " . $this->main_tbl_name . " where objId=$originalId and ExaStatus='" . AUDITED . "'";
                $changeCount = $baseDao->queryCount($sql);
                if ($changeCount == 1) {
                    $firstObj = $originalObj;
                    unset($firstObj['id']);
                    if (isset($firstObj['ID'])) unset($firstObj['ID']);
                    $firstObj['isTemp'] = 3;//3�����һ���汾
                    $firstObj['originalId'] = $originalId;
                    $firstObjId = $baseDao->add_d($firstObj);
                }
                $register = $this->register;
                $logObjInfo = $this->logObjInfo;
                foreach ($register as $k => $v) {
                    if (!is_array($v)) {
                        //ֻ����ע���˵��ֶ�
                        $originalObj[$k] = $tempObj[$k];
                    } else { //��ʼ��ע�����ϸ������б������
                        $relationField = $v['relationField'];
                        $expendStr = '';
                        if (isset($v['relationFieldAsset'])) {
                            foreach ($v['relationFieldAsset'] as $ik => $iv) {
                                $expendStr .= " AND " . $ik . " = '" . $iv . "'";
                            }
                        }
                        $changeTagField = $logObjInfo['changeTagField'];
                        $daoName = "model_" . $v[objDao];
                        $detailDao = $this->newBase(new $daoName ()); //��ϸdao
                        //������select *,�пյ�����
                        $sql = "select * from " . $detailDao->tbl_name . " where " . $relationField . "=" . $id . $expendStr;
                        if ($k == "uploadFiles" && !empty($v['uploadFilesTypeArr'])) {
                            $sql .= " and serviceType in(" . $v['uploadFilesTypeArr'] . ")";
                        }
                        //echo $sql;
                        $details = $detailDao->_db->getArray($sql);
                        //print_r($details);
                        if ($changeCount == 1) {
                            //��ȡԭʼ��¼
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

                        //��ԭ��ϸ
                        foreach ($details as $dk => $dv) {
                            //�����ɾ�����
                            if ($dv['isTemp'] == 2 || $dv['isDel'] == 1) {
                                if ($v['isFalseDel'] == true) { //����Ǽ�ɾ��
                                    $sql = "update " . $detailDao->tbl_name . " set isDel=1 where id=" . $dv['originalId'] . $expendStr; //��д��ɾ����־λ�ֶ�
                                    $detailDao->query($sql);
                                    if (!empty($changeTagField)) {
                                        $sql = "update " . $detailDao->tbl_name . " set $changeTagField=3 where id=" . $dv['originalId'] . $expendStr; //���ɾ����ʶ
                                        $detailDao->query($sql);
                                    }
                                } else {
                                    //ɾ��ԭʼ��¼
                                    $detailDao->deletes_d($dv['originalId']);
                                }
                                //ɾ����ʱ��¼
                                //$detailDao->deletes ( $dv['id'] );
                                //$sql = "delete from " . $detailDao->tbl_name . " where id=" . $dv['id'];
                                //$detailDao->query ( $sql );
                            } else if (empty ($dv['originalId'])) { //�����������
                                //throw new Exception("��ϸ��Ӧԭʼ��¼Ϊ�գ�");
                                //���������
                                $dv['isTemp'] = 0;
                                $dv[$relationField] = isset($originalObj['id']) ? $originalObj['id'] : $originalObj['ID'];
                                $dv[$changeTagField] = $dv[$changeTagField];
                                if ($logObj == "contract") {//��Ժ�ͬ�����¼�����ӱ����ʱID ����������������ϵĹ�����Ʒid
                                    $dv['tempId'] = $dv['id'];
                                }
                                unset($dv['id']);
                                if (isset($dv['ID'])) {
                                    unset($dv['ID']);
                                }
                                $detailDao->add_d($dv);//���һ����ԭ���ļ�¼��Ҫ����
                            } else { //�޸����
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
                }// �������idΪ��дID������
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
     * ����tempId��ȡ�������
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
     * �ж�ҵ������Ƿ��ڱ����
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
     * ���󸽼�����(��ʱû�п��Ƕ�����Ը������)
     * 1.ԭ���󸽼����ƴ��� isTemp=1
     * 2.������������ isTemp=1
     * 4.ɾ������isTemp=2
     * @param $obj
     * @param $servieType
     * @return array
     * @throws $e
     */
    function processUploadFile($obj, $servieType)
    {
        $oldId = $obj['oldId'];
        if (empty ($oldId)) {
            throw new Exception ("û�й���ԭʼ����");
        } else {
            $uploadFile = new model_file_uploadfile_management ();
            $files = $uploadFile->getFilesByObjId($oldId, $servieType);
            if (!$files) {
                $files = array();
            }
            $newFiles = array();
            $delFiles = array();
            //ԭ���󸽼�����
            foreach ($files as $key => $value) {
                $files[$key]['id'] = '';
                $files[$key]['oldId'] = $value['id'];
                //$files[$key]['isTemp'] = 1;
                $files[$key]['serviceId'] = ''; //��յ�Ŀ����Ϊ�˵ȱ������������ݿ��ʱ���ȡid���¹���
            }
            //������������
            if (!empty ($_POST['fileuploadIds'])) {
                $uploadFile->searchArr = array("ids" => implode(",", $_POST['fileuploadIds']));
                $newFiles = $uploadFile->list_d();
                //$newFiles[$key]['isTemp'] = 1;
                foreach ($newFiles as $key => $value) {
                    $newFiles[$key]['serviceId'] = '';
                }
            }
            //ɾ������
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
     * ��ȡ���һ�α������ͨ�������ϸ
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
        //��ȡ���һ�α��������id
        $maxSql = "select max(id) as id from $main_tbl_name where ExaStatus='���' and objId=" . $objId;
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
     * ��ȡ�����������
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
     * ��ȡ����ı���ֶΣ����ǰ��ֵ��������ֵ
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
     * ��ȡ���¼�¼
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
     * ��ȡ������ϸ��Ϣ
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
     * ��ȡ�������
     * @param $logObj
     * @param $objId
     * @return array
     */
    function getChangeFieldCnByObjId($logObj, $objId)
    {
        $this->tbl_name = $this->logObjArr[$logObj]['detailTable'];
        //����ʾ��ID��code�ı������
        $sql = "select distinct changeFieldCn from " . $this->tbl_name . " where objId = '" . $objId
            . "' and (changeFieldCn not like '%I%' and changeFieldCn not like '%code%')";
        return $this->listBySql($sql);
    }
}