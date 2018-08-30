$(document).ready(function() {

	$("#itemTable").yxeditgrid({
		objName : 'material[materialequ]',
		bodyAlign:'center',
		url : '?model=purchase_material_materialequ&action=listJson',
		title : '协议价明细',
		param : {
			parentId :$("#id").val()
		},
		type : 'view',
		colModel : [{
			name : 'lowerNum',
			display : '数量下限',
			width : 70,
			process : function (e) {
				if(e == 0){
				   return "<span style='color:red'>-</span>";
				}else {
					return e;
				}
			}
		}, {
			name : 'ceilingNum',
			display : '数量上限',
			width : 70,
			process : function (e) {
				if(e == 0){
				   return "<span style='color:red'>-</span>";
				}else {
					return e;
				}
			}
		}, {
			name : 'taxPrice',
			display : '单价',
			width : 70,
			process : function(v){
				return moneyFormat2(v ,6);
			}
		}, {
			name : 'startValidDate',
			display : '开始有效期',
			type : 'date',
			width : 70
		}, {
			name : 'validDate',
			display : '结束有效期',
			width : 70
		}, {
			name : 'suppName',
			display : '供应商名称',
			width : 180
		}, {
			name : 'isEffective',
			display : '是否有效',
			width : 20,
			process : function (e) {
				if(e == "on"){
				   return "<span style='color:blue'>√</span>";
				}else{
				   return "<span style='color:red'>×</span>";
				}
			}
		}, {
			name : 'giveCondition',
			display : '赠送条件',
			width : 150
		}, {
			name : 'remark',
			display : '备注',
			width : 150
		}]
	});

})