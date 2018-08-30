function checkRepeat() {
	return true;
}

/**
 * ��֤��Ϣ
 */
function validateForm() {
	validate({
		"assetName" : {
			required : true
		},
		"wirteDate" : {
			custom : ['date']
		},
		"buyDate" : {
			custom : ['date']
		},
		"orgName" : {
			required : true
		},
		"belongMan" : {
			required : true
		},
		"useOrgName" : {
			required : true
		},
		"userName" : {
			required : true
		},
		"agencyName" : {
			required : true
		}
	});
}
$(function() {
	//��֤��Ϣ
	validateForm();
	//ʹ��״̬����
	if ($('#useStatusCode').val() == 'SYZT-XZ') {
		$('.usingTr').hide();
		$('.hasUseTr').hide();
		$('.hasUseTr input').val("");
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
	//��ʼ����Ŀ
	if($('#useOrgName').val() != '' && $('#useOrgId').val() != ''){
		selectDept($('#useOrgId').val(),true);
	}
	$('#useStatusCode').change(function() {
		if ($('#useStatusCode').val() == 'SYZT-XZ' || $('#useOrgName').val() == '') {
			$('.usingTr').hide();
			$('.hasUseTr').hide();
			$('.hasUseTr input').val("");
		} else {
			$('.usingTr').show();
			$('.hasUseTr').show();
		}
	})
	//���ʲ�����Ϊ�ֻ�ʱ������ʾ�ֻ�Ƶ�Ρ��ֻ�����
	if($("#assetName").val() == "�ֻ�"){	//��ʼ����֤
		$("#mobileBand").parents("tr:first").show();
		$('#mobileBand').addClass("validate[required]");
		$('#mobileNetwork').addClass("validate[required]");
	}
	$("#assetName").change(function(){//�ֶ�����ʱ��֤
		if($(this).val() == "�ֻ�"){
			$("#mobileBand").parents("tr:first").show();
			$('#mobileBand').addClass("validate[required]");
			$('#mobileNetwork').addClass("validate[required]");
		}else{
			$("#mobileBand").parents("tr:first").hide();
			$('.mobileTr input').val("");
			$('#mobileBand').removeClass("validate[required]");
			$('#mobileNetwork').removeClass("validate[required]");
		}
	})
	//�ʲ��䶯ʱ���жϲ���Ա�Ƿ���Զ�����������б䶯
	if($("#agencyLimit").val() === '0'){
		$("#agencyName").parents("tr:first").hide();
	}
/********************�������********************/
	//��Ӧ��
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		gridOptions : {
			showcheckbox : false
		}
	});
	//���´�
	$("#agencyName").yxcombogrid_agency({
		hiddenId : 'agencyCode',
		gridOptions : {
			showcheckbox : false
		}
	});
	//�ʲ�����
	$("#assetName").yxcombogrid_assetinfo({
		nameCol : 'assetName',
		gridOptions : {
			action : 'comboAssetInfoJson&searchType=assetName',
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					var assetName = data.assetName;
					$('#assetName').val(assetName);
					//���ʲ�����Ϊ�ֻ�ʱ������ʾ�ֻ�Ƶ�Ρ��ֻ�����
					if(assetName == "�ֻ�"){
						$("#mobileBand").parents("tr:first").show();
						$('#mobileBand').addClass("validate[required]");
						$('#mobileNetwork').addClass("validate[required]");
					}else{
						$("#mobileBand").parents("tr:first").hide();
						$('.mobileTr input').val("");
						$('#mobileBand').removeClass("validate[required]");
						$('#mobileNetwork').removeClass("validate[required]");
					}
				}
			}
		}
	});
	//�ֻ�Ƶ��
	$("#mobileBand").yxcombogrid_assetinfo({
		nameCol : 'mobileBand',
		gridOptions : {
			action : 'comboAssetInfoJson&searchType=mobileBand',
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#mobileBand').val(data.mobileBand);
				}
			},
			// ��������
			searchitems : [{
					display : '�ֻ�Ƶ��',
					name : 'mobileBand'
				}
			]
		}
	});
	//�ֻ�����
	$("#mobileNetwork").yxcombogrid_assetinfo({
		nameCol : 'mobileNetwork',
		gridOptions : {
			action : 'comboAssetInfoJson&searchType=mobileNetwork',
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#mobileNetwork').val(data.mobileNetwork);
				}
			},
			// ��������
			searchitems : [{
					display : '�ֻ�����',
					name : 'mobileNetwork'
				}
			]
		}
	});
	//ʹ�ò��ţ�ʹ����
	$("#useOrgName").yxselect_dept({
		hiddenId : 'useOrgId',
		event : {
			'selectReturn' : function(e, data) {
				//������Ŀ��Ϣ
				$('.hasUseTr').show();
				selectDept(data.val);
				$('.userMan input').val("");
				$("#userName").yxselect_user("remove");
				$("#userName").yxselect_user({
					hiddenId : 'userId',
					deptIds : data.val
				});
			},
			'clearReturn' : function(e) {
				$('.hasUseTr').hide();
				$("#userName").yxselect_user("remove");
				$("#userName").yxselect_user({
					hiddenId : 'userId',
					isGetDept : [true, "useOrgId", "useOrgName"]
				});
			}
		}
	});
	$("#userName").yxselect_user({
		hiddenId : 'userId',
		isGetDept : [true, "useOrgId", "useOrgName"]
	});
	//�������ţ�������
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
	if(!first){
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
}

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
		setMoney('monthlyDepreciation',0)
		return;
	}
	if( netValue != '' && salvage!='' && estimateDay!='' ){
		var monthlyDepreciation = (netValue*1-salvage*1)/(estimateDay-alreadyDay)
		setMoney('monthlyDepreciation',monthlyDepreciation)
	}
}