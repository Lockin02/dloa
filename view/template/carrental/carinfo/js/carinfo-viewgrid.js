var show_page = function(page) {
	$("#carinfoGrid").yxgrid("reload");
};
$(function() {
	$("#carinfoGrid").yxgrid({
		model : 'carrental_carinfo_carinfo',
	   	title : '车辆信息',
       	param : { "unitsId" : $("#unitsId").val() },
	   	isViewAction : false,
	   	isDelAction : false,
	   	isAddAction : false,
	   	isEditAction : false,
		//列信息
		colModel : [
		{
 			display : 'id',
 			name : 'id',
 			sortable : true,
 			hide : true
		},{
        	name : 'unitsName',
  			display : '单位名称',
  			sortable : true
        },{
        	name : 'carType',
  			display : '车辆型号',
  			sortable : true
        },{
    		name : 'carNo',
  			display : '车牌号',
  			sortable : true
        },{
			name : 'limitedSeating',
  			display : '限载人数',
  			sortable : true
        },{
    		name : 'status',
  			display : '车辆状态',
  			sortable : true,
			process : function(val) {
				if (val == "0") {
				return "生效";
				} else if(val == "1"){
					return "失效";
				} else {
					return "数据失效";
				}
			}
         },{
			name : 'remark',
  			display : '备注说明',
  			sortable : true,
  			width : 200
         }],
		menusEx : [
		{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=carrental_carinfo_carinfo&action=viewTab&id=" + row.id + '&skey=' + row.skey_ );
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		comboEx : [{
					text : '车辆状态',
					key : 'status',
					data : [{
								text : '失效',
								value : '1'
							}, {
								text : '生效',
								value : '0'
							}]
				}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems :  [{
			display : '单位名称',
			name : 'unitName'
		}, {
			display : '车辆型号',
			name : 'carType'
		}]
 		});
 });