// 提交审批
function setAudit(thisVal) {
	$("#auditType").val(thisVal);
}

// 返回时间
function comeDay() {
	var comeDay = $("#comeDate").val();
	var endDay = $("#startDate").val();
	comeDay = comeDay.replace(/-/g, '/');
	endDay = endDay.replace(/-/g, '/');
	var b = new Date(endDay);
	var c = new Date(comeDay);
	endDay = b.getTime();
	comeDay = c.getTime();
	if (endDay > comeDay) {
		alert("你输入的日期小于出发时间");
		$("#comeDate").val("");
	}
}

// 第二期时间
function twoDay() {
	var comeDay = $("#twoDate").val();
	var endDay = $("#startDate").val();
	comeDay = comeDay.replace(/-/g, '/');
	endDay = endDay.replace(/-/g, '/');
	var b = new Date(endDay);
	var c = new Date(comeDay);
	endDay = b.getTime();
	comeDay = c.getTime();
	if (endDay > comeDay) {
		alert("你输入的日期小于出发日期");
		$("#twoDate").val("");
	}
}

function getDay() {
	var startDay = $("#requireTime").val();
	var endDay = $("#startDate").val();
	startDay = startDay.replace(/-/g, '/');
	endDay = endDay.replace(/-/g, '/');
	var a = new Date(startDay);
	var b = new Date(endDay);
	startDay = a.getTime();
	endDay = b.getTime();
	if (endDay > startDay) {
		if (endDay - startDay <= 3 * 24 * 3600 * 1000) {
			$("#reason").attr("style", "color:blue");
			validate({
				"requireReason" : {
					required : true
				}
			})
		} else {
			$("#reason").attr("style", "color:black");
			$("#requireReason").removeClass("validate[required]");
		}
	} else if (endDay < startDay) {
		alert("你输入的日期小于申请日期");
		$("#reason").attr("style", "color:black");
		$("#startDate").val("");
		$("#requireReason").removeClass("validate[required]");
	}
}

// 乘机类型选择时页面处理
function changeType(thisValue) {
	$("#firstTimes_a").hide();
	$("#firstTimes_b").hide();
	$("#middles").hide();
	$("#hlTimes_a").hide();
	$("#hlTimes_b").hide();

	//验证取消
	$("#middlePlace").removeClass("validate[required]");
	$("#twoDate").removeClass("validate[required]");
	$("#comeDate").removeClass("validate[required]");
	switch (thisValue) {
		case '10' :
			$("#startTimes_a").show();
			$("#startTimes_b").show();
			$("#endPlace").parent(".form_text_right").attr("colspan","3");
			$("#comeDate").val('');
			$("#middlePlace").val('');
			$("#twoDate").val('');
			break;
		case '11' :
			$("#startTimes_a").show();
			$("#startTimes_b").show();
			$("#hlTimes_a").show();
			$("#hlTimes_b").show();
			validate({
				"comeDate" : {
					required : true
				}
			})
			$("#endPlace").parent(".form_text_right").attr("colspan","1");
			$("#middlePlace").val('');
			$("#twoDate").val('');
			break;
		case '12' :
			$("#hlTimes_a").hide();
			$("#hlTimes_b").hide();
			$("#middles").show();
			$("#firstTimes_a").show();
			$("#firstTimes_b").show();
			validate({
				"middlePlace" : {
					required : true
				},
				"twoDate" : {
					required : true
				}
			})
			$("#endPlace").parent(".form_text_right").attr("colspan","3");
			$("#comeDate").val('');
			break;
		default :
			break;
	}
}

//证件发生变化时事件 - 无清空
function cardTypeChangeClear(){
	var cardTypeObj = $("#cardType");
	if (cardTypeObj.val() == "JPZJLX-01") {
		$('.cardAppendInfoShow').hide();
		$("#cardNo").removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly', true);
		validate({
			"nation" : {
				required : false
			},
			"validDate" : {
				required : false
			},
			"birthDate" : {
				required : false
			}
		});
	} else {
		$("#cardNo").removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly', false);
		validate({
			"nation" : {
				required : true
			},
			"validDate" : {
				required : true
			},
			"birthDate" : {
				required : true
			}
		});
		$('.cardAppendInfoShow').show();
	}
}

// 证件类型发生变化时处理事件
function cardTypeChange() {
	var cardTypeObj = $("#cardType");
	if (cardTypeObj.val() == "JPZJLX-01") {
		$("#cardNo").val('');
	} else {
		$("#cardNo").val('');
	}
	cardTypeChangeClear();
}

//设置默认机票类型选择
function setTicketCheck(){
    var ticketType = $("#ticketTypeHidden").val();

    $("input[name='require[ticketType]']").each(function(){
		if($(this).val() == ticketType){
			$(this).attr('checked',true);
			return false;
		}
    });
}

// 获取人事档案信息
function getPersonInfo(userAccount) {
	var responseText = $.ajax({
		url : 'index1.php?model=hr_personnel_personnel&action=getPersonnelInfo',
		type : "POST",
		data : {
			"userAccount" : userAccount
		},
		async : false
	}).responseText;
	var personInfo = eval("(" + responseText + ")");

	if (!personInfo) {
		personInfo = {
			'mobile' : '',
			'identityCard' : '',
			'birthdate' : '',
			'sex' : ''
		};
	}

	return personInfo;
}

//身份证号码格式化
function formatIdCard(v){
	if(v == ""){
		return '';
	}
	var strArr = v.split('');
	var strLength = strArr.length;
	var canSeeLenth = strLength - 4;
	var newStr = '';
	for(var i = 0;i < strLength ; i++){
		if(i >= canSeeLenth){
			newStr += strArr[i];
		}else{
			newStr += '*';
		}
	}
	return newStr;
}

//设定前置字符串名称
var beforeStr = "itemTable_cmp_";

//从表表单验证启用
function openCheck(rowNum){

	//手机号码
	var airPhone = beforeStr + "airPhone" + rowNum;
	$("#" + airPhone).addClass("validate[required]");

	//证件号
	var cardNo = beforeStr + "cardNo" + rowNum;
	$("#" + cardNo).addClass("validate[required]");

	var cardType = $("#" + beforeStr + "cardType" + rowNum);
	if(cardType.val() != 'JPZJLX-01'){
		//开启明细
		openDetailCheck(rowNum);
	}
}

//开启明细中非身份证验证
function openDetailCheck(rowNum){
	//证件有效期
	var validDate = beforeStr + "validDate" + rowNum;
	$("#" + validDate).addClass("validate[required]");

	//出生日期
	var birthDate = beforeStr + "birthDate" + rowNum;
	$("#" + birthDate).addClass("validate[required]");

	//国籍
	var nation = beforeStr + "nation" + rowNum;
	$("#" + nation).addClass("validate[required]");
}

//从表表单验证关闭
function closeCheck(rowNum){

	//手机号码
	var airPhone = beforeStr + "airPhone" + rowNum;
	$("#" + airPhone).removeClass("validate[required]");

	//证件号
	var cardNo = beforeStr + "cardNo" + rowNum;
	$("#" + cardNo).removeClass("validate[required]");

	//关闭明细中非身份证验证
	closeDetailCheck(rowNum);
}

//关闭明细中非身份证验证
function closeDetailCheck(rowNum){
	//证件有效期
	var validDate = beforeStr + "validDate" + rowNum;
	$("#" + validDate).removeClass("validate[required]");

	//出生日期
	var birthDate = beforeStr + "birthDate" + rowNum;
	$("#" + birthDate).removeClass("validate[required]");

	//国籍
	var nation = beforeStr + "nation" + rowNum;
	$("#" + nation).removeClass("validate[required]");
}

//添加、编辑页面要求至少添加一条乘机人信息
$(document).ready(function() {
	$("form").submit(function(){
		var row = $("#itemTable").yxeditgrid("getCmpByCol", "airName");
		if(row.length == 0){
			alert("请至少添加一条乘机人信息");
			return false;
		}
	});
});