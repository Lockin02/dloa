//$(function() {
//	var chanceStage = $("#chanceStage").val();
//	if ((chanceStage == "SJJD05" || chanceStage == "SJJD06" || chanceStage == "SJJD07") && chanceStage != "") {
//		//		$("#chanceMoney").val("");
//		$("#chanceMoney_v").attr('class', "readOnlyTxtNormal");
//		$("#chanceMoney_v").attr('readOnly', true);
//		$("#chanceMoney").attr('class', "readOnlyTxtNormal");
//		$("#chanceMoney").attr('readOnly', true);
//	} else {
//		$("#chanceMoney_v").attr('class', "txt");
//		$("#chanceMoney_v").attr('readOnly', false);
//		$("#chanceMoney").attr('class', "txt");
//		$("#chanceMoney").attr('readOnly', false);
//	}
//})
//�жϲ�Ʒ���������Ƿ����
function checkNum(obj){
		var num =/^(0|[1-9]\d*)$|^(0|[1-9]\d*)\.(\d+)$/;
		if(!num.test(obj.value)&&obj.value!=""){
			alert("��Ʒ����ֻ����������!");
			obj.value="";
		}
}
// �жϽ������ֵ
function checkMon(obj){
	var moneyNum =/^(-|\+)?\d+(\.\d+)?$/ ;
	if (!moneyNum.test(obj.value)&&obj.value!="") {
		alert("���ֻ����������!");
		obj.value="";
	}
}
//�����̻��ܽ��
function countSum() {
	var goodsTable = document.getElementById("productList");
	var rows = goodsTable.rows.length;
	var sumMoney = 0;
	for (var i = 0; i < rows; i++) {
		var money = $("#productList tr:eq(" + i + ") td:eq(3) input").val();
		if (money != "")
			sumMoney += parseFloat(money);
	}
	$("#chanceMoney").val(sumMoney);
	$("#chanceMoney_v").val(sumMoney);
}
//�������
function listNum() {
	var goodsTable = document.getElementById("productList");
	var rows = goodsTable.rows.length;
	for (var i = 0; i < rows; i++) {
		$("#productList tr:eq(" + i + ") td:eq(0)").text(i + 1);
	}
}
/** ****************��ͬ �������� --- ��ͬ��������************************************** */
$(function() {
	//��˾
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
				}
			}
		}
	});
	$("#formBelongName").yxcombogrid_branch({
		hiddenId : 'formBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					setAreaInfo();
				}
			}
		}
	});

//    regionList();
});
//��������
//function regionList(){
//	$("#areaName").yxcombogrid_area({
//		hiddenId : 'areaCode',
//		gridOptions : {
//			showcheckbox : false,
////			param : { 'businessBelong' : $("#businessBelong").val()},
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#areaPrincipal").val(data.areaPrincipal);
//					$("#areaCode").val(data.id);
//					$("#areaPrincipalId_v").val(data.areaPrincipalId);
//				}
//			}
//		}
//	});
//}
   //ʡ��
$(function (){
   // var countryId = $("#ContryId").val();
   // var proId = $("#ProvinceId").val();
   // var cityId = $("#CityId").val();
   //  $("#country").val(countryId);//��������Id
   //  $("#country").trigger("change");
   //  $("#province").val(proId);//����ʡ��Id
   //  $("#province").trigger("change");
	// $("#city").val(cityId);//����ID
	// $("#city").trigger("change");

});

// �ͻ���Ϣ��ʼ��
function initCustomerInfo(){
	$("#customerType").val('');
	$("#customerTypeName").val('');
	// $("#country").find("option[value='1']").attr("selected","selected").trigger("change");
	// $("#province").find("option[value='']").attr("selected","selected").trigger("change");
}

// ���������б�
$(function() {
			// �ͻ�
			$("#customerName").yxcombogrid_customer({
				hiddenId : 'customerId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							initCustomerInfo();
							// ���������Ŀͻ�����
							$("#customerType").val(data.TypeOne);
							$("#customerTypeName").val(data.TypeOneName);

							// ���������ͻ��Ĺ���/ʡ��/������Ϣ
							$("#country1").html("<option value='"+data.CountryId+"'>"+data.Country+"</option>");
							$("#province1").html("<option value='"+data.ProvId+"'>"+data.Prov+"</option>");
							$("#city1").html("<option value='"+data.CityId+"'>"+data.City+"</option>");
							$("#countryNameHide").val(data.Country);
							$("#provinceNameHide").val(data.Prov);
							$("#cityNameHide").val(data.City);
							$("#Country_Id").val(data.CountryId);
							$("#Province_Id").val(data.ProvId);
							$("#City_Id").val(data.CityId);

//							if ($("#countryName").val() == "�й�") {
//								$("#province").val(data.ProvId);// ����ʡ��Id
//								$("#province").trigger("change");
//								$("#provinceName").val(data.Prov);// ����ʡ��
//								$("#city").val(data.CityId);// ����ID
//								$("#cityName").val(data.City);// ��������
//							}
							$("#customerId").val(data.id);
//							$("#areaPrincipal").val(data.AreaLeader);// ��������
//							$("#areaPrincipalId").val(data.AreaLeaderId);// ��������Id
//							$("#areaName").val(data.AreaName);// ��ͬ��������
//							$("#areaCode").val(data.AreaId);// ��ͬ��������
							$("#address").val(data.Address);// �ͻ���ַ
							// $("#linkmanListInfo").yxeditgrid('remove');
							//
							var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
									"getCmpByCol", "linkmanName");
							linkmanCmp.yxcombogrid_linkman("remove");
							$("#linkmanListInfo").yxeditgrid('remove');
							linkmanList(data.id);
							setAreaInfo();
						}
					}
				}
			});

			$("#customerTypeWrap").children(".clear-trigger").click(function(){
				initCustomerInfo();
			});
		});

// ��������
function Type(obj) {
	$('#chanceNature').empty();
	var objV = document.getElementById('chanceNature');
	if (obj.value != "") {
		contractNatureCodeArr = getData(obj.value);
		objV.options.add(new Option("...��ѡ��...", "")); // �������IE��firefox
		addDataToSelect(contractNatureCodeArr, 'chanceNature');
	} else {
		objV.options.add(new Option("...��ѡ��...", "")); // �������IE��firefox
	}
}

$(function() {
			// ��֯������Աѡ��
			$("#trackman").yxselect_user({
						hiddenId : 'trackmanId',

						mode : 'check'
					});
		});
// ��֯����ѡ��
$(function() {
			$("#prinvipalName").yxselect_user({
						hiddenId : 'prinvipalId'
					});
		});
$(function() {

			/**
			 * ��֤��Ϣ
			 */
			validate({
						"chanceName" : {
							required : true
						},
						"chanceType" : {
							required : true
						},
//						"chanceNature" : {
//							required : true
//						},
						"winRate" : {
							required : true
						},
//						"chanceStage" : {
//							required : true
//						},
						"customerName" : {
							required : true
						},
						"prinvipalName" : {
							required : true
						},
						"areaName" : {
							required : true
						},
						"progress" : {
							required : true
						},
						"predictContractDate" : {
							required : true
						}
					});
		});

function sub() {
//	var chanceStage = $("#chanceStage").val();
//	var goodsTable = document.getElementById("productList");
//	var tr = goodsTable.getElementsByTagName("tr");
//	if (chanceStage != "SJJD01" && chanceStage != "" && tr.length == '0') {
//		alert("���̻�Ϊ�׶�һ����ʱ����Ʒ����Ϊ�գ���ѡ���Ʒ");
//		return false;
//	}
	var trackId = $("#trackmanId").val();
	var prinvipalId = $("#prinvipalId").val();
	if (trackId != '') {
		trackIdArr = trackId.split(",");
		for (i in trackIdArr) {
		if (trackIdArr[i] == prinvipalId) {
			alert("�Ŷӳ�Ա��������ڸ��̻������ˣ�������ѡ��");
			return false;
			break;
		}
	 }
	}
	return true;
}

// ѡ��Ʒ
function chooseProduct() {
	// url = "?model=projectmanagent_chance_chance&action=chanceProduct";
	// showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceProduct&chanceId="
			+ $("#chanceId").val()
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
}
// ɾ����Ʒ
function delectPro(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		listNum();
		countSum();
	}
}
//ѡ���豸Ӳ��
function chooseHardware() {
	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceHardware&chanceId="
	        + $("#chanceId").val()
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
}
function delectHard(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		// $(obj).parent().hide();
	}
}
/**
*  ����Ȩ�޿����ֶ�
**/
$(function (){
   if(strTrim($("#winRateL").html()) == '******'){
      document.getElementById("winRate").style.display = "none";
   }
   if(strTrim($("#chanceStageL").html()) == '******'){
      document.getElementById("chanceStage").style.display = "none";
   }
   if(strTrim($("#chanceMoneyL").html()) == '******'){
   	  document.getElementById("chanceMoney").style.display = "none";
   	  document.getElementById("chanceMoney_v").style.display = "none";
   	  var goodsTable = document.getElementById("productList");
   	  var rows = goodsTable.rows.length;
   	  for (var i = 1; i < rows+1; i++) {
   	      $("#goodsMoney"+i).html("******");
		  document.getElementById("product"+i).style.display = "none";
   	  }
   }

})
function changeStage() {
	var chanceStage = $("#chanceStage").val();
	if ((chanceStage == "SJJD05" || chanceStage == "SJJD06" || chanceStage == "SJJD07") && chanceStage != "") {
		//		$("#chanceMoney").val("");
		$("#chanceMoney_v").attr('class', "readOnlyTxtNormal");
		$("#chanceMoney_v").attr('readOnly', true);
		$("#chanceMoney").attr('class', "readOnlyTxtNormal");
		$("#chanceMoney").attr('readOnly', true);
	} else {
		$("#chanceMoney_v").attr('class', "txt");
		$("#chanceMoney_v").attr('readOnly', false);
		$("#chanceMoney").attr('class', "txt");
		$("#chanceMoney").attr('readOnly', false);
	}
}
function changeNature(obj) {
	$('#chanceNature').empty();

	contractNatureCodeArr = getData(obj.value);
	addDataToSelect(contractNatureCodeArr, 'chanceNature');
}

//��֤�ܽ��
function checkProduct(id){
	var product = $("#"+id).val();
	if(product == "" || product <=0){
		alert('��������0');
		return false;
	}
}

//��֤�ܽ��
function checkMoney(){
	var chanceMoney = $("#chanceMoney_v").val();
	var length = $("#productList").find("tr").length;
	if(chanceMoney =="" || chanceMoney == 0){
		alert('��������0');
		return false;
	}
	for(var i=0;i<length;i++){
		if($("input[name='chance[goods]["+ i +"][money]']").val() =="" || $("input[name='chance[goods]["+ i +"][money]']").val()<=0){
			alert('��������0');
			return false;
		}
	}
}

$(function(){
// 	 $("#areaName").attr("readonly",true);
// 	 $("#areaName").attr('class',"readOnlyTxtNormal");
    if($("#areaName").val() != "�����ݲ�"){
        var defaultAreaName = $('#originalAreaName').val();
        // ����ִ��������Ϣ
        setAreaInfo(defaultAreaName);
    }
	 // $("#province").change(function() {
		//  setAreaInfo();
	 // });
	 $("#customerType").change(function() {
		 setAreaInfo();
	 });
	 $("#module").change(function () {
		 setAreaInfo();
	 });

	var defaultAreaName = $('#originalAreaName').val();
	setAreaInfo(defaultAreaName);
 })

//�Զ����ҹ�������
function setAreaInfo(defaultAreaName){
	// ֻ����֮ǰ���ݵĹ�������Ϊ�����ݲ���
    if($("#originalAreaName").val() != "�����ݲ�"){
	    var customerType = $("#customerType").val();
        var province = $("#Province_Id").val();
        var businessBelong = $("#formBelong").val();
        var module = $("#module").val();
        if(customerType != '' && province != '' && businessBelong != '' && module != ''){
            var returnValue = $.ajax({
		        type : 'POST',
		        url : "?model=system_region_region&action=ajaxConRegion",
		        data:{
		            customerType : customerType,
		            province : province,
		            businessBelong : businessBelong,
                    module: module,
					getAll: 1
		        },
		        async: false,
		        success : function(data){
		        }
            }).responseText;
			returnValue = eval("(" + returnValue + ")");
            if(returnValue['count'] != undefined && returnValue['count'] > 0) {
                var returnData = returnValue['data'];
                if (returnValue['count'] == 1) {//ֻ��һ��������ֱ�Ӵ���
                    $('#areaName').show();// ��ʾ�����
                    $('#chooseAreaName').hide();// ����������
                    $('#chooseAreaName').removeClass("validate[required]");
                    $("#areaName").val(returnData[0].areaName);
                    $("#areaCode").val(returnData[0].id);
                    $("#areaPrincipal").val(returnData[0].areaPrincipal);// ��������
                    $("#areaPrincipalId_v").val(returnData[0].areaPrincipalId);// ��������Id
                }else{// ���ж�������,����������������Լ�ѡ

                    // ���������,����ʼ����Ӧ��Ϣ
                    $('#areaName').hide();
                    $("#areaName").val("");
                    $("#areaCode").val("");
                    $("#areaPrincipal").val("");// ��������
                    $("#areaPrincipalId_v").val("");// ��������Id

                    // ���ɶ�Ӧ��������
                    var optStr = '<option value="" title="...��ѡ��...">...��ѡ��...</option>';
                    $.each(returnData,function(){
                        var selected = "";
                        var thisData = $(this)[0];
                        if(defaultAreaName && defaultAreaName == thisData.areaName){
                            selected = "selected = 'selected'";
                            $("#areaName").val(thisData.areaName);
                            $("#areaCode").val(thisData.id);
                            $("#areaPrincipal").val(thisData.areaPrincipal);// ��������
                            $("#areaPrincipalId_v").val(thisData.areaPrincipalId);// ��������Id
                        }
                        optStr += '<option value="'+thisData.id+'" data-areaPrincipal="'+thisData.areaPrincipal+'" data-areaPrincipalId="'+thisData.areaPrincipalId+'" title="'+thisData.areaName+'" '+selected+'>'+thisData.areaName+'</option>';
                    });
                    $('#chooseAreaName').html(optStr);
                    $('#chooseAreaName').show();// ��ʾ������
                    $('#chooseAreaName').addClass("validate[required]");// ����Ϊ��ѡ��
                    // ѡ����������,������Ӧ������
                    $('#chooseAreaName').change(function(){
                        console.log($(this).find("option:selected").text());
                        $("#areaName").val($(this).find("option:selected").text());
                        $("#areaCode").val($(this).find("option:selected").val());
                        $("#areaPrincipal").val($(this).find("option:selected").attr('data-areaPrincipal'));// ��������
                        $("#areaPrincipalId_v").val($(this).find("option:selected").attr('data-areaPrincipalId'));// ��������Id
                    });
                }
            }else{
                $('#areaName').show();// ��ʾ�����
                $('#chooseAreaName').hide();// ����������
                $('#chooseAreaName').removeClass("validate[required]");
                $("#areaName").val("");
                $("#areaCode").val("");
                $("#areaPrincipal").val("");// ��������
                $("#areaPrincipalId_v").val("");// ��������Id
            }

        }else{
        	return false;
        }
    }
}