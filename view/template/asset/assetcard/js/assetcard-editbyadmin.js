function checkRepeat() {
	return true;
}

/**
 * ��֤��Ϣ
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
			custom : ['onlyNumber']
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
		}
	});
}
$(function() {
	//ʹ��״̬����
	if ($('#useStatusCode').val() == 'SYZT-XZ') {
		$('.usingTr').hide();
		$('.hasUseTr').hide();
		$('.useOrg input').val("");
		$('.hasUseTr input').val("");
		document.getElementsByName('hasUse')[0].checked = true;
	} else {
		$('.usingTr').show();
	}


	//��������
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
	 * ��֤��Ϣ
	 */
	validateForm();
	//��Ŀ
	if($("#userId").val()!=''||!$('#useProId').val()!=0 ){
		document.getElementsByName('hasUse')[1].checked = true;
		$('.hasUseTr').show();
		selectDept($('#useOrgId').val(),true);
	}else{
		$('.hasUseTr').hide();
	}
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
					alert('����ѡ����!');
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
				'ExaStatus' : '���',
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
//
//$(document).ready(function() {
//	$("#supplierName").yxcombogrid_supplier({
//		hiddenId : 'supplierId',
//		gridOptions : {
//			showcheckbox : false
//		}
//	});
//	$("#useOrgName").yxselect_dept({
//		hiddenId : 'useOrgId',
//		event : {
//			'selectReturn' : function(e, data) {
//				selectDept(data.val)
//			},
//			'clearReturn' : function(e){
//				$('.hasUseTr').hide();
//				$('.hasUseTr input').val("");
//				document.getElementsByName('hasUse')[0].checked=true;
//			}
//		}
//	});
//	$("#orgName").yxselect_dept({
//		hiddenId : 'orgId'
//	});
//	$("#belongMan").yxselect_user({
//		hiddenId : 'belongManId'
//	});
//});


//�Զ������ʲ�ԭֵ
function setNetValue(){
	var origina = $('#origina').val();
	var depreciation = $('#depreciation').val();
	if(origina!=''&&depreciation!=''){
		setMoney('netValue',origina*1-depreciation*1)
	}else{
		setMoney('netValue',0)
	}
}

function setSalvage(){
//	if( $('#assetTypeRate').val()!='' ){
//		var assetTypeRate = $('#assetTypeRate').val()*0.01;
//		if( $('origina').val() !='' ){
//			setMoney('salvage',accMul($('#origina').val(),assetTypeRate))
//		}
//	}
}
//�������۾ɶ�
function setMonthlyDepreciation(){
	var netValue = $('#netValue').val();//��ֵ
	var salvage = $('#salvage').val();//��ֵ
	var estimateDay = $('#estimateDay').val();//�ڼ���
	var alreadyDay = $('#alreadyDay').val();//��ʹ���ڼ���
	if( estimateDay<alreadyDay ){
		$('#estimateDay').val('');
		return;
	}if( estimateDay==alreadyDay ){
		alert(estimateDay)
		setMoney('monthlyDepreciation',0)
		return;
	}
	if( netValue != '' && salvage!='' && estimateDay!='' ){
		var monthlyDepreciation = (netValue*1-salvage*1)/(estimateDay-alreadyDay)
		setMoney('monthlyDepreciation',monthlyDepreciation)
	}
}