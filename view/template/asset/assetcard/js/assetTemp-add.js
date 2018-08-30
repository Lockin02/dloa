function checkRepeat() {
	return true;
}
/**
 * 验证信息
 */
function validateForm() {
	validate({
		"number" : {
			custom : ['numberA']
		},
		"assetName" : {
			required : true
		},
		"wirteDate" : {
			custom : ['date']
		},
		"buyDate" : {
			custom : ['date']
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
		"alreadyDay" : {
			required : true,
			custom : ['percentageNum']
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
		"assetTypeId" : {
			required : true
		},
		"useStatusCode" : {
			required : true
		},
		"assetSource" : {
			required : true
		},
		"changeTypeCode" : {
			required : true
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
		"isPro" : {
			required : true
		},
		"agencyName" : {
			required : true
		},
		"property" : {
			required : true
		},
		"machineCode" : {
			required : true
		}
	});
}
$(function() {
	//渲染邮件
    $("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
    $("#mailMan").yxselect_user({
		hiddenId : 'mailManId'
	})	
	//验证信息
	validateForm();   
	//是否关联物料
	$('.isPro').hide();
	$('#isPro').change(function(){
		if($(this).val()==0){
			$('.isPro').hide();
			$('#productName').removeClass("validate[required]")
		}else{
			$('.isPro').show();
			$('#productName').addClass("validate[required]")
		}
	});
	//当资产名称为手机时，才显示手机频段、手机网络
	if($("#assetName").val() == "手机"){
		$("#mobileBand").parents("tr:first").show();
		$('#mobileBand').addClass("validate[required]");
		$('#mobileNetwork').addClass("validate[required]");
	}
/********************下拉组件********************/
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
				$('.userMan input').val("");
				$("#userName").yxselect_user("remove");
				$("#userName").yxselect_user({
					hiddenId : 'userId',
					deptIds : data.val
				});
			},
			'clearReturn' : function(e){
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
	//物料转资产时，自动带出物料信息，且不允许修改
	if($("#requireinId").val() != ""){
		$("#isPro").val("1").attr("disabled","disabled");
		$("#productName").yxcombogrid_product("remove").removeClass("txt").addClass("readOnlyTxtNormal");
	}
});
function selectDept(deptId) {
	$('.hasUseTr input').val("");
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
//验证日期合法性
 function checkDate(){
 	var buyDate = $('#buyDate').val();
	if( buyDate != '' ){
		var MonthFirstDayObj=new Date((new Date).getFullYear(),(new Date).getMonth(),1);
		var MonthFirstDay=formatDate(MonthFirstDayObj);
		if( buyDate < MonthFirstDay ){
			alert( "购置日期不能小于本月第一天！" )
			$("#buyDate").val("");
		}else if( buyDate > formatDate(new Date) ){
			alert( "购置日期不能大于当天" )
			$("#buyDate").val("");
		}
	}
 }

//选择资产类型时带出相关信息
function getTypeRate(typeId){
	var id = $(typeId).val();
	$.ajax({
		type : 'POST',
		url : '?model=asset_basic_directory&action=getRate',
		data : {
			id : id
		},
	    async: false,
		success : function(data) {
			var dataArr = eval("(" + data + ")");
			$('#estimateDay').val(dataArr.limitYears*12)
			$('#assetTypeRate').val(dataArr.salvage)
			setSalvage()
			return false;
		}
	});
}
function setSalvage(){
	if( $('#assetTypeRate').val()!='' ){
		var assetTypeRate = $('#assetTypeRate').val()*0.01;
		if( $('origina').val() !='' ){
			setMoney('salvage',accMul($('#origina').val(),assetTypeRate))
		}
	}
}

//是否发送邮件
function ismailFun(){
	if( document.getElementsByName("assetTemp[ismail]")[1].checked ){
		$('#mailTr').hide();
	}else{
		$('#mailTr').show();
	}
}

//审核确认
function confirmAudit() {
	if (confirm("你确定要生成卡片吗?")) {
		//物料转资产时，验证数量合法性
		var maxNum = $("#maxNum").val();
		if($("#requireinId").val() != ""){
			if(accSub($("#number").val(),maxNum) > 0){
				alert("生成卡片的数量不能大于" + maxNum);
				return false;
			}
		}
		//验证机器码个数与数量是否一致
		var machineCodeArr = $("#machineCode").val().split(",");
		var machineCodeNum = machineCodeArr.length;
		var temp = machineCodeArr.sort();//对机器码排序
		if(machineCodeNum != $("#number").val()){
			alert("机器码个数与数量不一致！若有多个机器码请用英文逗号隔开，如【M1,M2】");
			return false;
		}else{
			for(var i = 0;i < machineCodeNum ; i++){
				if(temp[i] == ""){
					alert("请不要输入空的机器码");
					return false;
				}else if (temp[i] == temp[i+1]){
					alert("存在重复的机器码："+temp[i]);
					return false;
				}
			}
		}
		$("#form1").attr("action",
				"?model=asset_assetcard_assetTemp&action=add&actType=submit");
		$("#form1").submit();
	} else {
		return false;
	}
}

//资产类别获取
function getTypeName(){	
    var $m = $("#assetTypeId").children('option:selected').text();
    $('#assetTypeName').val($m);
 }

//资产来源获取
function getAssetSource(){
    var $s = $("#assetSource").children('option:selected').text();
    $('#assetSourceName').val($s);
}
