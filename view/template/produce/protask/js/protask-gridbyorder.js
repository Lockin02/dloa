var show_page = function(page) {
	$("#pagebyorderGrid").yxgrid("reload");
};
$(function() {
	var purchType = $('#purchType').val();
	var orderId = $('#orderId').val();
	$("#pagebyorderGrid").yxgrid({
		model : 'produce_protask_protask',
		action : 'pagebyorder',
		title : '��ͬ����������',
		param : { 'relDocType': purchType,'relDocId':orderId },
		showcheckbox :false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'taskCode',
			display : '����������',
			width : '120',
			sortable : true
		}, {
			name : 'relDocType',
			display : 'Դ������',
			width : '60',
			sortable : true,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "���ۺ�ͬ";
				}else if (v == 'oa_sale_lease') {
					return "���޺�ͬ";
				}else if (v == 'oa_sale_service'){
				    return "�����ͬ";
				}else if (v == 'oa_sale_rdproject'){
				    return "�з���ͬ";
				}else if (v == 'oa_borrow_borrow'){
				    return "���ú�ͬ";
				}
			}
		}, {
			name : 'relDocId',
			display : 'Դ��id',
			sortable : true,
			hide : true
		}, {
			name : 'relDocCode',
			display : 'Դ�����',
			width : '150',
			sortable : true
		}, {
			name : 'relDocName',
			display : 'Դ������',
			width : '180',
			sortable : true
		}, {
			name : 'issuedDeptName',
			display : '�µ�����',
			sortable : true
		}, {
			name : 'execDeptName',
			display : 'ִ�в���',
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'referDate',
			display : '��������',
			sortable : true
		}, {
			name : 'proStatus',
			display : '����״̬',
			width : '60',
			sortable : true,
			process : function(v){
				( v=='YWC' ) ? (v='�����') : ( v='δ���' );
				return v;
			}
		}, {
			name : 'issuedStatus',
			display : '�´�״̬',
			width : '60',
			sortable : true,
			process : function(v){
				( v==0 ) ? (v='δ�´�') : ( v='���´�' );
				return v;
			}
		}, {
			name : 'qualityType',
			display : '��֤����',
			sortable : true,
			hide : true
		}, {
			name : 'taskType',
			display : '����',
			sortable : true,
			hide : true
		}, {
			name : 'issuedman',
			display : '�´���',
			sortable : true
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=produce_protask_protask&action=toView&id='
						+ row.id
						+ '&relDocType='
						+ row.relDocType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}]
	});
});