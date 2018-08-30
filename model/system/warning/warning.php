<?php

/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:21
 * @version 1.0
 * @description:通用预警功能 Model层
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
     * 是否
     */
    function rtYesNo_d($thisVal)
    {
        if ($thisVal == 1) {
            return '是';
        } else {
            return '否';
        }
    }

    /**
     * 添加对象
     */
    function add_d($object)
    {
        $warningSettingDao = new model_system_warning_warningSetting();
        $warningSettingArr = $object['warningSetting'];
        unset($object['warningSetting']);

        try {
            $this->start_d();

            // 新增
            $id = parent::add_d($object);

            // 子表处理
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
     * 根据主键修改对象
     */
    function edit_d($object) {
        $warningSettingDao = new model_system_warning_warningSetting();
        $warningSettingArr = $object['warningSetting'];
        unset($object['warningSetting']);

        try {
            $this->start_d();

            // 新增
            parent::edit_d($object);

            // 子表处理
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
     * 执行预警功能
     * @param null $id
     * @return bool
     */
    function dealWarning_d($id = NULL,$isForNoon = false)
    {
		
        set_time_limit(0);
        //统一实例化
        $mailconfigDao = new model_system_mailconfig_mailconfig(); //邮件配置
        $warninglogsDao = new model_system_warninglogs_warninglogs(); //预警记录
        $warningmaillogsDao = new model_system_warningmaillogs_warningmaillogs(); //预警邮件记录
        $userDao = new model_deptuser_user_user(); //用户信息

        //添加开始执行预警记录
        $warninglogsArr = array('objId' => 0, 'objName' => '预警执行开始', 'executeTime' => date("Y-m-d H:i:s"));
        $warninglogsDao->add_d($warninglogsArr);

        if (!empty($id)) { //增加手动执行
            //获取指定的预警信息
            $object = $this->findAll(array('id' => $id));
        } else if($isForNoon){
            //获取已启动的且属于中午执行的预警功能
            $object = $this->findAll(array('isUsing' => 1,'isAtNoon' => 1));
        }else {
            //获取已启动的预警功能
            $object = $this->findAll(array('isUsing' => 1,'isAtNoon' => 0));
        }
        foreach ($object as $val) {
            //如果没有执行方法和邮件编码,直接跳过
            if (empty($val['mailCode']) && empty($val['executeFun']) && empty($val['executeClass'])) continue;

            //判断最后日期，间隔时间和定期计划是否为空或为零，如果都没有，那就直接执行预警功能
            if ($val['intervalDay'] > 0 || $val['regularPlan'] > 0) {
                if ($val['intervalDay'] > 0) {
                    if (empty($val['lastTime'])) {
                        $checkToDeal = true;
                    } else {
                        // 如果当前时间大于上次执行时间 + 天数，则需要执行
                        $checkToDeal =
                            strtotime(day_date) >= strtotime($val['lastTime']) + $val['intervalDay'] * 86400 ?
                                true : false;
                    }
                } else {
                    //定期计划,当当前天和计划执行期一致的时候才需要执行
                    $checkToDeal = $val['regularPlan'] == date("d") ? true : false;
                }
            } else {
                $checkToDeal = true;
            }

            $executeTime = date("Y-m-d H:i:s");
            if ($checkToDeal) { //用于判断是否执行预警功能
                //添加执行记录
                $warninglogsArr = array('objId' => $val['id'], 'objName' => $val['objName'],
                    'executeSql' => mysql_real_escape_string($val['executeSql']), 'executeTime' => $executeTime);
                $logId = $warninglogsDao->add_d($warninglogsArr);

                //默认发送人处理
                if ($val['mailCode']) {
                    $mailconfigArr = $mailconfigDao->find(array('objCode' => $val['mailCode']));
                }

                //对象实例化
                if ($val['executeFun'] && $val['executeClass']) {
                    try {
                        $executeDao = new $val['executeClass']();
                    } catch (Exception $e) {
                        unset($executeDao);
                    }
                }

                //获取业务信息
                $obj = $this->_db->getArray(stripslashes($val['executeSql']));
                foreach ($obj as $v) {
                    //邮件部分
                    if ($val['mailCode']) {
                        $arr = explode(",", $val['inKeys']);
                        $arrTemp = array();
                        foreach ($arr as $b) {
                            array_push($arrTemp, $v[$b]);
                        }
                        $array = array_combine($arr, $arrTemp);

                        //收件人
                        $receiverIds = array();
                        $receiverNames = array();
                        //判断收件人id字段是否为空
                        if (!empty($val['receiverIdKey'])) {
                            $receiverIds = explode(',', $v[$val['receiverIdKey']]);
                            $receiverNames = explode(',', $v[$val['receiverNameKey']]);
                        }
                        //判断默认发送人是否为空
                        if (!empty($mailconfigArr['defaultUserId'])) {
                            $receiverIds = array_merge($receiverIds, explode(',', $mailconfigArr['defaultUserId']));
                            $receiverNames = array_merge($receiverNames, explode(',', $mailconfigArr['defaultUserName']));
                        }
                        $receiverIdsStr = implode(',', array_unique($receiverIds));
                        $receiverNamesStr = implode(',', array_unique($receiverNames));

                        //抄送人
                        $ccMailUsers = array();
                        $ccMailUserNames = array();
                        //判断是否通知上级
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
                        //判断默认抄送人是否为空
                        if (!empty($mailconfigArr['ccUserId'])) {
                            $ccMailUsers = array_merge($ccMailUsers, explode(',', $mailconfigArr['ccUserId']));
                            $ccMailUserNames = array_merge($ccMailUserNames, explode(',', $mailconfigArr['ccUserName']));
                        }
                        $ccMailUsersStr = implode(',', array_unique($ccMailUsers));
                        $ccMailUserNamesStr = implode(',', array_unique($ccMailUserNames));

                        //发送邮件
                        try {
                            $this->mailDeal_d($val['mailCode'], $receiverIdsStr, $array, $ccMailUsersStr);
                            $mailFeedback = '发送成功';
                        } catch (Exception $e) {
                            $mailFeedback = '发送失败';
                        }

                        //添加预警邮件通知情况
                        $warningmaillogsArr = array('objId' => $val['id'], 'objName' => $val['objName'], 'logId' => $logId,
                            'mailUserIds' => $receiverIdsStr, 'mailUserNames' => $receiverNamesStr, 'mailFeedback' => $mailFeedback,
                            'executeTime' => $executeTime, 'ccmailUserIds' => $ccMailUsersStr, 'ccmailUserNames' => $ccMailUserNamesStr,
                            'result' => util_jsonUtil::encode($v)
                        );
                        $warningmaillogsDao->add_d($warningmaillogsArr);
                    }

                    //事件调用
                    if (isset($executeDao) && method_exists($executeDao, $val['executeFun'])) {
                        try {
                            if ($executeDao->$val['executeFun']($v)) {
                                $warningmaillogsArr = array('objId' => $val['id'], 'objName' => $val['objName'], 'logId' => $logId,
                                    'mailFeedback' => '执行成功',
                                    'executeTime' => $executeTime,
                                    'result' => util_jsonUtil::encode($v)
                                );
                            } else {
                                $warningmaillogsArr = array('objId' => $val['id'], 'objName' => $val['objName'], 'logId' => $logId,
                                    'mailFeedback' => '执行成功，但预警功能无返回',
                                    'executeTime' => $executeTime,
                                    'result' => util_jsonUtil::encode($v)
                                );
                            }
                        } catch (Exception $e) {
                            $warningmaillogsArr = array('objId' => $val['id'], 'objName' => $val['objName'], 'logId' => $logId,
                                'mailFeedback' => '执行失败',
                                'executeTime' => $executeTime,
                                'result' => $e->getMessage()
                            );
                        }
                        $warningmaillogsDao->add_d($warningmaillogsArr);
                    }
                }
                //更新通用预警功能的最后执行时间
                $this->update(array('id' => $val['id']), array('lastTime' => $executeTime));
                sleep(1);
            }
        }
        $warninglogsArr = array('objId' => 0, 'objName' => '预警执行结束', 'executeTime' => date("Y-m-d H:i:s"));
        $warninglogsDao->add_d($warninglogsArr);

        return true;
    }

    /**
     * 测试执行的脚本，并返回结果
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
            $someObj = array_slice($obj, 0, 10); //取前十条记录
            return array('count' => count($obj), 'result' => util_jsonUtil::encode($someObj), 'error' => '无', 'count_time' => $time);
        } catch (Exception $e) {
            return array('count' => 0, 'result' => '无', 'error' => $e->getMessage(), 'count_time' => '0',);
        }
    }

    /**
     * 获取预警sql
     * @param $id
     * @return string
     */
    function getWarningSql_d($id) {
        $sql = "";
        $warningSettingDao = new model_system_warning_warningSetting();
        $warningSettingList = $warningSettingDao->findAll(array('mainId' => $id));

        if (!empty($warningSettingList)) {

            // 获取对应的预警对象，然后替换到脚本中
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