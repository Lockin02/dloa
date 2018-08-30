/**
 * 下拉内部推荐组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_recommend', {
		options : {
			hiddenId : 'recommendId',
			nameCol : 'formCode',
			width : 500,
			isFocusoutCheck : true,
			gridOptions : {
				showcheckbox : false,
				model : 'hr_recruitment_recommend',
				action : 'pageJsonSelect',
				param : {
					noInterviewId : true,
					state : 2
				},
				//列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'formCode',
					display : '单据编号',
					width : 120,
					sortable : true,
					process : function(v, row) {
						return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showOpenWin(\'?model=hr_recruitment_recommend&action=toView&id='
							+ row.id
							+ '\',1)">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
					}
				},{
					name : 'isRecommendName',
					display : '被荐人',
					width : 90,
					sortable : true
				},{
					name : 'positionName',
					display : '推荐职位',
					width : 100,
					sortable : true
				},{
					name : 'recommendName',
					display : '推荐人',
					width : 90,
					sortable : true
				}],

				// 快速搜索
				searchitems : [{
					display : '被荐人',
					name : 'isRecommendName'
				},{
					display : '推荐人',
					name : 'recommendName'
				}],

				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);