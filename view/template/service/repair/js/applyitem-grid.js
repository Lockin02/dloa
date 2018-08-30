var show_page = function(page) {
	$("#applyitemGrid").yxgrid("reload");
	};
$(function() {
	$("#applyitemGrid").yxgrid({
		model : 'service_repair_applyitem',
               	title : '维修申请(报价)清单',

						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							  },{
                    					name : 'productType',
										display : '物料分类',
										hide : true,
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '物料编号',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '物料名称',
                  					sortable : true,
                  					width : 250
                              },{
                    					name : 'pattern',
                  					display : '规格型号',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '单位',
                  					sortable : true
                              },{
                    					name : 'serilnoName',
                  					display : '序列号',
                  					sortable : true
                              },{
                    					name : 'fittings',
                  					display : '配件信息',
                  					sortable : true
                              },{
                    					name : 'place',
                  					display : '现在地点',
                  					sortable : true
                              },{
                    					name : 'process',
                  					display : '预备过程',
                  					sortable : true
                              },{
                    					name : 'troubleInfo',
                  					display : '故障现象',
                  					sortable : true
                              },{
                    					name : 'checkInfo',
                  					display : '检测处理方法',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              },{
                    					name : 'isGurantee',
                  					display : '是否过保',
                  					sortable : true,
                  					 process : function(val) {
									  if (val == "0") {
										return "是";
									   }else {
										return "否";
									   }
								       }
                              },{
                    					name : 'repairType',
                  					display : '费用类型',
                  					sortable : true,
                  					 process : function(val) {
									  if (val == "0") {
										return "收费维修";
									   }
									   if(val="1") {
										return "保内维修";
									   }else{
									   return "内部维修";
									   }
								       }

                              },{
                    					name : 'repairCost',
                  					display : '维修费用',
                  					sortable : true
                              },{
                    					name : 'cost',
                  					display : '收取费用',
                  					sortable : true
                              },{
                    					name : 'isDetect',
                  					display : '是否已下达检测维修',
                  					sortable : true
                              },{
                    					name : 'delivery',
                  					display : '是否已发货',
                  					sortable : true
                              }],

		toEditConfig : {
			toEditFn : function(p) {
					action : showThickboxWin("?model=service_repair_applyitem&action=toEdit&id="
								+ row.id
								+ "&skey="
								+ row['skey_'])
			}
		},
		toViewConfig : {
			toViewFn : function(p) {
				action : showThickboxWin("?model=service_repair_applyitem&action=toView&id="
							+ row.id
							+ "&skey="
							+ row['skey_'])
			}
		},
		searchitems : {
					display : "搜索字段",
					name : 'XXX'
				}
 		});
 });