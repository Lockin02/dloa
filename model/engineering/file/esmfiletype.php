<?php

/**
 * @author tse
 * @Date 2014年1月4日 9:23:34
 * @version 1.0
 * @description:项目文档类型 Model层
 */
class model_engineering_file_esmfiletype extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_file_type";
		$this->sql_map = "engineering/file/esmfiletypeSql.php";
		parent::__construct();
	}

	/**
	 * 获取项目文档类型树结构
	 * @param unknown $projectId 对应项目编号
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
	 * 新增和更新项目类型的树结构
	 */
	function updateTree_d($projectId) {
		try {
			$this->start_d();
			$this->searchArr = array('projectId' => $projectId);
			$typerows = $this->list_d();//获取项目文档类型
			$ids = "";//存储项目已存在的文档设置表的id
			if (!empty($typerows)) {
				foreach ($typerows as $k => $v) {
					$ids .= $v['mainId'] . ",";
				}
				if ($ids != null)
					$ids = substr($ids, 0, strlen($ids) - 1);
			}
			$esmfilesettingDao = new model_engineering_file_esmfilesetting();
			$esmfilesettingDao->searchArr = array('notIds' => $ids);
			//获取在文档设置表里项目不存在的数据用于添加
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
			//获取在文档类型表在已存在的文档设置数据用于修改
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
	 * 判断是否有未上传的附件
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