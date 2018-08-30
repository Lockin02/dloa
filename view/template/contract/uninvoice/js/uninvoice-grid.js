var show_page = function(page) {	   $("#uninvoiceGrid").yxgrid("reload");};
$(function() {			$("#uninvoiceGrid").yxgrid({				      model : 'contract_uninvoice_uninvoice',
               	title : '合同不开票金额',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'objCode',
                  					display : '源单编号',
                  					sortable : true
                              },{
                    					name : 'objType',
                  					display : '源单类型',
                  					sortable : true
                              },{
                    					name : 'isRed',
                  					display : '是否红字',
                  					sortable : true
                              },{
                    					name : 'money',
                  					display : '金额',
                  					sortable : true
                              },{
                    					name : 'descript',
                  					display : '描述',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '录入人',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '录入人id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '录入时间',
                  					sortable : true
                              }],	   	
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
					display : "搜索字段",
					name : 'XXX'
				}
 		});
 });