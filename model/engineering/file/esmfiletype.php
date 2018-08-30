<?php

/**
 * @author tse
 * @Date 2014��1��4�� 9:23:34
 * @version 1.0
 * @description:��Ŀ�ĵ����� Model��
 */
class model_engineering_file_esmfiletype extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_file_type";
		$this->sql_map = "engineering/file/esmfiletypeSql.php";
		parent::__construct();
	}

	/**
	 * ��ȡ��Ŀ�ĵ��������ṹ
	 * @param unknown $projectId ��Ӧ��Ŀ���
	 * @return Ambigous <boolean, multitype:multitype: >
	 */
	function getTree_d($projectId) {
		$this->updateTree_d($projectId);
		$rows = $this->list_d();
		foreach ($rows as $key => $val) {
			if ($val['isNeedUpload'] == 1) {
				$rows[$key]['name'] = $rows[$key]['name'] . ' *';
			}
		}
		return $rows;
	}

	/**
	 * �����͸�����Ŀ���͵����ṹ
	 */
	function updateTree_d($projectId) {
		try {
			$this->start_d();
			$this->searchArr = array('projectId' => $projectId);
			$typerows = $this->list_d();//��ȡ��Ŀ�ĵ�����
			$ids = "";//�洢��Ŀ�Ѵ��ڵ��ĵ����ñ��id
			if (!empty($typerows)) {
				foreach ($typerows as $k => $v) {
					$ids .= $v['mainId'] . ",";
				}
				if ($ids != null)
					$ids = substr($ids, 0, strlen($ids) - 1);
			}
			$esmfilesettingDao = new model_engineering_file_esmfilesetting();
			$esmfilesettingDao->searchArr = array('notIds' => $ids);
			//��ȡ���ĵ����ñ�����Ŀ�����ڵ������������
			$rows = $esmfilesettingDao->list_d();
			if ($rows != null) {
				foreach ($rows as $key => $val) {
					$rows[$key]['projectId'] = $projectId;
					$rows[$key]['mainId'] = $rows[$key]['id'];
					unset($rows[$key]['id']);
				}
				$this->addBatch_d($rows);
			}
			$esmfilesettingDao->searchArr = array('ids' => $ids);
			//��ȡ���ĵ����ͱ����Ѵ��ڵ��ĵ��������������޸�
			$needUpdateRows = $esmfilesettingDao->list_d();
			foreach ($needUpdateRows as $m => $n) {
				foreach ($typerows as $i => $j) {
					if ($n['id'] == $j['mainId']) {
						if ($n['fileName'] != $j['fileName'] || $n['fileType'] != $j['fileType'] || $n['isNeedUpload'] != $j['isNeedUpload']) {
							$id = $n['id'];
							unset($n['id']);
							$this->update(array('projectId' => $projectId, 'mainId' => $id), $n);
						}
					}
				}
			}
			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
		}
	}

	/**
	 * �ж��Ƿ���δ�ϴ��ĸ���
	 */
	function checkFileSubmit_d($param) {
		$typeIds = $this->findAll(array("projectId" => $param['projectId'], "isNeedUpload" => 1), "", "id");
		$esmfileDao = new model_engineering_file_esmfile();
		foreach ($typeIds as $key => $val) {
			$esmfileObj = $esmfileDao->findAll(array('typeId' => $val['id']));
			// 			print_r($esmfileObj);
			if ($esmfileObj == null) {
				return false;
			}
		}
		return true;
	}
}