var show_page = function(page) {
	$("#schemeGrid").yxgrid("reload");
};
$(function() {
			$("#schemeGrid").yxgrid({
						model : 'hr_tutor_scheme',
						title : '��ʦ���˱�',
						isOpButton : false,
						bodyAlign:'center',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'userNo',
									display : '��ʦԱ�����',
									sortable : true
								}, {
									name : 'userAccount',
									display : '��ʦԱ���˺�',
									sortable : true
								}, {
									name : 'userName',
									display : '��ʦ����',
									sortable : true
								}, {
									name : 'jobId',
									display : '��ʦְλid',
									sortable : true
								}, {
									name : 'jobName',
									display : '��ʦְλ����',
									sortable : true
								}, {
									name : 'deptId',
									display : '��ʦ����Id',
									sortable : true
								}, {
									name : 'deptName',
									display : '��ʦ��������',
									sortable : true
								}, {
									name : 'studentNo',
									display : 'ѧԱԱ�����',
									sortable : true
								}, {
									name : 'studentAccount',
									display : 'ѧԱԱ���˺�',
									sortable : true
								}, {
									name : 'studentName',
									display : 'ѧԱ����',
									sortable : true
								}, {
									name : 'studentDeptName',
									display : 'ѧԱ��������',
									sortable : true
								}, {
									name : 'tryBeginDate',
									display : '���ÿ�ʼ����',
									sortable : true
								}, {
									name : 'tryEndDate',
									display : '���ý�������',
									sortable : true
								}, {
									name : 'superiorName',
									display : '��Ա��ֱ���ϼ�',
									sortable : true
								}, {
									name : 'superiorId',
									display : 'ֱ���ϼ�ID',
									sortable : true
								}, {
									name : 'hrName',
									display : 'HR������',
									sortable : true
								}, {
									name : 'hrId',
									display : 'HR������ID',
									sortable : true
								}, {
									name : 'assistantId',
									display : '��������ID',
									sortable : true
								}, {
									name : 'assistantName',
									display : '��������',
									sortable : true
								}, {
									name : 'createId',
									display : '������Id',
									sortable : true
								}, {
									name : 'createName',
									display : '����������',
									sortable : true
								}, {
									name : 'createTime',
									display : '����ʱ��',
									sortable : true
								}, {
									name : 'updateId',
									display : '�޸���Id',
									sortable : true
								}, {
									name : 'updateName',
									display : '�޸�������',
									sortable : true
								}, {
									name : 'updateTime',
									display : '�޸�ʱ��',
									sortable : true
								}, {
									name : 'sysCompanyName',
									display : 'ϵͳ��˾����',
									sortable : true
								}],
						// ���ӱ������
						subGridOptions : {
							url : '?model=hr_tutor_NULL&action=pageItemJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'XXX',
										display : '�ӱ��ֶ�'
									}]
						},

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "�����ֶ�",
									name : 'XXX'
								}]
					});
		});