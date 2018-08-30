/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_signcompany', {
		options : {
			hiddenId : 'signCompanyId',
			nameCol : 'signCompanyName',
			searchName : 'signCompanyNameSearch',
			gridOptions : {
				showcheckbox : false,
				model : 'contract_signcompany_signcompany',
				// 列信息
				colModel : [{
							display : '签约公司',
							name : 'signCompanyName',
							width : 120
						}, {
							display : '公司省份',
							name : 'proName',
							width : 80
						}, {
							display : '联系人',
							name : 'linkman',
							width : 80
						}, {
							display : '联系电话',
							name : 'phone',
							width : 80
						}, {
							display : '地址',
							name : 'address',
							sortable : true,
							width : 150
						}],
				// 快速搜索
				searchitems : [{
					display : '签约公司',
					name : 'signCompanyNameSearch'
				}, {
					display : '联系人',
					name : 'linkmanSearch'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);