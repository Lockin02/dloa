var show_page = function(page) {
	$("#skillAreaGrid").yxgrid("reload");};
$(function() {
		$("#skillAreaGrid").yxgrid({
				model : 'outsourcing_basic_skillArea',
               	title : '技能领域',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'skillarea',
                  					display : '技能领域',
                  					width:200,
                  					sortable : true
	                              },{
	                    					name : 'remark',
	                  					display : '备注',
	                  					width:400,
	                  					sortable : true
	                              }],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "技能领域",
					name : 'skillarea'
				}]
 		});
 });