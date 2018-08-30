<?php
/**
 * User: Michael
 * Date: 2014-12-01
 */
$setting = array(
	"productLine" => array(
		"title" => '产品线指标表',
		"fixedThead" => array(
			0 => array(
				"display" => '产品线',
				"name" => 'prolineName',
				"code" => 'prolineCode',
				"width" => 130
			)
		),
		"url" => '?model=contract_conproject_conproject&action=productLineReportJson', //获取表格数据Json
		"keyObj" => 'contract_conproject_conproject',
		"excelFunc" => 'lineProjectData'
	),
	"exeDept" => array(
		"title" => '执行区域指标表',
		"fixedThead" => array(
			0 => array(
				"display" => '执行区域',
				"name" => 'officeName',
				"code" => 'officeId',
				"width" => 130
			)
		),
		"url" => '?model=contract_conproject_conproject&action=productLineReportJson', //获取表格数据Json
		"keyObj" => 'contract_conproject_conproject',
		"excelFunc" => 'lineProjectData'
	)
);