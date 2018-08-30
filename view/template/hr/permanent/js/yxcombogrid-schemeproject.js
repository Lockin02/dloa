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
						model : 'hr_permanent_standard',
						//列信息
						colModel : [{
		 								display : 'id',
		 								name : 'id',
		 								sortable : true,
		 								hide : true
							        },{
		            					name : 'standardCode',
		              					display : '考核项目编码',
		              					hide : true
		                          },{
		            					name : 'standard',
		              					display : '考核项目',
		              					width:130,
		              					sortable : true
		                          },{
		            					name : 'standardType',
		              					display : '考核项目类型',
		              					width:130,
		              					sortable : true
		                          },{
		                				name : 'Content',
		              					display : '备注'
		                          }],
						// 快速搜索
						searchitems : [{
									display : '考核项目',
									name : 'standard'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);