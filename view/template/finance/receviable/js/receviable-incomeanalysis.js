/** 到款列表* */

var show_page = function(page) {
	$("#receviableGrid").yxgrid("reload");
};

$(function() {
	var titleStr = '合同明细 --' + $("#year").val() + "年" + $("#beginMonth").val() + "月至"  + $("#endMonth").val() + "月" ;
	var objType=$("#objType").val();
	switch(objType){
		case 'KPRK-01':var orderType='oa_sale_order';var objSign='是';break;
		case 'KPRK-02':var orderType='oa_sale_order';var objSign='否';break;
		case 'KPRK-03':var orderType='oa_sale_service';var objSign='是';break;
		case 'KPRK-04':var orderType='oa_sale_service';var objSign='否';break;
		case 'KPRK-05':var orderType='oa_sale_lease';var objSign='是';break;
		case 'KPRK-06':var orderType='oa_sale_lease';var objSign='否';break;
		case 'KPRK-07':var orderType='oa_sale_rdproject';var objSign='是';break;
		case 'KPRK-08':var orderType='oa_sale_rdproject';var objSign='否';break;
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
		"prinvipalId" : $("#prinvipalId").val()	  //合同负责人ID
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
		usepager : false, // 是否分页

		colModel : [{
			display: 'id',
			name: 'id',
			hide: true
		},{
			display: '年',
			name: 'createYear',
			width:50
		},
		{
			name: 'createMonth',
			display: '月',
			width:50
		},{
			display: '合同类型',
			name: 'objType',
			process : function(v){
				switch(v){
					case 'oa_sale_order':return '销售合同';break;
					case 'oa_sale_service':return '服务合同';break;
					case 'oa_sale_lease':return '租赁合同';break;
					case 'oa_sale_rdproject':return '研发合同';break;
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
			display: '合同号',
			width :180
		},
		{
			name: 'orderTempCode',
			display: '临时合同号',
			width : 180
		},
		{
			name: 'customerName',
			display: '客户名称'
		},
		{
			name: 'customerProvince',
			display: '省份',
			width : 70
		},
		{
			name: 'areaName',
			display: '区域',
			width : 70
		},
		{
			name: 'prinvipalName',
			display: '合同负责人'
		},
		{
			name: 'areaPrincipal',
			display: '区域负责人',
			width : 70
		},
		{
			name: 'thisOrderMoney',
			display: '合同金额',
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
			display: '已开票金额',
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
			display: '未开票金额',
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
			display: '已到款金额',
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
			display: '未到款金额',
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
				text : '过滤',
				icon : 'search',
				action : function(row, rows, grid) {
					showOpenWin("?model=finance_receviable_receviable&action=toSearch")
				}
			}
//			,
//			{
//				text : '返回',
//				icon : 'view',
//				action : function(row, rows, grid) {
//					location = "?model=finance_receviable_receviable&action=toIncomeAnalysis"
//				}
//			}
		],

		// 扩展右键菜单
		menusEx : [{
			text : '查看详细',
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