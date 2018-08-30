var show_page = function(page) {
	$(".esmpersonalworklog").yxgrid("reload");
};
$(function() {
			$(".esmpersonalworklog").yxgrid({
						model : 'engineering_worklog_esmworklog',
						action :　'personalWorkLog',
						title : '人员日志',
						isToolBar : false,
						showcheckbox : false,
						param : {"userCode" : $("#userCode").val()},

						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'executionDate',
									display : '日期',
									width:200,
									sortable : true
								}, {
									name : 'weekDate',
									display : '星期',
									sortable : true
								}, {
									name : 'workPlace',
									display : '地点',
									sortable : true
								}, {
									name : 'workStatus',
									display : '工作状态',
									sortable : true,
									datacode : 'GZRZZT'
								}, {
									name : 'proName',
									display : '所属项目',
									sortable : true
								}, {
									name : 'planEndDate',
									display : '预计完成日期',
									sortable : true
								}],
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [],
						//快速搜索
						searchitems : [{
									display : '日期',
									name : 'executionDate'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '日志',
						//默认搜索字段名
						sortname : "executionDate",
						//默认搜索顺序
						sortorder : "ASC",
						//隐藏查看按钮
						isViewAction : false,
						//隐藏编辑按钮
						isEditAction : false,
						//隐藏添加按钮
						isAddAction : false,
						//隐藏删除按钮
						isDelAction : false,
						//隐藏编辑按钮
						idEditAction : false,
						isRightMenu : false

	})	});
