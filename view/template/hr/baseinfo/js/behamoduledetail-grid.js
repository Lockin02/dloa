var show_page = function(page) {	   $("#behamoduledetailGrid").yxgrid("reload");};
$(function() {			$("#behamoduledetailGrid").yxgrid({				      model : 'hr_baseinfo_behamoduledetail',
               	title : '��ΪҪ�����ñ�',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'detailName',
                  					display : 'Ҫ������',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע˵��',
                  					sortable : true
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_baseinfo_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}]
		},
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "�����ֶ�",
					name : 'XXX'
				}]
 		});
 });