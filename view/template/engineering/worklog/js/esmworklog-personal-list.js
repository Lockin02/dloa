var show_page = function(page) {
	$(".esmpersonalworklog").yxgrid("reload");
};
$(function() {
			$(".esmpersonalworklog").yxgrid({
						model : 'engineering_worklog_esmworklog',
						action :��'personalWorkLog',
						title : '��Ա��־',
						isToolBar : false,
						showcheckbox : false,
						param : {"userCode" : $("#userCode").val()},

						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'executionDate',
									display : '����',
									width:200,
									sortable : true
								}, {
									name : 'weekDate',
									display : '����',
									sortable : true
								}, {
									name : 'workPlace',
									display : '�ص�',
									sortable : true
								}, {
									name : 'workStatus',
									display : '����״̬',
									sortable : true,
									datacode : 'GZRZZT'
								}, {
									name : 'proName',
									display : '������Ŀ',
									sortable : true
								}, {
									name : 'planEndDate',
									display : 'Ԥ���������',
									sortable : true
								}],
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [],
						//��������
						searchitems : [{
									display : '����',
									name : 'executionDate'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��־',
						//Ĭ�������ֶ���
						sortname : "executionDate",
						//Ĭ������˳��
						sortorder : "ASC",
						//���ز鿴��ť
						isViewAction : false,
						//���ر༭��ť
						isEditAction : false,
						//������Ӱ�ť
						isAddAction : false,
						//����ɾ����ť
						isDelAction : false,
						//���ر༭��ť
						idEditAction : false,
						isRightMenu : false

	})	});
