/** ��Ʊ�����б�* */
var show_page = function(page) {
	$("#payablesapplyGrid").yxgrid("reload");
};

$(function() {
	$("#payablesapplyGrid").yxgrid({
		model : 'finance_payablesapply_payablesapply',
		action : 'auditundoJson',
		title : 'δ������������',
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
			display : '���뵥Id',
			name : 'applyId',
			sortable : true,
			width : 150,
			hide : true
		}, {
			display : '���ݱ��',
			name : 'formNo',
			sortable : true,
			width : 150
		}, {
			display : '��Ӧ������',
			name : 'supplierName',
			width : 200
		}, {
			display : '���ʽ',
			name : 'payType',
			datacode : 'CWFKFS'
		}, {
			display : '���븶����',
			name : 'payMoney',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'taskId',
			name : 'taskId',
			hide : 'true'
		}, {
			display : '��������',
			name : 'formDate'
		}, {
			display : '����ʱ��',
			name : 'createTime',
			width : 150
		}, {
			display : '������',
			name : 'createName'
		}, {
			display : '����״̬',
			name : 'ExaStatus'
		}],

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=finance_payablesapply_payablesapply&action=init&perm=view&id='
							+ row.applyId
							+ '&skey=' + row['skey_1']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

				}

			}
		},{
			text : '����',
			icon : 'edit',
			action: function(row){
                location = 'controller/finance/payablesapply/ewf_index.php?taskId='+
                	row.task +
                	'&spid=' +
                	row.id +
					'&skey=' + row['skey_1'] +
                	'&billId=' +
                	row.applyId + '&examCode=oa_finance_payablesapply&actTo=ewfExam';
			}
		}],

		searchitems : [{
			display : '���뵥��',
			name : 'applyNo'
		}],
		sortname : 'id',
		sortorder : 'DESC'
	});
});