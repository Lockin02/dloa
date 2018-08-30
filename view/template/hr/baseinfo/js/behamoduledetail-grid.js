var show_page = function(page) {	   $("#behamoduledetailGrid").yxgrid("reload");};
$(function() {			$("#behamoduledetailGrid").yxgrid({				      model : 'hr_baseinfo_behamoduledetail',
               	title : '行为要项配置表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'detailName',
                  					display : '要项名称',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注说明',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_baseinfo_NULL&action=pageItemJson',
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