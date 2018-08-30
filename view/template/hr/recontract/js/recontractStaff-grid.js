var show_page = function(page) {
	$("#recontractGrid").yxgrid("reload");
};
$(function() {

	$("#recontractGrid")
			.yxgrid(
					{
						model : 'hr_recontract_recontractapproval',
						action:'pageJsonStaffList',
						title : '��ͬ��ǩ',
						isAddAction : false,
						isEditAction : false,
						isViewAction : false,
						isDelAction : false,
						// ����Ϣ
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'userNo',
									display : 'Ա�����',
									width:'50',
									sortable : true,
									hide : true
								},
								{
									name : 'userName',
									display : '����',
									width:'60',
									sortable : true
								},
								{
									name : 'companyName',
									display : '��˾',
									width:'80',
									sortable : true
								},
								{
									name : 'deptName',
									display : '����',
									width:'100',
									sortable : true
								},
								{
									name : 'jobName',
									display : 'ְλ',
									sortable : true
								},
								{
									name : 'comeinDate',
									display : '��ְ����',
									sortable : true
								},{
									name : 'obeginDate',
									display : '�ϴκ�ͬ��ʼʱ��',
									sortable : true
								}, {
									name : 'ocloseDate',
									display : '�ϴκ�ͬ����ʱ��',
									sortable : true
								}, {
									name : 'oconNumName',
									display : '�ϴκ�ͬ�ù�����',
									sortable : true
								}, {
									name : 'oconStateName',
									display : '�ϴκ�ͬ�ù���ʽ',
									sortable : true
								}, {
									name : 'staffFlag',
									display : '״̬',
									sortable : true,
									process : function(v, row) 
								{
									if(v==1)
									{
										return 'δȷ��'
									}else if(v==2)
									{
										return '��ȷ��'
									}
								}
								},{
									name : 'beginDate',
									display : '���κ�ͬ��ʼʱ��',
									sortable : true
								}, {
									name : 'closeDate',
									display : '���κ�ͬ����ʱ��',
									sortable : true
								}, {
									name : 'conNumName',
									display : '���κ�ͬ�ù�����',
									sortable : true
								}, {
									name : 'conStateName',
									display : '���κ�ͬ�ù���ʽ',
									sortable : true
								} ],
						//buttonsEx : buttonsArr,
						
						// ��չ�Ҽ��˵�
						menusEx : [{
							text : '����ȷ��',
							icon : 'add',
							showMenuFn : function(row) {
								if (row.staffFlag !='1') {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) 
							{
								if (row) 
								{
									showThickboxWin("?model=hr_recontract_recontract&action=InformStaff&id=" + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
								} else {
									alert("��ѡ��һ������");
								}
								
							}
						},{
							text : '�鿴��ϸ',
							icon : 'view',
							showMenuFn : function(row) {
								if (row.staffFlag==1) {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) 
								{
									showThickboxWin("?model=hr_recontract_recontract&action=detialInformStaff&id=" + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
								} else {
									alert("��ѡ��һ������");
								}
							}
						}]
						/**
						 * ��������
						
						searchitems : [ {
							display : 'Ա������',
							name : 'userName'
						}, {
							display : 'Ա�����',
							name : 'userNo'
						}, {
							display : '��ͬ���',
							name : 'conNo'
						}, {
							display : '��ͬ����',
							name : 'conName'
						}, {
							display : '��ͬ����',
							name : 'conTypeName'
						}, {
							display : '��ͬ״̬',
							name : 'conStateNames'
						} ],
						 */
						// ����״̬���ݹ���
						/*
						comboEx : [  {
							text : '��ͬ״̬',
							key : 'ExaStatus',
							value : '2',
							data : [ {
								text : 'δ��ǩ',
								value : '2'
							}, {
								text : '����ǩ',
								value : '1'
							} ]
						} ]
*/
					});
});