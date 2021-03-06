var show_page = function(page) {
	$("#weeklogGrid").yxgrid("reload");
};

$(function() {
	$("#weeklogGrid")
			.yxgrid(
					{
						model : 'produce_log_weeklog',
						title : '我的工作周报',
						showcheckbox : false,
						// 列信息
						param : {
							createId : $('#userid')[0].value
						},
						colModel : [
								{
									display : '序号',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'weekTitle',
									display : '名称',
									sortable : true,
									process : function(v, row) {
										return "<a href='?model=produce_log_worklog&action=page&id="
												+ row.id
												+ "'>"
												+ v
												+ "( "
												+ row.weekBeginDate
												+ "~"
												+ row.weekEndDate + " )</>";
									},
									width : 350

								}, {
									name : 'createName',
									display : '人员名称',
									sortable : true
								}, {
									name : 'weekBeginDate',
									display : '开始时间',
									sortable : true
								}, {
									name : 'weekEndDate',
									display : '结束时间',
									sortable : true
								}, {
									name : 'updateTime',
									display : '最近更新时间',
									sortable : true,
									width : 250
								} ],
						menusEx : [ {
							text : '打开周报',
							icon : 'view',
							action : function(row) {
								showThickboxWin("?model=produce_log_weeklog&action=toOpen&id="
										+ row.id
										+ "&skey="
										+ row['skey_']
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
							}
						} ],
						// 主从表格设置
						subGridOptions : {
							url : '?model=produce_log_NULL&action=pageItemJson',
							param : [ {
								paramId : 'weekId',
								colId : 'id'
							} ],
							colModel : [ {
								name : 'XXX',
								display : '从表字段'
							} ]
						},
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						isViewAction : false,
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [ {
							display : "名称",
							name : 'weekTitle'
						}, {
							display : "创建日期",
							name : 'createTime'
						} ]
					});
});