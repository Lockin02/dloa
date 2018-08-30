var show_page = function(page) {	   $("#produceinfoGrid").yxgrid("reload");};
$(function() {			$("#produceinfoGrid").yxgrid({				      model : 'report_report_produceinfo',
               	title : '生产能力表明细',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                                                                                    ,{
                    					name : 'proType',
                  					display : '库存情况',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=report_report_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "搜索字段",
					name : 'XXX'
				}]
 		});
 });