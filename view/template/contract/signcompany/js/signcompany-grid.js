var show_page = function(page) {	   $("#signcompanyGrid").yxgrid("reload");};
$(function() {			$("#signcompanyGrid").yxgrid({				      model : 'contract_signcompany_signcompany',
               	title : '签约公司',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'signCompanyName',
                  					display : '签约公司名称',
                  					sortable : true
                              },{
                    					name : 'proName',
                  					display : '公司省份',
                  					sortable : true
                              },{
                    					name : 'proCode',
                  					display : '公司省份编码',
                  					sortable : true
                              },{
                    					name : 'linkman',
                  					display : '联系人',
                  					sortable : true
                              },{
                    					name : 'phone',
                  					display : '联系电话',
                  					sortable : true
                              },{
                    					name : 'address',
                  					display : '联系地址',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人Id',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人名称',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建时间',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人Id',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人名称',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改时间',
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