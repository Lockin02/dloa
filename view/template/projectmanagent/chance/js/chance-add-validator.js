function toApproval() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_chance_chance&action=add&act=app";

}
/**
 * 产品清单是否显示
 */
function showProductInfo(thisObj) {
	if (thisObj.value == "SJJD01") {
		$(".productInfo").hide();
	} else {
		$(".productInfo").show();
	}
}
/**
 * 数据字典加载项
 */
$(function() {
	//回到顶部
	$.scrolltotop({className: 'totop'});

	// 商机阶段
//	chanceStageArr = getData('SJJD');
//	addDataToSelect(chanceStageArr, 'chanceStage');
//	// 商机等级
//	chanceStageArr = getData('SJDJ');
//	addDataToSelect(chanceStageArr, 'chanceLevel');
	// 商机赢率
	chanceStageArr = getData('SJYL');
	addDataToSelect(chanceStageArr, 'winRate');
	// 客户类型
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');
	// 板块
	moduleArr = getData('HTBK');
	addDataToSelect(moduleArr, 'module');

	//人为删除 阶段6，7  赢率100 的下拉option
//    var chanceStageObj = document.getElementById("chanceStage");
//    for(var i=0;i<chanceStageObj.options.length;i++)
//    {
//        if(chanceStageObj.options[i].text == "阶段六" || chanceStageObj.options[i].text == "阶段七")
//        {
//            chanceStageObj.options.remove(i);
//        }
//    }
//    for(var i=0;i<chanceStageObj.options.length;i++)
//    {
//        if(chanceStageObj.options[i].text == "阶段七")
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

// 客户信息初始化
function initCustomerInfo(){
	$("#customerType").val('');
	$("#customerTypeName").val('');
	$("#country").find("option[value='1']").attr("selected","selected").trigger("change");
	$("#province").find("option[value='']").attr("selected","selected").trigger("change");
}

// 加载下拉列表
$(function() {
	// 客户
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					initCustomerInfo();

					// 带出关联的客户类型
					$("#customerType").val(data.TypeOne);
					$("#customerTypeName").val(data.TypeOneName);

					// 带出关联客户的国家/省份/城市信息
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

//					if ($("#countryName").val() == "中国") {
//						$("#province").val(data.ProvId);// 所属省份Id
//						$("#province").trigger("change");
//						$("#provinceName").val(data.Prov);// 所属省份
//						$("#city").val(data.CityId);// 城市ID
//						$("#cityName").val(data.City);// 城市名称
//					}
					$("#customerId").val(data.id);
//					$("#areaPrincipal").val(data.AreaLeader);// 区域负责人
//					$("#areaPrincipalId").val(data.AreaLeaderId);// 区域负责人Id
//					$("#areaName").val(data.AreaName);// 合同所属区域
//					$("#areaCode").val(data.AreaId);// 合同所属区域
					$("#address").val(data.Address);// 客户地址
					// $("#linkmanListInfo").yxeditgrid('remove');
					//
					var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
							"getCmpByCol", "linkmanName");
					linkmanCmp.yxcombogrid_linkman("remove");
					$("#linkmanListInfo").yxeditgrid('remove');
					linkmanList(data.id);
                    //客户类型是运营商类的，带出客户类型及省份
                    // var typeName = data.TypeOne_name;
                    // if(typeName != undefined && typeName.indexOf("运营商") != -1){
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

 // 类型属性
 function Type(obj) {
 $('#chanceNature').empty();
 var objV = document.getElementById('chanceNature');
 if(obj.value != ""){
 contractNatureCodeArr = getData(obj.value);
 objV.options.add(new Option("...请选择...", "")); // 这个兼容IE与firefox
 addDataToSelect(contractNatureCodeArr, 'chanceNature');
 }else{
 objV.options.add(new Option("...请选择...", "")); // 这个兼容IE与firefox
 }
 }

$(function() {
	contractTypeArr = getData('HTLX');
	addDataToSelect(contractTypeArr, 'chanceType');
	// 签约主体
	signSubjectTypeArr = getData('QYZT');
	addDataToSelect(signSubjectTypeArr, 'signSubject');
});

$(function() {
	// 组织机构人员选择
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
 * 动态配置商机团队成员权限配置列表
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
// 组织机构选择
$(function() {
	$("#prinvipalName").yxselect_user({
		hiddenId : 'prinvipalId',
		isGetDept:[true,"depId","depName"]
	});
});

$(function() {
// 提交验证
//	$("#form1").validationEngine({
//	inlineValidation: false,
//	success :  function(){
//		   var country = $("#countryName").val();
//		   var province = $("#province").val();
//		   var city = $("#city").val();
//		   if(country == '中国'){
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
//		   $("#form1").submit();//加上验证后再提交表单，解决需要连续点击两次按钮才能提交表单的bug
//
//	},
//	failure :false
//	})
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
		   if(country == '中国'){
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
//		alert("当商机为阶段一以上时，产品不能为空，请选择产品");
//		return false;
//	}
	var trackId = $("#trackmanId").val();
	var prinvipalId = $("#prinvipalId").val();
	if (trackId != '') {
		var trackIdArr = trackId.split(",");
	}
	for (i in trackIdArr) {
		if (trackIdArr[i] == prinvipalId) {
			alert("团队成员不允许存在该商机负责人，请重新选择");
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

// 选产品
function chooseProduct() {
	// url = "?model=projectmanagent_chance_chance&action=chanceProduct";
	// showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");
	var productLen = $("table[id$='productList']>tbody").children("tr").length;
	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceProduct&productLen="
			+ productLen
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
}
//选择设备硬件
function chooseHardware() {
	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceHardware"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
}

// 删除产品
function delectPro(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		listNum();
		countSum();
		// $(obj).parent().hide();
	}
}
function delectHard(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		// $(obj).parent().hide();
	}
}
// 处理序号
function listNum() {
	var goodsTable = document.getElementById("productList");
	var rows = goodsTable.rows.length;
	for (var i = 0; i < rows; i++) {
		$("#productList tr:eq(" + i + ") td:eq(0)").text(i + 1);
	}

	formateMoney();
}
//判断产品数量输入是否合理
function checkNum(obj) {
	var num = /^(0|[1-9]\d*)$|^(0|[1-9]\d*)\.(\d+)$/;
	if (!num.test(obj.value) && obj.value != "") {
		alert("产品数量只能输入数字!");
		obj.value = "";
	}
}
// 判断金额输入值
function checkMon(obj) {
	var moneyNum = /^(0|[1-9]\d*)$|^(0|[1-9]\d*)\.(\d+)$/;
	if (!moneyNum.test(obj.value) && obj.value != "") {
		alert("金额只能输入数字!");
		obj.value = "";
	}
}
//计算商机总金额
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
 * 商机团队权限
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
//验证产品金额
function checkMoney(obj){
    alert(obj)
}

//验证商机金额
function checkMoneyAll(){
	var chanceMoney = $("#chanceMoney_v").val();
	if(chanceMoney =="" || chanceMoney == 0){
		alert('金额需大于0');
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

//自动查找归属区域
function setAreaInfo(){
	// 只处理归属区域不为大数据部的
    if($("#originalAreaName").val() != "大数据部"){
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
				if (returnValue['count'] == 1) {//只有一条数据则直接传入
					$('#areaName').show();// 显示输入框
					$('#chooseAreaName').hide();// 隐藏下拉框
					$('#chooseAreaName').removeClass("validate[required]");
					$("#areaName").val(returnData[0].areaName);
					$("#areaCode").val(returnData[0].id);
					$("#areaPrincipal").val(returnData[0].areaPrincipal);// 区域负责人
					$("#areaPrincipalId_v").val(returnData[0].areaPrincipalId);// 区域负责人Id
				}else{// 若有多条数据,则变下拉框让销售自己选

					// 隐藏输入框,并初始化对应信息
					$('#areaName').hide();
					$("#areaName").val("");
					$("#areaCode").val("");
					$("#areaPrincipal").val("");// 区域负责人
					$("#areaPrincipalId_v").val("");// 区域负责人Id

					// 生成对应的下拉框
					var optStr = '<option value="" title="...请选择...">...请选择...</option>';
					$.each(returnData,function(){
						var thisData = $(this)[0];
						optStr += '<option value="'+thisData.id+'" data-areaPrincipal="'+thisData.areaPrincipal+'" data-areaPrincipalId="'+thisData.areaPrincipalId+'" title="'+thisData.areaName+'">'+thisData.areaName+'</option>';
					});
					$('#chooseAreaName').html(optStr);
					$('#chooseAreaName').show();// 显示下拉框
					$('#chooseAreaName').addClass("validate[required]");// 设置为必选项
					// 选择归属区域后,更新相应的数据
					$('#chooseAreaName').change(function(){
						console.log($(this).find("option:selected").text());
						$("#areaName").val($(this).find("option:selected").text());
						$("#areaCode").val($(this).find("option:selected").val());
						$("#areaPrincipal").val($(this).find("option:selected").attr('data-areaPrincipal'));// 区域负责人
						$("#areaPrincipalId_v").val($(this).find("option:selected").attr('data-areaPrincipalId'));// 区域负责人Id
					});
				}
			}else{
				$('#areaName').show();// 显示输入框
				$('#chooseAreaName').hide();// 隐藏下拉框
				$('#chooseAreaName').removeClass("validate[required]");
				$("#areaName").val("");
				$("#areaCode").val("");
				$("#areaPrincipal").val("");// 区域负责人
				$("#areaPrincipalId_v").val("");// 区域负责人Id
			}
        }else{
        	return false;
        }
    }
}