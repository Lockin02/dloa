// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#basicNoGrid").yxgrid("reload");
};
$(function() {
	$("#basicNoGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_basic',
		action : 'myAuditPj',
		title : '�������ɹ������б�',
		isToolBar : false,
		showcheckbox : false,
		param:{'purchType':"rdproject"},

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ�������',
					name : 'planNumb',
					sortable : true,
					width : 180
				},  {
					display : '����״̬',
					name : 'ExaStatus',
					sortable : true
				},{
					display : '����Դ���ݺ�',
					name : 'sourceNumb',
					sortable : true,
					width:180
				}, {
					display : '������',
					name : 'createName',
					sortable : true
				}, {
					display : '����ʱ�� ',
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
					location="?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType+"&skey="+row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		},{
			name : 'sumbit',
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
					parent.location = "controller/purchase/plan/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_plan_basic&skey="+row['skey_'];
			}

		}],
		// ��������
		searchitems : [{
					display : '�ɹ�������',
					name : 'seachPlanNumb'
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