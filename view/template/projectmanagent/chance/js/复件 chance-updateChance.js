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
//判断产品数量输入是否合理
function checkNum(obj){
		var num =/^(0|[1-9]\d*)$|^(0|[1-9]\d*)\.(\d+)$/;
		if(!num.test(obj.value)&&obj.value!=""){
			alert("产品数量只能输入数字!");
			obj.value="";
		}
}
// 判断金额输入值
function checkMon(obj){
	var moneyNum =/^(-|\+)?\d+(\.\d+)?$/ ;
	if (!moneyNum.test(obj.value)&&obj.value!="") {
		alert("金额只能输入数字!");
		obj.value="";
	}
}
//计算商机总金额
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
//处理序号
function listNum() {
	var goodsTable = document.getElementById("productList");
	var rows = goodsTable.rows.length;
	for (var i = 0; i < rows; i++) {
		$("#productList tr:eq(" + i + ") td:eq(0)").text(i + 1);
	}
}
/** ****************合同 区域负责人 --- 合同归属区域************************************** */
$(function() {
	//公司
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
//加载区域
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
   //省市
$(function (){
   var countryId = $("#ContryId").val();
   var proId = $("#ProvinceId").val();
   var cityId = $("#CityId").val();
    $("#country").val(countryId);//所属国家Id
    $("#country").trigger("change");
    $("#province").val(proId);//所属省份Id
    $("#province").trigger("change");
	$("#city").val(cityId);//城市ID
	$("#city").trigger("change");

});
// 加载下拉列表
$(function() {
			// 客户
			$("#customerName").yxcombogrid_customer({
				hiddenId : 'customerId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#customerType").val(data.TypeOne);
//							if ($("#countryName").val() == "中国") {
//								$("#province").val(data.ProvId);// 所属省份Id
//								$("#province").trigger("change");
//								$("#provinceName").val(data.Prov);// 所属省份
//								$("#city").val(data.CityId);// 城市ID
//								$("#cityName").val(data.City);// 城市名称
//							}
							$("#customerId").val(data.id);
//							$("#areaPrincipal").val(data.AreaLeader);// 区域负责人
//							$("#areaPrincipalId").val(data.AreaLeaderId);// 区域负责人Id
//							$("#areaName").val(data.AreaName);// 合同所属区域
//							$("#areaCode").val(data.AreaId);// 合同所属区域
							$("#address").val(data.Address);// 客户地址
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

// 类型属性
function Type(obj) {
	$('#chanceNature').empty();
	var objV = document.getElementById('chanceNature');
	if (obj.value != "") {
		contractNatureCodeArr = getData(obj.value);
		objV.options.add(new Option("...请选择...", "")); // 这个兼容IE与firefox
		addDataToSelect(contractNatureCodeArr, 'chanceNature');
	} else {
		objV.options.add(new Option("...请选择...", "")); // 这个兼容IE与firefox
	}
}

$(function() {
			// 组织机构人员选择
			$("#trackman").yxselect_user({
						hiddenId : 'trackmanId',

						mode : 'check'
					});
		});
// 组织机构选择
$(function() {
			$("#prinvipalName").yxselect_user({
						hiddenId : 'prinvipalId'
					});
		});
$(function() {

			/**
			 * 验证信息
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
		alert("当商机为阶段一以上时，产品不能为空，请选择产品");
		return false;
	}
	var trackId = $("#trackmanId").val();
	var prinvipalId = $("#prinvipalId").val();
	if (trackId != '') {
		trackIdArr = trackId.split(",");
		for (i in trackIdArr) {
		if (trackIdArr[i] == prinvipalId) {
			alert("团队成员不允许存在该商机负责人，请重新选择");
			return false;
			break;
		}
	 }
	}
	return true;
}

// 选产品
function chooseProduct() {
	// url = "?model=projectmanagent_chance_chance&action=chanceProduct";
	// showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceProduct&chanceId="
			+ $("#chanceId").val()
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
}
// 删除产品
function delectPro(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		listNum();
		countSum();
	}
}
//选择设备硬件
function chooseHardware() {
	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceHardware&chanceId="
	        + $("#chanceId").val()
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
}
function delectHard(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		// $(obj).parent().hide();
	}
}
/**
*  处理权限控制字段
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

//验证总金额
function checkProduct(id){
	var product = $("#"+id).val();
	if(product == "" || product <=0){
		alert('金额需大于0');
		return false;
	}
}

//验证总金额
function checkMoney(){
	var chanceMoney = $("#chanceMoney_v").val();
	var length = $("#productList").find("tr").length;
	if(chanceMoney =="" || chanceMoney == 0){
		alert('金额需大于0');
		return false;
	}
	for(var i=0;i<length;i++){
		if($("input[name='chance[goods]["+ i +"][money]']").val() =="" || $("input[name='chance[goods]["+ i +"][money]']").val()<=0){
			alert('金额需大于0');
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
 //自动查找归属区域
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
			 $("#areaPrincipal").val(returnValue[0].areaPrincipal);// 区域负责人
		     $("#areaPrincipalId").val(returnValue[0].areaPrincipalId);// 区域负责人Id
		}else{
		     $("#areaName").val("");
			 $("#areaCode").val("");
			 $("#areaPrincipal").val("");// 区域负责人
		     $("#areaPrincipalId").val("");// 区域负责人Id
		}

      }else{
         return false;
      }
  }

