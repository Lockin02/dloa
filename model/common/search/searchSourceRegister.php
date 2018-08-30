<?php
/*
 * Created on 2011-10-29
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

//下查数组
$sourceArr = array(
	'stockin' => array(
		'thisClass' => 'model_stock_instock_stockin',  //检测是否存在下查值时使用的类名
		'thisDownAction' => 'hasSource',                           //检测是否存在下查值时使用的方法名
		'up' => array('RSLTZD' => 'arrival'),
		'down' => array('invpurchase'=>'CGFPYD-02'),
		'pageObj' => 'stockin'
	),
	'invpurchase' => array(
		'thisClass' => 'model_finance_invpurchase_invpurchase',  //对应业务类型
		'thisUpAction' => 'hasSourceInfo',                          //检测是否存在上查值时使用的方法名
		'thisDownAction' => 'hasSource',                           //检测是否存在下查值时使用的方法名
		'up' => array('CGFPYD-02' => 'stockin'),               //关联上查对象
		'down' => '',                 //关联下查对象
		'pageObj' => 'invpurchase'  //列表对象
	),
	'arrival' => array(
		'thisClass' => 'model_purchase_arrival_arrival',
		'thisDownAction' => 'hasSource',                           //检测是否存在下查值时使用的方法名
		'up' => array('purchasecontract'),
		'down' => array('stockin'=>'RSLTZD'),
		'pageObj' => 'arrival'
	),
	'purchasecontract' => array(
		'thisClass' => 'purchase_contract_purchasecontract',
		'thisDownAction' => 'hasSource',                           //检测是否存在下查值时使用的方法名
		'up' => '',
		'down' => array('arrival'=>''),
		'pageObj' => 'purchasecontract'
	),
);
?>
