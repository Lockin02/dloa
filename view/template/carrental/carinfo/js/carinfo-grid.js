var show_page = function(page) {
	$("#carinfoGrid").yxgrid("reload");
};
$(function() {
	$("#carinfoGrid").yxgrid({
		model : 'carrental_carinfo_carinfo',
	   	title : '车辆信息',
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
  			sortable : true,
 			hide : true
        },{
    		name : 'carNo',
  			display : '车牌号',
  			sortable : true,
  			width : 80,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=carrental_carinfo_carinfo&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
        },{
        	name : 'carTypeName',
  			display : '车型',
  			sortable : true,
  			width : 80
        },{
        	name : 'brand',
  			display : '品牌',
  			sortable : true,
  			width : 80
        },{
        	name : 'displacement',
  			display : '排量',
  			sortable : true,
  			width : 60
        },{
			name : 'buyDate',
  			display : '购车日期',
  			sortable : true,
  			width : 80
        },{
			name : 'owners',
  			display : '车主',
  			sortable : true,
  			width : 70
        },{
			name : 'driver',
  			display : '司机',
  			sortable : true,
  			width : 70
        },{
			name : 'linkPhone',
  			display : '联系方式',
  			sortable : true,
  			width : 80
        },{
			name : 'isSign',
  			display : '是否签合同',
  			sortable : true,
  			width : 70,
  			process : function(v){
  				if(v == '1'){
  					return "是";
  				}else{
  					return "否";
  				}
  			}
        },{
			name : 'useDays',
  			display : '累计用车天数',
  			sortable : true,
  			width : 80
        },{
			name : 'evaluate',
  			display : '评价',
  			sortable : true
        },{
			name : 'fuelConsumption',
  			display : '综合油耗',
  			sortable : true,
  			width : 60
        },{
			name : 'perFuel',
  			display : '平均油耗',
  			sortable : true,
  			width : 80
        },{
			name : 'lastCheckDate',
  			display : '最近安检',
  			sortable : true,
  			width : 80
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