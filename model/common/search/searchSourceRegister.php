<?php
/*
 * Created on 2011-10-29
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

//�²�����
$sourceArr = array(
	'stockin' => array(
		'thisClass' => 'model_stock_instock_stockin',  //����Ƿ�����²�ֵʱʹ�õ�����
		'thisDownAction' => 'hasSource',                           //����Ƿ�����²�ֵʱʹ�õķ�����
		'up' => array('RSLTZD' => 'arrival'),
		'down' => array('invpurchase'=>'CGFPYD-02'),
		'pageObj' => 'stockin'
	),
	'invpurchase' => array(
		'thisClass' => 'model_finance_invpurchase_invpurchase',  //��Ӧҵ������
		'thisUpAction' => 'hasSourceInfo',                          //����Ƿ�����ϲ�ֵʱʹ�õķ�����
		'thisDownAction' => 'hasSource',                           //����Ƿ�����²�ֵʱʹ�õķ�����
		'up' => array('CGFPYD-02' => 'stockin'),               //�����ϲ����
		'down' => '',                 //�����²����
		'pageObj' => 'invpurchase'  //�б����
	),
	'arrival' => array(
		'thisClass' => 'model_purchase_arrival_arrival',
		'thisDownAction' => 'hasSource',                           //����Ƿ�����²�ֵʱʹ�õķ�����
		'up' => array('purchasecontract'),
		'down' => array('stockin'=>'RSLTZD'),
		'pageObj' => 'arrival'
	),
	'purchasecontract' => array(
		'thisClass' => 'purchase_contract_purchasecontract',
		'thisDownAction' => 'hasSource',                           //����Ƿ�����²�ֵʱʹ�õķ�����
		'up' => '',
		'down' => array('arrival'=>''),
		'pageObj' => 'purchasecontract'
	),
);
?>
