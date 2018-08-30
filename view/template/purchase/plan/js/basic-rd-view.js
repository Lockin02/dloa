$(function() {

			if($('#readType').val()!="" ){
				$('#actType').hide();
			}
	$("#RDProductTable").yxeditgrid({
		objName : 'basic[equipment]',
		 delTagName : 'isDelTag',
		type : 'view',
		url : '?model=purchase_plan_equipment&action=listJson',
		param : {
			basicId : $("#id").val()
		},
		colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				}, {
					display : '是否归属固定资产',
					name : 'isAsset',
					tclass : 'txtmin',
					process : function(v, row) {
						if(v=="on"){
							return "是";
						}else{
							return "否";
						}
					}
				}, {
					display : 'productId',
					name : 'productId',
					type : 'hidden'
				}, {
					display : '设备编码',
					name : 'productNumb'
				}, {
					display : '设备名称',
					name : 'productName',
					tclass : 'txt'
				}, {
					display : '规格型号',
					name : 'pattem'
					// validation : {
				// required : true
				// }
			}	, {
					display : '单位',
					name : 'unitName',
					tclass : 'txtshort'
				}, {
					display : '数量',
					name : 'amountAll',
					tclass : 'txtshort'
				}, {
					display : '供应商',
					name : 'surpplierName'
				}, {
					display : '希望交货日期',
					name : 'dateHope',
					tclass : 'txtshort'
				}, {
					display : '设备使用年限',
					name : 'equUseYear',
					tclass : 'txtshort',
					process : function(v, row) {
						if(v==0){
							return "一年以上";
						}else{
							return "一年以下";
						}
					}
				}, {
					display : '预计购入单价',
					name : 'planPrice',
					tclass : 'txtmiddle',
					process : function(v, row) {
						if(v==0){
							return "500元以上";
						}else{
							return "500元以下";
						}
					}

				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]
	})

});