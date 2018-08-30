/**
 * 下拉开户银行
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_suppAccount', {
				options : {
					//hiddenId : 'suppId',
					nameCol : 'accountNum',
					gridOptions : {
						model : 'supplierManage_formal_bankinfo',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true
								}
								,{
									display : '供应商名称',
									name : 'suppName',
									width:150
								}
								,{
									display : '银行帐号',
									name : 'accountNum',
									width:150
								}
								,{
									display : '开户银行',
									name : 'bankName',
									width:150
								}
								,{
									display : '供应商ID',
									name : 'suppId',
									hide : true,
									width:100
								}
								],
						// 快速搜索
						searchitems : [{
									display : '供应商名称',
									name : 'suppName'
								},
								{
									display : '开户银行',
									name : 'depositbank'
								},
								{
									display : '银行帐号',
									name : 'accountNum'
								}
								],
						// 默认搜索字段名
						sortname : "depositbank",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);