/*
 * 外包人员下拉表格
 */
(function($){
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsourcPersonnel', {
		options : {
			hiddenId : 'id',
			nameCol : 'userName',
			width : 500,
			gridOptions : {
				showcheckbox : false,
				isFocusoutCheck : false,
				model : 'outsourcing_supplier_personnel',
				//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		 								display : '名字',
		 								name : 'userName',
		 								sortable : true
							        },{
		            					name : 'identityCard',
			          					display : '身份证号码',
			          					width:180,
			          					sortable : true
		                          	},{
		 								display : '联系电话',
		 								name : 'mobile',
		 								sortable : true
							        },{
		 								display : '邮箱',
		 								name : 'email',
		 								sortable : true
							        }],
						// 快速搜索
						searchitems : [{
									display : '单据编号',
									name : 'userName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
			}
		}
	});
})(jQuery);