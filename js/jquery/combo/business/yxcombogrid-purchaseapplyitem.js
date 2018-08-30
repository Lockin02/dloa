/**
 * 下拉采购检验申请单产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_purchaseapplyitem', {
			options : {
				hiddenId : 'materialId',
				nameCol : 'materialName',
				gridOptions : {
				showcheckbox : false,
				model : 'quality_apply_purchaseapplyitem',
				pageSize : 10,
				// 列信息
				colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '申请单id',
                  					hide : true
                              },{
                    					name : 'materialCode',
                  					display : '物料编码',
                  					sortable : true
                              },{
                    					name : 'materialName',
                  					display : '物料名称',
                  					sortable : true
                              },{
                    					name : 'materialId',
                  					display : '物料id',
                  					hide : true
                              },{
                    					name : 'businessType',
                  					display : '需求类型',
                  					hide : true
                              },{
                    					name : 'businessCode',
                  					display : '需求编号',
                  					hide : true
                              },{
                    					name : 'businessId',
                  					display : '需求id',
                  					hide : true
                              },{
                    					name : 'batchNum',
                  					display : '批次',
                  					sortable : true
                              },{
                    					name : 'cost',
                  					display : '暂估价',
                  					sortable : true
                              },{
                    					name : 'storageNum',
                  					display : '报检数量',
                  					sortable : true,
                  					width:50
                              },{
                    					name : 'subCheckNum',
                  					display : '累计检验数量',
                  					hide : true
                              }],
				// 快速搜索
				searchitems : [{
							display : '产品名称',
							name : 'materialName'
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);