<?php
/**
 * User: Michael
 * Date: 2014-12-01
 */
$setting = array(
	"productLine" => array(
		"title" => '��Ʒ��ָ���',
		"fixedThead" => array(
			0 => array(
				"display" => '��Ʒ��',
				"name" => 'prolineName',
				"code" => 'prolineCode',
				"width" => 130
			)
		),
		"url" => '?model=contract_conproject_conproject&action=productLineReportJson', //��ȡ�������Json
		"keyObj" => 'contract_conproject_conproject',
		"excelFunc" => 'lineProjectData'
	),
	"exeDept" => array(
		"title" => 'ִ������ָ���',
		"fixedThead" => array(
			0 => array(
				"display" => 'ִ������',
				"name" => 'officeName',
				"code" => 'officeId',
				"width" => 130
			)
		),
		"url" => '?model=contract_conproject_conproject&action=productLineReportJson', //��ȡ�������Json
		"keyObj" => 'contract_conproject_conproject',
		"excelFunc" => 'lineProjectData'
	)
);