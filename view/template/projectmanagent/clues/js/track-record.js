var show_page = function(page) {
	$("#recordGrid").yxgrid("reload");
};
$(function() {
	$("#recordGrid").yxgrid({
		model : 'projectmanagent_track_track',
		title : '跟踪线索记录',
		param : {
//			"trackName" : $('#trackName').val(),
			"cluesId" : $('#cluesId').val()
		},
		showcheckbox : false, // 是否显示checkbox
		/**
		 * 是否显示工具栏
		 *
		 * @type Boolean
		 */
		isToolBar : false,
		/**
		 * 是否显示修改按钮/菜单
		 *
		 * @type Boolean
		 */
		isEditAction : false,
		/**
		 * 是否显示删除按钮/菜单
		 *
		 * @type Boolean
		 */
		isDelAction : false,
		/**
		 * 是否显示添加按钮/菜单
		 *
		 * @type Boolean
		 */
		isAddAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'trackName',
			display : '跟踪人姓名',
			sortable : true
		}, {
			name : 'trackDate',
			display : '跟踪日期',
			sortable : true
		}, {
			name : 'trackType',
			display : '跟踪类型',
			sortable : true,
			datacode : 'GZLX'
		}, {
			name : 'linkmanName',
			display : '联系人姓名',
			sortable : true
		}, {
			name : 'trackPurpose',
			display : '跟踪目的',
			sortable : true,
			width : 150
		}, {
			name : 'customerFocus',
			display : '客户关注点',
			sortable : true,
			width : 150
		}, {
			name : 'result',
			display : '接触结果',
			sortable : true,
			width : 150
		}],

		menusEx : [

          	{
			text : '查看',
			icon : 'view',
			action: function(row){
               parent.location="?model=projectmanagent_track_track&action=init&id="
						+ row.id
                        + '&perm=view'

			}
		   }],
		toAddConfig : {
			action : 'toCluesTrack',
			plusUrl : '&id=' + $('#cluesId').val()
		}
	});
});