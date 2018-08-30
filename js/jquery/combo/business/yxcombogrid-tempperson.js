/**
 * 测试卡下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_tempperson', {
		options : {
			hiddenId : 'id',
			nameCol : 'personName',
			gridOptions : {
				model : 'engineering_tempperson_tempperson&action=listJson1',
				// 表单
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '姓名',
						name : 'personName',
						sortable : true,
						width : 80
					},{
			            name : 'province',
			            display : '籍贯(省)',
			            sortable : true
			        }, {
						name : 'city',
						display : '籍贯(市)',
						sortable : true
					}, {
						name : 'phone',
						display : '手机',
						sortable : true
					}
				],
				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : "姓名",
					name : 'personNameSearch'
				},{
					display : "身份证",
					name : 'idCardNoSearch'
				},{
					display : "手机号",
					name : 'phoneSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				title : '临聘人员信息'
			}
		}
	});
})(jQuery);