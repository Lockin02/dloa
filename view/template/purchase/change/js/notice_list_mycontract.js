// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#noticeGrid").yxgrid('reload');
};

$(function() {
			$("#noticeGrid").yxgrid({
				// �������url�����ô����url������ʹ��model��action�Զ���װ
				model : 'purchase_change_notice',
				action : 'myChangeJSON',
				isAddAction : false,
				isEditAction : false,
				isDelAction : false,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '��������',
							name : 'changeNumb',
							width : 200
						}, {
							display : '���뵥���',
							name : 'basicNumb',
							width : 200
						}
//						, {
//							display : '�������',
//							name : 'subject',
//							width : 200
//						}
						, {
							display : '״̬',
							name : 'state',
							process : function(v) {
								if (v == 0) {
									return "δ����";
								}
								return "�ѽ���";

							}
						}, {
							display : '�����ϸ',
							name : 'remark',
							width : 600
						}],
				comboEx : [{
							text : "���״̬",
							key : 'state',
							data : [{
										text : 'δ����',
										value : 0
									}, {
										text : '�ѽ���',
										value : 1
									}]
						}],
				param : {"subject" : "�ɹ���ͬ���"},
				// ��չ�Ҽ��˵�
				menusEx : [{
					text : '����',
					icon : 'add',
					action : function(row, rows, rowIds, grid) {
						$.get(
								"?model=purchase_change_notice&action=receive&id="
										+ row.id, function(data) {
									if (data == 1) {
										alert('���ճɹ���');
										show_page();
									} else {
										alert('����ʧ�ܣ�');
									}
								});

					}
				}],
				// ��������
				searchitems : [{
							display : '��������',
							name : 'changeNumb'
						}, {
							display : '���뵥���',
							name : 'basicNumb',
							isdefault : true
						}],
				// title : '�ͻ���Ϣ',
				// ҵ���������
				boName : '���֪ͨ',
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			});

		});