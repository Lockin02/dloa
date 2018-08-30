var show_page = function(page) {
	$("#countryGrid").yxgrid("reload");
};
$(function() {
			$("#countryGrid").yxgrid({
				      model : 'system_procity_country',
               	title : '国家信息',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'countryCode',
                  					display : '国家编码',
                  					sortable : true
                              },{
                    					name : 'countryName',
                  					display : '国家名称',
                  					sortable : true,
                  					width:200
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }],
			toAddConfig : {
				formWidth : 700,
				/**
				 * 新增表单默认高度
				 */
				formHeight : 300
			},
			toViewConfig : {
				/**
				 * 查看表单默认宽度
				 */
				formWidth : 700,
				/**
				 * 查看表单默认高度
				 */
				formHeight : 300
			},
			toEditConfig : {
				action : 'toEdit'
			},

				/**
				 * 快速搜索
				 */
				searchitems : [{
							display : '国家编码',
							name : 'countryCode'
						},{
							display : '国家名称',
							name : 'countryName'
						}]
 		});
 });