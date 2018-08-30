function checkRepeat() {
	return true;
}
/**
 * ��֤��Ϣ
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
	//��Ⱦ�ʼ�
    $("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
    $("#mailMan").yxselect_user({
		hiddenId : 'mailManId'
	})	
	//��֤��Ϣ
	validateForm();   
	//�Ƿ��������
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
	//���ʲ�����Ϊ�ֻ�ʱ������ʾ�ֻ�Ƶ�Ρ��ֻ�����
	if($("#assetName").val() == "�ֻ�"){
		$("#mobileBand").parents("tr:first").show();
		$('#mobileBand').addClass("validate[required]");
		$('#mobileNetwork').addClass("validate[required]");
	}
/********************�������********************/
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
	//����ת�ʲ�ʱ���Զ�����������Ϣ���Ҳ������޸�
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
//��֤���ںϷ���
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

//�Ƿ����ʼ�
function ismailFun(){
	if( document.getElementsByName("assetTemp[ismail]")[1].checked ){
		$('#mailTr').hide();
	}else{
		$('#mailTr').show();
	}
}

//���ȷ��
function confirmAudit() {
	if (confirm("��ȷ��Ҫ���ɿ�Ƭ��?")) {
		//����ת�ʲ�ʱ����֤�����Ϸ���
		var maxNum = $("#maxNum").val();
		if($("#requireinId").val() != ""){
			if(accSub($("#number").val(),maxNum) > 0){
				alert("���ɿ�Ƭ���������ܴ���" + maxNum);
				return false;
			}
		}
		//��֤����������������Ƿ�һ��
		var machineCodeArr = $("#machineCode").val().split(",");
		var machineCodeNum = machineCodeArr.length;
		var temp = machineCodeArr.sort();//�Ի���������
		if(machineCodeNum != $("#number").val()){
			alert("�����������������һ�£����ж������������Ӣ�Ķ��Ÿ������硾M1,M2��");
			return false;
		}else{
			for(var i = 0;i < machineCodeNum ; i++){
				if(temp[i] == ""){
					alert("�벻Ҫ����յĻ�����");
					return false;
				}else if (temp[i] == temp[i+1]){
					alert("�����ظ��Ļ����룺"+temp[i]);
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

//�ʲ�����ȡ
function getTypeName(){	
    var $m = $("#assetTypeId").children('option:selected').text();
    $('#assetTypeName').val($m);
 }

//�ʲ���Դ��ȡ
function getAssetSource(){
    var $s = $("#assetSource").children('option:selected').text();
    $('#assetSourceName').val($s);
}
