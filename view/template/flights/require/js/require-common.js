// �ύ����
function setAudit(thisVal) {
	$("#auditType").val(thisVal);
}

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

    $("input[name='require[ticketType]']").each(function(){
		if($(this).val() == ticketType){
			$(this).attr('checked',true);
			return false;
		}
    });
}

// ��ȡ���µ�����Ϣ
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

//���֤�����ʽ��
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

//�趨ǰ���ַ�������
var beforeStr = "itemTable_cmp_";

//�ӱ����֤����
function openCheck(rowNum){

	//�ֻ�����
	var airPhone = beforeStr + "airPhone" + rowNum;
	$("#" + airPhone).addClass("validate[required]");

	//֤����
	var cardNo = beforeStr + "cardNo" + rowNum;
	$("#" + cardNo).addClass("validate[required]");

	var cardType = $("#" + beforeStr + "cardType" + rowNum);
	if(cardType.val() != 'JPZJLX-01'){
		//������ϸ
		openDetailCheck(rowNum);
	}
}

//������ϸ�з����֤��֤
function openDetailCheck(rowNum){
	//֤����Ч��
	var validDate = beforeStr + "validDate" + rowNum;
	$("#" + validDate).addClass("validate[required]");

	//��������
	var birthDate = beforeStr + "birthDate" + rowNum;
	$("#" + birthDate).addClass("validate[required]");

	//����
	var nation = beforeStr + "nation" + rowNum;
	$("#" + nation).addClass("validate[required]");
}

//�ӱ����֤�ر�
function closeCheck(rowNum){

	//�ֻ�����
	var airPhone = beforeStr + "airPhone" + rowNum;
	$("#" + airPhone).removeClass("validate[required]");

	//֤����
	var cardNo = beforeStr + "cardNo" + rowNum;
	$("#" + cardNo).removeClass("validate[required]");

	//�ر���ϸ�з����֤��֤
	closeDetailCheck(rowNum);
}

//�ر���ϸ�з����֤��֤
function closeDetailCheck(rowNum){
	//֤����Ч��
	var validDate = beforeStr + "validDate" + rowNum;
	$("#" + validDate).removeClass("validate[required]");

	//��������
	var birthDate = beforeStr + "birthDate" + rowNum;
	$("#" + birthDate).removeClass("validate[required]");

	//����
	var nation = beforeStr + "nation" + rowNum;
	$("#" + nation).removeClass("validate[required]");
}

//��ӡ��༭ҳ��Ҫ���������һ���˻�����Ϣ
$(document).ready(function() {
	$("form").submit(function(){
		var row = $("#itemTable").yxeditgrid("getCmpByCol", "airName");
		if(row.length == 0){
			alert("���������һ���˻�����Ϣ");
			return false;
		}
	});
});