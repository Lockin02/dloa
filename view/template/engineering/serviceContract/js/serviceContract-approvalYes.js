// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".approvalYesGrid").yxgrid("reload");
};
$(function() {
	$(".approvalYesGrid").yxgrid({
		//�������url�����ô����url������ʹ��model��action�Զ���װ
		//						 url :
		model : 'engineering_serviceContract_serviceContract',
		//						action : 'pageJson&contractID='+$("#contractID").val()+"&systemCode="+$("#systemCode").val(),
		action : 'pageJsonYes',
				param : {
					"ExaStatuss" : "���,���"
				},
		title : '����˵ķ����ͬ',
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
		},{
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
		buttonsEx : [],
		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&id="
							+ row.contractId + "&skey="+row['skey_']);
				} else {
					alert("��ѡ��һ������");
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
		isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});