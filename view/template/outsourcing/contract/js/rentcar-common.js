var carRentCostTypesData = [];
var sltcarRentCostTypesArr = [];
var signCompanyCache = [];
var maxPayInfoNum = 2;
$(document).ready(function() {
	carRentCostTypesData = [];
	sltcarRentCostTypesArr = [];
	signCompanyCache = [];

	// ����й�Ӧ��ID���ȡ��Ӧ������
	var signCompanyId = $("#signCompanyId").val();
	if(signCompanyId != ''){
		var signCompanyArr = $.ajax({
			type : "POST",
			url : "?model=outsourcing_outsourcessupp_vehiclesupp&action=pageJson",
			data : {
				id : signCompanyId
			},
			async : false
		}).responseText;
		signCompanyArr = eval("("+signCompanyArr+")");
		if(signCompanyArr.collection.length > 0){
			signCompanyCache = signCompanyArr.collection[0];
		}
	}

	//��ͬ���ʸı��¼�
	$("#contractNatureCode").change(function (){
		var contractNatureName = $("#contractNatureCode").find("option:selected").text();

		if($("#contractNature").val() != contractNatureName){
			if($("#payInfoAct").val() == 'add'){
				$("#contractNature").val(contractNatureName);
				$("#payInfoWrap table tbody").html("");
				$("#payInfoCul").val(0);
				$("#payInfoDelCul").val(0);
			}else if($("#payInfoAct").val() == 'edit'){// �༭��ʱ������ɾ��
				var currentRowNum = ($("#payInfoCul").val() != '')? Number($("#payInfoCul").val()) : 0;
				var currentDelNum = ($("#payInfoDelCul").val() != '')? Number($("#payInfoDelCul").val()) : 0;
				for(var i = 1;i <= currentRowNum;i++){
					if($("#isDel"+i).val() != 1){
						$("#removePayItemBtn"+i).append("<input type='hidden' id='isDel"+i+"' name='rentcar[payInfo]["+i+"][isDel]' value='1'/>");
						$(".payForm"+i).hide();
						currentDelNum += 1;
					}
				}
				$("#payInfoDelCul").val(currentDelNum);
			}
		}

		if($(this).val() == 'ZCHTXZ-01') {
			$("#fundCondition").addClass("validate[required]");
			$("#fundConditionSpan").css("color" ,"blue");
		} else {
			$("#fundCondition").removeClass("validate[required]");
			$("#fundConditionSpan").css("color" ,"");
		}
	});

	//��ͬ������
	$("#principalName").yxselect_user({
		hiddenId : 'principalId',
		isGetDept : [true, "deptId", "deptName"]
	});

	//������˾
	$("#ownCompany").yxcombogrid_branch({
		hiddenId : 'ownCompany',
		height : 200,
		width : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#ownCompanyId").val(data.id);
					$("#ownCompanyCode").val(data.NamePT);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#ownCompanyId").val("");
				$("#ownCompanyCode").val("");
			}
		}
	});

	//�⳵���뵥��
	$("#rentalcarCode").yxcombogrid_rentalcar({
		hiddenId : 'rentalcarId',
		height : 260,
		isFocusoutCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#signCompany").next().trigger("click");
					$("#signCompany").yxcombogrid_vehiclesupp("remove");
					var suppIds = $.ajax({
						type : "POST",
						url : "?model=outsourcing_vehicle_rentalcarequ&action=getSuppByParent",
						data : {
							parentId : data.id
						},
						async : false
					}).responseText;
					$("#suppIds").val(suppIds);
					setSignCompany();
					if ($("#isUseOilcard").val() == data.isApplyOilCard) {
						$("#isUseOilcard").attr("checked" ,"checked").trigger("change");
					} else {
						$("#isUseOilcard").next().attr("checked" ,"checked").trigger("change");
					}

					//������Ŀ��Ϣ
					$("#projectId").val(data.projectId);
					$("#projectCode").val(data.projectCode);
					$("#projectName").val(data.projectName);
					$("#projectType").val(data.projectType);
					$("#projectTypeCode").val(data.projectTypeCode);
					$("#projectManager").val(data.projectManager);
					$("#projectManagerId").val(data.projectManagerId);
					$("#officeId").val(data.officeId);
					$("#officeName").val(data.officeName);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#signCompany").next().trigger("click");
				$("#signCompany").yxcombogrid_vehiclesupp("remove");
				$("#suppIds").val("");
				setSignCompany();
			}
		}
	});

	//��Ŀ����
	$("#projectName").yxcombogrid_esmproject({
		isDown : false,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		height : 250,
		isFocusoutCheck : true,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'statusArr':'GCXMZT01,GCXMZT02'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectId").val(data.id);
					$("#projectCode").val(data.projectCode);
					$("#officeId").val(data.officeId);
					$("#officeName").val(data.officeName);
					$("#projectType").val(data.natureName);
					$("#projectTypeCode").val(data.nature);
					$("#projectManager").val(data.managerName);
					$("#projectManagerId").val(data.managerId);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectId").val("");
				$("#projectCode").val("");
				$("#officeId").val("");
				$("#officeName").val("");
				$("#projectType").val("");
				$("#projectTypeCode").val("");
				$("#projectManager").val("");
				$("#projectManagerId").val("");
			}
		}
	});

	//��ͬ���͸ı��¼�
	$("#contractTypeCode").change(function (){
		if ($(this).val() == 'ZCHTLX-01') {
			$("#fuelChargeTd1").hide();
			$("#fuelChargeTd2").hide();
			$("#fuelCharge_v").removeClass("validate[required]").val("");
			$("#fuelCharge").val("");
			$("#oilPriceTd1").show();
			$("#oilPriceTd2").show();
			$("#oilPrice_v").addClass("validate[required]");
		} else if ($(this).val() == 'ZCHTLX-02') {
			$("#oilPriceTd1").hide();
			$("#oilPriceTd2").hide();
			$("#oilPrice_v").removeClass("validate[required]").val("");
			$("#oilPrice").val("");
			$("#fuelChargeTd1").show();
			$("#fuelChargeTd2").show();
			$("#fuelCharge_v").addClass("validate[required]");
		} else {
			$("#oilPriceTd1").hide();
			$("#oilPriceTd2").hide();
			$("#oilPrice_v").removeClass("validate[required]").val("");
			$("#oilPrice").val("");
			$("#fuelChargeTd1").hide();
			$("#fuelChargeTd2").hide();
			$("#fuelCharge_v").removeClass("validate[required]").val("");
			$("#fuelCharge").val("");
		}
	});

	$("#signCompany").dblclick(function (){
		if (!$("#rentalcarCode") && !$("#rentalcarCode").val()) {
			alert("����ѡ���⳵���뵥�ţ�");
		}
	});

	//�Ƿ����
	$("input[name=rentcar[isNeedStamp]]:radio").change(function () {
		if ($(this).val() == 1) {
			//����������֤
			if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "�����κθ���"){
				alert('�������ǰ��Ҫ�ϴ���ͬ����!');
				$(this).next(":radio").attr("checked" ,true);
				return false;
			}
			setStampType();
		} else {
			$("#stampType").yxcombogrid_stampconfig("remove").removeClass("validate[required]").val("");
			$("#stampTypeSpan").css("color" ,"");
		}
	});

	//�����ͬ����
	$("#contractStartDate").blur(function () {
		if ($(this).val()) {
			getUseDay();
		} else{
			$("#contractUseDay").val(0);
		}
	});
	$("#contractEndDate").blur(function () {
		if ($(this).val()) {
			getUseDay();
		} else{
			$("#contractUseDay").val(0);
		}
	});

	//�Ƿ�ʹ���Ϳ�
	$("input[name=rentcar[isUseOilcard]]:radio").change(function () {
		if ($(this).val() == 1) {
			$("#oilcardMoneyTd1").show();
			$("#oilcardMoneyTd2").show();
			$("#oilcardMoney_v").addClass("validate[required]");
		} else {
			$("#oilcardMoneyTd1").hide();
			$("#oilcardMoneyTd2").hide();
			$("#oilcardMoney_v").removeClass("validate[required]").val("");
		}
	});

	validate({
		"contractNatureCode" : {
			required : true
		},
		"contractTypeCode" : {
			required : true
		},
		"projectName" : {
			required : true
		},
		"orderName" : {
			required : true
		},
		"principalName" : {
			required : true
		},
		"signCompany" : {
			required : true
		},
		"companyProvinceCode" : {
			required : true
		},
		"orderMoney_v" : {
			required : true
		},
		"companyCityCode" : {
			required : true
		},
		"linkman" : {
			required : true
		},
		"isNeedStamp" : {
			required : true
		},
		"phone" : {
			required : true
		},
		"ownCompany" : {
			required : true
		},
		"rentUnitPrice_v" : {
			required : true
		},
		"contractStartDate" : {
			required : true
		},
		"contractEndDate" : {
			required : true
		},
		"signDate" : {
			required : true
		},
		"payBankName" : {
			required : true
		},
		"payBankNum" : {
			required : true
		},
		"payMan" : {
			required : true
		},
		"payConditions" : {
			required : true
		},
		"payTypeCode" : {
			required : true
		},
		"payApplyMan" : {
			required : true
		},
        "rentalcarCode" : {
            required : true
        }
	});

	// ����������Ϣ
	$("#addPayInfoFormBtn").click(function(){
		var contractNatureCode = $("#contractNatureCode").val();
		if(contractNatureCode == ''){
			alert("����ѡ�к�ͬ����!");
			$("#contractNatureCode").focus();
		}else{
			var currentAddNum = ($("#payInfoCul").val() == '' && Number($("#payInfoCul").val()) != 'NAN')? 0 : Number($("#payInfoCul").val());
			var currentDelNum = ($("#payInfoDelCul").val() == '')? 0 : Number($("#payInfoDelCul").val());
			var currentFormNum = currentAddNum - currentDelNum;

			if(currentFormNum < maxPayInfoNum){
				var newFormNum = currentAddNum+1;
				var newformStr = buildPayInFoFprm(newFormNum,contractNatureCode,currentFormNum);
				$("#payInfoCul").val(newFormNum);
				$("#payInfoWrap table tbody").append(newformStr);
				$('#carRentCostTypesCombobox'+newFormNum).combobox({
					onChange: function(e) {
						var chkArr = [];
						var nameArr = [];
						sltcarRentCostTypesArr[newFormNum] = [];
						var value = $('#carRentCostTypesCombobox'+newFormNum).combobox('getValues');
						$.each($(".combo-value"),function(index,valueObj){
							chkArr.push($(valueObj).val());
						});

						var pass = false;
						$.each(value,function(i,item){
							if(item != ''){
								sltcarRentCostTypesArr[newFormNum].push(item);
								nameArr.push(carRentCostTypesData[item]);
							}
						});

						var includeFeeTypeCode = (sltcarRentCostTypesArr[newFormNum].length <= 0)? '' : sltcarRentCostTypesArr[newFormNum].join(",");
						var includeFeeType = (nameArr.length <= 0)? '' : nameArr.join(",");
						$("#includeFeeTypeCode"+newFormNum).val(includeFeeTypeCode);
						$("#includeFeeType"+newFormNum).val(includeFeeType);
						if(includeFeeType == ''){
							$("#includeFeeTypeCode"+newFormNum).val('');
							$("#includeFeeTypeCode"+newFormNum).prev('span').children(".combo-value").remove();
							$("#includeFeeTypeCode"+newFormNum).prev('span').children(".combo-text").val('');
						}

						if(!chkDuplFeeType()){
							$(".combo-p").each(function (i,item){
								if($(item).css("display") != "none"){
									$(item).children(".combo-panel").children(".combobox-item").each(function(childI,childItme){
										if($(childItme).text() == nameArr[nameArr.length-1]){// ������ѡ��
											$(childItme).removeClass("combobox-item-selected")
										}
									});
								}
							});

							// �������ոյ��е�ѡ��
							setTimeout(function(){
								var lastType = sltcarRentCostTypesArr[newFormNum].pop();
								nameArr.pop();
								$("#includeFeeType"+newFormNum).val(nameArr.join(","));
								$("#includeFeeTypeCode"+newFormNum).val(sltcarRentCostTypesArr[newFormNum].join(","));
								$("#includeFeeTypeCode"+newFormNum).prev('span').children(".combo-text").val(nameArr.join(","));
								$("#includeFeeTypeCode"+newFormNum).prev('span').children(".combo-value").each(function (i,item) {
									if($(item).val() == lastType){
										$(item).remove();
									}
								});
							},60);
						};
					}
				});

				// ��ʼ��ѡ��
				if(currentFormNum == 0){// ����ǵ�һ��֧����ʽ,Ĭ��ȫѡ
					var opts = [];
					$.each(carRentCostTypesData,function(key,val){
						if($("#contractNatureCode").val() == 'ZCHTXZ-01'){
							if(key != 'rentalCarCost'){
								opts.push(key);
							}
						}else{
							opts.push(key);
						}
					});

					$('#carRentCostTypesCombobox'+newFormNum).combobox('setValues',opts);
				}else{
					$('#carRentCostTypesCombobox'+newFormNum).combobox('setValues','');
					$('#carRentCostTypesCombobox'+newFormNum).combobox('clear');
				}

				$("#includeFeeTypeCode"+newFormNum).prev('span').children(".combo-text").attr('readonly','true');

				// ֧����ʽѡ��仯�����¼�
				$("#payTypeSlt"+newFormNum).change(function(){
					// $('#carRentCostTypesCombobox'+newFormNum).combobox('clear');

					$("#bankName"+newFormNum).val('');
					$("#bankAccount"+newFormNum).val('');
					$("#bankReceiver"+newFormNum).val('');
					$("#bankInfoId"+newFormNum).val('');
					switch($(this).val()){
						// ������������
						case 'BXFQR':
							$("#payType"+newFormNum).val("������������");
							$("#bankName"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
							$("#bankAccount"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
							$("#bankReceiver"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
							break;
						// ������˾��
						case 'BXFSJ':
							$("#payType"+newFormNum).val("������˾��");
							$("#bankName"+newFormNum).attr("class","txt").removeAttr("readonly");
							$("#bankAccount"+newFormNum).attr("class","txt").removeAttr("readonly");
							$("#bankReceiver"+newFormNum).attr("class","txt").removeAttr("readonly");
							break;
						// ��ͬ����
						case 'HETFK':
							$("#payType"+newFormNum).val("��ͬ����");
							$("#bankInfoId"+newFormNum).val(signCompanyCache.id);
							$("#bankName"+newFormNum).val(signCompanyCache.bankName);
							$("#bankAccount"+newFormNum).val(signCompanyCache.bankAccount);
							$("#bankReceiver"+newFormNum).val(signCompanyCache.bankReceiver);
							$("#bankName"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
							$("#bankAccount"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
							$("#bankReceiver"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
							break;
					}
				});
			}
		}

		// ����Ƴ�����
		listenPayInfoRemove();
	});

	// ������Ϣ��ʼ�����༭ҳ�棩
	if(Number($("#payInfoCul").val()) > 0 && ($("#payInfoAct").val() == 'edit' || $("#payInfoAct").val() == 'change')){
		// �⳵ϵͳ��Ŀ
		var carRentCostTypes =  $.ajax({
			type : 'GET',
			url : '?model=system_configurator_configurator&action=getCarRentCostTypes',
			data : {'datatype' : 'options'},
			async: false,
			success : function(data){
				return data;
			}
		}).responseText;
		var sltcarRentCostTypesArr = [];
		var carRentCostTypesObj = eval("("+carRentCostTypes+")");
		carRentCostTypesData = carRentCostTypesObj.data.dataArr;
		for(var i = 1;i<=$("#payInfoCul").val();i++){

			listenFeeTypeChange($("#payTypeSlt"+i));

			// ����������
			if($("#includeFeeTypeCode"+i).val() != undefined && $("#isDel"+i).val() != 1){
				setCarRentCostTypesCombobox($("#includeFeeTypeCode"+i));
				// var feeTypeSltArr = $("#includeFeeTypeCode"+i).val().split(",");
				// console.log(feeTypeSltArr);
				//
			}
		}
	}

	// ����Ƴ�����
	listenPayInfoRemove();
});

var setCarRentCostTypesCombobox = function(obj){
	var feeTypeSltArr = obj.val().split(",");
	var indexVal = obj.attr("data-index");
	$('#carRentCostTypesCombobox'+indexVal).combobox({
		onChange: function(e) {
			var chkArr = [];
			var nameArr = [];
			sltcarRentCostTypesArr[indexVal] = [];
			$.each($(".combo-value"),function(index,valueObj){
				chkArr.push($(valueObj).val());
			});

			var pass = false;
			$.each(e,function(vi,item){
				if(item != ''){
					sltcarRentCostTypesArr[indexVal].push(item);
					nameArr.push(carRentCostTypesData[item]);
				}
			});

			var includeFeeTypeCode = (sltcarRentCostTypesArr[indexVal].length <= 0)? '' : sltcarRentCostTypesArr[indexVal].join(",");
			var includeFeeType = (nameArr.length <= 0)? '' : nameArr.join(",");
			$("#includeFeeTypeCode"+indexVal).val(includeFeeTypeCode);
			$("#includeFeeType"+indexVal).val(includeFeeType);
			if(includeFeeType == ''){
				$("#includeFeeTypeCode"+indexVal).val('');
				$("#includeFeeTypeCode"+indexVal).prev('span').children(".combo-value").remove();
				$("#includeFeeTypeCode"+indexVal).prev('span').children(".combo-text").val('');
			}

			if(!chkDuplFeeType()){
				$(".combo-p").each(function (i,item){
					if($(item).css("display") != "none"){
						$(item).children(".combo-panel").children(".combobox-item").each(function(childI,childItme){
							if($(childItme).text() == nameArr[nameArr.length-1]){// ������ѡ��
								$(childItme).removeClass("combobox-item-selected")
							}
						});
					}
				});

				// �������ոյ��е�ѡ��
				setTimeout(function(){
					var lastType = sltcarRentCostTypesArr[indexVal].pop();
					nameArr.pop();
					$("#includeFeeType"+indexVal).val(nameArr.join(","));
					$("#includeFeeTypeCode"+indexVal).val(sltcarRentCostTypesArr[indexVal].join(","));
					$("#includeFeeTypeCode"+indexVal).prev('span').children(".combo-text").val(nameArr.join(","));
					$("#includeFeeTypeCode"+indexVal).prev('span').children(".combo-value").each(function (i,item) {
						if($(item).val() == lastType){
							$(item).remove();
						}
					});
				},60);
			};

		}
	});
	var catchArr = [];
	$.each(feeTypeSltArr,function(key,val){
		if($("#contractNatureCode").val() == 'ZCHTXZ-01'){
			if(val != 'rentalCarCost'){
				catchArr.push(val);
			}
		}else{
			catchArr.push(val);
		}
	});
	feeTypeSltArr = catchArr;
	console.log(feeTypeSltArr);
	$('#carRentCostTypesCombobox'+indexVal).combobox('setValues',feeTypeSltArr);
}

/**
 * �����Ƴ�֧����ʽ����¼�
 * ɾ���߼�����:
 * 	1.�Ƚ��¼ӵ������ص�,�����isDel�ֶ���Ϣ;
 * 	2.����ɾ������,������ʱͨ����ǰҳ����������������ȥ��ǰҳ���������˵����������ж��Ƿ񳬳���������
 * 	3.û��������Ԫ��ID��������ֶ���Ψһ��,�����滻����
 * 	4.�ύ����Ժ�isDel����������Ӧ�Ĵ�����������Ե�,�༭���������isDel�ֶ���Ϣ��
 */
var listenPayInfoRemove = function(){
	$(".removePayItemBtn").unbind('click');
	$(".removePayItemBtn").bind('click',function(){
		var currentDelNum = $("#payInfoDelCul").val();
		currentDelNum = (currentDelNum != '')? Number(currentDelNum) : 0;

		// ����ɾ����
		var delRowId = $(this).attr("data-index");
		$(".payForm"+delRowId).hide();
		var indexNum = 1;
		$(this).append("<input type='hidden' id='isDel"+delRowId+"' name='rentcar[payInfo]["+delRowId+"][isDel]' value='1'/>");
		$.each($('.payTypeNum'),function(){
			if($(this).parent("span").parent("td").parent("tr").css("display") != 'none'){
				$(this).text(indexNum);
				indexNum += 1;
			}
		});

		// ����ɾ������
		currentDelNum += 1;
		$("#payInfoDelCul").val(currentDelNum);
	});

	$(".includeFeeTypeWrap").children("span").children("input").attr("readonly",1);
};

// ����֧����ʽѡ��仯�¼�
var listenFeeTypeChange = function(obj){
	var newFormNum = $(obj).attr("data-index");
	$(obj).change(function(){
		// $('#carRentCostTypesCombobox'+newFormNum).combobox('clear');

		// ֧����ʽѡ��仯�����¼�
		$("#bankName"+newFormNum).val('');
		$("#bankAccount"+newFormNum).val('');
		$("#bankReceiver"+newFormNum).val('');
		$("#bankInfoId"+newFormNum).val('');
		switch($(this).val()){
			// ������������
			case 'BXFQR':
				$("#payType"+newFormNum).val("������������");
				$("#bankName"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
				$("#bankAccount"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
				$("#bankReceiver"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
				break;
			// ������˾��
			case 'BXFSJ':
				$("#payType"+newFormNum).val("������˾��");
				$("#bankName"+newFormNum).attr("class","txt").removeAttr("readonly");
				$("#bankAccount"+newFormNum).attr("class","txt").removeAttr("readonly");
				$("#bankReceiver"+newFormNum).attr("class","txt").removeAttr("readonly");
				break;
			// ��ͬ����
			case 'HETFK':
				$("#payType"+newFormNum).val("��ͬ����");
				$("#bankInfoId"+newFormNum).val(signCompanyCache.id);
				$("#bankName"+newFormNum).val(signCompanyCache.bankName);
				$("#bankAccount"+newFormNum).val(signCompanyCache.bankAccount);
				$("#bankReceiver"+newFormNum).val(signCompanyCache.bankReceiver);
				$("#bankName"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
				$("#bankAccount"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
				$("#bankReceiver"+newFormNum).attr("class","readOnlyTxtNormal").attr("readonly","true");
				break;
		}
	})
};

// ���ɸ�����Ϣ��
var buildPayInFoFprm = function(newFormNum,contractNatureCode,currentFormNum) {
	// �⳵ϵͳ��Ŀ
	var carRentCostTypes =  $.ajax({
		type : 'GET',
		url : '?model=system_configurator_configurator&action=getCarRentCostTypes',
		data : {'datatype' : 'options'},
		async: false,
		success : function(data){
			return data;
		}
	}).responseText;
	var sltcarRentCostTypesArr = [];
	var carRentCostTypesObj = eval("("+carRentCostTypes+")");
	carRentCostTypesData = carRentCostTypesObj.data.dataArr;
	var carRentCostTypesOpts = (carRentCostTypesObj.msg == 'ok')? carRentCostTypesObj.data.options : '';
	var carRentCostTypesSlts = '<div id="carRentCostTypesOpts'+newFormNum+'"></div><select id="carRentCostTypesCombobox'+newFormNum+'" data-options="multiple:true" class="esayui-combobox" style="width:250px;">'+carRentCostTypesOpts+'</select></div>';

	var payTypeOpts = (contractNatureCode == "ZCHTXZ-01")? '<option value="BXFQR">������������</option><option value="BXFSJ">������˾��</option><option value="HETFK">��ͬ����</option>' : '<option value="BXFQR">������������</option><option value="BXFSJ">������˾��</option>';
	var formHtml = '<tr class="payFormTr payForm'+newFormNum+'" style="height:10px"></tr><tr class="payFormTr payForm'+newFormNum+'"  data-index="'+newFormNum+'">' +
		'<td rowspan="3"><input class="removePayItemBtn" id="removePayItemBtn'+newFormNum+'" data-index="'+newFormNum+'" type="button" value="-">' +
		'<input id="payInfoCheckbox'+newFormNum+'" style="display:none" value="'+newFormNum+'" type="checkbox"></td>' +
		'<td class="form_text_left_three"><span style="color:blue">֧����ʽ<span class="payTypeNum" id="payTypeNum'+newFormNum+'">'+(currentFormNum + 1)+'</span>��</span></td>' +
		'<td class="form_text_right_three" colspan="5">' +
		'<select name="rentcar[payInfo]['+newFormNum+'][payTypeCode]" id="payTypeSlt'+newFormNum+'">' + payTypeOpts +
		'</select><input type="hidden" class="hidden" id="payType'+newFormNum+'" name="rentcar[payInfo]['+newFormNum+'][payType]" value="������������"/>' +
		'</td>' +
		'</tr>' +
		'<tr class="payFormTr payForm'+newFormNum+'"  data-index="'+newFormNum+'">' +
		'<td class="form_text_left_three"><span style="color:blue">�տ����У�</span></td><td class="form_text_right_three">' +
		'<input type="text" class="readOnlyTxtNormal" id="bankName'+newFormNum+'" name="rentcar[payInfo]['+newFormNum+'][bankName]" title="" readonly>' +
		'<input type="hidden" class="hidden" id="bankInfoId'+newFormNum+'" name="rentcar[payInfo]['+newFormNum+'][bankInfoId]" value=""/></td>' +
		'<td class="form_text_left_three"><span style="color:blue">�տ��˺ţ�</span></td><td class="form_text_right_three"><input type="text" class="readOnlyTxtNormal" id="bankAccount'+newFormNum+'" name="rentcar[payInfo]['+newFormNum+'][bankAccount]" title="" readonly></td>' +
		'<td class="form_text_left_three"><span style="color:blue">�տ��ˣ�</span></td><td class="form_text_right_three"><input type="text" class="readOnlyTxtNormal" id="bankReceiver'+newFormNum+'" name="rentcar[payInfo]['+newFormNum+'][bankReceiver]" title="" readonly></td>' +
		'</tr>' +
		'<tr class="payFormTr payForm'+newFormNum+'"  data-index="'+newFormNum+'"><td class="form_text_left_three"><span style="color:blue">���������</span></td>' +
		'<td class="form_text_right_three includeFeeTypeWrap" colspan="5">'+carRentCostTypesSlts+'<input type="hidden" class="hidden" id="includeFeeTypeCode'+newFormNum+'" name="rentcar[payInfo]['+newFormNum+'][includeFeeTypeCode]" value=""/><input type="hidden" class="hidden" id="includeFeeType'+newFormNum+'" name="rentcar[payInfo]['+newFormNum+'][includeFeeType]" value=""/></td></tr>';
	return formHtml;
}

// ���¸�����Ϣ�ֶ�
var updatePayInfoItems = function(signCompanyId){
	var payInfoCul = ($("#payInfoCul").val() == '')? 0 : $("#payInfoCul").val();
	if(payInfoCul > 0){
		for(var i = 1;i<= payInfoCul;i++){
			if($("#payTypeSlt"+i).val() == 'HETFK'){
				if(signCompanyId == 'empty'){
					$("#bankName"+i).val('');
					$("#bankAccount"+i).val('');
					$("#bankReceiver"+i).val('');
					$("#bankInfoId"+i).val('');
					signCompanyCache = [];
				}else{
					$("#bankInfoId"+i).val(signCompanyCache.id);
					$("#bankName"+i).val(signCompanyCache.bankName);
					$("#bankAccount"+i).val(signCompanyCache.bankAccount);
					$("#bankReceiver"+i).val(signCompanyCache.bankReceiver);
				}
			}
		}
	}
}

var chkForm = function(){
	condole.log("test");
	return false;
}


//����companyProvince��ֵ��������ѡ��
function setProSelectByName() {
	$("#companyProvinceCode option").each(function () {
		if ($(this).text() == $("#companyProvince").val()) {
			$(this).attr("selected" ,"selected");
		}
	});
}

//����companyCity��ֵ��������ѡ��
function setCitySelectByName() {
	$("#companyCityCode option").each(function () {
		if ($(this).text() == $("#companyCity").val()) {
			$(this).attr("selected" ,"selected");
		}
	});
}

//ǩԼ��˾������Ⱦ
function setSignCompany() {
	var suppIds = $("#suppIds").val();
	$("#signCompany").yxcombogrid_vehiclesupp({
		hiddenId : 'signCompanyId',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			param : {
				'idArr' : suppIds
			},
			isTitle : true,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#companyProvince").val(data.province);
					setProSelectByName();
					$("#companyProvinceCode").trigger("change");
					$("#companyCity").val(data.city);
					setCitySelectByName();
					$("#linkman").val(data.linkmanName);
					$("#phone").val(data.linkmanPhone);
					$("#address").val(data.address);
					$("#payBankName").val(data.bankName);
					$("#payBankNum").val(data.bankAccount);
					$("#payMan").val(data.suppName);
					signCompanyCache = data;
					updatePayInfoItems(data.id);
				}
			}
		},
		event : {
			'clear' : function() {
				updatePayInfoItems('empty');
				$("#companyProvinceCode").val("").trigger("change");
				$("#linkman").val("");
				$("#phone").val("");
				$("#address").val("");
				$("#payBankName").val("");
				$("#payBankNum").val("");
				$("#payMan").val("");
			}
		}
	});

	var signComId = ($("#signCompanyId").val() == '')? 'empty' : $("#signCompanyId").val();
	updatePayInfoItems(signComId);
}

//��������������Ⱦ
function setStampType() {
	$("#stampType").yxcombogrid_stampconfig({
		hiddenId : 'stampType',
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : true
		}
	}).addClass("validate[required]");
	$("#stampTypeSpan").css("color" ,"blue");
}

//�����ͬ����
function getUseDay() {
	var startDateVal = $("#contractStartDate").val();
	var endDateVal = $("#contractEndDate").val();
	var startDate = new Date(Date.parse(startDateVal.replace(/-/g ,"/")));
	var endDate = new Date(Date.parse(endDateVal.replace(/-/g ,"/")));
	var useDay = (endDate.getTime() - startDate.getTime())/3600000/24;
	if (useDay > 0) {
		$("#contractUseDay").val(useDay);
	} else{
		$("#contractUseDay").val(0);
	}
}

var chkDuplFeeType = function(){
	var feeTypesCache = [];
	var dupFeeTypesCul = 0;// �ظ����ֵķ���������
	var hasRentalCarCostInPayCont = 0;// �����ͬ�д������⳵�ѵ�����
	for(var i = 1;i<= $("#payInfoCul").val();i++){
		if($("#isDel"+i).val() != 1){
			var feeTypesArr = ($("#includeFeeTypeCode"+i).val() != '')? $("#includeFeeTypeCode"+i).val().split(",") : [];
			$.each(feeTypesArr,function(index,item){
				if($.inArray(item,feeTypesCache) < 0){
					feeTypesCache.push(item);
				}else{
					// console.log(item);
					// console.log(feeTypesCache);
					dupFeeTypesCul += 1;
				}
			});
		}
	}

	if($("#contractNatureCode").val() == 'ZCHTXZ-01' && $.inArray("rentalCarCost",feeTypesCache) > 0){
		hasRentalCarCostInPayCont += 1;
	}

	var loadNum = $("#loadNum").val();
	if(dupFeeTypesCul > 0){
		alert("�ڶ���֧����ʽ�а������������ֻ��ѡһ��!");
		return false;
	}else if(hasRentalCarCostInPayCont > 0 && loadNum == 1){
		alert("��ͬ����Ϊ�������ͬ����ô�ڲ���֧����ʽʱ��������ѡ���⳵�ѡ���");
		return false;
	}else{
		return true;
	}
}

// �ύ�⳵��ͬ��
var submitForm = function(type){
	// �ȼ��ú�ͬ�Ƿ���ڹ����ķ������¼,�����Ҵ˴α���޸��˶�Ӧ��֧����ʽ�ķ�����Ļ�,���ñ���������ķ��������Ҫ����ͨ������ܽ��б��
	if(type == "change" && chkHasUndoneExpenseTmp()){
		alert("����δ��ɵķ��õ����ֲ��������ͬ��������Ƚ����õ�����á�");
		return false;
	}
	var currentRowNum = ($("#payInfoCul").val() != '')? Number($("#payInfoCul").val()) : 0;
	var currentDelNum = ($("#payInfoDelCul").val() != '')? Number($("#payInfoDelCul").val()) : 0;
	var currentChkNum = currentRowNum - currentDelNum;
	// ��鸶����Ϣ
	if(currentChkNum <= 0){
		alert("��������дһ��֧����ʽ! ���ڸ�����Ϣ������Ӻ���������");
		return false;
	}else{
		for(var i = 1;i<= $("#payInfoCul").val();i++){
			if($("#isDel"+i).val() != 1){
				if($("#payTypeSlt"+i).val() == 'HETFK' && ($("#bankName"+i).val() == '' || $("#bankAccount"+i).val() == '' || $("#bankReceiver"+i).val() == '')){
					alert("'��ͬ����'�������տ���Ϣ������!");
					return false;
				}else if($("#payTypeSlt"+i).val() == 'BXFSJ'){
					if($("#bankName"+i).val() == '' || $("#bankAccount"+i).val() == '' || $("#bankReceiver"+i).val() == ''){// ������Ϣ
						alert("����д'������˾��'�����������տ���Ϣ!");
						return false;
					}else if($("#includeFeeTypeCode"+i).val() == '' || $("#includeFeeType"+i).val() == ''){// ����������
						alert("�����������Ϊ��!");
						return false;
					}
				}else if($("#includeFeeTypeCode"+i).val() == '' || $("#includeFeeType"+i).val() == ''){// ����������
					alert("�����������Ϊ��!");
					return false;
				}
			}
		}
		if(!chkDuplFeeType()){
			return false;
		};
	}

	if(type != '' && type != undefined){// �ύ
		switch(type){
			case 'addSave':
				document.getElementById('form1').action="?model=outsourcing_contract_rentcar&action=add";
				$('#form1').submit();
				break;
			case 'addAudit':
				document.getElementById('form1').action="?model=outsourcing_contract_rentcar&action=add&actType=actType";
				$('#form1').submit();
				break;
			case 'editSave':
				document.getElementById('form1').action="?model=outsourcing_contract_rentcar&action=edit";
				$('#form1').submit();
				break;
			case 'editAudit':
				document.getElementById('form1').action="?model=outsourcing_contract_rentcar&action=edit&actType=actType";
				$('#form1').submit();
				break;
			case 'change':
				document.getElementById('form1').action="?model=outsourcing_contract_rentcar&action=change";
				$('#form1').submit();
				break;
		}
	}
}

// ��鵱ǰ֧����ʽ����ķ������Ƿ��Ѿ��й����˶�Ӧ�ķ������¼,����в��������˱���������޸ķ��������������������ġ�
var chkHasUndoneExpenseTmp = function(){
	var dupFeeTypesCul = 0;// �ظ����ֵķ���������
	var feeTypesCache = [];
	var hasRecord = false;
	for(var i = 1;i<= $("#payInfoCul").val();i++){
		var feeTypesArr = ($("#includeFeeTypeCode"+i).val() != '')? $("#includeFeeTypeCode"+i).val().split(",") : [];
		$.each(feeTypesArr,function(index,item){
			if($("#payTypeId"+i).val() != undefined){
				var payTypeId = $("#payTypeId"+i).val();
				// �ȼ��ø��ʽ��û�����ķ������¼
				var chkResult = $.ajax({
					type : "POST",
					url : "?model=outsourcing_outsourcessupp_vehiclesupp&action=getRelativeExpenseTmp",
					data : {
						rentContId : $("#id").val(),
						payTypeId : payTypeId
					},
					async : false
				}).responseText;
				var chkResultObj = eval("("+chkResult+")");
				var includeFeeTypeCodeArr = [];
				if(chkResultObj.result == "ok"){
					includeFeeTypeCodeArr = (chkResultObj.data.includeFeeTypeCode != '')? chkResultObj.data.includeFeeTypeCode.split(",") : [];
				}

				//Ȼ���жϴ˴��޸��Ƿ������޸ģ�ɾ��|�޸ķ����
				if(includeFeeTypeCodeArr.length > 0 && !hasRecord){
					var feeTypesArr = ($("#includeFeeTypeCode"+i).val() != '')? $("#includeFeeTypeCode"+i).val().split(",") : [];
					var culNum = 0;
					$.each(feeTypesArr,function(ii,iitem){
						if($.inArray(iitem,includeFeeTypeCodeArr) >= 0){
							culNum += 1;
						}
					});
					if(includeFeeTypeCodeArr.length < feeTypesArr.length || culNum != includeFeeTypeCodeArr.length){
						// console.log(feeTypesArr);
						// console.log(includeFeeTypeCodeArr);
						hasRecord = true;
						return false;
					}

					if(!hasRecord && $("#isDel"+i).val() == 1){
						hasRecord = true;
						return false;
					}
				}
			}
		});
	}
	return hasRecord;
}

var rentUnitPriceCalWayChange = function(obj){
	var rentUnitPriceCalWay = $("#rentUnitPriceCalWay").find("option:selected").val();
	switch(rentUnitPriceCalWay){
		case 'byMonth':
			$("#rentUnitPriceCalWayLabel").text("����(Ԫ/��/��)");
			break;
		case 'byDay':
			$("#rentUnitPriceCalWayLabel").text("����(Ԫ/��/��)");
			break;
	}
}

$(function(){
	rentUnitPriceCalWayChange();

	var loadNum = $("#loadNum").val();
	if(loadNum == undefined){
		$("#contractNatureCode").after("<input type='hidden' id='loadNum' value='1'>");
	}
})