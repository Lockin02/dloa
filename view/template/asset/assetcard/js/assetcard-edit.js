function checkRepeat() {
	return true;
}

/**
 * 验证信息
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
	//验证信息
	validateForm();
	//使用状态联动
	if ($('#useStatusCode').val() == 'SYZT-XZ') {
		$('.usingTr').hide();
		$('.hasUseTr').hide();
		$('.hasUseTr input').val("");
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
	//初始化项目
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
	//当资产名称为手机时，才显示手机频段、手机网络
	if($("#assetName").val() == "手机"){	//初始化验证
		$("#mobileBand").parents("tr:first").show();
		$('#mobileBand').addClass("validate[required]");
		$('#mobileNetwork').addClass("validate[required]");
	}
	$("#assetName").change(function(){//手动输入时验证
		if($(this).val() == "手机"){
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
	//资产变动时，判断操作员是否可以对行政区域进行变动
	if($("#agencyLimit").val() === '0'){
		$("#agencyName").parents("tr:first").hide();
	}
/********************下拉组件********************/
	//供应商
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		gridOptions : {
			showcheckbox : false
		}
	});
	//办事处
	$("#agencyName").yxcombogrid_agency({
		hiddenId : 'agencyCode',
		gridOptions : {
			showcheckbox : false
		}
	});
	//资产名称
	$("#assetName").yxcombogrid_assetinfo({
		nameCol : 'assetName',
		gridOptions : {
			action : 'comboAssetInfoJson&searchType=assetName',
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					var assetName = data.assetName;
					$('#assetName').val(assetName);
					//当资产名称为手机时，才显示手机频段、手机网络
					if(assetName == "手机"){
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
	//手机频段
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
			// 快速搜索
			searchitems : [{
					display : '手机频段',
					name : 'mobileBand'
				}
			]
		}
	});
	//手机网络
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
			// 快速搜索
			searchitems : [{
					display : '手机网络',
					name : 'mobileNetwork'
				}
			]
		}
	});
	//使用部门，使用人
	$("#useOrgName").yxselect_dept({
		hiddenId : 'useOrgId',
		event : {
			'selectReturn' : function(e, data) {
				//加载项目信息
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
	//所属部门，所属人
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
}

//自动设置资产原值
function setNetValue(){
	var origina = $('#origina').val();
	var depreciation = $('#depreciation').val();
	if(origina!=''&&depreciation!=''){
		setMoney('netValue',origina*1-depreciation*1)
	}else{
		setMoney('netValue',0)
	}
}

//设置月折旧额
function setMonthlyDepreciation(){
	var netValue = $('#netValue').val();//净值
	var salvage = $('#salvage').val();//残值
	var estimateDay = $('#estimateDay').val();//期间数
	var alreadyDay = $('#alreadyDay').val();//已使用期间数
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