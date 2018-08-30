var show_page = function(page) {	   $("#certifyGrid").yxgrid("reload");};
$(function() {			$("#certifyGrid").yxgrid({				      model : 'outsourcing_supplier_certify',
               	title : '供应商资质证书',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'suppName',
                  					display : '外包供应商',
                  					sortable : true
                              },{
                    					name : 'suppCode',
                  					display : '供应商编号',
                  					sortable : true
                              },{
                    					name : 'typeName',
                  					display : '类型',
                  					sortable : true
                              },{
                    					name : 'typeCode',
                  					display : '类型Code',
                  					sortable : true
                              },{
                    					name : 'certifyName',
                  					display : '资质名称',
                  					sortable : true
                              },{
                    					name : 'certifyLevel',
                  					display : '资质等级',
                  					sortable : true
                              },{
                    					name : 'certifyCode',
                  					display : '资质证书编号',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '资质有效期(起始日)',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '资质有效期(终止日)',
                  					sortable : true
                              },{
                    					name : 'certifyCompany',
                  					display : '资质发放单位',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
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