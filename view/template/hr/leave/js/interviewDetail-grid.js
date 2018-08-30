var show_page = function(page) {
	$("#interviewDetailGrid").yxgrid("reload");
};
$(function() {
			$("#interviewDetailGrid").yxgrid({
						model : 'hr_leave_interviewDetail',
						title : '离职--面谈记录表详细',
						isOpButton : false,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'interviewer',
									display : '面谈者',
									sortable : true
								}, {
									name : 'interviewerId',
									display : '面谈者ID',
									sortable : true
								}, {
									name : 'interviewContent',
									display : '面谈内容',
									sortable : true
								}, {
									name : 'interviewDate',
									display : '面谈日期',
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