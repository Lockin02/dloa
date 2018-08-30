var show_page = function(page) {	   $("#socialplaceGrid").yxgrid("reload");};
$(function() {
$("#socialplaceGrid").yxgrid({
		model : 'hr_basicinfo_socialplace',
       	title : '社保购买地',
		isOpButton:false,
       	isViewAction:false,
		bodyAlign:'center',
				//列信息
				colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
					        },{
            					name : 'socialCity',
          					display : '社保城市',
          					sortable : true
                      },{
            					name : 'createName',
          					display : '创建人',
          					sortable : true
                      }],

					toEditConfig : {
						action : 'toEdit'
					},
					toViewConfig : {
						action : 'toView'
					},
					searchitems : [{
								display : "城市",
								name : 'socialCity'
							}]
			 		});
 });