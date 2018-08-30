var show_page = function(page) {
	$("#holsGrid").yxgrid("reload");
};
$(function() {
			$("#holsGrid").yxgrid({
						model : 'hr_hols_hols',
						title : '考勤信息',
						param :{
							userNoSearch:$("#UserId").attr("val")
						},
						toViewConfig : {
							action : "toView"
						},
						showcheckbox:false,
						bodyAlign:'center',
						// 列信息
						colModel : [ {
									name : 'userNo',
									display : '员工编号',
									sortable : true,
									width:'70'
								}, {
									name : 'userName',
									display : '员工姓名',
									sortable : true,
									width:'60'
								}, {
									name : 'companyName',
									display : '公司名称',
									sortable : true,
									width:'80'
								}, {
									name : 'deptName',
									display : '直属部门',
									sortable : true,
									width:'80'
								}, {
									name : 'deptNameS',
									display : '二级部门',
									sortable : true,
									width:'80'
								}, {
									name : 'deptNameT',
									display : '三级部门',
									sortable : true,
									width:'80'
								}, {
                                    name : 'deptNameF',
                                    display : '四级部门',
                                    sortable : true,
                                    width:'80'
                                },   {
									name : 'ApplyDT',
									display : '申请时间',
									sortable : true,
									width:'70'
								},{
									name : 'BeginDT',
									display : '开始时间',
									sortable : true,
									width:'70'
								}, {
									name : 'EndDT',
									display : '结束时间',
									sortable : true,
									width:'70'
								}, {
									name : 'DTA',
									display : '天数',
									sortable : true,
									width:'50'
								}, {
									name : 'Type',
									display : '请假类型',
									sortable : true,
									width:'70'
								}, {
									name : 'ExaStatus',
									display : '状态',
									sortable : true,
									width:'70'
								}, {
									name : 'Reason',
									display : '申请理由',
									sortable : true,
									width:'300'
								}],
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						searchitems : [{
									display : "员工编号",
									name : 'userNo'
								},{
									display : "员工姓名",
									name : 'userName'
								},{
									display : "公司名称",
									name : 'companyName'
								},{
									display : "直属部门",
									name : 'deptName'
								},{
									display : "二级部门",
									name : 'deptNameS'
								},{
									display : "三级部门",
									name : 'deptNameT'
								},{
                                    display : "四级部门",
                                    name : 'deptNameF'
                                }]
					});
		});