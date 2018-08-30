/**
 * 下拉评估项目表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_schemeproject', {
				options : {
					hiddenId : 'id',
					nameCol : 'assesProName',
					gridOptions : {
						showcheckbox : true,
						model : 'supplierManage_scheme_schemeproject',
						//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'assesProCode',
			          					display : '评估项目编码',
			          					sortable : true
		                          },{
		            					name : 'assesProName',
		              					display : '评估项目名称',
		              					width:130,
		              					sortable : true
		                          },{
		            					name : 'assesProProportion',
		              					display : '评估项目权重',
		              					sortable : true,
		              					hide:true
		                          },{
		                					name : 'formManName',
		              					display : '创建人',
		              					hide : true
		                          }],
						// 快速搜索
						searchitems : [{
									display : '评估项目名称',
									name : 'assesProName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);