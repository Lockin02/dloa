// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assPeopleConfigGrid").yxgrid("reload");
};
$(function() {
			$(".assPeopleConfigGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_assessment_assPeopleConfig',
						action : 'asspageJson',
						title:'考核等级配置',
						showcheckbox : true,	//显示checkbox
						isToolBar : false,		//显示列表上方的工具栏

						//列信息
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : 'levelId',
								name : 'levelId',
								sortable : true,
								hide : true
							}, {
								display : '名称',
								name : 'name',
								sortable : true
							},{
								display : '权重',
								name : 'weight',
								sortable : true
							}
						],
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [],
						//快速搜索
						searchitems : [{
									display : '名称',
									name : 'name'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '名称',
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