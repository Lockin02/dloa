function checkRepeat() {
	return true;
}

/**
 * ��֤��Ϣ
 */
function validateForm() {
	validate({
//		"assetabbrev" : {
//			custom : ['noSpecialCaracters']
//		},
		"number" : {
			custom : ['onlyNumber']
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
		"belongMan" : {
			required : true
		},
		"isPro" : {
			required : true
		},
		"agencyName" : {
			required : true
		}
	});
}
function selectDept(deptId) {
	$('.hasUseTr input').val("");
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
$(function() {
	/**
	 * ��֤��Ϣ
	 */
	validateForm();

	$('.usingTr').hide();
	$('#useStatusCode').change(function(){
		if($('#useStatusCode').val() == 'SYZT-XZ'){
			$('.usingTr').hide();
			$('.hasUseTr').hide();
			$('.useOrg').val("");
			$('.hasUseTr input').val("");
			document.getElementsByName('hasUse')[0].checked=true;
		}else{
			$('.usingTr').show();
		}
	})

	$('.hasUseTr').hide();


	$('.hasUse').change(function() {
		alert("*")
		var hasUse = document.getElementsByName('hasUse');
		for (var i = 0; i < hasUse.length; i++) {
			if (hasUse[i].checked && hasUse[i].value == 1) {
				if($('#useOrgId').val()==''){
					alert('����ѡ����!');
					document.getElementsByName('hasUse')[0].checked=true;
					break;
				}
				$('.hasUseTr').show();
			} else {
				$('.hasUseTr').hide();
				$('.hasUseTr input').val("");
			}
		}
	})

	$('.hasUse').select(function() {
		alert("*")
		var hasUse = document.getElementsByName('hasUse');
		for (var i = 0; i < hasUse.length; i++) {
			if (hasUse[i].checked && hasUse[i].value == 1) {
				if($('#useOrgId').val()==''){
					alert('����ѡ����!');
					document.getElementsByName('hasUse')[0].checked=true;
					break;
				}
				$('.hasUseTr').show();
			} else {
				$('.hasUseTr').hide();
				$('.hasUseTr input').val("");
			}
		}
	})

});


 function checkDate(){
 	var buyDate = $('#buyDate').val();
	if( buyDate != '' ){
		var MonthFirstDayObj=new Date((new Date).getFullYear(),(new Date).getMonth(),1);
		var MonthFirstDay=formatDate(MonthFirstDayObj);
		if( buyDate < MonthFirstDay ){
			alert( "�������ڲ���С�ڱ��µ�һ�죡" )
			$("#buyDate").val("");
		}else if( buyDate > formatDate(new Date) ){
			alert( "�������ڲ��ܴ��ڵ���" )
			$("#buyDate").val("");
		}
	}
 }

$(function(){
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
});


$(document).ready(function() {
	setSalvage()//���þ���ֵ
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
//			,event : {
//				'row_dblclick' : function(e, row, data) {
//					$('#agencyCode').val(data.agencyCode);
//				}
//			}
		}
	});
	$("#useOrgName").yxselect_dept({
		hiddenId : 'useOrgId',
		event : {
			'selectReturn' : function(e, data) {
				selectDept(data.val)
			},
			'clearReturn' : function(e){
				$('.hasUseTr').hide();
				$('.hasUseTr input').val("");
				document.getElementsByName('hasUse')[0].checked=true;
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
//ѡ���ʲ�����ʱ���������Ϣ
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

//ѡ���ʲ�����ʱ���������Ϣ
function getTypeRateByVal(typeId){
	$.ajax({
		type : 'POST',
		url : '?model=asset_basic_directory&action=getRate',
		data : {
			id : typeId
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
		alert(estimateDay)
		setMoney('monthlyDepreciation',0)
		return;
	}
	if( netValue != '' && salvage!='' && estimateDay!='' ){
		var monthlyDepreciation = (netValue*1-salvage*1)/(estimateDay-alreadyDay)
		setMoney('monthlyDepreciation',monthlyDepreciation)
	}
}