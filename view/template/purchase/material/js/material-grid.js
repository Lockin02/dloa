var show_page = function(page) {
	$("#materialGrid").yxgrid("reload");
};
$(function() {
	$("#materialGrid").yxgrid({
	model : 'purchase_material_material',
               	title : '物料协议价信息',
               	bodyAlign : 'center',
               	isDelAction : false,
               	showcheckbox : false,
			//列信息
				colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
                      },{
            				name : 'productCode',
      					display : '物料编号',
      					sortable : true,
      					   width : 120,
						process : function(v,row){
								return "<a href='#' onclick='showOpenWin(\"?model=purchase_material_material&action=toView&id="
										+ row.id +"\",1)'>" + v + "</a>";
						}
                  },{
        					name : 'productName',
      					display : '物料名称',
      					sortable : true,
      					   width : 300,
						process : function(v,row){
								return "<a href='#' onclick='showThickboxWin(\"?model=stock_productinfo_productinfo&action=View&id="
										+ row.productId +"&placeValuesBefore&TB_iframe=true&modal=false&height=590&width=900\")'>"
										+ v + "</a>";
						}
                  },{
        					name : 'productId',
      					display : '物料id',
      					sortable : true,
 							hide : true
                  },{
        					name : 'protocolType',
      					display : '协议类型',
      					sortable : true,
      						width : 60
                  },{
            				name : 'createName',
      					display : '录入人',
      					sortable : true,
      						width : 80
                  },{
        					name : 'createTime',
      					display : '录入时间',
      					sortable : true,
      					   width : 130
                  },{
        					name : 'remark',
      					display : '备注说明',
      					sortable : true,
      					   width : 300,
      						align : 'left'
                  }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=purchase_material_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},

		menusEx : [{
			text : '删除',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=purchase_material_material&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg) {
								alert('删除成功！');
								$("#materialGrid").yxgrid("reload");
							}else {
							     alert("删除失败！")
							}
						}
					});
				}
			}
		}],

		comboEx:[{
			text:'协议类型',
			key:'protocolType',
			data:[{
			   text:'协议价格',
			   value:'协议价格'
			},{
			   text:'阶梯价格',
			   value:'阶梯价格'
			}]
		}],

		toEditConfig : {
			formWidth:990,
			action : 'toEdit'
		},
		toViewConfig : {
			formWidth:980,
			action : 'toView'
		},
		toAddConfig : {
			formWidth:990
		},
		searchitems : [{
					display : "物料编号",
					name : 'productCode'
				},{
					display : "物料名称",
					name : 'productName'
				},{
					display : "录入人",
					name : 'createName'
				}]
 		});
 });