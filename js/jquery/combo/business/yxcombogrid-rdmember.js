/**
 * 下拉研发项目表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rdmember', {
		options : {
			hiddenId : 'memberId',
			nameCol : 'memberName',
			valueCol : 'memberId',
			gridOptions : {
				showcheckbox : true,
				model : 'rdproject_team_rdmember',
				action : 'pageJsonProject',
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '项目名称',
							name : 'projectName',
							width : 180
						}, {
							display : '项目编号',
							name : 'projectCode',
							width : 130,
							hide : true
						}, {
							display : '成员名称',
							name : 'memberName'
						}, {
							display : '成员名称id',
							name : 'memberId',
							hide : true
						}, {
							display : '描述信息',
							name : 'description',
							width : 150
						}],
				// 快速搜索
				searchitems : [{
							display : '项目名称',
							name : 'projectName'
						}],
				// 默认搜索字段名
				sortname : "memberName",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);