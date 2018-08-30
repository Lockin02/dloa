// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assplanGrid").yxgrid("reload");
};
$(function() {
			$(".assplanGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_assessment_assplan',

						// action : 'pageJson',
						title:'考核计划',
						showcheckbox : true,	//显示checkbox
						isToolBar : true,		//显示列表上方的工具栏

						//列信息
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '考核计划名称',
								name : 'planName',
								sortable : true
							},{
								display : '起始日期',
								name : 'planStartTime',
								sortable : true
							}, {
								display : '结束日期',
								name : 'planEndTime',
								sortable : true
							},{
								display : '办事处',
								name : 'office',
								sortable : true
							}
						],
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [],
						//快速搜索
						searchitems : [{
									display : '考核计划名称',
									name : 'planName'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '考核计划',
						//默认搜索字段名
						sortname : "planName",
						//默认搜索顺序
						sortorder : "ASC",
						//显示查看按钮
						isViewAction : true,
						//隐藏添加按钮
						isAddAction : true,
						//隐藏删除按钮
						isDelAction : true,
						toAddConfig : {
										text : '新建考核计划',
										action : 'toassplanAdd'
									},
						//修改扩展信息
						toEditConfig : { action : 'toassplanEdit' },
						//查看扩展信息
						toViewConfig : { action : 'toassplanView' }
					});

		});