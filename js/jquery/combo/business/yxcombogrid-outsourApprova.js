/*
 * 外包立项下拉表格
 */
(function($){
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsourApprova', {
		options : {
			hiddenId : 'id',
			nameCol : 'formCode',
			width : 500,
			gridOptions : {
				showcheckbox : false,
				isFocusoutCheck : false,
				model : 'outsourcing_approval_basic',
				action : 'pullPage',
				param:{ExaStatusArr:'完成'},
				//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'formCode',
			          					display : '单据编号',
			          					width:180,
			          					sortable : true
		                          }],
						// 快速搜索
						searchitems : [{
									display : '单据编号',
									name : 'formCode'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
			}
		}
	});
})(jQuery);