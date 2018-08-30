/**
 * 下拉打印离职证明信息
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_printleave', {
		options : {
			hiddenId : 'id',
			nameCol : 'userName',
			gridOptions : {
				title : '可打印离职证明人员信息',
				showcheckbox : false,
				model : 'hr_leave_leave',
				// 列信息
				colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							},
							{
								name : 'leaveCode',
								display : '单据编号',
								sortable : true
							},{
								name : 'userNo',
								display : '员工编号',
								width:80,
								sortable : true
							}, {
								name : 'userName',
								display : '员工姓名',
								width:60,
								sortable : true
							}
				],
				// 快速搜索
//				searchitems : [{
//						display : '区域名称',
//						name : 'agencyName'
//					}
//				],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);