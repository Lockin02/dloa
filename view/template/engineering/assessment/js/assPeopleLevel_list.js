// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".peopleLevelGrid").yxgrid("reload");
};
$(function() {
			$(".peopleLevelGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_assessment_assPeopleLevel',

						// action : 'pageJson',
						title:'人员等级指标配置',
						showcheckbox : true,	//显示checkbox
						isToolBar : true,		//显示列表上方的工具栏

						//列信息
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '等级名称',
								name : 'levelName',
								sortable : true
							},{
								display : '创建人',
								name : 'createName',
								sortable : true
							},{
								display : '创建人Id',
								name : 'createId',
								sortable : true,
								hide : true
							},{
								display : '创建时间',
								name : 'createTime',
								sortable : true,
								width :200
							}, {
								display : '指标审核人',
								name : 'auditName',
								sortable : true
							}, {
								display : '指标审核人Id',
								name : 'auditId',
								sortable : true,
								hide : true
							}, {
								display : '系数',
								name : 'ratio',
								sortable : true
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
										showThickboxWin("?model=engineering_assessment_assPeopleLevel&action=toEdit&id="
												+ row.id
												+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
									}
								},{
									name : 'detail',
									text : '详细',
									icon : 'edit',
									action : function(row,rows,grid) {
											showThickboxWin("?model=engineering_assessment_assPeopleConfig&action=toassTree&id="+row.id+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
									}
								},{
									name : 'preview',
									text : '预览',
									icon : 'edit',
									action : function(row,rows,grid) {
											showThickboxWin("?model=engineering_assessment_assPeopleConfig&action=toassBrowse&id="+row.id+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
									}
								}],
						//快速搜索
						searchitems : [{
									display : '等级名称',
									name : 'levelName'
								},{
									display : '创建人',
									name : 'createName'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '等级名称',
						//默认搜索字段名
						sortname : "levelName",
						//默认搜索顺序
						sortorder : "ASC",
						//显示查看按钮
						isViewAction : false,
						//隐藏添加按钮
						isAddAction : true,
						//隐藏删除按钮
						isDelAction : true,
						isEditAction : false
					});

		});