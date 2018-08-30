/**
 * 预算项目下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_eperson', {
		options : {
			hiddenId : 'id',
			nameCol : 'personLevel',
			gridOptions : {
				model : 'engineering_baseinfo_eperson',
				param : {'status' : '0'},
				// 表单
				colModel : [{
                    display : 'id',
                    name : 'id',
                    sortable : true,
                    hide : true
                }, {
                    display : '人员等级',
                    name : 'personLevel',
                    sortable : true
                }, {
                    display : '单价自定义',
                    name : 'customPrice',
                    sortable : true,
                    process : function(v){
                        return v == "1" ? "<span class='blue'>是</span>" : "否";
                    }
                }, {
                    name : 'remark',
                    display : '备注',
                    sortable : true,
                    width : 200
                }],
				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '人员等级',
					name : 'personLevel'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "orderNum asc,id",
				title : '人力预算'
			}
		}
	});
})(jQuery);