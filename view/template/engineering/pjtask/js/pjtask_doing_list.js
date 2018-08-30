// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".pjtaskdoingGrid").yxgrid("reload");
};
$(function() {
			$(".pjtaskdoingGrid").yxgrid({

						model : 'engineering_pjtask_pjtask',
						action : 'doingPageJson',
						title:'项目任务--在执行',
						showcheckbox : true,	//显示checkbox
						isToolBar : false,		//显示列表上方的工具栏

						//列信息
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '任务名称',
								name : 'name',
								sortable : true
							},{
								display : '所属项目',
								name : 'projectName',
								sortable : true
							},{
								display : '优先级',
								name : 'priority',
								sortable : true
							}, {
								display : '状态',
								name : 'status',
								sortable : true
							},{
								display : '完成率',
								name : 'effortRate',
								sortable : true
							},{
								display : '偏差率',
								name : 'warpRate',
								sortable : true
							}, {
								display : '责任人',
								name : 'chargeName',
								sortable : true
							},{
								display : '最近更新时间',
								name : 'updateTime',
								sortable : true,
								width : 150
							}, {
								display : '计划完成时间',
								name : 'planEndDate',
								sortable : true
							},{
								display : '任务类型',
								name : 'score',
								sortable : true
							}
						],
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [],
						//快速搜索
						searchitems : [{
									display : '等级',
									name : 'name'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '等级',
						//默认搜索字段名
						sortname : "name",
						//默认搜索顺序
						sortorder : "ASC",
						//显示查看按钮
						isViewAction : true,
						//隐藏添加按钮
						isAddAction : true,
						//隐藏删除按钮
						isDelAction : true,
						//查看扩展信息
						toViewConfig : { action : 'toRead' },

						//修改扩展信息
						toEditConfig : { action : 'toEdit' }
					});

		});