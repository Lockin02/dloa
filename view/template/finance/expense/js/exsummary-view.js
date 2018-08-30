$(document).ready(function() {
	//���ù���
	$("#costbelong").costbelong({
		objName: 'expense',
		url: '?model=finance_expense_expense&action=ajaxGet',
		data: {"id": $("#id").val()},
		actionType: 'view'
	});

	//�ص�����
	$.scrolltotop({className: 'totop'});

	var chinseAmountObj = $("#chinseAmount");
	if (chinseAmountObj.length != 0) {
		chinseAmountObj.html(toChinseMoney($("#Amount").val() * 1));
	}

	//��Ⱦ�ӳٱ�������
	if ($("#isLate").val() == "1") {
		$("#mainTitle").css('color', 'red').html('���������ڱ�����');
	}
	//����Ԥ������û����ʱ����������Ԥ��
	if ($.trim($("#budgetDetail").text()) == '') {
		$("#budgetWarning").hide();
	}
	// ��������Ϊ��ǰ���ۺ�ʱ������ʾͳ��ֵ
	if ($("#detailType").val() != '4' && $("#detailType").val() != '5') {
		$("#statistic").hide();
	}

	// ���ñ��������Ƿ���ڷ������밢�����õļ�¼���ͻ��
	setTimeout(function(){
		chkAliTripRecords();
	},100);
})

//������Ҫ��д������
function changeTypeView(thisVal) {
	$("#saleInfo").hide();
	$("#projectInfo").hide();
	$("#feeDeptInfo").hide();
	$("#contractInfo").hide();

	switch (thisVal) {
		case '1' :
			$("#feeDeptInfo").show();

			//��Ⱦ��Ҫ���Ĳ�����չ��Ϣ
			initCheckDeptView();

			break;
		case '2' :
			$("#projectCodeView").html('��ͬ��Ŀ���');
			$("#projectNameView").html('��ͬ��Ŀ����');
			$("#projectInfo").show();
			break;
		case '3' :
			$("#projectCodeView").html('�з���Ŀ���');
			$("#projectNameView").html('�з���Ŀ����');
			$("#projectInfo").show();
			break;
		case '4' :
			$("#projectCodeView").html('������Ŀ���');
			$("#projectNameView").html('������Ŀ����');
			$("#projectInfo").show();
			$("#saleInfo").show();
			$("#procityInfo").show();
			$("#chanceDeptInfo").show();
			break;
		case '5' :
			$("#contractInfo").show();
			$("#procityInfo").show();
			$("#chanceDeptInfo").show();
			break;
		default :
			break;
	}
}

//ҳ����Ⱦ
function initCheckDeptView() {
	//�����Ҫ���ż��
	var provinceObj = $('#provinceHidden');
	if (provinceObj.length == 1 && provinceObj.val() != "") {
		//����ʡ��
		var str = "<td class='form_text_left_three'>����ʡ��</td><td class='form_text_right_three'>" + provinceObj.val() + "</td>";
		//��д���Ÿ�
		$("#feeDept").attr("colspan", 1).attr("class", "form_text_right_three").after(str);
	}
}

/****************** �޸����� ********************/
//������ɱ༭����
function openSavePurpose() {
	$('#purposeInfo').dialog({
		title: '����ģ��',
		width: 400,
		height: 200,
		modal: true
	}).dialog('open');
}

function savePurpose() {
	var Purpose = $("#Purpose").val();
	if (strTrim(Purpose) == "") {
		alert('���ɲ���Ϊ��');
		return false;
	}
	//�첽�޸�
	$.ajax({
		type: "POST",
		url: "?model=finance_expense_expense&action=ajaxUpdate",
		data: {'id': $("#id").val(), 'myKey': "Purpose", "myValue": Purpose},
		async: false,
		success: function(data) {
			if (data == "1") {
				alert('���³ɹ�');
				$("#PurposeShow").html(Purpose);
				$('#purposeInfo').dialog('close');
			}
		}
	});
}

/****************** ���ò��� ********************/

//�༭�޸ķ�������
function changeDetail(costType, costTypeName, mainType, mainTypeName) {
	//��Ҫ�޸ĵĵ��ݺ�
	var BillNo = $("#BillNo").val();

	//�Ƿ������ͬ�����ж�
	var costTypeArr = [];
	$("span[id^='spanDetail']").each(function(i, n) {
		costTypeArr.push($(this).attr("title"));
	});
	if (costTypeArr.length > 0) {
		var orgCostTypes = costTypeArr.toString();
	} else {
		var orgCostTypes = "";
	}

	//������
	var MainTypeArr = [];
	$("input[id^='MainType']").each(function(i, n) {
		MainTypeArr.push(this.value);
	});
	if (MainTypeArr.length > 0) {
		var mainTypes = MainTypeArr.toString();
	} else {
		var mainTypes = "";
	}

	//�޸ķ�������
	showThickboxWin('?model=finance_expense_expensedetail&action=toEditDetail&CostTypeID=' + costType
	+ "&CostTypeName=" + costTypeName
	+ "&mainTypeName=" + mainTypeName
	+ "&mainType=" + mainType
	+ "&BillNo=" + BillNo
	+ "&orgCostTypes=" + orgCostTypes
	+ "&mainTypes=" + mainTypes
	+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800");
}

//ҳ��ˢ��
function show_pageDetail() {
	var expensebodyObj = $("#expensebody");
	expensebodyObj.empty();
	var BillNo = $("#BillNo").val();
	//�첽�޸�
	$.ajax({
		type: "POST",
		url: "?model=finance_expense_expensedetail&action=ajaxGetCostDetail",
		data: {'BillNo': BillNo},
		async: false,
		success: function(data) {
			if (data) {
				expensebodyObj.html(data);
			}
		}
	});
}

/****************** ��Ʊ���� ********************/

//�༭�޸ķ�Ʊ����
function changeInv(invType, invTypeName) {
	//��Ҫ�޸ĵĵ��ݺ�
	var BillNo = $("#BillNo").val();

	//�Ƿ������ͬ�����ж�
	var billTypeArr = [];
	$("span[id^='spanInv']").each(function(i, n) {
		billTypeArr.push($(this).attr("title"));
	});
	if (billTypeArr.length > 0) {
		var orgBillTypes = billTypeArr.toString();
	} else {
		var orgBillTypes = "";
	}

	//�޸ķ�Ʊ����
	showThickboxWin('?model=finance_expense_expenseinv&action=toEitBillTypeID&BillTypeID=' + invType
	+ "&BillTypeIDName=" + invTypeName
	+ "&BillNo=" + BillNo
	+ "&orgBillTypes=" + orgBillTypes
	+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=400");
}

//ҳ��ˢ��
function show_pageInv(billTypeId, newBillTypeId, newBillType, isMarge) {
	if (isMarge == '0') {
		//������Ԫ��
		$("#imgInv" + billTypeId).after(
			'<img src="images/changeedit.gif" id="imgInv' + newBillTypeId + '" onclick="changeInv(\'' + newBillTypeId + '\',\'' + newBillType + '\')" title="�޸ķ�Ʊ����"/> ' +
			'<span id="spanInv' + newBillTypeId + '">' + newBillType + '</span>'
		);
		//�ɵ�ԭ������Ϣ
		$("#spanInv" + billTypeId).remove();
		$("#imgInv" + billTypeId).remove();
	} else {
		$("#invbody").empty();
		var BillNo = $("#BillNo").val();
		//�첽�޸�
		$.ajax({
			type: "POST",
			url: "?model=finance_expense_expenseinv&action=ajaxGetBillDetail",
			data: {'BillNo': BillNo},
			async: false,
			success: function(data) {
				if (data) {
					$("#invbody").html(data);
				}
			}
		});
	}

	//���¸�ʽ�����
	formateMoney();
}


//��Ʊ�������¸�ֵ
function recountInvoiceNumber() {
	var invoiceNumber = 0;
	$.each($("td[id^='detailInvoiceNumber']"), function(i, n) {
		invoiceNumber = accAdd(invoiceNumber, $(this).html());
	});
	$("#invoiceNumber").html(invoiceNumber);
}

//ҳ��ˢ�� �� ˢ�·��úͷ�Ʊ����
function show_pageExpense(reloadType) {
	if (reloadType == "1") {
		window.location.reload();
	} else {
		show_pageDetail();
		show_pageInv(1, 1, 1, 1);
		show_pageDetailCostshare();
		recountInvoiceNumber();
	}
}

//�鿴�ر���������
function viewSpecialApply(formNo) {
	$.ajax({
		type: "POST",
		url: "?model=general_special_specialapply&action=getId",
		data: {"formNo": formNo},
		async: false,
		success: function(id) {
			if (id != "" && id != "0") {
				showOpenWin("?model=general_special_specialapply&action=toView&id=" + id, 1, 800, 1100, id);
			} else {
				alert('û�в�ѯ����Ӧ���ر���������');
			}
		}
	});
}

/****************** ��̯���� ********************/

//�༭�޸ķ��÷�̯
function changeDetailCostShare(costType, costTypeName, mainType, mainTypeName) {
	//��Ҫ�޸ĵĵ��ݺ�
	var BillNo = $("#BillNo").val();

	//�޸ķ�������
	showThickboxWin('?model=finance_expense_expensecostshare&action=toEditDetail&CostTypeID=' + costType
	+ "&CostTypeName=" + costTypeName
	+ "&mainTypeName=" + mainTypeName
	+ "&mainType=" + mainType
	+ "&BillNo=" + BillNo
	+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800");
}

//�༭��ˢ�·��÷�̯
function show_pageDetailCostshare() {
	var costshareObj = $("#costshare");
	costshareObj.find("tbody").remove();
	var BillNo = $("#BillNo").val();
	//�첽�޸�
	$.ajax({
		type: "POST",
		url: "?model=finance_expense_expensecostshare&action=ajaxGetCostDetail",
		data: {'BillNo': BillNo},
		async: false,
		success: function(data) {
			if (data) {
				costshareObj.append(data);
			}
		}
	});
	//���¸�ʽ�����
	formateMoney();
}

/****************** ����ͳ�Ʋ��� ********************/

//�鿴����ͳ����ϸ
function showStatisticDetail(userId,areaId,feeType,thisYear) {
    showModalWin('?model=finance_expense_exsummary&action=showStatisticDetail&userId='
            + userId
            + '&areaId='
            + areaId
            + '&feeType='
            + feeType
			+ '&thisYear='
            + thisYear
            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
}

/****************** ���� ��677 ��Է��ñ���������˲������ӿ��������������͵��޸Ĺ��ܡ� �������� ******************/
$(function(){
	if($("#addCostLimit").val() == 1){
		$("#addNewType").show();
        $(".deleteNewCostType").show();
		$("#addNewType").click(function(){
			addDetail();
		})
	}else{
        $("#addNewType").hide();
		$(".deleteNewCostType").hide();
	}
})

//������������
function addDetail() {
	//��Ҫ�޸ĵĵ��ݺ�
	var BillNo = $("#BillNo").val();

	//�޸ķ�������
	showThickboxWin('?model=finance_expense_expensedetail&action=toAddDetail'
		+ "&BillNo=" + BillNo
		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=800");
}

// ɾ������ҳ�������ķ�����
var deleteCostType = function(CostTypeId,CostTypeName){
	if(confirm("ɾ���󽫰ѷ���ͳһ�˻ص�ԭ��������,���ж�����Ʊ���̯��¼,ֻ��ͳһ�˵�����һ����¼�С�\n ȷ��ɾ����"+CostTypeName+"���ķ�����Ϣ�Լ������ķ�Ʊ&��̯��Ϣ��?")){
		var deleteResult = $.ajax({
			type : "POST",
			url : '?model=finance_expense_expensedetail&action=deleteDetail',
			data : {
                CostTypeId : CostTypeId,
				BillNo : $("#BillNo").val()
			},
			async : false
		}).responseText;
        if(deleteResult == "ok"){
			alert("ɾ���ɹ�!");
			show_pageExpense(1);
		}else{
			alert("ɾ��ʧ��!");
		}
	}
}

var chkAliTripRecords = function(){
	$("#aliTripTips").remove();
	// $("#PurposeShow").after("<span id='aliTripTips' style='color: red;margin-left: 15px;'>* ���ڼ�鱾�����������Ƿ�����밢�������ص��ķ�����, �����ĵȴ�!</span>");

	// setTimeout(function(){
		var chkResult = $.ajax({
			url : 'index1.php?model=finance_expense_expense&action=checkAliTripCostRecord',
			data: {
				type : 'byBillNo',
				billNo : ($("#BillNo").val())? $("#BillNo").val() : ''
			},
			type : "POST",
			async : false
		}).responseText;
		chkResult = eval("("+chkResult+")");

		if(chkResult.msg == "ok"){
			$("#aliTripTips").remove();
			if(chkResult.result.length > 0){
				$("#PurposeShow").after("<span id='aliTripTips' style='color: red;margin-left: 15px;'>*�����������ڣ������밢�������ص��ķ����</span>");
				$.each(chkResult.result,function(i,item){
					$(".detailCostType"+item.expenseCostTypeId).css("color","red").attr("title","���ܴ����ظ��ķ���С��");
					$("#spanDetail"+item.expenseCostTypeId).css("color","red").attr("title","���ܴ����ظ��ķ���С��");
				})
			}
		}
		// console.log(chkResult.result);
	// },200);
}