var show_page = function(page) {
	$("#holsGrid").yxgrid("reload");
};
$(function() {
			$("#holsGrid").yxgrid({
						model : 'hr_hols_hols',
						title : '������Ϣ',
						param :{
							userNoSearch:$("#UserId").attr("val")
						},
						toViewConfig : {
							action : "toView"
						},
						showcheckbox:false,
						bodyAlign:'center',
						// ����Ϣ
						colModel : [ {
									name : 'userNo',
									display : 'Ա�����',
									sortable : true,
									width:'70'
								}, {
									name : 'userName',
									display : 'Ա������',
									sortable : true,
									width:'60'
								}, {
									name : 'companyName',
									display : '��˾����',
									sortable : true,
									width:'80'
								}, {
									name : 'deptName',
									display : 'ֱ������',
									sortable : true,
									width:'80'
								}, {
									name : 'deptNameS',
									display : '��������',
									sortable : true,
									width:'80'
								}, {
									name : 'deptNameT',
									display : '��������',
									sortable : true,
									width:'80'
								}, {
                                    name : 'deptNameF',
                                    display : '�ļ�����',
                                    sortable : true,
                                    width:'80'
                                },   {
									name : 'ApplyDT',
									display : '����ʱ��',
									sortable : true,
									width:'70'
								},{
									name : 'BeginDT',
									display : '��ʼʱ��',
									sortable : true,
									width:'70'
								}, {
									name : 'EndDT',
									display : '����ʱ��',
									sortable : true,
									width:'70'
								}, {
									name : 'DTA',
									display : '����',
									sortable : true,
									width:'50'
								}, {
									name : 'Type',
									display : '�������',
									sortable : true,
									width:'70'
								}, {
									name : 'ExaStatus',
									display : '״̬',
									sortable : true,
									width:'70'
								}, {
									name : 'Reason',
									display : '��������',
									sortable : true,
									width:'300'
								}],
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						searchitems : [{
									display : "Ա�����",
									name : 'userNo'
								},{
									display : "Ա������",
									name : 'userName'
								},{
									display : "��˾����",
									name : 'companyName'
								},{
									display : "ֱ������",
									name : 'deptName'
								},{
									display : "��������",
									name : 'deptNameS'
								},{
									display : "��������",
									name : 'deptNameT'
								},{
                                    display : "�ļ�����",
                                    name : 'deptNameF'
                                }]
					});
		});