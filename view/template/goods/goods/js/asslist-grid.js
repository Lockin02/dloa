var show_page = function(page) {	   $("#asslistGrid").yxgrid("reload");};
$(function() {			$("#asslistGrid").yxgrid({				      model : 'goods_goods_asslist',
               	title : '������������',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'itemNames',
                  					display : '����������s',
                  					sortable : true
                              },{
                    					name : 'itemIds',
                  					display : '������ids',
                  					sortable : true
                              }],	   	
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "�����ֶ�",
					name : 'XXX'
				}[
 		});
 });