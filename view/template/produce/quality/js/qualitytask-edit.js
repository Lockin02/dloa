$(document).ready(function() {

	if ($("#acceptStatus").val() == "YJS") {
		$("#chargeUserName").attr("class", "readOnlyTxtNormal");
		$("#chargeUserName").attr("readonly", true);
	} else {
		$("#chargeUserName").yxselect_user({
			hiddenId : 'chargeUserCode'
		});
	}

	/*
	 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' } });
	 */$("#itemTable").yxeditgrid({
		objName : 'qualitytask[items]',
		url : '?model=produce_quality_qualitytaskitem&action=editItemJson',
		param : {
			mainId : $("#id").val()
		},
		isAddAndDel : false,
		colModel : [ {
			name : 'id',
			display : 'id',
			type : 'hidden'
		}, {
			name : 'applyItemId',
			display : 'applyItemId',
			type : 'hidden'
		}, {
			name : 'productCode',
			tclass : 'readOnlyTxtItem',
			display : '物料编号',
			readonly : true,
			validation : {
				required : true
			}
		}, {
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			display : '物料名称',
			validation : {
				required : true
			}
		}, {
			name : 'pattern',
			tclass : 'txt',
			tclass : 'readOnlyTxtItem',
			readonly : true,
			display : '型号'
		}, {
			name : 'unitName',
			tclass : 'readOnlyTxtItem',
			readonly : true,
			display : '单位'
		}, {
			name : 'assignNum',
			tclass : 'txtshort',
			display : '数量'
		}, {
			name : 'standardNum',
			tclass : 'readOnlyTxtItem',
			readonly : true,
			display : '合格数量'
		} ]
	})
})

function checkForm() {
	var checkResult = true;
	var g = $("#itemTable").data("yxeditgrid");
	// var rowNum=$("#itemTable").yxeditgrid("getCurShowRowNum");
	// 验证质检数量 不低于合格数量 高于申请单数量
	for ( var i = 0; i < g.getCurShowRowNum(); i++) {
		if (parseFloat($("#" + g.getRowColId(i, "assignNum")).val()) < parseFloat($(
				"#" + g.getRowColId(i, "standardNum")).val())) {
			checkResult = false;
			alert("数量不能小于合格数量!");
		}
		$
				.ajax({
					async:false,
					type : 'POST',
					url : '?model=produce_quality_qualitytaskitem&action=checkAssignNum',
					data : {
						assignNum : $("#" + g.getRowColId(i, "assignNum"))
								.val(),
						id : $("#" + g.getRowColId(i, "id")).val(),
						applyItemId : $("#" + g.getRowColId(i, "applyItemId"))
								.val()
					},
					success : function(data) {
						if (data == "0") {
							alert("分配数量有误!")
							checkResult = false;
						}
					}
				});
		if (!checkResult) {
			break;
		}
	}

	return checkResult;
}

function reloadItemCount() {
	var i = 1;
	$("#itembody").children("tr").each(function() {
		if ($(this).css("display") != "none") {
			$(this).children("td").eq(1).text(i);
			i++;

		}
	})
}
// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append(
				'<input type="hidden" name="qualitytask[items][' + rowNo
						+ '][isDelTag]" value="1" id="isDelTag' + rowNo
						+ '" />');
		reloadItemCount();
	}
}