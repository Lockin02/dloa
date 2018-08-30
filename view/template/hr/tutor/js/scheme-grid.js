var show_page = function(page) {
	$("#schemeGrid").yxgrid("reload");
};
$(function() {
			$("#schemeGrid").yxgrid({
						model : 'hr_tutor_scheme',
						title : '导师考核表',
						isOpButton : false,
						bodyAlign:'center',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'userNo',
									display : '导师员工编号',
									sortable : true
								}, {
									name : 'userAccount',
									display : '导师员工账号',
									sortable : true
								}, {
									name : 'userName',
									display : '导师姓名',
									sortable : true
								}, {
									name : 'jobId',
									display : '导师职位id',
									sortable : true
								}, {
									name : 'jobName',
									display : '导师职位名称',
									sortable : true
								}, {
									name : 'deptId',
									display : '导师部门Id',
									sortable : true
								}, {
									name : 'deptName',
									display : '导师部门名称',
									sortable : true
								}, {
									name : 'studentNo',
									display : '学员员工编号',
									sortable : true
								}, {
									name : 'studentAccount',
									display : '学员员工账号',
									sortable : true
								}, {
									name : 'studentName',
									display : '学员姓名',
									sortable : true
								}, {
									name : 'studentDeptName',
									display : '学员部门名称',
									sortable : true
								}, {
									name : 'tryBeginDate',
									display : '试用开始日期',
									sortable : true
								}, {
									name : 'tryEndDate',
									display : '试用结束日期',
									sortable : true
								}, {
									name : 'superiorName',
									display : '新员工直接上级',
									sortable : true
								}, {
									name : 'superiorId',
									display : '直接上级ID',
									sortable : true
								}, {
									name : 'hrName',
									display : 'HR评分人',
									sortable : true
								}, {
									name : 'hrId',
									display : 'HR评分人ID',
									sortable : true
								}, {
									name : 'assistantId',
									display : '部门助理ID',
									sortable : true
								}, {
									name : 'assistantName',
									display : '部门助理',
									sortable : true
								}, {
									name : 'createId',
									display : '创建人Id',
									sortable : true
								}, {
									name : 'createName',
									display : '创建人名称',
									sortable : true
								}, {
									name : 'createTime',
									display : '创建时间',
									sortable : true
								}, {
									name : 'updateId',
									display : '修改人Id',
									sortable : true
								}, {
									name : 'updateName',
									display : '修改人名称',
									sortable : true
								}, {
									name : 'updateTime',
									display : '修改时间',
									sortable : true
								}, {
									name : 'sysCompanyName',
									display : '系统公司名称',
									sortable : true
								}],
						// 主从表格设置
						subGridOptions : {
							url : '?model=hr_tutor_NULL&action=pageItemJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'XXX',
										display : '从表字段'
									}]
						},

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "搜索字段",
									name : 'XXX'
								}]
					});
		});