// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assPeopleConfigGrid").yxgrid("reload");
};
$(function() {
			$(".assPeopleConfigGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_assessment_assPeopleConfig',
						action : 'asspageJson',
						title:'���˵ȼ�����',
						showcheckbox : true,	//��ʾcheckbox
						isToolBar : false,		//��ʾ�б��Ϸ��Ĺ�����

						//����Ϣ
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : 'levelId',
								name : 'levelId',
								sortable : true,
								hide : true
							}, {
								display : '����',
								name : 'name',
								sortable : true
							},{
								display : 'Ȩ��',
								name : 'weight',
								sortable : true
							}
						],
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [],
						//��������
						searchitems : [{
									display : '����',
									name : 'name'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '����',
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