// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".pjtaskdoingGrid").yxgrid("reload");
};
$(function() {
			$(".pjtaskdoingGrid").yxgrid({

						model : 'engineering_pjtask_pjtask',
						action : 'doingPageJson',
						title:'��Ŀ����--��ִ��',
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
								name : 'score',
								sortable : true
							}
						],
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [],
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
						isViewAction : true,
						//������Ӱ�ť
						isAddAction : true,
						//����ɾ����ť
						isDelAction : true,
						//�鿴��չ��Ϣ
						toViewConfig : { action : 'toRead' },

						//�޸���չ��Ϣ
						toEditConfig : { action : 'toEdit' }
					});

		});