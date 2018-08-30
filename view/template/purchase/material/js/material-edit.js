$(document).ready(function() {

	$("#protocolTypeCode").trigger('change');
	$("#itemTable").yxeditgrid({
		objName : 'material[materialequ]',
		bodyAlign:'center',
		url : '?model=purchase_material_materialequ&action=listJson',
		title : '协议价明细',
		param : {
			parentId :$("#id").val()
		},
		colModel : [{
			name : 'lowerNum',
			display : '数量下限',
			width : 70,
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		}, {
			name : 'ceilingNum',
			display : '数量上限',
			width : 70,
			validation : {
				required : true,
				custom : ['onlyNumber']
			}
		}, {
			name : 'taxPrice',
			display : '单价',
			width : 70,
			validation : {
				required : true,
				custom : ['percentageNum']
			}
		}, {
			name : 'startValidDate',
			display : '开始有效期',
			type : 'date',
			width : 70,
			readonly : true,
			validation : {
				required : true
			}
		}, {
			name : 'validDate',
			display : '结束有效期',
			width : 70,
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		}, {
			name : 'suppName',
			display : '供应商名称',
			width : 180,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var itemTableObj = $("#itemTable");
				$input.yxcombogrid_supplier({
					hiddenId : 'itemTable_cmp_suppName' + rowNum,
					isShowButton : false,
					width : 615,
					isFocusoutCheck : false,
					gridOptions : {
						event : {
							row_dblclick : function(e, row, data) {
								//本行报检数量
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppId").val(data.id);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppName").val(data.suppName);
								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"suppCode").val(data.busiCode);
							}
						}
					}
				});
			},
			validation : {
				required : true
			}
		}, {
			name : 'suppId',
			display : '供应商id',
			type : 'hidden'
		}, {
			name : 'suppCode',
			display : '供应商编码',
			type : 'hidden'
		}, {
			name : 'isEffective',
			display : '是否有效',
			width : 35,
			type : 'checkbox',
			process : function (e) {
				if(e == "on"){
				   return true;
				}else{
				   return false;
				}
			},
			value : 'on'
		}, {
			name : 'giveCondition',
			display : '赠送条件',
			width : 150,
			type : 'textarea'
		}, {
			name : 'remark',
			display : '备注',
			width : 150,
			type : 'textarea'
		}]
	});

   validate({
				"productCode" : {
					required : true
				},
				"protocolTypeCode" :{
					required : true
				}
			});
   });