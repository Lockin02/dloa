var show_page = function(page) {	   $("#socialplaceGrid").yxgrid("reload");};
$(function() {
$("#socialplaceGrid").yxgrid({
		model : 'hr_basicinfo_socialplace',
       	title : '�籣�����',
		isOpButton:false,
       	isViewAction:false,
		bodyAlign:'center',
				//����Ϣ
				colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
					        },{
            					name : 'socialCity',
          					display : '�籣����',
          					sortable : true
                      },{
            					name : 'createName',
          					display : '������',
          					sortable : true
                      }],

					toEditConfig : {
						action : 'toEdit'
					},
					toViewConfig : {
						action : 'toView'
					},
					searchitems : [{
								display : "����",
								name : 'socialCity'
							}]
			 		});
 });