<?php

/**
 * @author Show
 * @Date 2013��7��11�� ������ 13:30:10
 * @version 1.0
 * @description:ͨ���ʼ����� Model��
 */
class model_system_mailconfig_mailconfig extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_system_mailconfig";
		$this->sql_map = "system/mailconfig/mailconfigSql.php";
		parent:: __construct();
	}

	/**
	 * �Ƿ�
	 */
	function rtYesNo_d($thisVal) {
		if ($thisVal == 1) {
			return '��';
		} else {
			return '��';
		}
	}

	/*********************** ��ɾ�Ĳ� ************************/
	/**
	 * ����
	 */
	function add_d($object) {
		try {
			$this->start_d();

			//����
			$newId = parent::add_d($object);

			//�ӱ�
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
	 * ����
	 */
	function edit_d($object) {
		try {
			$this->start_d();

			//����
			parent::edit_d($object);

			//�ӱ�
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
	 * ��ȡת������
	 */
	function getMoveSql_d($id) {
		$obj = $this->get_d($id);

		//��ȡ����sql
		$sql = $this->buildCreateSql_d($obj, $this->tbl_name, 'id');

		//��ȡ�ӱ�sql
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

	/*********************** �ʼ����� ************************/
	/**
	 * �ʼ����� - ��ȡ�ʼ�Ĭ�Ϸ�����
	 * @param string $objCode ����õ��ʼ���ҵ�����
	 * @param bool $separateCompany �Ƿ����ֹ�˾
	 * @param null $company ��˾
	 * @return bool|mixed
	 */
	function getMailUser_d($objCode, $separateCompany = false, $company = null) {
		$obj = $this->find(array('objCode' => $objCode), null, 'defaultUserName,defaultUserId,ccUserName,ccUserId,bccUserName,bccUserId');
		//�ռ��˴���
		if ($obj['defaultUserId'] && $separateCompany) {
			$defaultUserDone = $this->filterCompnayUser_d($obj['defaultUserId'], $company);
			$obj['defaultUserId'] = $defaultUserDone['userIds'];
			$obj['defaultUserName'] = $defaultUserDone['userNames'];
		}
		//������Ա����
		if ($obj['ccUserId'] && $separateCompany) {
			$defaultUserDone = $this->filterCompnayUser_d($obj['ccUserId'], $company);
			$obj['ccUserId'] = $defaultUserDone['userIds'];
			$obj['ccUserName'] = $defaultUserDone['userNames'];
		}
		//������Ա����
		if ($obj['bccUserId'] && $separateCompany) {
			$defaultUserDone = $this->filterCompnayUser_d($obj['bccUserId'], $company);
			$obj['bccUserId'] = $defaultUserDone['userIds'];
			$obj['bccUserName'] = $defaultUserDone['userNames'];
		}
		return $obj;
	}

	/**
	 * �ʼ�����,���˹�˾��Ա
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
	 * �ʼ����� - ��ȡ�ʼ����������Ϣ
	 * @p1 ����õ��ʼ���ҵ����� str
	 * @p2 �ʼ������� str
	 * @p3 �ǲ�ѯ�ű��е���Ϣ array
	 * @p4 ������ str
	 * @p5 �Ƿ����ֹ�˾ boolean
	 * @p6 ��˾ str
	 */
	function getMailAllInfo_d($objCode, $mailUser = null, $exaInfo = null, $ccMailUser = null, $separateCompany = false, $company = null) {
		$obj = $this->find(array('objCode' => $objCode));
		if ($obj) {
			//����д����ռ���,�Ǿ�ֱ�Ӷ��ռ��˷��ʼ� edit by kuangzw 2014-02-21
			if ($mailUser) {
				$obj['defaultUserId'] = $mailUser;
			} else {
				if ($separateCompany) {
					$defaultUserDone = $this->filterCompnayUser_d($obj['defaultUserId'], $company);
					$obj['defaultUserId'] = $defaultUserDone['userIds'];
				}
			}

			//����д��볭����,�Ǿ�ֱ�ӶԳ����˷��ʼ� edit by kuangzw 2014-02-21
			if ($ccMailUser) {
				$obj['ccUserId'] = $ccMailUser;
			} else {
				if ($separateCompany) {
					$defaultUserDone = $this->filterCompnayUser_d($obj['ccUserId'], $company);
					$obj['ccUserId'] = $defaultUserDone['userIds'];
				}
			}

			//�ʼ����ݴ���
			$obj['mailTitle'] = 'OA-֪ͨ : ' . $obj['mailTitle']; //����

			//SESSION����
			$obj['mailContent'] = $this->initContectSession_d($obj['mailContent']);

			if ($exaInfo) {//�ǲ�ѯ�ű�����
				$obj['mailContent'] = $this->initContentExaInfo_d($obj['mailContent'], $exaInfo);
			}

			//��ѯ��������
			if ($obj['isMain'] && !empty($exaInfo)) {
				$obj['mailContent'] = $this->initContentMain_d($obj['mailContent'], $exaInfo, $obj['mainSource']);
			}

			//�ӱ���������
			if ($obj['isItem'] && !empty($exaInfo)) {
				$obj['mailContent'] = $this->initContentItem_d($obj['id'], $obj['mailContent'], $exaInfo, $obj['itemSource']);
			}
			return $obj;
		} else {
			return false;
		}
	}

	/**
	 * �ʼ�����
	 * @p1 ����õ��ʼ���ҵ����� str
	 * @p2 �ʼ������� str
	 * @p3 �ǲ�ѯ�ű��е���Ϣ array
	 * @p4 ������ str
	 * @p5 �Ƿ����ֹ�˾ boolean
	 * @p6 ��˾ str
	 */
	function mailDeal_d($objCode, $mailUser = null, $exaInfo = null, $ccMailUser = null, $separateCompany = false, $company = null, $exaTitleInfo = null) {
		$obj = $this->find(array('objCode' => $objCode));
		if ($obj) {
			//����д����ռ���,�Ǿ�ֱ�Ӷ��ռ��˷��ʼ� edit by kuangzw 2014-02-21
			if ($mailUser) {
				$obj['defaultUserId'] = $mailUser;
			} else {
				if ($separateCompany) {
					$defaultUserDone = $this->filterCompnayUser_d($obj['defaultUserId'], $company);
					$obj['defaultUserId'] = $defaultUserDone['userIds'];
				}
			}

			//����д��볭����,�Ǿ�ֱ�ӶԳ����˷��ʼ� edit by kuangzw 2014-02-21
			if ($ccMailUser) {
				$obj['ccUserId'] = $ccMailUser;
			} else {
				if ($separateCompany) {
					$defaultUserDone = $this->filterCompnayUser_d($obj['ccUserId'], $company);
					$obj['ccUserId'] = $defaultUserDone['userIds'];
				}
			}

			//�ʼ����ݴ���
			$title = 'OA-֪ͨ : ' . $obj['mailTitle']; //����
			$mailUser = $obj['defaultUserId']; //�ʼ�������
			$ccMailUser = $obj['ccUserId']; //�ʼ�������

			//�ʼ���������
			$content = $obj['mailContent'];

			//SESSION����
			$content = $this->initContectSession_d($content);

			if ($exaInfo) {//�ǲ�ѯ�ű�����
				$content = $this->initContentExaInfo_d($content, $exaInfo);
			}

			//��ѯ��������
			if ($obj['isMain'] && !empty($exaInfo)) {
				$content = $this->initContentMain_d($content, $exaInfo, $obj['mainSource']);
			}

			//�ӱ���������
			if ($obj['isItem'] && !empty($exaInfo)) {
				$content = $this->initContentItem_d($obj['id'], $content, $exaInfo, $obj['itemSource']);
			}

			// ���������Ϣ����
            if($exaTitleInfo){
                $title = $this->initContentExaInfo_d($title, $exaTitleInfo);
            }

            $this->addMailRecord($title,$content,$mailUser,$ccMailUser);
			$emailDao = new model_common_mail();
			$emailDao->mailGeneral($title, $mailUser, $content, $ccMailUser, $separateCompany, $company);
		}
	}

    /**
     * ����ʼ����ͼ�¼
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
	 * ������Ⱦ - SESSION
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
	 * ������Ⱦ
	 */
	function initContentExaInfo_d($content, $exaInfo) {
		foreach ($exaInfo as $key => $val) {
			$content = str_replace('[' . $key . ']', $val, $content);
		}

		return $content;
	}

	/**
	 * ��ѯ��������
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
	 * ��ѯ�������� - �ӱ�
	 */
	function initContentItem_d($mainId, $content, $exaInfo, $sql) {
		foreach ($exaInfo as $key => $val) {
			$sql = str_replace('{' . $key . '}', $val, $sql);
		}
		$rs = $this->_db->getArray($sql);

		if ($rs) {
			//��ѯ��ϸ�������
			$mailconfigItemDao = new model_system_mailconfig_mainconfigitem();
			$mailconfigItemArr = $mailconfigItemDao->findAll(array('mainId' => $mainId), 'orderNum');

			//�����ֵ�
			$datadictDao = null;

			//��ϸ��Ⱦ
			if ($mailconfigItemArr) {
				//��ʼ����ϸ���
				$detailStr = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center><tr>";
				//��ͷ��Ⱦ
				foreach ($mailconfigItemArr as $key => $val) {
					$detailStr .= "<td>" . $val['fieldName'] . "</td>";
				}
				$detailStr .= "</tr>";

				//��������Ⱦ
				foreach ($rs as $key => $val) {
					foreach ($mailconfigItemArr as $k => $v) {
						$fieldName = $v['fieldCode'];
						if ($v['showType'] == '�����ֵ�') {
							if (!$datadictDao) {
								$datadictDao = new model_system_datadict_datadict();
							}
							$thisField = $datadictDao->getDataNameByCode($val[$fieldName]);
							//û�ҵ������ֵ䴦��
							if (empty($thisField)) {
								$thisField = $val[$fieldName];
							}
						} else {
							$thisField = $val[$fieldName];
						}
						if (count($mailconfigItemArr) - 1 != $k) {
							$detailStr .= "<td>" . $thisField . "</td>";
						} else {
							//ÿ����¼����󣬱��뻻��
							$detailStr .= "<td>" . $thisField . "</td></tr><tr>";
						}
					}
				}
				$detailStr .= "</tr></table>";

				//���شӱ�����
				$content = str_replace('!itemTable!', $detailStr, $content);
			}
		}

		return $content;
	}
}