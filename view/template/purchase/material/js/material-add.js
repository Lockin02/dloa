$(document).ready(function() {
	initDetail();

	validate({
				"productCode" : {
					required : true
				},
				"protocolTypeCode" :{
					required : true
				}
			});
    })

//初始化质检明细
function initDetail(){
	//缓存质检内容表
	var itemTableObj = $("#itemTable");//获取对象

	itemTableObj.yxeditgrid({//对象调用编辑表格方法
		objName : 'material[materialequ]',
		event : {
			'reloadData' : function(e){
				$("#protocolTypeCode").trigger("change");
			},
			'removeRow' : function(){
			}
		},
		title : '协议价明细',
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
			type : 'date',
			width : 70,
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
			value : 'on'
		}, {
			name : 'giveCondition',
			display : '赠送条件',
			type : 'textarea',
			width : 150
		}, {
			name : 'remark',
			display : '备注',
			type : 'textarea',
			width : 150
		}]
	});
}