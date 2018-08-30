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
								display : '签收人',
								name : 'changeManName',
								width : 380,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : '签收时间',
								name : 'changeTime',
								width : 380,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : 'tempId',
								name : 'tempId',
								hide : true
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
									display : '变更属性'
								}, {
									name : 'oldValue',
									width : 150,
									display : '变更前值'
								}, {
									name : 'newValue',
									width : 150,
									display : '变更后值'
								}]
					},
					sortorder : "DESC",
					sortname : "id",
					title : '签收信息'
				}
			});
})(jQuery);