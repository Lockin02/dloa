$(function() {
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
})
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
					$("#areaName").val("");
					$("#areaCode").val("");
					$("#areaPrincipal").val("");
					$("#areaPrincipalId_v").val("");

					$("#areaName").yxcombogrid_area("remove");
					setAreaInfo();
//					regionList();
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
   var countryId = $("#ContryId").val();
   var proId = $("#ProvinceId").val();
   var cityId = $("#CityId").val();
    $("#country").val(countryId);//��������Id
    $("#country").trigger("change");
    $("#province").val(proId);//����ʡ��Id
    $("#province").trigger("change");
	$("#city").val(cityId);//����ID
	$("#city").trigger("change");

});
// ���������б�
$(function() {
			// �ͻ�
			$("#customerName").yxcombogrid_customer({
				hiddenId : 'customerId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#customerType").val(data.TypeOne);
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
						}
					}
				}
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
						"chanceStage" : {
							required : true
						},
						"customerName" : {
							required : true
						},
						"prinvipalName" : {
							required : true
						},
						"areaName" : {
							required : true
						}
					});
		});

function sub() {
	var chanceStage = $("#chanceStage").val();
	var goodsTable = document.getElementById("productList");
	var tr = goodsTable.getElementsByTagName("tr");
	if (chanceStage != "SJJD01" && chanceStage != "" && tr.length == '0') {
		alert("���̻�Ϊ�׶�һ����ʱ����Ʒ����Ϊ�գ���ѡ���Ʒ");
		return false;
	}
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


	 $("#province").change(function() {
		 setAreaInfo();
	 });
	 $("#customerType").change(function() {
		 setAreaInfo();
	 });
	 $("#businessBelong").change(function() {
		 setAreaInfo();
	 });
	 $("#module").change(function () {
		 setAreaInfo();
	 });
 })
 //�Զ����ҹ�������
  function setAreaInfo(){
      var customerType = $("#customerType").val();
      var province = $("#province").val();
      var businessBelong = $("#businessBelong").val();
      var module = $("#module").val();
      if(customerType != '' && province != '' && businessBelong != '' && module != ''){
         var returnValue = $.ajax({
		    type : 'POST',
		    url : "?model=system_region_region&action=ajaxConRegion",
		    data:{
		        customerType : customerType,
		        province : province,
		        businessBelong : businessBelong,
                module: module
		    },
		    async: false,
		    success : function(data){
			}
		}).responseText;
		returnValue = eval("(" + returnValue + ")");
		if(returnValue){
			 $("#areaName").val(returnValue[0].areaName);
			 $("#areaCode").val(returnValue[0].id);
			 $("#areaPrincipal").val(returnValue[0].areaPrincipal);// ��������
		     $("#areaPrincipalId").val(returnValue[0].areaPrincipalId);// ��������Id
		}else{
		     $("#areaName").val("");
			 $("#areaCode").val("");
			 $("#areaPrincipal").val("");// ��������
		     $("#areaPrincipalId").val("");// ��������Id
		}

      }else{
         return false;
      }
  }

