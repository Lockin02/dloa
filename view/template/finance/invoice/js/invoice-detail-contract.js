var show_page = function(page) {
	$("#invoiceDetail").yxgrid("reload");
};
$(function() {

	$("#invoiceDetail").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		model : 'finance_invoice_invoice',
		action : 'contractPagejson',
		param : {'exObjCode' : $('#objCode').val(),'exObjType' : $('#objType').val()},
        /**
		 * 是否显示查看按钮/菜单
		 */
		isViewAction : false,
		/**
		 * 是否显示修改按钮/菜单
		 */
		isEditAction : false,
		/**
		 * 是否显示删除按钮/菜单
		 */
        isDelAction : false,
        //是否显示添加按钮
        isAddAction : false,
        //是否显示工具栏
        isToolBar : false,
        //是否显示checkbox
         showcheckbox : false,
		// 扩展右键菜单
		menusEx : [
		{
			text : '查看开票申请',
			icon : 'view',
			action : function(row){
				showThickboxWin('?model=finance_invoiceapply_invoiceapply&action=init'
				+ '&id=' + row.applyId
				+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},
		{
			text : '查看开票记录',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=finance_invoice_invoice&action=init&perm=view&id='
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&width=900&height=500");
			}
		}],
		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : '发票号',
			name : 'invoiceNo',
			sortable : true,
			width : 100

		},{
			display : '开票申请id',
			name : 'applyId',
			hide : true

		}, {
			display : '开票申请单号',
			name : 'applyNo',
			sortable : true,

			width : 150
		}, {
			display : '开票类型',
			name : 'invoiceType',
			sortable : true,
			width : 100,
			datacode : 'FPLX'
		}, {
			display : '总金额',
			name : 'invoiceMoney',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '软件金额',
			name : 'softMoney',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '硬件金额',
			name : 'hardMoney',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '服务金额',
			name : 'serviceMoney',
			sortable : true,
            width : 100,
			process : function(v){
				return moneyFormat2(v);
			}

		}, {
			display : '维修金额',
			name : 'repairMoney',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '设备租赁金额',
			name : 'equRentalMoney',
			sortable : true,
            width : 90,
			process : function(v,row){
				if(row.isRed == 0){
					return moneyFormat2(v);
				}else{
					return '-' + moneyFormat2(v);
				}
			}

		}, {
			display : '场地租赁金额',
			name : 'spaceRentalMoney',
			sortable : true,
			width : 90,
			process : function(v,row){
				if(row.isRed == 0){
					return moneyFormat2(v);
				}else{
					return '-' + moneyFormat2(v);
				}
			}
		}, {
			display : '开票人',
			name : 'createName',
			sortable : true,
			width : 100
		}, {
			display : '开票日期',
			name : 'invoiceTime',
			sortable : true,
			width : 100
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '发票号',
			name : 'invoiceNo'
		}],
		sortorder : "ASC",
		title : '开票记录'
	});
});