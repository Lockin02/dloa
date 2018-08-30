var show_page = function(page) {
	$("#detailGrid").yxgrid("reload");
};
$(function() {
			$("#detailGrid").yxgrid({
				      model : 'finance_related_detail',
               	title : '发表勾稽记录表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'relatedId',
                  					display : '钩稽主表id(勾稽序号)',
                  					sortable : true
                              },{
                    					name : 'productId',
                  					display : '产品id',
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '产品编码',
                  					sortable : true
                              },{
                    					name : 'productModel',
                  					display : '规格型号',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '本次勾稽数量',
                  					sortable : true
                              },{
                    					name : 'amount',
                  					display : '本次勾稽金额',
                  					sortable : true
                              },{
                    					name : 'hookNumber',
                  					display : '已钩稽数量',
                  					sortable : true
                              },{
                    					name : 'hookAmount',
                  					display : '已钩稽金额',
                  					sortable : true
                              },{
                    					name : 'unHookNumber',
                  					display : '未钩稽数量',
                  					sortable : true
                              },{
                    					name : 'unHookAmount',
                  					display : '未钩稽金额',
                  					sortable : true
                              },{
                    					name : 'formDate',
                  					display : '单据日期',
                  					sortable : true
                              },{
                    					name : 'supplierId',
                  					display : '供应商Id',
                  					sortable : true
                              },{
                    					name : 'supplierName',
                  					display : '供应商名称',
                  					sortable : true
                              },{
                    					name : 'purType',
                  					display : '采购方式',
                  					sortable : true
                              },{
                    					name : 'property',
                  					display : '辅助属性',
                  					sortable : true
                              },{
                    					name : 'unit',
                  					display : '计量单位',
                  					sortable : true
                              },{
                    					name : 'isAcount',
                  					display : '是否已核算',
                  					sortable : true
                              },{
                    					name : 'hookObjCode',
                  					display : '钩稽对象编号',
                  					sortable : true
                              },{
                    					name : 'hookObj',
                  					display : '钩稽对象',
                  					sortable : true
                              },{
                    					name : 'hookId',
                  					display : '钩稽条目id',
                  					sortable : true
                              },{
                    					name : 'hookDate',
                  					display : '日期',
                  					sortable : true
                              }]
 		});
 });