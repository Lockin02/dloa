// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assLevelGrid").yxgrid("reload");
};
$(function() {
			$(".assLevelGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_assessment_assLevel',

						// action : 'pageJson',
						title:'�ȼ���ֵ����',
						showcheckbox : true,	//��ʾcheckbox
						isToolBar : true,		//��ʾ�б��Ϸ��Ĺ�����

						//����Ϣ
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '�ȼ�',
								name : 'name',
								sortable : true,
								width:400
							},{
								display : '��ֵ',
								name : 'score',
								sortable : true,
								width:400
							}
						],
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [{
							name : 'edit',
							text : '�༭',
							icon : 'edit',
							action : function(row, rows, grid) {
								showThickboxWin("?model=engineering_assessment_assLevel&action=toEdit&id="
										+ row.id
										+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
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
						isAddAction : true,
						//����ɾ����ť
						isDelAction : true,
						isEditAction : false,
						//�鿴��չ��Ϣ
						toViewConfig : { action : 'toRead' }

					});

		});