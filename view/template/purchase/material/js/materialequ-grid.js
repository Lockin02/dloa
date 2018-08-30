var show_page = function(page) {
	$("#materialequGrid").yxgrid("reload");
};
$(function() {
	$("#materialequGrid").yxgrid({
		model : 'purchase_material_materialequ',
       	title : '物料协议价明细表',
    	bodyAlign : 'center',
        showcheckbox : false,
        isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		//列信息
				colModel : [{
 					 display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
    					name : 'parentId',
  					display : '主表id',
          			sortable : true,
 						hide : true
              },{
            			name : 'productId',
  					display : '物料id',
          			sortable : true,
 						hide : true
              },{
            			name : 'productCode',
  					display : '物料编号',
  					sortable : true,
  						width : 120,
					process : function(v,row){
							return "<a href='#' onclick='showOpenWin(\"?model=purchase_material_material&action=toView&id="
									+ row.parentId +"\",1)'>" + v + "</a>";
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
    					name : 'lowerNum',
  					display : '数量下限',
  					sortable : true,
  						width : 70,
  					process : function (e) {
						if(e == 0){
						   return "<span style='color:red'>-</span>";
						}else{
						   return e;
						}
					}
              },{
    					name : 'ceilingNum',
  					display : '数量上限',
  					sortable : true,
  						width : 70,
  					process : function (e) {
						if(e == 0){
						   return "<span style='color:red'>-</span>";
						}else{
						   return e;
						}
					}
              },{
    					name : 'taxPrice',
  					display : '单价',
  					sortable : true,
					process : function(v){
						return moneyFormat2(v ,6);
					}
              },{
    					name : 'startValidDate',
  					display : '开始有效期',
  					sortable : true,
  						width : 90
              },{
    					name : 'validDate',
  					display : '结束有效期',
  					sortable : true,
  						width : 90
              },{
    					name : 'suppId',
  					display : '供应商id',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppName',
  					display : '供应商名称',
  					sortable : true,
  						width : 180
              },{
    					name : 'suppCode',
  					display : '供应商编码',
  					sortable : true,
 						hide : true
              },{
    					name : 'isEffective',
  					display : '是否有效',
  					sortable : true,
						width : 50,
					process : function (e) {
						if(e == "on"){
						   return "<span style='color:blue'>√</span>";
						}else{
						   return "<span style='color:red'>×</span>";
						}
					}
              },{
    					name : 'giveCondition',
  					display : '赠送条件',
  					sortable : true,
						width : 300,
      					align : 'left'
              },{
    					name : 'remark',
  					display : '备注',
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

		comboEx:[{
			text:'是否有效',
			key:'isEffective',
			data:[{
			   text:'是',
			   value:'on'
			},{
			   text:'否',
			   value:'no'
			}]
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "物料编号",
					name : 'productCode'
				},{
					display : "物料名称",
					name : 'productName'
				},{
					display : "供应商名称",
					name : 'suppName'
				}]
 		});
 });