// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".esmtaskdoneGrid").yxgrid("reload");
};
$(function() {
			$(".esmtaskdoneGrid").yxgrid({
                         param:  {"status":"TG"},
						model : 'engineering_task_esmtask',
						action : 'donePageJson',
						title:'��Ŀ����--�����',
						showcheckbox : true,	//��ʾcheckbox
						isToolBar : false,		//��ʾ�б��Ϸ��Ĺ�����

						//����Ϣ
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '��������',
								name : 'name',
								sortable : true
							},{
								display : '������Ŀ',
								name : 'projectName',
								sortable : true
							},{
								display : '���ȼ�',
								name : 'priority',
								sortable : true
							}, {
								display : '״̬',
								name : 'status',
								datacode : 'XMRWZT',
								sortable : true
							},{
								display : '�����',
								name : 'effortRate',
								sortable : true
							},{
								display : 'ƫ����',
								name : 'warpRate',
								sortable : true
							}, {
								display : '������',
								name : 'chargeName',
								sortable : true
							},{
								display : '�������ʱ��',
								name : 'updateTime',
								sortable : true,
								width : 150
							}, {
								display : '�ƻ����ʱ��',
								name : 'planEndDate',
								sortable : true
							},{
								display : '��������',
								name : 'taskType',
								sortable : true
							}
						],
						//��չ��ť
						buttonsEx : [],
						 //��չ�Ҽ�
						 menusEx : [{
								text : '�鿴',
								icon : 'view',
								action : function(row) {

									showThickboxWin('?model=engineering_task_protask&action=taskTab&id='
											+ row.id
											+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
								}

							}],
						//��������
						searchitems : [{
									display : '�ȼ�',
									name : 'name'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '�ȼ�',
						//Ĭ�������ֶ���
						sortname : "name",
						//Ĭ������˳��
						sortorder : "ASC",
						//��ʾ�鿴��ť
						isViewAction : false,
						//������Ӱ�ť
						isAddAction : false,
						//����ɾ����ť
						isDelAction : false,
						//���ر༭��ť
						isEditAction : false,
						//�鿴��չ��Ϣ
						toViewConfig : { action : 'toRead' },

						//�޸���չ��Ϣ
						toEditConfig : { action : 'toEdit' }
					});

		});