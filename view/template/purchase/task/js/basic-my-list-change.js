// ��������/�޸ĺ�ص�ˢ�±���
var show_page = function(page) {
	$(".myChangeGrid").yxgrid("reload");
};
$(function() {
	$(".myChangeGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_task_basic',
		action : 'pageJsonMy',
		title : '�ɹ��������б�',
		isToolBar : false,
		showcheckbox : false,

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ�������',
					name : 'taskNumb',
					sortable : true,
					width : 180
				}, {
					display : '�����´���',
					name : 'createName',
					sortable : true
				}, {
					display : '��������',
					name : 'sendName',
					sortable : true
				}, {
					display : '�����´�ʱ�� ',
					name : 'sendTime',
					sortable : true,
					width : 60
				}, {
					display : 'ϣ�����ʱ�� ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					parent.location="?model=purchase_task_basic&action=read&id="
							+ row.id+"&skey="+row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		}],
		// ��������
		searchitems : [{
					display : '�ɹ�������',
					name : 'taskNumb'
				}
		],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});