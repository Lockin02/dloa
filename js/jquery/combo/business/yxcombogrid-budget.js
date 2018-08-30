/**
 * 预算项目下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_budget', {
		options : {
			hiddenId : 'id',
			nameCol : 'budgetName',
			gridOptions : {
				model : 'engineering_baseinfo_budget',
				// 表单
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '预算编码',
							name : 'budgetCode',
							sortable : true,
							width : 80
						}, {
							display : '预算名称',
							name : 'budgetName',
							sortable : true
						}, {
							display : '上级名称',
							name : 'parentName',
							sortable : true
						}, {
							display : '货币单位',
							name : 'currencyUnit',
							sortable : true,
							width : 50,
				            hide:true
						}, {
							display : '费用类型',
							name : 'budgetType',
							datacode : 'FYLX',
							sortable : true,
							width : 80
						},{
				            name : 'subjectName',
				            display : '科目名称',
				            sortable : true,
				            hide:true
				        },{
				            name : 'subjectCode',
				            display : '科目编码',
				            sortable : true,
				            hide:true
				        } ,{
				            name : 'remark',
				            display : '备注',
				            sortable : true,
							width : 120
				        }],

				/**
				 * 快速搜索
				 */
				searchitems : [{
							display : '预算编码',
							name : 'budgetCode'
						}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "budgetCode",
				title : '预算项目'
			}
		}
	});
})(jQuery);