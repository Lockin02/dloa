var show_page = function(page) {
	$("#materialGrid").yxgrid("reload");
};
$(function() {
			$("#materialGrid").yxgrid({
				model : 'stock_material_material',
               	title : '物料BOM清单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'proTypeId',
                  					display : '物料类型id',
                  					sortable : true
                              },{
                    					name : 'proType',
                  					display : '物料类型',
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '物料编码',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '物料名称',
                  					sortable : true
                              },{
                    					name : 'pattern',
                  					display : '规格型号',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '单位',
                  					sortable : true
                              },{
                    					name : 'parentProductID',
                  					display : '最高节点物料ID',
                  					sortable : true
                              },{
                    					name : 'parentProductName',
                  					display : '最高节点物料名称',
                  					sortable : true
                              },{
                    					name : 'parentProductCode',
                  					display : '最高节点物料编码',
                  					sortable : true
                              },{
                    					name : 'parentName',
                  					display : '父节点名称',
                  					sortable : true
                              },{
                    					name : 'parentId',
                  					display : '父节点id',
                  					sortable : true
                              },{
                    					name : 'lft',
                  					display : '节点左值',
                  					sortable : true
                              },{
                    					name : 'rgt',
                  					display : '节点右值',
                  					sortable : true
                              },{
                    					name : 'isLeaf',
                  					display : '是否叶子节点',
                  					sortable : true
                              },{
                    					name : 'materialNum',
                  					display : '清单数量',
                  					sortable : true
                              },{
                    					name : 'parentMaterialNum',
                  					display : '父节点清单数量',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建日期',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人id',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改日期',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_material_NULL&action=pageItemJson',
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