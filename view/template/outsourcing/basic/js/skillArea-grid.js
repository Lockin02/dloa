var show_page = function(page) {
	$("#skillAreaGrid").yxgrid("reload");};
$(function() {
		$("#skillAreaGrid").yxgrid({
				model : 'outsourcing_basic_skillArea',
               	title : '��������',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'skillarea',
                  					display : '��������',
                  					width:200,
                  					sortable : true
	                              },{
	                    					name : 'remark',
	                  					display : '��ע',
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
					display : "��������",
					name : 'skillarea'
				}]
 		});
 });