<?php
/**
 * ���Ϲ���ҵ�������
 * key Ϊҵ�����
 * value Ϊ�������ֶι�ϵ
 */
$productRelationTableArr=array(
	"sale"=>
	array(
		//���ۺ�ͬ���ϴӱ�
		"oa_contract_equ" => array(
			"���ۺ�ͬ",
			"id" => "productId",
			"productName" => "productName",
			"productCode" => "productCode",
            "pattern"=> "productModel",
            "unitName"=>"unitName"
		),
		//���޺�ͬ���ϴӱ�
//		"oa_lease_equ" => array(
//			"���޺�ͬ",
//			"id" => "productId",
//			"productName" => "productName",
//			"productCode" => "productNo",
//            "pattern"=> "productModel",
//            "unitName"=>"unitName"
//
//		),
//		//�з���ͬ���ϴӱ�
//		"oa_rdproject_equ" => array(
//			"�з���ͬ",
//			"id" => "productId",
//			"productName" => "productName",
//			"productCode" => "productNo",
//            "pattern"=> "productModel",
//            "unitName"=>"unitName"
//             ),
//		//�����ͬ���ϴӱ�
//		"oa_service_equ" => array(
//			"�����ͬ",
//			"id" => "productId",
//			"productName" => "productName",
//			"productCode" => "productCode",
//            "pattern"=> "productModel",
//            "unitName"=>"unitName"
//
//		),
//		//�̻����ϴӱ�
//		"oa_sale_chance_equ" => array(
//			"�̻�",
//			"id" => "productId",
//			"productName" => "productName",
//			"productCode" => "productCode",
//            "pattern"=> "productModel",
//            "unitName"=>"unitName"
//
//		),
		//�������ϴӱ�
		"oa_present_equ" => array(
			"����",
			"id" => "productId",
			"productName" => "productName",
			"productCode" => "productNo",
            "pattern"=> "productModel",
            "unitName"=>"unitName"

		),
		//�˻����ϴӱ�
		"oa_sale_return_equ" => array(
			"�˻�",
			"id" => "productId",
			"productName" => "productName",
			"productCode" => "productNo",
            "pattern"=> "productModel",
            "unitName"=>"unitName"

		),
		//���������ϴӱ�
		"oa_borrow_equ" => array(
			"������",
			"id" => "productId",
			"productName" => "productName",
			"productCode" => "productNo",
            "pattern"=> "productModel",
            "unitName"=>"unitName"
            )
	),
	"delivery"=>
	array(
		//�����ƻ�������ϸ
		'oa_stock_outplan_product'=>array(
			"�����ƻ�������ϸ",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			"pattern"=>"productModel",
			"unitName"=>"unitName"
		),
		//������������ϸ
		'oa_stock_ship_product'=>array(
			"������������ϸ",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			"pattern"=>"productModel",
			"unitName"=>"unitName"
		),
		//�ʼ���Ϣ������ϸ
		'oa_mail_products'=>array(
			"�ʼ���Ϣ������ϸ",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo"
		)
	),
//	"production"=>
//	array(
//		//��������������ϸ
//		'oa_produce_protaskequ'=>array(
//			"��������������ϸ",
//			"id"=>"productId",
//			"productName"=>"productName",
//			"productCode"=>"productNo",
//			"pattern"=>"productModel",
//			"unitName"=>"unitName"
//		)
//	),
	"purch"=>
	array(
		//�ɹ����������嵥
		'oa_purch_plan_equ'=>array(
			"�ɹ�����",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'unitName'
		),
		//�ɹ����������嵥
		'oa_purch_task_equ'=>array(
			"�ɹ�����",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'unitName'
		),
		//�ɹ�ѯ�������嵥
		'oa_purch_inquiry_equ'=>array(
			"�ɹ�ѯ��",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//�ɹ�ѯ�۱��������嵥
		'oa_purch_inquiry_suppequ'=>array(
			"�ɹ�ѯ�۱���",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//�ɹ����������嵥
		'oa_purch_apply_equ'=>array(
			"�ɹ�����",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//���������嵥
		'oa_purchase_arrival_equ'=>array(
			"����",
			'id'=>'productId',
			'productCode'=>'sequence',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//���������嵥
		'oa_purchase_delivered_equ'=>array(
			"����",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//��Ӧ����Ӫ�������嵥
		'oa_supp_prod'=>array(
			"��Ӧ����Ӫ��",
			'id'=>'productId',
			'productName'=>'productName'
		),
		//��Ӧ����ʱ�������嵥
		'oa_supp_prod_temp'=>array(
			"��Ӧ����ʱ��",
			'id'=>'productId',
			'productName'=>'productName'
		)
	),
	"finance"=>
	array(
		//��������
		'oa_finance_adjustment_detail'=>array(
			"��������",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel'
		),
		//���
		'oa_finance_costajust_detail'=>array(
			"���",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel'
		),
		//�ɹ���Ʊ
		'oa_finance_invpurchase_detail'=>array(
			"�ɹ���Ʊ",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel',
			'unitName' => 'unit'
		),
		//������־
		'oa_finance_related_detail'=>array(
			"������־",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel',
			'unitName' => 'unit'
		),
		//������
		'oa_finance_stockbalance'=>array(
			"������",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel',
			'unitName' => 'units'
		)
	),
	"stock"=>
	array(
		//�ڳ����
		'oa_stock_inventory_info'=>array(
			"�ڳ����",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//�ֿ�������¼
		'oa_stock_lock'=>array(
			"�ֿ�������¼",
			'id'=>'productId',
			'productCode'=>'productNo',
			'productName'=>'productName'
		),
		//��ⵥ�����嵥
		'oa_stock_instock_item'=>array(
			"��ⵥ",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//���ⵥ�����嵥
		'oa_stock_outstock_item'=>array(
			"���ⵥ",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//���ⵥ���϶��������嵥
		'oa_stock_stockout_extraitem'=>array(
			"���ⵥ���϶��������嵥",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern'
		),
		//�������к�̨��
		'oa_stock_product_serialno'=>array(
			"�������к�̨��",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern'
		),
		//�������κ�̨��
		'oa_stock_product_batchno'=>array(
			"�������κ�̨��",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName'
		),
		//�̵������嵥
		'oa_stock_check_item'=>array(
			"�̵�",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//����ƻ������嵥
		'oa_stock_fillup_detail'=>array(
			"����ƻ�",
			'id'=>'productId',
			'productCode'=>'sequence',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//�����������嵥
		'oa_stock_allocation_item'=>array(
			"������",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//�������
		'oa_stock_product_configuration'=>array(
			"�������",
			'id'=>'configId',
			'productCode'=>'configCode',
			'productName'=>'configName',
			'pattern'=>'configPattern',
			'condition'=>" and configType<>'proconfig' "
		)
	)
//	,"asset"=>
//	array(
//		//�ʲ���Ƭ
//		'oa_asset_card'=>array(
//			"�ʲ���Ƭ",
//			'id'=>'productId',
//			'productCode'=>'productCode',
//			'productName'=>'productName'
//		),
//		//�ʲ���Ƭ��ʱ������
//		'oa_asset_card_temp'=>array(
//			"�ʲ���Ƭ��ʱ������",
//			'id'=>'productId',
//			'productCode'=>'productCode',
//			'productName'=>'productName'
//		)
//	)

);

?>
