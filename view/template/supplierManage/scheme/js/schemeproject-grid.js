var show_page = function(page) {
$("#schemeprojectGrid").yxgrid("reload");};
$(function() {
			$("#schemeprojectGrid").yxgrid({
				model : 'supplierManage_scheme_schemeproject',
               	title : '������Ŀ',
				//����Ϣ
				colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
					        },{
            					name : 'assesProCode',
	          					display : '������Ŀ����',
	          					sortable : true
                          },{
            					name : 'assesProName',
              					display : '������Ŀ����',
              					width:130,
              					sortable : true
                          },{
            					name : 'assesProProportion',
              					display : '������ĿȨ��',
              					sortable : true,
              					hide:true
                          },{
                					name : 'formManName',
              					display : '������',
              					sortable : true
                          },{
                				name : 'remark',
              					display : '��ע',
              					width:200,
              					sortable : true
                          }],

				toEditConfig : {
					action : 'toEdit'
				},
				toViewConfig : {
					action : 'toView'
				},
				searchitems : {
							display : "������Ŀ����",
							name : 'assesProName'
						}
		 		});
 });