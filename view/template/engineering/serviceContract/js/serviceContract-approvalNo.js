// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".approvalNoGrid").yxgrid("reload");
};
$(function() {
	$(".approvalNoGrid").yxgrid({
		//�������url�����ô����url������ʹ��model��action�Զ���װ
		//						 url :
		model : 'engineering_serviceContract_serviceContract',
		action : 'pageJsonNo',
		title : '�������ķ����ͬ',
		formHeight : 600,
		isToolBar : false,
		showcheckbox : false,

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'parentOrder',
			display : '����ͬ����',
			sortable : true,
			hide : true
		}, {
			name : 'orderCode',
			display : '������ͬ��',
			sortable : true
		}, {
			name : 'orderTempCode',
			display : '��ʱ��ͬ��',
			sortable : true
		}, {
			name : 'orderName',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'state',
			display : '��ͬ״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ�ύ";
				} else if (v == '1') {
					return "������";
				} else if (v == '2') {
					return "ִ����";
				} else if (v == '3') {
					return "�ѹر�";
				} else if (v == '4') {
					return "�����";
				}
			},
			width : 90
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 90
		}, {
			name : 'transmit',
			display : '������״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ�´�";
				} else if (v == '1') {
					return "���´�";
				}
			}
		}],
		//��չ��ť
		//						buttonsEx : [],
		param : {
			ExaStatus : '��������'
		},
		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&id="
							+ row.contractId  + "&skey="+row['skey_']);
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '����',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					location = "controller/engineering/serviceContract/ewf_index.php?actTo=ewfExam&taskId="
							+ row.task
							+ "&spid="
							+ row.id
							+ "&billId="
							+ row.contractId + "&examCode=oa_contract_service" + "&skey="+row['skey_'];
				}
			}
		}],
		//��������
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		},{
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		}],
		// title : '�ͻ���Ϣ',
		//ҵ���������
		//						boName : '��Ӧ����ϵ��',
		//Ĭ�������ֶ���
		sortname : "id",
		//Ĭ������˳��
		sortorder : "ASC",
		//��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false
	});

});