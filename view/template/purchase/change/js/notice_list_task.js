// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#noticeGrid").yxgrid('reload');
};

$(function() {
			$("#noticeGrid").yxgrid({
				// �������url�����ô����url������ʹ��model��action�Զ���װ
				model : 'purchase_change_notice',
				action : 'pageJsonTask',
				isAddAction : false,
				isEditAction : false,
				isDelAction : false,
				isViewAction:false,
				showcheckbox:false,
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
						}
						, {
							display : '�ɹ�����ID',
							name : 'basicId',
							hide:true
						}
						, {
							display : '�ɹ�������',
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
							width : 300
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
				param : {
					modelCode : "task"
					},
				// ��չ�Ҽ��˵�
				menusEx : [
					{
						text : '�鿴',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=purchase_change_notice&action=init&perm=view&id="+ row.id
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800"
							);
						}
					},{
					text : '����',
					icon : 'add',
					showMenuFn : function(row){
								if(row.state == '0'){
									return true;
								}
								return false;
							},
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
				sortname : "updateTime",
				// Ĭ������˳��
				sortorder : "DESC"
			});

		});