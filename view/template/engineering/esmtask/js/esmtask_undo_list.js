// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".esmtaskundoGrid").yxgrid("reload");
};
$(function() {
			$(".esmtaskundoGrid").yxgrid({

						model : 'engineering_esmtask_esmtask',
						 //�Ҽ���չ
					     menusEx : [
					     {
					         text : '�鿴',
			                 icon : 'view',
			                 action : function(row) {

								 showThickboxWin('?model=engineering_task_protask&action=taskTab&id='
										+ row.id
										+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
			                                        }

					      },{
								text : '��д��־',
								icon : 'add',
								action : function(row) {

									showThickboxWin('?model=.../.../...&action=....&id='
											+ row.id
											+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
								}

							  } ],
//						action : 'undoPageJson',
//						title:'��Ŀ����--δִ��',
//						showcheckbox : true,	//��ʾcheckbox
//						isToolBar : false,		//��ʾ�б��Ϸ��Ĺ�����

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
						//��ʾ�༭��ť
						isEditAction : false,
						//������Ӱ�ť
						isAddAction : false,
						//����ɾ����ť
						isDelAction : false,
						//�鿴��չ��Ϣ
						toViewConfig : { action : 'toRead' },

						//�޸���չ��Ϣ
						//toEditConfig : { action : 'toEdit' }
					});

		});