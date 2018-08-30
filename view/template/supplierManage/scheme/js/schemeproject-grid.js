var show_page = function(page) {
$("#schemeprojectGrid").yxgrid("reload");};
$(function() {
			$("#schemeprojectGrid").yxgrid({
				model : 'supplierManage_scheme_schemeproject',
               	title : '评估项目',
				//列信息
				colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
					        },{
            					name : 'assesProCode',
	          					display : '评估项目编码',
	          					sortable : true
                          },{
            					name : 'assesProName',
              					display : '评估项目名称',
              					width:130,
              					sortable : true
                          },{
            					name : 'assesProProportion',
              					display : '评估项目权重',
              					sortable : true,
              					hide:true
                          },{
                					name : 'formManName',
              					display : '创建人',
              					sortable : true
                          },{
                				name : 'remark',
              					display : '备注',
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
							display : "评估项目名称",
							name : 'assesProName'
						}
		 		});
 });