var show_page = function(page) {
    $("#payablesGrid").yxgrid("reload");
};

$(function() {
    $("#payablesGrid").yxgrid({
        model: 'finance_payables_payables',
        action : 'historyJson',
        param : {"supplierId" : $("#supplierId").val()},
        title: '付款记录历史',
        isAddAction : false,
        isEditAction : false,
        isDelAction :false,
        isViewAction : false,
        showcheckbox :false,
        //列信息
        colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '单据编号',
			name : 'formNo',
			sortable : true,
			width : 150
		}, {
			display : '单据日期',
			name : 'formDate'
		}, {
			display : '供应商名称',
			name : 'supplierName',
			width : 160
		}, {
			display : '单据金额',
			name : 'amount',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '结算金额',
			name : 'money',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '结算方式',
			name : 'payType',
			datacode : 'CWFKFS'
		}, {
			display : '录入人',
			name : 'createName'
		}, {
			display : '录入时间',
			name : 'createTime',
			width : 180
		}, {
			display : '状态',
			name : 'status',
			datacode : 'YFDZT',
			width : 90,
			hide : true
		}, {
			display : '关联付款申请号',
			name : 'payApplyNo',
			width : 150
		}],
        menusEx : [
        	{
				text : '查看付款记录',
				icon : 'view',
				action : function(row) {
					showThickboxWin('?model=finance_payables_payables&action=init&perm=view&id='
							+ row.id + "&skey=" + row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
        ],

        toAddConfig :{
        	formWidth : 900,
        	formHeight : 500
        },
        toViewConfig :{
        	formWidth : 900,
        	formHeight : 500
        },
        toEditConfig :{
        	formWidth : 900,
        	formHeight : 500
        },
		sortname : 'updateTime'
    });
});