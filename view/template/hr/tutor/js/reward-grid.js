var show_page = function(page) {
	$("#rewardGrid").yxgrid("reload");
};
$(function() {
			$("#rewardGrid").yxgrid({
						model : 'hr_tutor_reward',
						title : '��ʦ��������',
						isOpButton : false,
						bodyAlign:'center',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'code',
									display : '���',
									sortable : true
								}, {
									name : 'name',
									display : '����',
									sortable : true
								}, {
									name : 'dept',
									display : '��������',
									sortable : true
								}, {
									name : 'deptId',
									display : '����id',
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '���״̬',
									sortable : true
								}, {
									name : 'ExaDT',
									display : '�������',
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