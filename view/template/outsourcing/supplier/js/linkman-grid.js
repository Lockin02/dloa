var show_page = function(page) {	   $("#linkmanGrid").yxgrid("reload");};
$(function() {			$("#linkmanGrid").yxgrid({				      model : 'outsourcing_supplier_linkman',
               	title : '供应商联系人',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'suppCode',
                  					display : '供应商编号',
                  					sortable : true
                              }                    ,{
                    					name : 'name',
                  					display : '姓名',
                  					sortable : true
                              },{
                    					name : 'jobName',
                  					display : '职务',
                  					sortable : true
                              },{
                    					name : 'mobile',
                  					display : '电话',
                  					sortable : true
                              },{
                    					name : 'mobile2',
                  					display : '联系电话2',
                  					sortable : true
                              },{
                    					name : 'zipCode',
                  					display : '邮编',
                  					sortable : true
                              },{
                    					name : 'address',
                  					display : '地址',
                  					sortable : true
                              },{
                    					name : 'fax',
                  					display : '传真',
                  					sortable : true
                              },{
                    					name : 'email',
                  					display : '邮箱',
                  					sortable : true
                              },{
                    					name : 'defaultContact',
                  					display : '默认联系人',
                  					sortable : true
                              },{
                    					name : 'plane',
                  					display : '座机',
                  					sortable : true
                              },{
                    					name : 'remarks',
                  					display : '备注',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建时间',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人名称',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人Id',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改时间',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人名称',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人Id',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_supplier_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "搜索字段",
					name : 'XXX'
				}]
 		});
 });