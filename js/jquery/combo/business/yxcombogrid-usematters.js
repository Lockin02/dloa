/**
 * 下拉使用事项表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_usematters', {
		options : {
			hiddenId : 'id',
			nameCol : 'matterName',
			searchName : 'matterNameSearch',
			width : 550,
			gridOptions : {
				showcheckbox : false,
				model : 'system_stamp_stampmatter',
				action : 'jsonSelect',
				param : { 'status' : 1 },
				// 列信息
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						name : 'matterName',
						display : '事项名称',
						sortable : true,
						width : 180
					},{
						name : 'stamp_cId',
						display : '盖章类别Id',
						sortable : true,
						hide : true
					},{
						name : 'stamp_cName',
						display : '盖章类别',
						sortable : true,
						hide : true
					},{
						name : 'directions',
						display : '特别事项',
						sortable : true,
						width : 180
					}, {
						name : 'needAudit',
						display : '是否需要审批',
						sortable : true,
						width : 80,
						process : function(v){
							if(v == 1){
								return '是';
							}
							else{
								return '否';
							}
						}
					}
				],
				// 快速搜索
				searchitems : [{
						display : '事项名称',
						name : 'matterNameSearch'
					}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
					}
				}
			});
})(jQuery);