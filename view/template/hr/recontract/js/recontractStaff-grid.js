var show_page = function(page) {
	$("#recontractGrid").yxgrid("reload");
};
$(function() {

	$("#recontractGrid")
			.yxgrid(
					{
						model : 'hr_recontract_recontractapproval',
						action:'pageJsonStaffList',
						title : '合同续签',
						isAddAction : false,
						isEditAction : false,
						isViewAction : false,
						isDelAction : false,
						// 列信息
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'userNo',
									display : '员工编号',
									width:'50',
									sortable : true,
									hide : true
								},
								{
									name : 'userName',
									display : '姓名',
									width:'60',
									sortable : true
								},
								{
									name : 'companyName',
									display : '公司',
									width:'80',
									sortable : true
								},
								{
									name : 'deptName',
									display : '部门',
									width:'100',
									sortable : true
								},
								{
									name : 'jobName',
									display : '职位',
									sortable : true
								},
								{
									name : 'comeinDate',
									display : '入职日期',
									sortable : true
								},{
									name : 'obeginDate',
									display : '上次合同开始时间',
									sortable : true
								}, {
									name : 'ocloseDate',
									display : '上次合同结束时间',
									sortable : true
								}, {
									name : 'oconNumName',
									display : '上次合同用工年限',
									sortable : true
								}, {
									name : 'oconStateName',
									display : '上次合同用工方式',
									sortable : true
								}, {
									name : 'staffFlag',
									display : '状态',
									sortable : true,
									process : function(v, row) 
								{
									if(v==1)
									{
										return '未确认'
									}else if(v==2)
									{
										return '已确认'
									}
								}
								},{
									name : 'beginDate',
									display : '本次合同开始时间',
									sortable : true
								}, {
									name : 'closeDate',
									display : '本次合同结束时间',
									sortable : true
								}, {
									name : 'conNumName',
									display : '本次合同用工年限',
									sortable : true
								}, {
									name : 'conStateName',
									display : '本次合同用工方式',
									sortable : true
								} ],
						//buttonsEx : buttonsArr,
						
						// 扩展右键菜单
						menusEx : [{
							text : '进行确认',
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
									alert("请选中一条数据");
								}
								
							}
						},{
							text : '查看明细',
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
									alert("请选中一条数据");
								}
							}
						}]
						/**
						 * 快速搜索
						
						searchitems : [ {
							display : '员工姓名',
							name : 'userName'
						}, {
							display : '员工编号',
							name : 'userNo'
						}, {
							display : '合同编号',
							name : 'conNo'
						}, {
							display : '合同名称',
							name : 'conName'
						}, {
							display : '合同类型',
							name : 'conTypeName'
						}, {
							display : '合同状态',
							name : 'conStateNames'
						} ],
						 */
						// 审批状态数据过滤
						/*
						comboEx : [  {
							text : '合同状态',
							key : 'ExaStatus',
							value : '2',
							data : [ {
								text : '未续签',
								value : '2'
							}, {
								text : '已续签',
								value : '1'
							} ]
						} ]
*/
					});
});