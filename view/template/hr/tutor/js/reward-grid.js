var show_page = function(page) {
	$("#rewardGrid").yxgrid("reload");
};
$(function() {
			$("#rewardGrid").yxgrid({
						model : 'hr_tutor_reward',
						title : '导师奖励管理',
						isOpButton : false,
						bodyAlign:'center',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'code',
									display : '编号',
									sortable : true
								}, {
									name : 'name',
									display : '名称',
									sortable : true
								}, {
									name : 'dept',
									display : '部门名称',
									sortable : true
								}, {
									name : 'deptId',
									display : '部门id',
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '审核状态',
									sortable : true
								}, {
									name : 'ExaDT',
									display : '审核日期',
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