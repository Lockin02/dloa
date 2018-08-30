// ����ʱ��
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
		alert("�����������С�ڳ���ʱ��");
		$("#comeDate").val("");
	}
}

// �ڶ���ʱ��
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
		alert("�����������С�ڳ�������");
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
		alert("�����������С����������");
		$("#reason").attr("style", "color:black");
		$("#startDate").val("");
		$("#requireReason").removeClass("validate[required]");
	}
}

// �˻�����ѡ��ʱҳ�洦��
function changeType(thisValue) {
	$("#firstTimes_a").hide();
	$("#firstTimes_b").hide();
	$("#middles").hide();
	$("#hlTimes_a").hide();
	$("#hlTimes_b").hide();

	//��֤ȡ��
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

//����Ʊ�Ƿ���ͼ�
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

//֤�������仯ʱ�¼� - �����
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

// ֤�����ͷ����仯ʱ�����¼�
function cardTypeChange() {
	var cardTypeObj = $("#cardType");
	if (cardTypeObj.val() == "JPZJLX-01") {
		$("#cardNo").val('');
	} else {
		$("#cardNo").val('');
	}
	cardTypeChangeClear();
}

//����Ĭ�ϻ�Ʊ����ѡ��
function setTicketCheck(){
    var ticketType = $("#ticketTypeHidden").val();

    $("input[name='message[ticketType]']").each(function(){
		if($(this).val() == ticketType){
			$(this).attr('checked',true);
			return false;
		}
    });
}


//���ʱ���Ƿ���ȷ
function checkTime(){
	var flightTime = $("#flightTime").val();
	var arrivalTime = $("#arrivalTime").val();
	if(flightTime != ""){
		if(arrivalTime !=""){
			if(flightTime > arrivalTime){
				$("#flightTime").val("");
				$("#arrivalTime").val("");
				alert("�˻�ʱ�䲻�ܴ��ڵ���ʱ��");
			}
		}
	}
}