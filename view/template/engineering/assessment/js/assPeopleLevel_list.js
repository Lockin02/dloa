// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".peopleLevelGrid").yxgrid("reload");
};
$(function() {
			$(".peopleLevelGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_assessment_assPeopleLevel',

						// action : 'pageJson',
						title:'��Ա�ȼ�ָ������',
						showcheckbox : true,	//��ʾcheckbox
						isToolBar : true,		//��ʾ�б��Ϸ��Ĺ�����

						//����Ϣ
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '�ȼ�����',
								name : 'levelName',
								sortable : true
							},{
								display : '������',
								name : 'createName',
								sortable : true
							},{
								display : '������Id',
								name : 'createId',
								sortable : true,
								hide : true
							},{
								display : '����ʱ��',
								name : 'createTime',
								sortable : true,
								width :200
							}, {
								display : 'ָ�������',
								name : 'auditName',
								sortable : true
							}, {
								display : 'ָ�������Id',
								name : 'auditId',
								sortable : true,
								hide : true
							}, {
								display : 'ϵ��',
								name : 'ratio',
								sortable : true
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
										showThickboxWin("?model=engineering_assessment_assPeopleLevel&action=toEdit&id="
												+ row.id
												+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
									}
								},{
									name : 'detail',
									text : '��ϸ',
									icon : 'edit',
									action : function(row,rows,grid) {
											showThickboxWin("?model=engineering_assessment_assPeopleConfig&action=toassTree&id="+row.id+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
									}
								},{
									name : 'preview',
									text : 'Ԥ��',
									icon : 'edit',
									action : function(row,rows,grid) {
											showThickboxWin("?model=engineering_assessment_assPeopleConfig&action=toassBrowse&id="+row.id+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
									}
								}],
						//��������
						searchitems : [{
									display : '�ȼ�����',
									name : 'levelName'
								},{
									display : '������',
									name : 'createName'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '�ȼ�����',
						//Ĭ�������ֶ���
						sortname : "levelName",
						//Ĭ������˳��
						sortorder : "ASC",
						//��ʾ�鿴��ť
						isViewAction : false,
						//������Ӱ�ť
						isAddAction : true,
						//����ɾ����ť
						isDelAction : true,
						isEditAction : false
					});

		});