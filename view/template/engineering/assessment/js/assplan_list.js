// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assplanGrid").yxgrid("reload");
};
$(function() {
			$(".assplanGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_assessment_assplan',

						// action : 'pageJson',
						title:'���˼ƻ�',
						showcheckbox : true,	//��ʾcheckbox
						isToolBar : true,		//��ʾ�б��Ϸ��Ĺ�����

						//����Ϣ
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '���˼ƻ�����',
								name : 'planName',
								sortable : true
							},{
								display : '��ʼ����',
								name : 'planStartTime',
								sortable : true
							}, {
								display : '��������',
								name : 'planEndTime',
								sortable : true
							},{
								display : '���´�',
								name : 'office',
								sortable : true
							}
						],
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [],
						//��������
						searchitems : [{
									display : '���˼ƻ�����',
									name : 'planName'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '���˼ƻ�',
						//Ĭ�������ֶ���
						sortname : "planName",
						//Ĭ������˳��
						sortorder : "ASC",
						//��ʾ�鿴��ť
						isViewAction : true,
						//������Ӱ�ť
						isAddAction : true,
						//����ɾ����ť
						isDelAction : true,
						toAddConfig : {
										text : '�½����˼ƻ�',
										action : 'toassplanAdd'
									},
						//�޸���չ��Ϣ
						toEditConfig : { action : 'toassplanEdit' },
						//�鿴��չ��Ϣ
						toViewConfig : { action : 'toassplanView' }
					});

		});