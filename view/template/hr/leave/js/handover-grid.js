var show_page = function(page) {
	$("#handoverGrid").yxgrid("reload");
};
$(function() {
			$("#handoverGrid").yxgrid({
						model : 'hr_leave_handover',
						title : '离职交接清单',
						isOpButton : false,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'userNo',
									display : '员工编号',
									sortable : true
								}, {
									name : 'userAccount',
									display : '员工账号',
									sortable : true
								}, {
									name : 'userName',
									display : '员工姓名',
									sortable : true
								}, {
									name : 'deptName',
									display : '部门名称',
									sortable : true
								}, {
									name : 'deptId',
									display : '部门Id',
									sortable : true
								}, {
									name : 'jobName',
									display : '职位名称',
									sortable : true
								}, {
									name : 'entryDate',
									display : '入职日期',
									sortable : true
								}, {
									name : 'quitDate',
									display : '离职日期',
									sortable : true
								}, {
									name : 'quitReson',
									display : '离职原因',
									sortable : true
								}, {
									name : 'quitTypeCode',
									display : '离职类型',
									sortable : true
								}, {
									name : 'quitTypeName',
									display : '离职类型名称',
									sortable : true
								}, {
									name : 'createName',
									display : '创建人',
									sortable : true
								}, {
									name : 'createId',
									display : '创建人ID',
									sortable : true
								}, {
									name : 'createTime',
									display : '创建时间',
									sortable : true
								}, {
									name : 'updateName',
									display : '修改人',
									sortable : true
								}, {
									name : 'updateId',
									display : '修改人ID',
									sortable : true
								}, {
									name : 'updateTime',
									display : '修改时间',
									sortable : true
								}, {
									name : 'Column_21',
									display : 'Column_21',
									sortable : true
								}],
						// 主从表格设置
						subGridOptions : {
							url : '?model=hr_leave_NULL&action=pageItemJson',
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