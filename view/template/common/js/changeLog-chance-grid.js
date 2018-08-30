(function($) {
	$.woo.yxsubgrid.subclass('woo.yxgrid_changeLog', {
				options : {
					model : 'common_changeLog',
					showcheckbox : false,
					isToolBar : false,
					/**
					 * 是否显示右键菜单，如果为flase，则右键菜单失效
					 *
					 * @type Boolean
					 */
					isRightMenu : false,
					// 表单
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '更新人',
								name : 'changeManName',
								width : 400,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : '更新时间',
								name : 'changeTime',
								width : 400,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}],
					subGridOptions : {
						url : '?model=common_changeLog&action=pageJsonDetail',// 获取从表数据url
						// 显示的列
						colModel : [{
									name : 'detailTypeCn',
									width:'80',
									display : '对象类型'
								},
								/**
								 * { name : 'changeField', width : 150, display :
								 * '变更字段' },
								 */
								{
									name : 'detailId',
									width : 30,
									display : '标志',
									process : function(v) {
										if (v != 0) {
											return v;
										}
										return "";
									}
								}, {
									name : 'objField',
									width : 150,
									display : '对象名称'
								}, {
									name : 'changeFieldCn',
									width : 150,
									display : '更新属性'
								}, {
									name : 'oldValue',
									width : 150,
									display : '更新前值'
								}, {
									name : 'newValue',
									width : 150,
									display : '更新后值'
								}]
					},
					sortorder : "DESC",
					sortname : "id",
					title : '更新信息'
				}
			});
})(jQuery);