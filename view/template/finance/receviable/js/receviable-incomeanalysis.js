/** �����б�* */

var show_page = function(page) {
	$("#receviableGrid").yxgrid("reload");
};

$(function() {
	var titleStr = '��ͬ��ϸ --' + $("#year").val() + "��" + $("#beginMonth").val() + "����"  + $("#endMonth").val() + "��" ;
	var objType=$("#objType").val();
	switch(objType){
		case 'KPRK-01':var orderType='oa_sale_order';var objSign='��';break;
		case 'KPRK-02':var orderType='oa_sale_order';var objSign='��';break;
		case 'KPRK-03':var orderType='oa_sale_service';var objSign='��';break;
		case 'KPRK-04':var orderType='oa_sale_service';var objSign='��';break;
		case 'KPRK-05':var orderType='oa_sale_lease';var objSign='��';break;
		case 'KPRK-06':var orderType='oa_sale_lease';var objSign='��';break;
		case 'KPRK-07':var orderType='oa_sale_rdproject';var objSign='��';break;
		case 'KPRK-08':var orderType='oa_sale_rdproject';var objSign='��';break;
		default:var orderType='';var objSign='';break;
	}
	objParam = {
		"custId" : $("#customerId").val(),
		"customerType" : $("#customerType").val(),
		"customerProvince" : $("#customerProvince").val(),
		"searchYear" : $("#year").val(),
		"begin" : $("#beginMonth").val(),
		"end" : $("#endMonth").val(),
		"orderId" : $("#objId").val(),
		"orderType" : orderType,
		"objSign" : objSign,
		"areaName" : $("#areaName").val(),
		"prinvipalId" : $("#prinvipalId").val()	  //��ͬ������ID
	};
	$("#receviableGrid").yxgrid({
		model : 'finance_receviable_receviable',
		action : 'incomeAnalysisPj',
		title : titleStr ,
		param : objParam,
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		usepager : false, // �Ƿ��ҳ

		colModel : [{
			display: 'id',
			name: 'id',
			hide: true
		},{
			display: '��',
			name: 'createYear',
			width:50
		},
		{
			name: 'createMonth',
			display: '��',
			width:50
		},{
			display: '��ͬ����',
			name: 'objType',
			process : function(v){
				switch(v){
					case 'oa_sale_order':return '���ۺ�ͬ';break;
					case 'oa_sale_service':return '�����ͬ';break;
					case 'oa_sale_lease':return '���޺�ͬ';break;
					case 'oa_sale_rdproject':return '�з���ͬ';break;
				}
			},
			width:60
		},{
			display: 'orgid',
			name: 'orgid',
			hide:true
		},
		{
			name: 'orderCode',
			display: '��ͬ��',
			width :180
		},
		{
			name: 'orderTempCode',
			display: '��ʱ��ͬ��',
			width : 180
		},
		{
			name: 'customerName',
			display: '�ͻ�����'
		},
		{
			name: 'customerProvince',
			display: 'ʡ��',
			width : 70
		},
		{
			name: 'areaName',
			display: '����',
			width : 70
		},
		{
			name: 'prinvipalName',
			display: '��ͬ������'
		},
		{
			name: 'areaPrincipal',
			display: '��������',
			width : 70
		},
		{
			name: 'thisOrderMoney',
			display: '��ͬ���',
			process : function(v){
				if(v >= 0){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 80
		},
		{
			name: 'invoiceMoney',
			display: '�ѿ�Ʊ���',
			process : function(v){
				if(v >= 0){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 80
		},
		{
			name: 'unInvoiceMoney',
			display: 'δ��Ʊ���',
			process : function(v){
				if(v >= 0){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 80
		},
		{
			name: 'incomeMoney',
			display: '�ѵ�����',
			process : function(v){
				if(v >= 0){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 80
		},
		{
			name: 'unIncomeMoney',
			display: 'δ������',
			process : function(v){
				if(v >= 0){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 80
		}],
		buttonsEx : [
			{
				text : '����',
				icon : 'search',
				action : function(row, rows, grid) {
					showOpenWin("?model=finance_receviable_receviable&action=toSearch")
				}
			}
//			,
//			{
//				text : '����',
//				icon : 'view',
//				action : function(row, rows, grid) {
//					location = "?model=finance_receviable_receviable&action=toIncomeAnalysis"
//				}
//			}
		],

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',showMenuFn:function(row){
		         if(row.orgid != undefined ){
		            return true;
		         }
		         return false;
		    },
			action : function(row, rows, grid) {
				switch(row.objType){
					case 'oa_sale_order' : showOpenWin("?model=projectmanagent_order_order&action=toViewTab&id=" + row.orgid + "&skey=" + row['skey_'] ,1);break;
					case 'oa_sale_service' : showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id=" + row.orgid + "&skey=" + row['skey_']  ,1);break;
					case 'oa_sale_lease' : showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id=" + row.orgid + "&skey=" + row['skey_']  ,1);break;
					case 'oa_sale_rdproject' : showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=" + row.orgid + "&skey=" + row['skey_']  ,1);break;
					default : return '';
				}
			}
		}],
		sortname : 'createTime'
	});
});