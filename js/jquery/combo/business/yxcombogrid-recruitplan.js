/**
 * 招聘计划信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_plan', {
		options : {
			hiddenId : 'id',
			nameCol : 'formCode',
			// isFocusoutCheck:false,
			gridOptions : {
				showcheckbox : false,
				model : 'hr_recruitplan_plan',
				action : 'pageJson',
				param : {},
				pageSize : 10,
				// 列信息
				colModel : [ {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'formCode',
					display : '单据编号',
					width:130,
					sortable : true
				},{
					name : 'stateC',
					display : '单据状态',
					sortable : true,
					hide : true
				},{
					name : 'deptName',
					display : '需求部门',
					sortable : true
				},{
					name : 'postTypeName',
					display : '职位类型',
					sortable : true
				},{
					name : 'positionName',
					display : '需求职位',
					sortable : true
				},{
					name : 'developPositionName',
					display : '研发职位',
					sortable : true,
					hide : true
				},{
					name : 'positionLevel',
					display : '级别',
					sortable : true,
					hide : false
				},{
					name : 'needNum',
					display : '需求人数',
					sortable : true,
					hide : false
				},{
					name : 'hopeDate',
					display : '希望到岗时间',
					sortable : true,
					hide : false
				},{
					name : 'workPlace',
					display : '工作地点',
					sortable : true,
					hide : false
				},{
					name : 'applyReason',
					display : '需求原因',
					sortable : true,
					width : 300,
					hide : false
				},{
					name : 'formManName',
					display : '填表人',
					width : 70,
					sortable : true
				},{
					name : 'formDate',
					display : '填表时间',
					width : 70,
					sortable : true
				}],
				// 快速搜索
				searchitems : [{
					display : "需求部门",
					name : 'deptName'
				},{
					display : "需求职位",
					name : 'positionName'
				},{
					display : '填表人',
					name : 'formManName'
				},{
					display : '填表时间',
					name : 'formDate'
				}],

				sortname : "id",
				// 默认搜索顺序
				sortorder : "desc"
			}
		}
	});
})(jQuery);