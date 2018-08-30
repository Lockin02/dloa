var show_page = function(page) {	   $("#changeitemGrid").yxgrid("reload");};
$(function() {			$("#changeitemGrid").yxgrid({				      model : 'service_change_changeitem',
               	title : '设备更换清单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                        ,{
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
                    					name : 'remark',
                  					display : '更换原因',
                  					sortable : true
                              }],

		toEditConfig : {
			toEditFn : function(p) {
				action : showThickboxWin("?model=service_change_changeitem&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_'])
			}
		},
		toViewConfig : {
			toViewFn : function(p) {
				action : showThickboxWin("?model=service_change_changeitem&action=toView&id="
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