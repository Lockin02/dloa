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
			break;
		default :
			break;
	}
}

//检测机票是否最低价
function changeTypes(thisValue) {
	switch (thisValue) {
		case '0' :
			$("#reson").show();
			$("#resons").show();
			validate({
				"lowremark" : {
					required : true
				}
			})
			break;
		case '1' :
			$("#reson").hide();
			$("#resons").hide();
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

    $("input[name='message[ticketType]']").each(function(){
		if($(this).val() == ticketType){
			$(this).attr('checked',true);
			return false;
		}
    });
}


//检测时间是否正确
function checkTime(){
	var flightTime = $("#flightTime").val();
	var arrivalTime = $("#arrivalTime").val();
	if(flightTime != ""){
		if(arrivalTime !=""){
			if(flightTime > arrivalTime){
				$("#flightTime").val("");
				$("#arrivalTime").val("");
				alert("乘机时间不能大于到达时间");
			}
		}
	}
}