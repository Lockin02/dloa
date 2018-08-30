function getQueryStringPay(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null)
		return unescape(r[2]);
	return null;
}
var show_page = function(page) {
    $("#payablesGrid").yxgrid("reload");
};

$(function() {
	var gdbtable = getQueryStringPay('gdbtable');
	var skey = "&skey=" + $("#skey").val();

    $("#payablesGrid").yxgrid({
        model: 'finance_payables_payables',
        action : 'historyJson',
        param : {"dObjId" : $("#objId").val(),"dObjType" : $("#objType").val(),"gdbtable" : gdbtable},
        title: '付款记录历史 -- 采购订单: ' + $("#objCode").val(),
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
			display : '单据类型',
			name : 'formType',
			sortable : true,
			datacode : 'CWYF'
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
		},{
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
        buttonsEx : [
        	{
				text : '付款申请历史',
				icon : 'view',
				action : function(row) {
					location="?model=finance_payablesapply_payablesapply&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			},{
				text : '付款记录历史',
				icon : 'edit',
				action : function(row) {
					location="?model=finance_payables_payables&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
			,{
				text : '采购发票记录',
				icon : 'view',
				action : function(row) {
					location="?model=finance_invpurchase_invpurchase&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
			,{
				text : '收料记录',
				icon : 'view',
				action : function(row) {
					location="?model=purchase_arrival_arrival&action=toListByOrder"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
        ],
        menusEx : [
        	{
				text : '查看付款记录',
				icon : 'view',
				action : function(row) {
					showThickboxWin('?model=finance_payables_payables&action=init&perm=view&id='
							+ row.id + "&skey=" + row.skey_+ "&gdbtable=" + gdbtable
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