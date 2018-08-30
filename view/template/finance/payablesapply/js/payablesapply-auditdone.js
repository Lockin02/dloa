/**收票管理列表**/

var show_page = function(page) {
	$("#payablesapplyGrid").yxgrid("reload");
};

$(function() {

	$("#payablesapplyGrid").yxgrid({

		model : 'finance_payablesapply_payablesapply',
		action : 'auditdoneJson',
		title : '未审批付款申请',
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
			display : '申请单Id',
			name : 'applyId',
			sortable : true,
			width : 150,
			hide : true
		}, {
			display : '单据编号',
			name : 'formNo',
			sortable : true,
			width : 150
		}, {
			display : '供应商名称',
			name : 'supplierName',
			width : 200
		}, {
			display : '付款方式',
			name : 'payType',
			datacode : 'CWFKFS'
		}, {
			display : '申请付款金额',
			name : 'payMoney',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'taskId',
			name : 'taskId',
			hide : 'true'
		}, {
			display : '单据日期',
			name : 'formDate'
		}, {
			display : '申请时间',
			name : 'createTime',
			width : 150
		}, {
			display : '申请人',
			name : 'createName'
		}, {
			display : '审批状态',
			name : 'ExaStatus'
		}],

		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=finance_payablesapply_payablesapply&action=init&perm=view&id='
							+ row.applyId
							+ '&skey=' + row['skey_1']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

				}

			}
		}, {
			text : '审批情况',
			icon : 'view',
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_finance_payablesapply&pid='
						+ row.applyId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}

		],

		searchitems : [{
			display : '申请单号',
			name : 'applyNo'
		}],
		sortname : 'id',
		sortorder : 'DESC'
	});
});