function toApproval() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_chance_chance&action=add&act=app";

}
/**
 * ��Ʒ�嵥�Ƿ���ʾ
 */
function showProductInfo(thisObj) {
	if (thisObj.value == "SJJD01") {
		$(".productInfo").hide();
	} else {
		$(".productInfo").show();
	}
}
/**
 * �����ֵ������
 */
$(function() {
	//�ص�����
	$.scrolltotop({className: 'totop'});

	// �̻��׶�
//	chanceStageArr = getData('SJJD');
//	addDataToSelect(chanceStageArr, 'chanceStage');
//	// �̻��ȼ�
//	chanceStageArr = getData('SJDJ');
//	addDataToSelect(chanceStageArr, 'chanceLevel');
	// �̻�Ӯ��
	chanceStageArr = getData('SJYL');
	addDataToSelect(chanceStageArr, 'winRate');
	// �ͻ�����
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');
	// ���
	moduleArr = getData('HTBK');
	addDataToSelect(moduleArr, 'module');

	//��Ϊɾ�� �׶�6��7  Ӯ��100 ������option
//    var chanceStageObj = document.getElementById("chanceStage");
//    for(var i=0;i<chanceStageObj.options.length;i++)
//    {
//        if(chanceStageObj.options[i].text == "�׶���" || chanceStageObj.options[i].text == "�׶���")
//        {
//            chanceStageObj.options.remove(i);
//        }
//    }
//    for(var i=0;i<chanceStageObj.options.length;i++)
//    {
//        if(chanceStageObj.options[i].text == "�׶���")
//        {
//            chanceStageObj.options.remove(i);
//        }
//    }
    var winRateObj = document.getElementById("winRate");
    for(var i=0;i<winRateObj.options.length;i++)
    {
        if(winRateObj.options[i].text == "100%")
        {
            winRateObj.options.remove(i);
        }
    }
});
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

// �ͻ���Ϣ��ʼ��
function initCustomerInfo(){
	$("#customerType").val('');
	$("#customerTypeName").val('');
	$("#country").find("option[value='1']").attr("selected","selected").trigger("change");
	$("#province").find("option[value='']").attr("selected","selected").trigger("change");
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
					$("#country").find("option[value='"+data.CountryId+"']").attr("selected","selected").trigger("change");
					$("#province").find("option[value='"+data.ProvId+"']").attr("selected","selected").trigger("change");
					$("#country_Id").val(data.CountryId);
					$("#province_Id").val(data.ProvId);

					if($("#city").find("option[value='"+data.CityId+"']").length > 0){
						$("#city").find("option[value='"+data.CityId+"']").attr("selected","selected").trigger("change");
						$("#city_Id").val(data.CityId);
					}else{
						$("#city").find("option[value='']").attr("selected","selected").trigger("change");
						$("#city_Id").val('');
					}

//					if ($("#countryName").val() == "�й�") {
//						$("#province").val(data.ProvId);// ����ʡ��Id
//						$("#province").trigger("change");
//						$("#provinceName").val(data.Prov);// ����ʡ��
//						$("#city").val(data.CityId);// ����ID
//						$("#cityName").val(data.City);// ��������
//					}
					$("#customerId").val(data.id);
//					$("#areaPrincipal").val(data.AreaLeader);// ��������
//					$("#areaPrincipalId").val(data.AreaLeaderId);// ��������Id
//					$("#areaName").val(data.AreaName);// ��ͬ��������
//					$("#areaCode").val(data.AreaId);// ��ͬ��������
					$("#address").val(data.Address);// �ͻ���ַ
					// $("#linkmanListInfo").yxeditgrid('remove');
					//
					var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
							"getCmpByCol", "linkmanName");
					linkmanCmp.yxcombogrid_linkman("remove");
					$("#linkmanListInfo").yxeditgrid('remove');
					linkmanList(data.id);
                    //�ͻ���������Ӫ����ģ������ͻ����ͼ�ʡ��
                    // var typeName = data.TypeOne_name;
                    // if(typeName != undefined && typeName.indexOf("��Ӫ��") != -1){
                    // 	$("#customerType").find("option[text='" + typeName + "']").attr("selected","selected");
                    // 	$("#province").find("option[text='" + data.Prov + "']").attr("selected","selected").trigger("change");
                    // }else{
                    // 	$("#customerType").val("");
                    // 	$("#province").val("").trigger("change");
                    // }
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
 if(obj.value != ""){
 contractNatureCodeArr = getData(obj.value);
 objV.options.add(new Option("...��ѡ��...", "")); // �������IE��firefox
 addDataToSelect(contractNatureCodeArr, 'chanceNature');
 }else{
 objV.options.add(new Option("...��ѡ��...", "")); // �������IE��firefox
 }
 }

$(function() {
	contractTypeArr = getData('HTLX');
	addDataToSelect(contractTypeArr, 'chanceType');
	// ǩԼ����
	signSubjectTypeArr = getData('QYZT');
	addDataToSelect(signSubjectTypeArr, 'signSubject');
});

$(function() {
	// ��֯������Աѡ��
	$("#trackman").yxselect_user({
		hiddenId : 'trackmanId',
		mode : 'check',
		event : {
			"select" : function(obj, row) {
				authorizeList();
			}
		}
	});
});
/**
 * ��̬�����̻��Ŷӳ�ԱȨ�������б�
 */
function authorizeList() {
	var trackmanIds = $("#trackmanId").val();
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=toSetauthorizeInfo',
		data : {
			trackmanIds : trackmanIds
		},
		async : false,
		success : function(data) {
//			//					    	var obj = eval("(" + data +")");
//			//					    	alert(data)
			$("#authorize").html(data);
//			self.parent.tb_remove();
//			parent.listNum();
		}
	});
}
function fs_selectAll(value) {
            var ckelems = document.getElementById("authorize").getElementsByTagName("input");
            for (var i = 0; i < ckelems.length; i++) {
                if (ckelems[i].type == "checkbox") {
                    if (value == 1)
                        ckelems[i].checked = true;
                    else
                        ckelems[i].checked = false;
                }
            }
        }
// ��֯����ѡ��
$(function() {
	$("#prinvipalName").yxselect_user({
		hiddenId : 'prinvipalId',
		isGetDept:[true,"depId","depName"]
	});
});

$(function() {
// �ύ��֤
//	$("#form1").validationEngine({
//	inlineValidation: false,
//	success :  function(){
//		   var country = $("#countryName").val();
//		   var province = $("#province").val();
//		   var city = $("#city").val();
//		   if(country == '�й�'){
//		       validate({
//		   	      "province":{
//		   	          required : true
//		   	      },
//		   	      "city":{
//		   	          required : true
//		   	      }
//
//		   	   })
//		   }
//		   sub();
////		   $("#form1").trigger("onsubmit");
//		   $("#form1").submit();//������֤�����ύ���������Ҫ����������ΰ�ť�����ύ����bug
//
//	},
//	failure :false
//	})
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
//		"chanceNature" : {
//			required : true
//		},
		"winRate" : {
			required : true
		},
//		"chanceStage" : {
//			required : true
//		},
		"customerName" : {
			required : true
		},
		"prinvipalName" : {
			required : true
		},
		"areaName" : {
			required : true
		},
		"predictContractDate" : {
			required : true
		}
	});
});


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


function sub() {
	var country = $("#countryName").val();
		   var province = $("#province").val();
		   var city = $("#city").val();
		   if(country == '�й�'){
		       validate({
		   	      "province":{
		   	          required : true
		   	      },
		   	      "city":{
		   	          required : true
		   	      }

		   	   })
		   }
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
		var trackIdArr = trackId.split(",");
	}
	for (i in trackIdArr) {
		if (trackIdArr[i] == prinvipalId) {
			alert("�Ŷӳ�Ա��������ڸ��̻������ˣ�������ѡ��");
			return false;
			break;
		}
	}
	return true;
}

$(function() {
	$("#goods").yxcombogrid_goods({
		hiddenId : 'areaCode',
		gridOptions : {
			showcheckbox : true,
			event : {
				'row_dblclick' : function(e, row, data) {

				}
			}
		}
	});

});

// ѡ��Ʒ
function chooseProduct() {
	// url = "?model=projectmanagent_chance_chance&action=chanceProduct";
	// showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");
	var productLen = $("table[id$='productList']>tbody").children("tr").length;
	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceProduct&productLen="
			+ productLen
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
}
//ѡ���豸Ӳ��
function chooseHardware() {
	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceHardware"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
}

// ɾ����Ʒ
function delectPro(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		listNum();
		countSum();
		// $(obj).parent().hide();
	}
}
function delectHard(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		// $(obj).parent().hide();
	}
}
// �������
function listNum() {
	var goodsTable = document.getElementById("productList");
	var rows = goodsTable.rows.length;
	for (var i = 0; i < rows; i++) {
		$("#productList tr:eq(" + i + ") td:eq(0)").text(i + 1);
	}

	formateMoney();
}
//�жϲ�Ʒ���������Ƿ����
function checkNum(obj) {
	var num = /^(0|[1-9]\d*)$|^(0|[1-9]\d*)\.(\d+)$/;
	if (!num.test(obj.value) && obj.value != "") {
		alert("��Ʒ����ֻ����������!");
		obj.value = "";
	}
}
// �жϽ������ֵ
function checkMon(obj) {
	var moneyNum = /^(0|[1-9]\d*)$|^(0|[1-9]\d*)\.(\d+)$/;
	if (!moneyNum.test(obj.value) && obj.value != "") {
		alert("���ֻ����������!");
		obj.value = "";
	}
}
//�����̻��ܽ��
function countSum() {
	var goodsTable = document.getElementById("productList");
	var rows = goodsTable.rows.length;
	var sumMoney = 0;
	for (var i = 0; i < rows; i++) {
		var idStr = $("#productList tr:eq(" + i + ") td:eq(3) input").attr("id");
		var idNum = idStr.split("_")[0];
		var money = $("#"+idNum).val();
		if (money != "")
			sumMoney += parseFloat(money);
	}
	$("#chanceMoney").val(sumMoney);
	$("#chanceMoney_v").val(sumMoney);
}

/**
 * �̻��Ŷ�Ȩ��
 */
function authorize() {
	var temp = document.getElementById("authorize");
	var zk =  document.getElementById("zk");
	var sf =  document.getElementById("sf");
	if (temp.style.display == ''){
	   temp.style.display = "none";
	   zk.style.display = "";
	   sf.style.display = "none";
	}else if (temp.style.display == "none"){
	   temp.style.display = '';
	   zk.style.display = 'none';
	   sf.style.display = '';
	}
}
//��֤��Ʒ���
function checkMoney(obj){
    alert(obj)
}

//��֤�̻����
function checkMoneyAll(){
	var chanceMoney = $("#chanceMoney_v").val();
	if(chanceMoney =="" || chanceMoney == 0){
		alert('��������0');
		return false;
	}
}

 $(function(){
// 	 $("#areaName").attr("readonly",true);
// 	 $("#areaName").attr('class',"readOnlyTxtNormal");
	 $("#province").change(function() {
		 setAreaInfo();
	 });
	 $("#customerType").change(function() {
		 setAreaInfo();
	 });
	 $("#module").change(function() {
		 setAreaInfo();
	 });
 })

//�Զ����ҹ�������
function setAreaInfo(){
	// ֻ�����������Ϊ�����ݲ���
    if($("#originalAreaName").val() != "�����ݲ�"){
	    var customerType = $("#customerType").val();
        var province = $("#province").val();
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
						var thisData = $(this)[0];
						optStr += '<option value="'+thisData.id+'" data-areaPrincipal="'+thisData.areaPrincipal+'" data-areaPrincipalId="'+thisData.areaPrincipalId+'" title="'+thisData.areaName+'">'+thisData.areaName+'</option>';
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