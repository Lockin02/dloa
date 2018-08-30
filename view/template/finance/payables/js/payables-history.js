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
        title: '�����¼��ʷ -- �ɹ�����: ' + $("#objCode").val(),
        isAddAction : false,
        isEditAction : false,
        isDelAction :false,
        isViewAction : false,
        showcheckbox :false,
        //����Ϣ
        colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���ݱ��',
			name : 'formNo',
			sortable : true,
			width : 150
		}, {
			display : '��������',
			name : 'formDate'
		}, {
			display : '��������',
			name : 'formType',
			sortable : true,
			datacode : 'CWYF'
		}, {
			display : '��Ӧ������',
			name : 'supplierName',
			width : 160
		}, {
			display : '���ݽ��',
			name : 'amount',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '������',
			name : 'money',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '���㷽ʽ',
			name : 'payType',
			datacode : 'CWFKFS'
		}, {
			display : '¼����',
			name : 'createName'
		},{
			display : '״̬',
			name : 'status',
			datacode : 'YFDZT',
			width : 90,
			hide : true
		}, {
			display : '�������������',
			name : 'payApplyNo',
			width : 150
		}],
        buttonsEx : [
        	{
				text : '����������ʷ',
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
				text : '�����¼��ʷ',
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
				text : '�ɹ���Ʊ��¼',
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
				text : '���ϼ�¼',
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
				text : '�鿴�����¼',
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