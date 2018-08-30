/** 到款列表* */

var show_page = function(page) {
	$("#payableGrid").yxgrid("reload");
};

$(function() {

	$("#payableGrid").yxgrid({

		model : 'finance_payable_payable',
		title : '所有的应付账款',
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '采购合同/申请单号',
					name : 'applyNumb',
					sortable : true,
					width : 200
				}, {
					display : '供应商',
					name : 'suppName',
					width : 200
				}, {
					display : '合同申请人',
					name : 'createName'
				}, {
					display : '合同金额',
					name : 'applyMoney',
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					display : '需付款金额',
					name : 'invoiceMoney',
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					display : '已付款金额',
					name : 'payMoney',
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					display : '未付款金额',
					name : 'remainMoney',
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					display : '已付金额占合同百分比',
					name : 'perMoney',
					width : 120 ,
					process : function(v) {
						return v + ' %' ;
					}
				}],

		searchitems : [{
					display : '采购合同/申请单号',
					name : 'applyNumb'
				}],
		sortname : 'id',

		// 扩展右键菜单
		menusEx : [{
			text : '查看详细',
			icon : 'view',
			action : function(row, rows, grid) {
				if( row ){
					location = "?model=finance_payapply_payapply&action=payableDetail&applyNumb=" + row.applyNumb;
				}
			}

		}]
	});
});