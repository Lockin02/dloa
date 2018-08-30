var show_page = function(page) {
    $("#payablesGrid").yxgrid("reload");
};

$(function() {
    $("#payablesGrid").yxgrid({
        model: 'finance_payables_payables',
        action : 'historyJson',
        param : {"supplierId" : $("#supplierId").val()},
        title: '�����¼��ʷ',
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
		}, {
			display : '¼��ʱ��',
			name : 'createTime',
			width : 180
		}, {
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
        menusEx : [
        	{
				text : '�鿴�����¼',
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