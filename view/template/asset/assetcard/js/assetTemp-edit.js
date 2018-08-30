function checkRepeat() {
	return true;
}

/**
 * 验证信息
 */
function validateForm() {
	validate({
//		"assetabbrev" : {
//			//required : true
//			custom : ['noSpecialCaracters']
//		},
		"assetName" : {
			required : true
		},
		"wirteDate" : {
			custom : ['date']
		},
		"buyDate" : {
			custom : ['date']
		},
		//				"subName" : {
		//					required : true
		//				},
		"orgName" : {
			required : true
		},
		"origina" : {
			custom : ['percentageNum']
		},
		"buyDepr" : {
			custom : ['money']
		},
		"beginTime" : {
			custom : ['date']
		},
		"estimateDay" : {
			required : true
		},
		//				"depSubName" : {
		//					required : true
		//				},
		"alreadyDay" : {
			required : true
		},
		"depreciation" : {
			custom : ['money']
		},
		"salvage" : {
			custom : ['percentageNum']
		},
		"netValue" : {
			custom : ['percentageNum']
		},
		"property" : {
			required : true
		}
	});
}
$(function() {
	$("#property").val($("#property").attr("val"));

	//使用状态联动
	if ($('#useStatusCode').val() == 'SYZT-XZ') {
		$('.usingTr').hide();
		$('.hasUseTr').hide();
		$('.useOrg input').val("");
		$('.hasUseTr input').val("");
		document.getElementsByName('hasUse')[0].checked = true;
	} else {
		$('.usingTr').show();
	}


	//关联物料
	$("#productName").yxcombogrid_product({
		hiddenId : 'productId',
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#productCode').val(data.productCode);
				}
			}
		}
	});
	/**
	 * 验证信息
	 */
	validateForm();
	//项目
//	if($("#userId").val()!=''||!$('#useProId').val()!=0 ){
//		document.getElementsByName('hasUse')[1].checked = true;
//		$('.hasUseTr').show();
//		selectDept($('#useOrgId').val(),true);
//	}else{
//		$('.hasUseTr').hide();
//	}
	$('#useStatusCode').change(function() {
		if ($('#useStatusCode').val() == 'SYZT-XZ') {
			$('.usingTr').hide();
			$('.hasUseTr').hide();
			$('.useOrg').val("");
			$('.hasUseTr input').val("");
			document.getElementsByName('hasUse')[0].checked = true;
		} else {
			$('.usingTr').show();
		}
	})
	$('.hasUse').change(function() {
		var hasUse = document.getElementsByName('hasUse');
		for (var i = 0; i < hasUse.length; i++) {
			if (hasUse[i].checked && hasUse[i].value == 1) {
				if ($('#useOrgId').val() == '') {
					alert('请先选择部门!');
					document.getElementsByName('hasUse')[0].checked = true;
					break;
				}else{
					$('.hasUseTr').show();
					selectDept($('#useOrgId').val(),true);
				}
			} else {
				$('.hasUseTr').hide();
				$('.hasUseTr input').val("");
			}
		}
	})
});
$(document).ready(function() {
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		gridOptions : {
			showcheckbox : false
		}
	});
//	$("#userName").yxselect_user(sjzlop ncjoxpamdksoapcm iopav{
//		hiddenId : 'userId'
//	});
	$("#useOrgName").yxselect_dept({
		hiddenId : 'useOrgId',
		event : {
			'selectReturn' : function(e, data) {
				selectDept(data.val,false)
			},
			'clearReturn' : function(e) {
				$('.hasUseTr').hide();
				$('.hasUseTr input').val("");
				document.getElementsByName('hasUse')[0].checked = true;
			}
		}
	});
	$("#orgName").yxselect_dept({
		hiddenId : 'orgId',
		event : {
			'selectReturn' : function(e, data) {
				$('.belongMan input').val("");
				$("#belongMan").yxselect_user("remove");
				$("#belongMan").yxselect_user({
					hiddenId : 'belongManId',
					deptIds : data.val,
					event : {
						select : function(e, returnValue) {
							if (returnValue) {
								$('#companyCode').val(returnValue.companyCode)
								$('#companyName').val(returnValue.companyName)
							}
						}
					}
				});
			},
			'clearReturn' : function(e){
				$("#belongMan").yxselect_user("remove");
				$("#belongMan").yxselect_user({
					hiddenId : 'belongManId',
					isGetDept : [true, "orgId", "orgName"],
					event : {
						select : function(e, returnValue) {
							if (returnValue) {
								$('#companyCode').val(returnValue.companyCode)
								$('#companyName').val(returnValue.companyName)
							}
						}
					}
				});
			}
		}
	});
	$("#belongMan").yxselect_user({
		hiddenId : 'belongManId',
		deptIds : $('#orgId').val(),
		isGetDept : [true, "orgId", "orgName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#companyCode').val(returnValue.companyCode)
					$('#companyName').val(returnValue.companyName)
				}
			}
		}
	});

	if($('#productName').val()==''){
		$('#isPro').val('0');
		$('.isPro').hide();
		$('#productName').removeClass("validate[required]")
	}else{
		$('#isPro').val('1');
		$('.isPro').show();
		$('#productName').addClass("validate[required]")
	}
});


function selectDept(deptId,first) {
	if(first){
		;
	}else{
		$('.hasUseTr input').val("");
	}
	if ($("#useProName").yxcombogrid_esmproject) {
		$("#useProName").yxcombogrid_esmproject("remove");
	}
	$("#useProName").yxcombogrid_esmproject({
		hiddenId : 'useProId',
		gridOptions : {
			param : {
				'ExaStatus' : '完成',
				'depId' : deptId
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#projectCode").val(data.projectCode);
				}
			}
		}
	});
	if ($("#userName").yxselect_user) {
		$("#userName").yxselect_user("remove");
	}
	$("#userName").yxselect_user({
		hiddenId : 'userId',
		deptIds : deptId
	});
}

/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要生成卡片吗?")) {
		$("#form1").attr("action",
				"?model=asset_assetcard_assetTemp&action=edit&actType=submit");
		$("#form1").submit();
	} else {
		return false;
	}
}
$(function(){
	if($('#number').val()=='1'){
		$('.machineClass').show();
	}else{
		$('.machineClass').hide();
		$('#machineCode').val('');
	}
	$('#number').blur(function(){
		if($(this).val()=='1'){
			$('.machineClass').show();
		}else{
			$('.machineClass').hide();
			$('#machineCode').val('');
		}
	})
})