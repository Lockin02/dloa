<?php

/**
 * @author Show
 * @Date 2013年7月11日 星期四 13:30:10
 * @version 1.0
 * @description:通用邮件配置 Model层
 */
class model_system_mailconfig_mailconfig extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_system_mailconfig";
		$this->sql_map = "system/mailconfig/mailconfigSql.php";
		parent:: __construct();
	}

	/**
	 * 是否
	 */
	function rtYesNo_d($thisVal) {
		if ($thisVal == 1) {
			return '是';
		} else {
			return '否';
		}
	}

	/*********************** 增删改查 ************************/
	/**
	 * 新增
	 */
	function add_d($object) {
		try {
			$this->start_d();

			//新增
			$newId = parent::add_d($object);

			//从表
			if ($object['mainconfigitem']) {
				$mailconfigItemDao = new model_system_mailconfig_mainconfigitem();
				$object['mainconfigitem'] = util_arrayUtil::setArrayFn(array('mainId' => $newId), $object['mainconfigitem']);
				$mailconfigItemDao->saveDelBatch($object['mainconfigitem']);
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * 新增
	 */
	function edit_d($object) {
		try {
			$this->start_d();

			//新增
			parent::edit_d($object);

			//从表
			if ($object['mainconfigitem']) {
				$mailconfigItemDao = new model_system_mailconfig_mainconfigitem();
				$object['mainconfigitem'] = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $object['mainconfigitem']);
				$mailconfigItemDao->saveDelBatch($object['mainconfigitem']);
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * 获取转移数据
	 */
	function getMoveSql_d($id) {
		$obj = $this->get_d($id);

		//获取主表sql
		$sql = $this->buildCreateSql_d($obj, $this->tbl_name, 'id');

		//获取从表sql
		$mailconfigItemDao = new model_system_mailconfig_mainconfigitem();
		$mailconfigItemArr = $mailconfigItemDao->findAll(array('mainId' => $id));
		foreach ($mailconfigItemArr as $key => $val) {
			$sql .= $this->buildCreateSql_d($val, $mailconfigItemDao->tbl_name, 'mainId', false);
		}

		return $sql;
	}

	//createSql
	function buildCreateSql_d($row, $thisTbl, $replaceKey, $isId = true) {
		if (!$isId) {
			unset($row['id']);
		}
		if (!is_array($row))
			return FALSE;
		if (empty ($row))
			return FALSE;
		foreach ($row as $key => $value) {
			$cols [] = $key;
			if ($replaceKey == $key) {
				$vals [] = '"<span class="replaceArea">' . addslashes($value) . '</span>"';
			} else {
				$vals [] = '"' . addslashes($value) . '"';
			}
		}
		$col = join(',', $cols);
		$val = join(',', $vals);

		$sql = "INSERT INTO {$thisTbl} ({$col}) VALUES ({$val});";
		return $sql;
	}

	/*********************** 邮件调用 ************************/
	/**
	 * 邮件处理 - 获取邮件默认发送人
	 * @param string $objCode 你调用的邮件的业务编码
	 * @param bool $separateCompany 是否区分公司
	 * @param null $company 公司
	 * @return bool|mixed
	 */
	function getMailUser_d($objCode, $separateCompany = false, $company = null) {
		$obj = $this->find(array('objCode' => $objCode), null, 'defaultUserName,defaultUserId,ccUserName,ccUserId,bccUserName,bccUserId');
		//收件人处理
		if ($obj['defaultUserId'] && $separateCompany) {
			$defaultUserDone = $this->filterCompnayUser_d($obj['defaultUserId'], $company);
			$obj['defaultUserId'] = $defaultUserDone['userIds'];
			$obj['defaultUserName'] = $defaultUserDone['userNames'];
		}
		//抄送人员处理
		if ($obj['ccUserId'] && $separateCompany) {
			$defaultUserDone = $this->filterCompnayUser_d($obj['ccUserId'], $company);
			$obj['ccUserId'] = $defaultUserDone['userIds'];
			$obj['ccUserName'] = $defaultUserDone['userNames'];
		}
		//迷送人员处理
		if ($obj['bccUserId'] && $separateCompany) {
			$defaultUserDone = $this->filterCompnayUser_d($obj['bccUserId'], $company);
			$obj['bccUserId'] = $defaultUserDone['userIds'];
			$obj['bccUserName'] = $defaultUserDone['userNames'];
		}
		return $obj;
	}

	/**
	 * 邮件处理,过滤公司人员
	 * @param $userIds
	 * @param null $company
	 * @return array
	 */
	function filterCompnayUser_d($userIds, $company = null) {
		$userIdsNew = util_jsonUtil::strBuild($userIds);
		$newArr = array('userNames' => array(), 'userIds' => array());
		$allinfo = "select USER_ID,USER_NAME,Company from user where USER_ID in (" . $userIdsNew . ") and has_left=0";
		$rows = $this->findSql($allinfo);
		$company = $company ? $company : $_SESSION['Company'];
		foreach ($rows as $key => $val) {
			if ($company == $val['Company']) {
				array_push($newArr['userNames'], $val['USER_NAME']);
				array_push($newArr['userIds'], $val['USER_ID']);
			}
		}
		$newArr['userIds'] = implode(',', $newArr['userIds']);
		$newArr['userNames'] = implode(',', $newArr['userNames']);
		return $newArr;
	}

	/**
	 * 邮件处理 - 获取邮件发送相关信息
	 * @p1 你调用的邮件的业务编码 str
	 * @p2 邮件接收人 str
	 * @p3 非查询脚本中的信息 array
	 * @p4 抄送人 str
	 * @p5 是否区分公司 boolean
	 * @p6 公司 str
	 */
	function getMailAllInfo_d($objCode, $mailUser = null, $exaInfo = null, $ccMailUser = null, $separateCompany = false, $company = null) {
		$obj = $this->find(array('objCode' => $objCode));
		if ($obj) {
			//如果有传入收件人,那就直接对收件人发邮件 edit by kuangzw 2014-02-21
			if ($mailUser) {
				$obj['defaultUserId'] = $mailUser;
			} else {
				if ($separateCompany) {
					$defaultUserDone = $this->filterCompnayUser_d($obj['defaultUserId'], $company);
					$obj['defaultUserId'] = $defaultUserDone['userIds'];
				}
			}

			//如果有传入抄送人,那就直接对抄送人发邮件 edit by kuangzw 2014-02-21
			if ($ccMailUser) {
				$obj['ccUserId'] = $ccMailUser;
			} else {
				if ($separateCompany) {
					$defaultUserDone = $this->filterCompnayUser_d($obj['ccUserId'], $company);
					$obj['ccUserId'] = $defaultUserDone['userIds'];
				}
			}

			//邮件内容处理
			$obj['mailTitle'] = 'OA-通知 : ' . $obj['mailTitle']; //标题

			//SESSION处理
			$obj['mailContent'] = $this->initContectSession_d($obj['mailContent']);

			if ($exaInfo) {//非查询脚本内容
				$obj['mailContent'] = $this->initContentExaInfo_d($obj['mailContent'], $exaInfo);
			}

			//查询内容载入
			if ($obj['isMain'] && !empty($exaInfo)) {
				$obj['mailContent'] = $this->initContentMain_d($obj['mailContent'], $exaInfo, $obj['mainSource']);
			}

			//从表内容载入
			if ($obj['isItem'] && !empty($exaInfo)) {
				$obj['mailContent'] = $this->initContentItem_d($obj['id'], $obj['mailContent'], $exaInfo, $obj['itemSource']);
			}
			return $obj;
		} else {
			return false;
		}
	}

	/**
	 * 邮件处理
	 * @p1 你调用的邮件的业务编码 str
	 * @p2 邮件接收人 str
	 * @p3 非查询脚本中的信息 array
	 * @p4 抄送人 str
	 * @p5 是否区分公司 boolean
	 * @p6 公司 str
	 */
	function mailDeal_d($objCode, $mailUser = null, $exaInfo = null, $ccMailUser = null, $separateCompany = false, $company = null, $exaTitleInfo = null) {
		$obj = $this->find(array('objCode' => $objCode));
		if ($obj) {
			//如果有传入收件人,那就直接对收件人发邮件 edit by kuangzw 2014-02-21
			if ($mailUser) {
				$obj['defaultUserId'] = $mailUser;
			} else {
				if ($separateCompany) {
					$defaultUserDone = $this->filterCompnayUser_d($obj['defaultUserId'], $company);
					$obj['defaultUserId'] = $defaultUserDone['userIds'];
				}
			}

			//如果有传入抄送人,那就直接对抄送人发邮件 edit by kuangzw 2014-02-21
			if ($ccMailUser) {
				$obj['ccUserId'] = $ccMailUser;
			} else {
				if ($separateCompany) {
					$defaultUserDone = $this->filterCompnayUser_d($obj['ccUserId'], $company);
					$obj['ccUserId'] = $defaultUserDone['userIds'];
				}
			}

			//邮件内容处理
			$title = 'OA-通知 : ' . $obj['mailTitle']; //标题
			$mailUser = $obj['defaultUserId']; //邮件发送人
			$ccMailUser = $obj['ccUserId']; //邮件发送人

			//邮件发送内容
			$content = $obj['mailContent'];

			//SESSION处理
			$content = $this->initContectSession_d($content);

			if ($exaInfo) {//非查询脚本内容
				$content = $this->initContentExaInfo_d($content, $exaInfo);
			}

			//查询内容载入
			if ($obj['isMain'] && !empty($exaInfo)) {
				$content = $this->initContentMain_d($content, $exaInfo, $obj['mainSource']);
			}

			//从表内容载入
			if ($obj['isItem'] && !empty($exaInfo)) {
				$content = $this->initContentItem_d($obj['id'], $content, $exaInfo, $obj['itemSource']);
			}

			// 邮箱标题信息载入
            if($exaTitleInfo){
                $title = $this->initContentExaInfo_d($title, $exaTitleInfo);
            }

            $this->addMailRecord($title,$content,$mailUser,$ccMailUser);
			$emailDao = new model_common_mail();
			$emailDao->mailGeneral($title, $mailUser, $content, $ccMailUser, $separateCompany, $company);
		}
	}

    /**
     * 添加邮件发送记录
     * @param $title
     * @param $content
     * @param $addresses
     * @param $ccAddresses
     */
	function addMailRecord($title,$content,$addresses = '',$ccAddresses = ''){
	    $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType,status)values('".$_SESSION['USER_ID']."','$title','$content','$addresses','$ccAddresses',NOW(),'','','1','1')";
        $this->_db->query($sql);
    }
	/**
	 * 内容渲染 - SESSION
	 */
	function initContectSession_d($content) {
		$sessionArr = array(
			'USER_ID' => $_SESSION['USER_ID'],
			'USERNAME' => $_SESSION['USERNAME'],
			'DEPT_ID' => $_SESSION['DEPT_ID'],
			'DEPT_NAME' => $_SESSION['DEPT_NAME'],
			'EMAIL' => $_SESSION['EMAIL']
		);

		foreach ($sessionArr as $key => $val) {
			$content = str_replace('#' . $key . '#', $val, $content);
		}

		return $content;
	}

	/**
	 * 内容渲染
	 */
	function initContentExaInfo_d($content, $exaInfo) {
		foreach ($exaInfo as $key => $val) {
			$content = str_replace('[' . $key . ']', $val, $content);
		}

		return $content;
	}

	/**
	 * 查询内容载入
	 */
	function initContentMain_d($content, $exaInfo, $sql) {
		foreach ($exaInfo as $key => $val) {
			$sql = str_replace('{' . $key . '}', $val, $sql);
		}
		$rs = $this->_db->getArray($sql);
		if ($rs) {
			foreach ($rs[0] as $key => $val) {
				$content = str_replace('{' . $key . '}', $val, $content);
			}
		}
		return $content;
	}

	/**
	 * 查询内容载入 - 从表
	 */
	function initContentItem_d($mainId, $content, $exaInfo, $sql) {
		foreach ($exaInfo as $key => $val) {
			$sql = str_replace('{' . $key . '}', $val, $sql);
		}
		$rs = $this->_db->getArray($sql);

		if ($rs) {
			//查询明细表个内容
			$mailconfigItemDao = new model_system_mailconfig_mainconfigitem();
			$mailconfigItemArr = $mailconfigItemDao->findAll(array('mainId' => $mainId), 'orderNum');

			//数据字典
			$datadictDao = null;

			//明细渲染
			if ($mailconfigItemArr) {
				//初始化明细表个
				$detailStr = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center><tr>";
				//表头渲染
				foreach ($mailconfigItemArr as $key => $val) {
					$detailStr .= "<td>" . $val['fieldName'] . "</td>";
				}
				$detailStr .= "</tr>";

				//表内容渲染
				foreach ($rs as $key => $val) {
					foreach ($mailconfigItemArr as $k => $v) {
						$fieldName = $v['fieldCode'];
						if ($v['showType'] == '数据字典') {
							if (!$datadictDao) {
								$datadictDao = new model_system_datadict_datadict();
							}
							$thisField = $datadictDao->getDataNameByCode($val[$fieldName]);
							//没找到数据字典处理
							if (empty($thisField)) {
								$thisField = $val[$fieldName];
							}
						} else {
							$thisField = $val[$fieldName];
						}
						if (count($mailconfigItemArr) - 1 != $k) {
							$detailStr .= "<td>" . $thisField . "</td>";
						} else {
							//每条记录到最后，必须换行
							$detailStr .= "<td>" . $thisField . "</td></tr><tr>";
						}
					}
				}
				$detailStr .= "</tr></table>";

				//加载从表内容
				$content = str_replace('!itemTable!', $detailStr, $content);
			}
		}

		return $content;
	}
}