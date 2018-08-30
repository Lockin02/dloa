// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assLevelGrid").yxgrid("reload");
};
$(function() {
			$(".assLevelGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_assessment_assLevel',

						// action : 'pageJson',
						title:'等级分值管理',
						showcheckbox : true,	//显示checkbox
						isToolBar : true,		//显示列表上方的工具栏

						//列信息
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '等级',
								name : 'name',
								sortable : true,
								width:400
							},{
								display : '分值',
								name : 'score',
								sortable : true,
								width:400
							}
						],
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [{
							name : 'edit',
							text : '编辑',
							icon : 'edit',
							action : function(row, rows, grid) {
								showThickboxWin("?model=engineering_assessment_assLevel&action=toEdit&id="
										+ row.id
										+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
							}
						}],
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
						isViewAction : false,
						//隐藏添加按钮
						isAddAction : true,
						//隐藏删除按钮
						isDelAction : true,
						isEditAction : false,
						//查看扩展信息
						toViewConfig : { action : 'toRead' }

					});

		});