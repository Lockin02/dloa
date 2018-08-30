$(document).ready(function() {
	/*
	 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' } });
	 */
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		nameCol : 'productCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productName").val(data.productName);
					$("#pattern").val(data.pattern);
					$("#unitName").val(data.unitName);
				}
			}
		}
	});

	$("#productName").yxcombogrid_product({
		hiddenId : 'productId',
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productCode").val(data.productCode);
					$("#pattern").val(data.pattern);
					$("#unitName").val(data.unitName);
				}
			}
		}
	});

	reloadRelDocType();
	$("#relDocType").bind("change", function() {
		reloadRelDocType();
	})

	validate({
		"relDocCode" : {
			required : true
		},
		"productCode" : {
			required : true
		},
		"configName" : {
			required : true
		}
	});

})

/**
 * 喧染需求编号
 */
function reloadRelDocType() {
	$("#relDocCode").yxcombogrid_allcontract("remove");
	$("#relDocCode").yxcombogrid_borrow("remove");
	$("#relDocCode").yxcombogrid_present("remove");
	
	$("#relDocId").val("");
	$("#relDocCode").val("");
	if ($("#relDocType").val() == "0") {// 合同
		$("#relDocCode").yxcombogrid_allcontract({
			hiddenId : 'relDocId',
			// nameCol : 'contractCode',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
					}
				}
			}
		});
	}
	if ($("#relDocType").val() == "1") {// 借试用
		$("#relDocCode").yxcombogrid_borrow({
			hiddenId : 'relDocId',
			// nameCol : 'contractCode',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
					}
				}
			}
		});
	}
	if ($("#relDocType").val() == "2") {// 赠送
		$("#relDocCode").yxcombogrid_present({
			hiddenId : 'relDocId',
			// nameCol : 'contractCode',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
					}
				}
			}
		});
	}
}