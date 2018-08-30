<?php

/**
 * @author Administrator
 * @Date 2014��3��17�� 14:21:21
 * @version 1.0
 * @description:ͨ��Ԥ������ Model��
 */
class model_system_warning_warning extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_system_warning";
        $this->sql_map = "system/warning/warningSql.php";
        parent::__construct();
    }

    /**
     * �Ƿ�
     */
    function rtYesNo_d($thisVal)
    {
        if ($thisVal == 1) {
            return '��';
        } else {
            return '��';
        }
    }

    /**
     * ��Ӷ���
     */
    function add_d($object)
    {
        $warningSettingDao = new model_system_warning_warningSetting();
        $warningSettingArr = $object['warningSetting'];
        unset($object['warningSetting']);

        try {
            $this->start_d();

            // ����
            $id = parent::add_d($object);

            // �ӱ���
            $warningSettingArr = util_arrayUtil::setArrayFn(array('mainId' => $id), $warningSettingArr, array('warningObject'));
            if (!empty($warningSettingArr)) {
                $warningSettingDao->saveDelBatch($warningSettingArr);
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
        return true;
    }
    /**
     * ���������޸Ķ���
     */
    function edit_d($object) {
        $warningSettingDao = new model_system_warning_warningSetting();
        $warningSettingArr = $object['warningSetting'];
        unset($object['warningSetting']);

        try {
            $this->start_d();

            // ����
            parent::edit_d($object);

            // �ӱ���
            $warningSettingArr = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $warningSettingArr);
            if (!empty($warningSettingArr)) {
                $warningSettingDao->saveDelBatch($warningSettingArr);
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
        return true;
    }

    /**
     * ִ��Ԥ������
     * @param null $id
     * @return bool
     */
    function dealWarning_d($id = NULL,$isForNoon = false)
    {
		
        set_time_limit(0);
        //ͳһʵ����
        $mailconfigDao = new model_system_mailconfig_mailconfig(); //�ʼ�����
        $warninglogsDao = new model_system_warninglogs_warninglogs(); //Ԥ����¼
        $warningmaillogsDao = new model_system_warningmaillogs_warningmaillogs(); //Ԥ���ʼ���¼
        $userDao = new model_deptuser_user_user(); //�û���Ϣ

        //��ӿ�ʼִ��Ԥ����¼
        $warninglogsArr = array('objId' => 0, 'objName' => 'Ԥ��ִ�п�ʼ', 'executeTime' => date("Y-m-d H:i:s"));
        $warninglogsDao->add_d($warninglogsArr);

        if (!empty($id)) { //�����ֶ�ִ��
            //��ȡָ����Ԥ����Ϣ
            $object = $this->findAll(array('id' => $id));
        } else if($isForNoon){
            //��ȡ������������������ִ�е�Ԥ������
            $object = $this->findAll(array('isUsing' => 1,'isAtNoon' => 1));
        }else {
            //��ȡ��������Ԥ������
            $object = $this->findAll(array('isUsing' => 1,'isAtNoon' => 0));
        }
        foreach ($object as $val) {
            //���û��ִ�з������ʼ�����,ֱ������
            if (empty($val['mailCode']) && empty($val['executeFun']) && empty($val['executeClass'])) continue;

            //�ж�������ڣ����ʱ��Ͷ��ڼƻ��Ƿ�Ϊ�ջ�Ϊ�㣬�����û�У��Ǿ�ֱ��ִ��Ԥ������
            if ($val['intervalDay'] > 0 || $val['regularPlan'] > 0) {
                if ($val['intervalDay'] > 0) {
                    if (empty($val['lastTime'])) {
                        $checkToDeal = true;
                    } else {
                        // �����ǰʱ������ϴ�ִ��ʱ�� + ����������Ҫִ��
                        $checkToDeal =
                            strtotime(day_date) >= strtotime($val['lastTime']) + $val['intervalDay'] * 86400 ?
                                true : false;
                    }
                } else {
                    //���ڼƻ�,����ǰ��ͼƻ�ִ����һ�µ�ʱ�����Ҫִ��
                    $checkToDeal = $val['regularPlan'] == date("d") ? true : false;
                }
            } else {
                $checkToDeal = true;
            }

            $executeTime = date("Y-m-d H:i:s");
            if ($checkToDeal) { //�����ж��Ƿ�ִ��Ԥ������
                //���ִ�м�¼
                $warninglogsArr = array('objId' => $val['id'], 'objName' => $val['objName'],
                    'executeSql' => mysql_real_escape_string($val['executeSql']), 'executeTime' => $executeTime);
                $logId = $warninglogsDao->add_d($warninglogsArr);

                //Ĭ�Ϸ����˴���
                if ($val['mailCode']) {
                    $mailconfigArr = $mailconfigDao->find(array('objCode' => $val['mailCode']));
                }

                //����ʵ����
                if ($val['executeFun'] && $val['executeClass']) {
                    try {
                        $executeDao = new $val['executeClass']();
                    } catch (Exception $e) {
                        unset($executeDao);
                    }
                }

                //��ȡҵ����Ϣ
                $obj = $this->_db->getArray(stripslashes($val['executeSql']));
                foreach ($obj as $v) {
                    //�ʼ�����
                    if ($val['mailCode']) {
                        $arr = explode(",", $val['inKeys']);
                        $arrTemp = array();
                        foreach ($arr as $b) {
                            array_push($arrTemp, $v[$b]);
                        }
                        $array = array_combine($arr, $arrTemp);

                        //�ռ���
                        $receiverIds = array();
                        $receiverNames = array();
                        //�ж��ռ���id�ֶ��Ƿ�Ϊ��
                        if (!empty($val['receiverIdKey'])) {
                            $receiverIds = explode(',', $v[$val['receiverIdKey']]);
                            $receiverNames = explode(',', $v[$val['receiverNameKey']]);
                        }
                        //�ж�Ĭ�Ϸ������Ƿ�Ϊ��
                        if (!empty($mailconfigArr['defaultUserId'])) {
                            $receiverIds = array_merge($receiverIds, explode(',', $mailconfigArr['defaultUserId']));
                            $receiverNames = array_merge($receiverNames, explode(',', $mailconfigArr['defaultUserName']));
                        }
                        $receiverIdsStr = implode(',', array_unique($receiverIds));
                        $receiverNamesStr = implode(',', array_unique($receiverNames));

                        //������
                        $ccMailUsers = array();
                        $ccMailUserNames = array();
                        //�ж��Ƿ�֪ͨ�ϼ�
                        if ($val['isMailManager'] == 1) {
                            $userDao->getParam(array('USER_IDS' => $receiverIds));
                            $userArr = $userDao->list_d();
                            foreach ($userArr as $j) {
                                $ccMailUserArr = $this->_db->getArray("select userid from dept_com where dept = '" . $j['DEPT_ID'] . "' and compt = '" . $j['Company'] . "' ");
                                array_push($ccMailUsers, $ccMailUserArr[0]['userid']);
                                $ccMailUserNameArr = $userDao->find(array('USER_ID' => $ccMailUserArr[0]['userid']), null, 'USER_NAME');
                                array_push($ccMailUserNames, $ccMailUserNameArr['USER_NAME']);
                            }
                        }
                        //�ж�Ĭ�ϳ������Ƿ�Ϊ��
                        if (!empty($mailconfigArr['ccUserId'])) {
                            $ccMailUsers = array_merge($ccMailUsers, explode(',', $mailconfigArr['ccUserId']));
                            $ccMailUserNames = array_merge($ccMailUserNames, explode(',', $mailconfigArr['ccUserName']));
                        }
                        $ccMailUsersStr = implode(',', array_unique($ccMailUsers));
                        $ccMailUserNamesStr = implode(',', array_unique($ccMailUserNames));

                        //�����ʼ�
                        try {
                            $this->mailDeal_d($val['mailCode'], $receiverIdsStr, $array, $ccMailUsersStr);
                            $mailFeedback = '���ͳɹ�';
                        } catch (Exception $e) {
                            $mailFeedback = '����ʧ��';
                        }

                        //���Ԥ���ʼ�֪ͨ���
                        $warningmaillogsArr = array('objId' => $val['id'], 'objName' => $val['objName'], 'logId' => $logId,
                            'mailUserIds' => $receiverIdsStr, 'mailUserNames' => $receiverNamesStr, 'mailFeedback' => $mailFeedback,
                            'executeTime' => $executeTime, 'ccmailUserIds' => $ccMailUsersStr, 'ccmailUserNames' => $ccMailUserNamesStr,
                            'result' => util_jsonUtil::encode($v)
                        );
                        $warningmaillogsDao->add_d($warningmaillogsArr);
                    }

                    //�¼�����
                    if (isset($executeDao) && method_exists($executeDao, $val['executeFun'])) {
                        try {
                            if ($executeDao->$val['executeFun']($v)) {
                                $warningmaillogsArr = array('objId' => $val['id'], 'objName' => $val['objName'], 'logId' => $logId,
                                    'mailFeedback' => 'ִ�гɹ�',
                                    'executeTime' => $executeTime,
                                    'result' => util_jsonUtil::encode($v)
                                );
                            } else {
                                $warningmaillogsArr = array('objId' => $val['id'], 'objName' => $val['objName'], 'logId' => $logId,
                                    'mailFeedback' => 'ִ�гɹ�����Ԥ�������޷���',
                                    'executeTime' => $executeTime,
                                    'result' => util_jsonUtil::encode($v)
                                );
                            }
                        } catch (Exception $e) {
                            $warningmaillogsArr = array('objId' => $val['id'], 'objName' => $val['objName'], 'logId' => $logId,
                                'mailFeedback' => 'ִ��ʧ��',
                                'executeTime' => $executeTime,
                                'result' => $e->getMessage()
                            );
                        }
                        $warningmaillogsDao->add_d($warningmaillogsArr);
                    }
                }
                //����ͨ��Ԥ�����ܵ����ִ��ʱ��
                $this->update(array('id' => $val['id']), array('lastTime' => $executeTime));
                sleep(1);
            }
        }
        $warninglogsArr = array('objId' => 0, 'objName' => 'Ԥ��ִ�н���', 'executeTime' => date("Y-m-d H:i:s"));
        $warninglogsDao->add_d($warninglogsArr);

        return true;
    }

    /**
     * ����ִ�еĽű��������ؽ��
     * @param $id
     * @return array
     */
    function testSql_d($id)
    {
        try {
            $object = $this->find(array('id' => $id), null, 'executeSql');
            $time = microtime(true);
            $obj = $this->_db->getArray(stripslashes($object['executeSql']));
            $time = microtime(true) - $time;
            $someObj = array_slice($obj, 0, 10); //ȡǰʮ����¼
            return array('count' => count($obj), 'result' => util_jsonUtil::encode($someObj), 'error' => '��', 'count_time' => $time);
        } catch (Exception $e) {
            return array('count' => 0, 'result' => '��', 'error' => $e->getMessage(), 'count_time' => '0',);
        }
    }

    /**
     * ��ȡԤ��sql
     * @param $id
     * @return string
     */
    function getWarningSql_d($id) {
        $sql = "";
        $warningSettingDao = new model_system_warning_warningSetting();
        $warningSettingList = $warningSettingDao->findAll(array('mainId' => $id));

        if (!empty($warningSettingList)) {

            // ��ȡ��Ӧ��Ԥ������Ȼ���滻���ű���
            $warningObjectDao = new model_system_warning_warningObject();
            $warningObjectHash = $warningObjectDao->getHash_d();

            foreach ($warningSettingList as $v) {
                $warningObject = $v['warningObjectId'] && isset($warningObjectHash[$v['warningObjectId']]) ?
                    $warningObjectHash[$v['warningObjectId']] : $v['warningObject'];
                $compareObject = $v['compareObjectId'] && isset($warningObjectHash[$v['compareObjectId']]) ?
                    $warningObjectHash[$v['compareObjectId']] : $v['compareObject'];
                $sql .= $v['logic'] . " " . $v['leftK'] . " " . $warningObject . " " . $v['compare'] . " " .
                    $compareObject . " " . $v['rightK'] . " ";
            }
        }
        return $sql;
    }
}