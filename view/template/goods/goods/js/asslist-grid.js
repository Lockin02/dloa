var show_page = function(page) {	   $("#asslistGrid").yxgrid("reload");};
$(function() {			$("#asslistGrid").yxgrid({				      model : 'goods_goods_asslist',
               	title : '关联出现条件',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'itemNames',
                  					display : '关联项名称s',
                  					sortable : true
                              },{
                    					name : 'itemIds',
                  					display : '关联项ids',
                  					sortable : true
                              }],	   	
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "搜索字段",
					name : 'XXX'
				}[
 		});
 });