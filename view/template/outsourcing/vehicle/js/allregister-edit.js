var registeridsGlobal = '';
var costCatchArrGlobal = '';
$(document).ready(function() {
	var itemTableObj = $("#registerInfo");
	$("#registerInfo").yxeditgrid({
		objName : 'allregister[register]',
		url : '?model=outsourcing_vehicle_register&action=statisticsJson',
		param : {
			dir : 'ASC',
			allregisterId : $("#id").val(),
			useCarDateLimit : $("#useCarDate").val()
		},
		isAddAndDel : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			name : 'deductInformation',
			display : '<input type="button" id="dzAddCost" class="txt_btn_a"value="�����" onclick="toAddCost(this,\'batch\')" data-type="dz"/>',
			width : 30,
			type : 'statictext',
			process : function (v,row,tr){
				var rowNum = $(tr).attr("rownum");
				if(row.rentalProperty == '����'){
					return (row.payInfoMoney1 == "0" || row.payInfoMoney1 == "" || row.payInfoMoney1 == "δ����")? "<input type='checkbox' class='recordCheckbox dz-chkebox' id='recordCheckbox"+rowNum+"' data-type='dz' data-registerIds='"+row.registerIds+"' onclick='recordCheckboxChange(this,"+rowNum+")'/>" : "-";
				}else{
				    if(row.payInfoMoney1 > 0 || row.payInfoMoney2 > 0){
                        return "-";
                    }else if((row.pay1payTypeCode == 'HETFK' && row.payInfoMoney2 == 0) || (row.pay2payTypeCode == 'HETFK' && row.payInfoMoney1 == 0)){
                    	// ��֧����ʽ1��2Ϊ��ͬ�������ʵ�,����һ��֧����ʽ��˽��Ϊ0��ʱ��,������ʾ����Ĺ�ѡ��
						return "-";
					}else{
                        return "<input type='checkbox' class='recordCheckbox cz-chkebox' id='recordCheckbox"+rowNum+"' data-type='cz' data-registerIds='"+row.registerIds+"' onclick='recordCheckboxChange(this,"+rowNum+")'/>";
                    }

				}
			}
		},{
			name : 'deductInformation',
			display : '�ۿ���Ϣ',
			width : 200,
			type : 'hidden'
		},{
			name : 'estimate',
			display : '����',
			width : 200,
			type : 'textarea'
		},{
			name : 'useCarDate',
			display : '�ó�����',
			width : 80,
			type : 'statictext',
			process : function (v,row,tr){
				var rowNum = $(tr).attr("rownum");
				return v.substr(0, 7)+"<input id='useCarDateHide"+rowNum+"' type='hidden' value='" + v + "'>";
			}
		},{
			name : 'createName',
			display : '¼����',
			width : 80,
			type : 'statictext'
		},{
			name : 'carNum',
			display : '��  ��',
			width : 80,
			type : 'statictext',
			process : function(v,row,tr){
				var rowNum = $(tr).attr("rownum");
				var needConDateFielt = (row.rentalContractId > 0)? '1' : '2';
				return "<a href='javascript:void(0)' onclick='toSeeInfoInPageInnerWin(\"?model=outsourcing_vehicle_register&action=pageView"
						+ "&needConDateFielt="+ needConDateFielt +"&carNum=" + row.carNum
						+ "&allregisterId=" + $("#id").val()
						+"&placeValuesBefore&TB_iframe=true&modal=false\",\"��" + v +"��"+ row.useCarDate.substr(0, 7) + " ���⳵�ǼǼ�¼\")'>" + v + "</a><input id='carNumHide"+rowNum+"' type='hidden' value='" + v + "'>";
			}
		},{
			name : 'carModel',
			display : '��  ��',
			width : 70,
			type : 'statictext'
		},{
			name : 'rentalContractNature',
			display : '��ͬ����',
			type : 'statictext',
			width : 70
		},{
			name : 'deductMoney',
			display : '�ۿ���',
			width : 90,
			type : 'input',
			process : function (v,row,tr){
				var deductMoney = $(v).val();
				var rowNum = $(tr).attr("rownum");
				$("#registerInfo_cmp_deductMoney"+rowNum).after("<input type='hidden' id='registerInfo_cmp_deductInfoId"+rowNum+"' value='"+row.deductInfoId+"'>");
				if(row.rentalProperty == '����'){
					$("#registerInfo_cmp_deductMoney"+rowNum).before("<span id='deductMoneyRow"+rowNum+"' data-val='"+deductMoney+"'>-</span>");
					$("#registerInfo_cmp_deductMoney"+rowNum).remove();
				}else{
					$("#registerInfo_cmp_deductMoney"+rowNum).attr("originalVal",deductMoney);
				}
                $("#registerInfo_cmp_deductMoney"+rowNum).attr("data-registerIds",row.registerIds);
			},
			event: {
				blur: function() {
					var rowNum = $(this).data("rowNum");
					updateDeductInfo(rowNum);
				}
			}
		},{
			name : 'deductReason',
			display : '�ۿ�����',
			width : 200,
			type : 'textarea',
			process : function (v,row,tr){
				var deductReason = $(v).val();
				var rowNum = $(tr).attr("rownum");
				if(row.rentalProperty == '����'){
					$("#registerInfo_cmp_deductReason"+rowNum).before("<span id='deductReasonRow"+rowNum+"' data-val='"+deductReason+"'>-</span>");
					$("#registerInfo_cmp_deductReason"+rowNum).remove();
				}
			},
			event: {
				blur: function() {
					var rowNum = $(this).data("rowNum");
					updateDeductInfo(rowNum);
				}
			}
		},{
			name : 'payInfoMoney1',
			display : '֧�����1',
			type : 'statictext',
			width : 70,
			process : function(v,row,tr){
				var rowNum = $(tr).attr("rownum");
				var str = '';
				var realNeedPayCost = Number(row.realNeedPayCost1);// ֧����ʽ1ʵ��Ӧ����
				var belongGroup = (row.belongGroup != undefined)? row.belongGroup : '';
				if(row.rentalProperty != '����' && row.isFirstCar == 1 && v > 0 && row.isFirstCzCar == 0){
					str = '-';
				}else{
					if(row.pay1payTypeCode == "HETFK"){
						str = "<input type='hidden' id='isNeedChange"+rowNum+"-1' value='0'><a href='javascript:void(0)' id='payInfo1Link"+rowNum+"' data-id='"+row.expenseTmpId1+"' data-val='"+v+"' " +
							"onclick='toAddCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\",\""+row.registerIds+"\")'>-</a>";
					}else if(v != '-' && v != ''){
						if(row.isFirstCar == 1 && v != 0){
							var showStr = "<input type='hidden' id='isNeedChange"+rowNum+"-1' value='0'><a href='javascript:void(0)' id='payInfo1Link"+rowNum+"' data-id='"+row.expenseTmpId1+"' data-val='"+v+"' " +
								"onclick='toAddCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"</a>";
							if(v != 'δ����'){
								showStr = (row.realNeedPayCost1 > 0 && Number(v) != Number(row.realNeedPayCost1))?
								"<input type='hidden' id='isNeedChange"+rowNum+"-1' value='1'><a href='javascript:void(0)' id='payInfo1Link"+rowNum+"' data-id='"+row.expenseTmpId1+"' data-val='"+v+"' style='color:red' " +
								"onclick='toAddCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"<br>(�����)</a>" :
								"<input type='hidden' id='isNeedChange"+rowNum+"-1' value='0'><a href='javascript:void(0)' id='payInfo1Link"+rowNum+"' data-id='"+row.expenseTmpId1+"' data-val='"+v+"' " +
								"onclick='toAddCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"</a>";
							}else if(Number(v) == 0 && Number(row.realNeedPayCost1) == 0){
								showStr = v;
							}

							str = (row.rentalProperty == '����' && (row.payInfoMoney1 == "δ����" || row.payInfoMoney1 == "0" || row.payInfoMoney1 == ""))? v : showStr;
						}else{
							str = "-";
						}
					}else{
						str = "-";
					}
				}
				str = "<input type='hidden' id='belongGroup"+rowNum+"' value='"+belongGroup+"'>" +
					"<input type='hidden' id='realNeedPayCostOne"+rowNum+"' value='"+realNeedPayCost+"'>" +
					"<input type='hidden' id='payInfo1Id"+rowNum+"' value='"+row.payInfoId1+"'>"+str;
				return str;
			}
		},{
			name : 'payInfoMoney2',
			display : '֧�����2',
			type : 'statictext',
			width : 70,
			process : function(v,row,tr){
				var rowNum = $(tr).attr("rownum");
				var str = '';
				var realNeedPayCost = Number(row.realNeedPayCost2);// ֧����2ʽʵ��Ӧ����
				var belongGroup = (row.belongGroup != undefined)? row.belongGroup : '';
				if(row.rentalProperty != '����' && row.isFirstCar == 1 && v > 0 && row.isFirstCzCar == 0){
					str = '-';
				}else{
					if(row.pay2payTypeCode == "HETFK"){
						str = "<input type='hidden' id='isNeedChange"+rowNum+"-2' value='0'><a href='javascript:void(0)' id='payInfo2Link"+rowNum+"' data-id='"+row.expenseTmpId2+"' data-val='"+v+"' " +
							"onclick='toAddCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>-</a>";
					}else if(v != '-' && v != ''){
						var showStr = "<input type='hidden' id='isNeedChange"+rowNum+"-2' value='0'><a href='javascript:void(0)' id='payInfo2Link"+rowNum+"' data-id='"+row.expenseTmpId2+"' data-val='"+v+"' " +
							"onclick='toAddCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"</a>";
						if(v != 'δ����' && v > 0){
							showStr = (row.realNeedPayCost2 >= 0 && Number(v) != Number(row.realNeedPayCost2))?
							"<input type='hidden' id='isNeedChange"+rowNum+"-2' value='1'><a href='javascript:void(0)' id='payInfo2Link"+rowNum+"' data-id='"+row.expenseTmpId2+"' data-val='"+v+"' style='color:red' " +
							"onclick='toAddCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"<br>(�����)</a>" :
							"<input type='hidden' id='isNeedChange"+rowNum+"-2' value='0'><a href='javascript:void(0)' id='payInfo2Link"+rowNum+"' data-id='"+row.expenseTmpId2+"' data-val='"+v+"' " +
							"onclick='toAddCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"</a>";
						}else if(Number(v) == 0 && Number(row.realNeedPayCost2) == 0){
							showStr = (row.rentalProperty == '����' || v == 0)? '-' : v;
						}
						str = showStr;
					}else{
						str = "-";
					}
				}
				if(row.rentalProperty == '����'){
					str = '-';
				}
				str = "<input type='hidden' id='realNeedPayCostTwo"+rowNum+"' value='"+realNeedPayCost+"'>" +
					"<input type='hidden' id='payInfo2Id"+rowNum+"' value='"+row.payInfoId2+"'>"+str;
				return str;
			}
		},{
			name : 'rentalProperty',
			display : '�⳵����',
			width : 70,
			type : 'statictext'
		},{
			name : 'contractUseDay',
			display : '��ͬ�ó�����',
			width : 70,
			type : 'statictext',
			process : function (v ,row) {
				if (row.rentalPropertyCode == 'ZCXZ-02') {
					return '';
				} else {
					return v;
				}
			}
		},{
			name : 'registerNum',
			display : 'ʵ���ó�����',
			width : 70,
			type : 'statictext'
		},{
			name : 'rentalPropertyCode',
			display : '���ص��⳵���ʱ���',
			type : 'hidden'
		},{
			name : 'suppName',
			display : '��Ӧ������',
			width : 130,
			type : 'statictext'
		},{
			display : '�⳵��ͬID',
			name : 'rentalContractId',
			type : 'hidden'
		},{
			name : 'rentalContractCode',
			display : '���ص��⳵��ͬ���',
			type : 'hidden'
		},{
			name : 'rentalContractCode',
			display : '�⳵��ͬ���',
			width : 120,
			type : 'statictext',
			process : function (v,row){
				var str = '';
				if(row.rentalContractId != '' && v != ''){
					str = "<a href='javascript:void(0)' onclick='toSeeInfoInPageInnerWin(\"index1.php?model=outsourcing_contract_rentcar&action=viewTab&notCloseBtn=1&id="+row.rentalContractId+"\",\"�⳵��ͬ��Ϣ\")'>"+v+"</a>";
					// str = "<a href='index1.php?model=outsourcing_contract_rentcar&action=viewTab&id="+row.rentalContractId+"' target='_blank'>"+v+"</a>"
				}
				return str;
			}
		},{
			name : 'effectMileage',
			display : '��Ч���',
			width : 80,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolinePrice',
			display : '�ͼۣ�Ԫ��',
			width : 80,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolineKMPrice',
			display : '������Ƽ��ͷѵ��ۣ�Ԫ��',
			width : 140,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolineKMCost',
			display : '������Ƽ��ͷѣ�Ԫ��',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'reimbursedFuel',
			display : 'ʵ��ʵ���ͷѣ�Ԫ��',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'parkingCost',
			display : 'ͣ���ѣ�Ԫ��',
			type : 'statictext',
			width : 120,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'tollCost',
			display : '·�ŷѣ�Ԫ��',
			type : 'statictext',
			width : 120,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'rentalCarCost',
			display : '�⳵�ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'mealsCost',
			display : '�����ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'accommodationCost',
			display : 'ס�޷ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'overtimePay',
			display : '�Ӱ�ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'specialGas',
			display : '�����ͷѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'allCost',
			display : '�ܷ��ã�Ԫ��',
			width : 90,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'effectLogTime',
			display : '��ЧLOGʱ��',
			width : 120,
			type : 'statictext'
		}]
	});

});

var recordCheckboxChange = function(obj,rowNum){
	var isChecked = $(obj).is(':checked');
	var chkBoxType = $(obj).attr('data-type');
	switch(chkBoxType){
		case 'dz':// ����
			$("#dzAddCost").attr("data-type","dz");
			if(isChecked){
				$(".cz-chkebox").each(function(){
					$(this).removeAttr("checked");
					$(this).attr("disabled",true);
				});
			}else{
				if($(".dz-chkebox:checked").length <= 0 ){
					registeridsGlobal = '';
					$(".cz-chkebox").each(function(){
						$(this).removeAttr("disabled");
					});
				}
			}
			break;
		case 'cz':// ����
			$("#dzAddCost").attr("data-type","cz");
			if(isChecked){
				$(".dz-chkebox").each(function(){
					$(this).removeAttr("checked");
					$(this).attr("disabled",true);
				});
				var checkedContractId = $("#registerInfo_cmp_rentalContractId"+rowNum).val();
				$.each($(':input[id^="registerInfo_cmp_rentalContractId"]'), function(i, n) {
					var rowNum = i;
					if($(n).val() != checkedContractId){
						$("#recordCheckbox"+rowNum).removeAttr("checked");
						$("#recordCheckbox"+rowNum).attr("disabled",true);
					}
				})
				var link1Str = $("#payInfo1Link"+rowNum).text();
				var link2Str = $("#payInfo2Link"+rowNum).text();
				$("#payInfo1Link"+rowNum).after("<span id='payInfo1LinkStr"+rowNum+"'>"+link1Str+"</span>").hide();
				$("#payInfo2Link"+rowNum).after("<span id='payInfo2LinkStr"+rowNum+"'>"+link2Str+"</span>").hide();
			}else{
				$("#payInfo1LinkStr"+rowNum).remove();$("#payInfo1Link"+rowNum).show();
				$("#payInfo2LinkStr"+rowNum).remove();$("#payInfo2Link"+rowNum).show();
				if($(".cz-chkebox:checked").length <= 0 ){
					registeridsGlobal = '';
					$("#dzAddCost").attr("data-type","dz");
					$(".dz-chkebox").each(function(){
						$(this).removeAttr("disabled");
					});
					$(".cz-chkebox").each(function(){
						$(this).removeAttr("disabled");
					});
				}
			}
			break;
	}
}

// �����Ƿ������Ҫ���µĽ��
function chkOldCostUpdate(){
	var dataList = $.ajax({
		type : "POST",
		url : '?model=outsourcing_vehicle_register&action=statisticsJson',
		data : {
			dir : 'ASC',
			allregisterId : $("#id").val(),
			useCarDateLimit : $("#useCarDate").val()
		},
		async : false
	}).responseText;
	var flag = true;
	if(dataList != ''){
		dataList = eval("("+dataList+")");
		if(dataList.length > 0){
			$.each(dataList,function(i,data){
				if(data.pay1payTypeCode != 'HETFK'){
					var payInfoMoney1 = (data.payInfoMoney1 > 0)? data.payInfoMoney1 : 0;
					var payInfoMoney2 = (data.payInfoMoney2 > 0)? data.payInfoMoney2 : 0;
					var realNeedPayCost1 = (data.realNeedPayCost1 > 0 && data.payInfoMoney1 != '-')? data.realNeedPayCost1 : 0;
					var realNeedPayCost2 = (data.realNeedPayCost2 > 0 && data.payInfoMoney2 != '-')? data.realNeedPayCost2 : 0;
					var allCost = Number(realNeedPayCost1) + Number(realNeedPayCost2);
					flag = (flag)? Number(allCost) == (Number(payInfoMoney1) + Number(payInfoMoney2)) : flag;
				}
				// console.log("all: "+allCost);
				// console.log("money1: "+payInfoMoney1);
				// console.log("money2: "+payInfoMoney2);
			});
		}
	}
	return flag;
}

//ֱ���ύ
function toSubmit(act){
	var pass = true;
	if(act == 'audit'){
		// �����⳵���ܼ�¼��������
		$("div[id^='registerInfo_cmp_rentalContractNature']").each(function(){
			var contractType = $(this).text();
			var rowNum = $(this).parent("td").parent("tr").attr("rownum");
			if(contractType == "�޿����ͬ" || contractType == "��"){
				var pay1Result = $("#registerInfo_cmp_payInfoMoney1"+rowNum).children("a").text();
				var pay1ResultT = $("#registerInfo_cmp_payInfoMoney1"+rowNum).text();
				var pay2Result = $("#registerInfo_cmp_payInfoMoney2"+rowNum).children("a").text();
				var pay2ResultT = $("#registerInfo_cmp_payInfoMoney2"+rowNum).text();
				if(pay1Result == "δ����" || pay2Result == "δ����" || pay1ResultT == "δ����" || pay2ResultT == "δ����"){
					alert("�ǿ����ͬ,����ȫ������ñ�󣬷����ύ�⳵�Ǽǻ���������");
					pass = false;
					return false;
				}
			}
		});

		if(pass){
			// �����Ƿ������Ҫ���µĽ��
			var chkResult = chkOldCostUpdate();
			pass = chkResult;
			if(!chkResult){
				alert("������Ҫ���µ�֧�����,�������Ӧ������ú����ύ��");
				return false;
			}
		}
	}

	// ���ۿ���Ϣ
	if(pass){
		$.each($(':input[id^="registerInfo_cmp_deductMoney"]'), function(i, n) {
			var rowNum = i;
			var chkDeductRs = deductInfoChk(rowNum,"chkForm");
			if(!chkDeductRs){
				pass = false;
				return false;
			}
		})
	}

	if(pass){
		var dateArr = $("#useCarDate").val();
		var nowData = new Date();
		var nowYear = nowData.getFullYear(); //��ȡ��
		var nowMonth = nowData.getMonth() + 1; //��ȡ��
		var year = parseInt(dateArr.substr(0 ,4));
		var month = parseInt(dateArr.substr(5 ,2));
		var tmp = true; //�ж��Ƿ���һ����
		if (nowYear < year) {
			tmp = false;
		}else if (nowMonth <= month &&��nowYear == year) {
			tmp = false;
		}
		// console.log("nowMonth: "+nowMonth+"; month: "+month+"; nowYear: "+nowYear+"; year: "+year);

		if (tmp) {
			if(act == 'audit'){
				document.getElementById('form1').action="?model=outsourcing_vehicle_allregister&action=edit&actType=audit";
			}else{
				document.getElementById('form1').action="?model=outsourcing_vehicle_allregister&action=edit";
			}
			$("#form1").submit();
		} else {
			alert("��ǰ��¼��δ��һ����ʱ�䣡");
		}
	}
}

// ͳ��ѡ�еĶ���ķ�����Ϣ
function sumCheckedCost(){
	var costCatchArr = {
		'gasolinePrice' : 0,
		'gasolineKMPrice' : 0,
		'gasolineKMCost' : 0,
		'reimbursedFuel' : 0,
		'parkingCost' : 0,
		'tollCost' : 0,
		'rentalCarCost' : 0,
		'mealsCost' : 0,
		'accommodationCost' : 0,
		'overtimePay' : 0,
		'specialGas' : 0
	};
	var selectedRowNum = '';

	if($(".recordCheckbox").length <= 0){
		alert("�޿ɲ����ļ�¼;");
		return false;
	}else{
		var carNum = '',useCarDate = '',rowIds = '',registerIds = '';
		$.each($(".recordCheckbox"),function(i,item){
			if($(item).attr("checked")){
				if($(item).attr("data-registerids") != undefined && $(item).attr("data-registerids") != ''){
					registerIds += (registerIds == '')? $(item).attr("data-registerids") : ","+$(item).attr("data-registerids");
				}

				// ��ȡ��ǰ���ڵ�����
				var index = $(item).parent("div").parent("td").parent("tr").attr("rownum");
				selectedRowNum += (selectedRowNum == '')? index : ','+index;
				var gasolinePrice = $("#registerInfo_cmp_gasolinePrice"+index).text();// �ͷ�
				costCatchArr.gasolinePrice += (Number(gasolinePrice.replace(/,/g,'')) >= 0)? Number(gasolinePrice.replace(/,/g,'')) : 0;
				var gasolineKMPrice = $("#registerInfo_cmp_gasolineKMPrice"+index).text();// ������Ƽ��ͷѵ���
				costCatchArr.gasolineKMPrice = (Number(gasolineKMPrice.replace(/,/g,'')) >= 0)? Number(gasolineKMPrice.replace(/,/g,'')) : 0;
				var effectMileage = $("#registerInfo_cmp_effectMileage"+index).text();// ��Ч���
				costCatchArr.effectMileage = (Number(effectMileage.replace(/,/g,'')) >= 0)? Number(effectMileage.replace(/,/g,'')) : 0;
				var gasolineKMCost = $("#registerInfo_cmp_gasolineKMCost"+index).text();// ������Ƽ��ͷ�
				costCatchArr.gasolineKMCost += (Number(gasolineKMCost.replace(/,/g,'')) >= 0)? Number(gasolineKMCost.replace(/,/g,'')) : 0;
				var reimbursedFuel = $("#registerInfo_cmp_reimbursedFuel"+index).text();// ʵ��ʵ���ͷ�
				costCatchArr.reimbursedFuel += (Number(reimbursedFuel.replace(/,/g,'')) >= 0)? Number(reimbursedFuel.replace(/,/g,'')) : 0;
				var parkingCost = $("#registerInfo_cmp_parkingCost"+index).text();// ͣ����
				costCatchArr.parkingCost += (Number(parkingCost.replace(/,/g,'')) >= 0)? Number(parkingCost.replace(/,/g,'')) : 0;
				var tollCost = $("#registerInfo_cmp_tollCost"+index).text();// ·�ŷ�
				costCatchArr.tollCost += (Number(tollCost.replace(/,/g,'')) >= 0)? Number(tollCost.replace(/,/g,'')) : 0;
				var rentalCarCost = $("#registerInfo_cmp_rentalCarCost"+index).text();// �⳵��
				costCatchArr.rentalCarCost += (Number(rentalCarCost.replace(/,/g,'')) >= 0)? Number(rentalCarCost.replace(/,/g,'')) : 0;
				var mealsCost = $("#registerInfo_cmp_mealsCost"+index).text();// ������
				costCatchArr.mealsCost += (Number(mealsCost.replace(/,/g,'')) >= 0)? Number(mealsCost.replace(/,/g,'')) : 0;
				var accommodationCost = $("#registerInfo_cmp_accommodationCost"+index).text();// ס�޷�
				costCatchArr.accommodationCost += (Number(accommodationCost.replace(/,/g,'')) >= 0)? Number(accommodationCost.replace(/,/g,'')) : 0;
				var overtimePay = $("#registerInfo_cmp_overtimePay"+index).text();// �Ӱ��
				costCatchArr.overtimePay += (Number(overtimePay.replace(/,/g,'')) >= 0)? Number(overtimePay.replace(/,/g,'')) : 0;
				var specialGas = $("#registerInfo_cmp_specialGas"+index).text();// �����ͷ�
				costCatchArr.specialGas += (Number(specialGas.replace(/,/g,'')) >= 0)? Number(specialGas.replace(/,/g,'')) : 0;
				costCatchArr.rentalPropertyCode = $("#registerInfo_cmp_rentalPropertyCode"+index).val();// �⳵����

				var thisCarNum = $("#registerInfo_cmp_carNum"+index).children("input").val();
				var thisUseCarDate = $("#registerInfo_cmp_useCarDate"+index).children("input").val();
				carNum += (thisCarNum != undefined)? ( (carNum == "")? thisCarNum : ","+thisCarNum) : '';// ���ƺ�
				useCarDate += (thisUseCarDate != undefined)? ( (useCarDate == "")? thisUseCarDate : ","+thisUseCarDate) : '';// �ó�����
				rowIds += (rowIds == "")? index : ","+index;
			}

		});
		costCatchArr.rowIds = rowIds;
		costCatchArr.carNum = carNum;
		costCatchArr.useCarDate = useCarDate;
		registeridsGlobal = registerIds;

		if(selectedRowNum == ''){
			alert("������ѡ��һ������ļ�¼;");
			return false;
		}else{
			var backArr = {
				sltedRows : selectedRowNum,
				costCatchArr : costCatchArr
			}
			return backArr;
		}
	}
}

var sumCheckedCostForCZ = function(){
	var costCatchArr = {
		'gasolinePrice' : 0,
		'gasolineKMPrice' : 0,
		'gasolineKMCost' : 0,
		'reimbursedFuel' : 0,
		'parkingCost' : 0,
		'tollCost' : 0,
		'rentalCarCost' : 0,
		'mealsCost' : 0,
		'accommodationCost' : 0,
		'overtimePay' : 0,
		'specialGas' : 0
	};
	var selectedRowNum = '';

	if($(".recordCheckbox").length <= 0){
		alert("�޿ɲ����ļ�¼;");
		return false;
	}else{
		var retalContractId = '',carNum = '',useCarDate = '',rowIds = '',registerIds = '',payInfoId = '',deductInfoId = '',pass = true;
		$.each($(".recordCheckbox"),function(i,item){
			// ��ȡ��ǰ���ڵ�����
			if($(item).is(':checked')){
				var index = $(item).parent("div").parent("td").parent("tr").attr("rownum");

				// ����ѡ���������д�ۿ���,��Ϊ�ۿ����������Ƿֿ����,����ŵ��������,����ۿ�ر���ҳ��,�ᵼ�¿ۿ���Ϣ������ϵ�ϵ�,���������ݶ�ʧ
				if($("#registerInfo_cmp_deductInfoId"+index).val() == '' || $("#registerInfo_cmp_deductInfoId"+index).val() == 'undefined'){
					if(pass){
						alert("ѡ���к���δ��д�ۿ�����Ŀ�ģ����޿ۿ���0��");
					}
					$("#registerInfo_cmp_deductMoney"+index).focus();
					pass = false;
				}else{
					deductInfoId += (deductInfoId == '')? $("#registerInfo_cmp_deductInfoId"+index).val() : ","+$("#registerInfo_cmp_deductInfoId"+index).val();
					if(payInfoId == ''){
						payInfoId = $("#payInfo1Id"+index).val();
						if($("#payInfo2Id"+index).val() != ''){
							payInfoId += ","+$("#payInfo2Id"+index).val();
						}
					}

					if($(item).attr("data-registerids") != undefined && $(item).attr("data-registerids") != ''){
						registerIds += (registerIds == '')? $(item).attr("data-registerids") : ","+$(item).attr("data-registerids");
					}

					selectedRowNum += (selectedRowNum == '')? index : ','+index;
					retalContractId = (retalContractId == '' || retalContractId == undefined)? $("#registerInfo_cmp_rentalContractId"+index).val() : retalContractId;
					var gasolinePrice = $("#registerInfo_cmp_gasolinePrice"+index).text();// �ͷ�
					costCatchArr.gasolinePrice += (Number(gasolinePrice.replace(/,/g,'')) >= 0)? Number(gasolinePrice.replace(/,/g,'')) : 0;
					var gasolineKMPrice = $("#registerInfo_cmp_gasolineKMPrice"+index).text();// ������Ƽ��ͷѵ���
					costCatchArr.gasolineKMPrice = (Number(gasolineKMPrice.replace(/,/g,'')) >= 0)? Number(gasolineKMPrice.replace(/,/g,'')) : 0;
					var effectMileage = $("#registerInfo_cmp_effectMileage"+index).text();// ��Ч���
					costCatchArr.effectMileage = (Number(effectMileage.replace(/,/g,'')) >= 0)? Number(effectMileage.replace(/,/g,'')) : 0;
					var gasolineKMCost = $("#registerInfo_cmp_gasolineKMCost"+index).text();// ������Ƽ��ͷ�
					costCatchArr.gasolineKMCost += (Number(gasolineKMCost.replace(/,/g,'')) >= 0)? Number(gasolineKMCost.replace(/,/g,'')) : 0;
					var reimbursedFuel = $("#registerInfo_cmp_reimbursedFuel"+index).text();// ʵ��ʵ���ͷ�
					costCatchArr.reimbursedFuel += (Number(reimbursedFuel.replace(/,/g,'')) >= 0)? Number(reimbursedFuel.replace(/,/g,'')) : 0;
					var parkingCost = $("#registerInfo_cmp_parkingCost"+index).text();// ·��ͣ����
					costCatchArr.parkingCost += (Number(parkingCost.replace(/,/g,'')) >= 0)? Number(parkingCost.replace(/,/g,'')) : 0;
					var tollCost = $("#registerInfo_cmp_tollCost"+index).text();// ·��ͣ����
					costCatchArr.tollCost += (Number(tollCost.replace(/,/g,'')) >= 0)? Number(tollCost.replace(/,/g,'')) : 0;
					var rentalCarCost = $("#registerInfo_cmp_rentalCarCost"+index).text();// �⳵��
					costCatchArr.rentalCarCost += (Number(rentalCarCost.replace(/,/g,'')) >= 0)? Number(rentalCarCost.replace(/,/g,'')) : 0;
					var mealsCost = $("#registerInfo_cmp_mealsCost"+index).text();// ������
					costCatchArr.mealsCost += (Number(mealsCost.replace(/,/g,'')) >= 0)? Number(mealsCost.replace(/,/g,'')) : 0;
					var accommodationCost = $("#registerInfo_cmp_accommodationCost"+index).text();// ס�޷�
					costCatchArr.accommodationCost += (Number(accommodationCost.replace(/,/g,'')) >= 0)? Number(accommodationCost.replace(/,/g,'')) : 0;
					var overtimePay = $("#registerInfo_cmp_overtimePay"+index).text();// �Ӱ��
					costCatchArr.overtimePay += (Number(overtimePay.replace(/,/g,'')) >= 0)? Number(overtimePay.replace(/,/g,'')) : 0;
					var specialGas = $("#registerInfo_cmp_specialGas"+index).text();// �����ͷ�
					costCatchArr.specialGas += (Number(specialGas.replace(/,/g,'')) >= 0)? Number(specialGas.replace(/,/g,'')) : 0;
					costCatchArr.rentalPropertyCode = $("#registerInfo_cmp_rentalPropertyCode"+index).val();// �⳵����

					var thisCarNum = $("#registerInfo_cmp_carNum"+index).children("input").val();
					var thisUseCarDate = $("#registerInfo_cmp_useCarDate"+index).children("input").val();
					carNum += (thisCarNum != undefined)? ( (carNum == "")? thisCarNum : ","+thisCarNum) : '';// ���ƺ�
					useCarDate += (thisUseCarDate != undefined)? ( (useCarDate == "")? thisUseCarDate : ","+thisUseCarDate) : '';// �ó�����
					rowIds += (rowIds == "")? index : ","+index;
				}
			}
		})

		costCatchArr.rowIds = rowIds;
		costCatchArr.carNum = carNum;
		costCatchArr.useCarDate = useCarDate;
		registeridsGlobal = registerIds;
		costCatchArr.registerIds = registerIds;
		costCatchArr.payInfoId = payInfoId;
		costCatchArr.deductInfoId = deductInfoId;
		costCatchArr.retalContractId = retalContractId;

		if(selectedRowNum == '' && pass){
			alert("������ѡ��һ������ļ�¼;");
			return false;
		}else{
			var backArr = {
				sltedRows : selectedRowNum,
				costCatchArr : costCatchArr
			}
			return (pass)? backArr : false;
		}
	}
}

// ��ӱ�����¼��Ϣ
function toAddCost(elmObj,payInfoId,expenseTmpId,registerIds,belongGroup){
	var isCzBatchAdd = false;// �����������ʾ
	var costCatchArr = {
		'gasolinePrice' : 0,
		'gasolineKMPrice' : 0,
		'gasolineKMCost' : 0,
		'reimbursedFuel' : 0,
		'parkingCost' : 0,
		'tollCost' : 0,
		'rentalCarCost' : 0,
		'mealsCost' : 0,
		'accommodationCost' : 0,
		'overtimePay' : 0,
		'specialGas' : 0,
		'deductInfoId' : ''
	};

	var index = '',pass = true;
	if(payInfoId == 'batch'){
		costCatchArr.payInfoId = '';
		var dataType = $(elmObj).attr("data-type");
		switch(dataType){
			case 'dz':
				var resultArr = sumCheckedCost();
				costCatchArr = resultArr.costCatchArr;
				if(!costCatchArr){
					pass = false;
					return false;
				}
				break;
			case 'cz':
				isCzBatchAdd = true;
				var resultArr = sumCheckedCostForCZ();
				costCatchArr = resultArr.costCatchArr;
				if(!costCatchArr){
					pass = false;
					return false;
				}
				costCatchArrGlobal = costCatchArr;
				break;
		}
	}else{
		// ��ȡ��ǰ���ڵ�����
		var indexVal= $(elmObj).parent("div").parent("td").parent("tr").attr("rownum");

		// ������ںϲ������, ͳ��ͬһ�����ؼ�¼��
		var groupRows = [];
		if(belongGroup > 0){
			$.each($(':input[id^="belongGroup"]'), function(i, n) {
				if($(n).val() == belongGroup){
					groupRows.push(i.toString());
				}
			});
		}else{
			groupRows = [indexVal];
		}

		$.each(groupRows,function(i,index){
			var retalContractId = $("#registerInfo_cmp_rentalContractId"+index).val();
			var rentalPropertype = $("#registerInfo_cmp_rentalProperty"+index).text();
			var gasolinePrice = $("#registerInfo_cmp_gasolinePrice"+index).text();// �ͷ�
			costCatchArr.gasolinePrice += (Number(gasolinePrice.replace(/,/g,'')) >= 0)? Number(gasolinePrice.replace(/,/g,'')) : 0;
			var gasolineKMPrice = $("#registerInfo_cmp_gasolineKMPrice"+index).text();// ������Ƽ��ͷѵ���
			costCatchArr.gasolineKMPrice += (Number(gasolineKMPrice.replace(/,/g,'')) >= 0)? Number(gasolineKMPrice.replace(/,/g,'')) : 0;
			var effectMileage = $("#registerInfo_cmp_effectMileage"+index).text();// ��Ч���
			costCatchArr.effectMileage += (Number(effectMileage.replace(/,/g,'')) >= 0)? Number(effectMileage.replace(/,/g,'')) : 0;
			var gasolineKMCost = $("#registerInfo_cmp_gasolineKMCost"+index).text();// ������Ƽ��ͷ�
			costCatchArr.gasolineKMCost += (Number(gasolineKMCost.replace(/,/g,'')) >= 0)? Number(gasolineKMCost.replace(/,/g,'')) : 0;
			var reimbursedFuel = $("#registerInfo_cmp_reimbursedFuel"+index).text();// ʵ��ʵ���ͷ�
			costCatchArr.reimbursedFuel += (Number(reimbursedFuel.replace(/,/g,'')) >= 0)? Number(reimbursedFuel.replace(/,/g,'')) : 0;
			var parkingCost = $("#registerInfo_cmp_parkingCost"+index).text();// ͣ����
			costCatchArr.parkingCost += (Number(parkingCost.replace(/,/g,'')) >= 0)? Number(parkingCost.replace(/,/g,'')) : 0;
			var tollCost = $("#registerInfo_cmp_tollCost"+index).text();// ·�ŷ�
			costCatchArr.tollCost += (Number(tollCost.replace(/,/g,'')) >= 0)? Number(tollCost.replace(/,/g,'')) : 0;
			var rentalCarCost = $("#registerInfo_cmp_rentalCarCost"+index).text();// �⳵��
			costCatchArr.rentalCarCost += (Number(rentalCarCost.replace(/,/g,'')) >= 0)? Number(rentalCarCost.replace(/,/g,'')) : 0;
			var mealsCost = $("#registerInfo_cmp_mealsCost"+index).text();// ������
			costCatchArr.mealsCost += (Number(mealsCost.replace(/,/g,'')) >= 0)? Number(mealsCost.replace(/,/g,'')) : 0;
			var accommodationCost = $("#registerInfo_cmp_accommodationCost"+index).text();// ס�޷�
			costCatchArr.accommodationCost += (Number(accommodationCost.replace(/,/g,'')) >= 0)? Number(accommodationCost.replace(/,/g,'')) : 0;
			var overtimePay = $("#registerInfo_cmp_overtimePay"+index).text();// �Ӱ��
			costCatchArr.overtimePay += (Number(overtimePay.replace(/,/g,'')) >= 0)? Number(overtimePay.replace(/,/g,'')) : 0;
			var specialGas = $("#registerInfo_cmp_specialGas"+index).text();// �����ͷ�
			costCatchArr.specialGas += (Number(specialGas.replace(/,/g,'')) >= 0)? Number(specialGas.replace(/,/g,'')) : 0;
			costCatchArr.rentalPropertyCode = $("#registerInfo_cmp_rentalPropertyCode"+index).val();// �⳵����

			costCatchArr.carNum = $("#registerInfo_cmp_carNum"+index).children("input").val();// ���ƺ�
			costCatchArr.useCarDate = $("#registerInfo_cmp_useCarDate"+index).children("input").val();// �ó�����
			costCatchArr.rowIds = index;

			// ����Ƿ���д�ۿ���
			var rentalProperty = $("#registerInfo_cmp_rentalProperty"+index).text();
			if(rentalProperty != '����'){
				costCatchArr.deductInfoId += (costCatchArr.deductInfoId == '')? $("#registerInfo_cmp_deductInfoId"+index).val() : ","+$("#registerInfo_cmp_deductInfoId"+index).val();
				var deductChk = deductInfoChk(index,"chkForm");
				if(!deductChk){
					pass = false;
					return false;
				}
			}
		});

		registeridsGlobal = registerIds;
		costCatchArr.payInfoId = (payInfoId == undefined)? '' : payInfoId;
	}

	costCatchArr.rowIds = (costCatchArr.rowIds != undefined)? costCatchArr.rowIds : '';
	costCatchArr.expenseTmpId = expenseTmpId;
	costCatchArr.allregisterId = ($("#id").val() == undefined)? '' : $("#id").val();
	var addCostApply = costCatchArr;
	costCatchArr = {addCostApply : addCostApply};
	// console.log(costCatchArr);

	if(isCzBatchAdd){
		var url = 'index1.php?model=outsourcing_vehicle_rentalcar&action=toBatchAddCostExpense' +
			'&expenseTmpId='+$("#expenseTmpId").val() +
			'&useCarDate='+$("#useCarDate").val() +
			'&costInfo='+$("#costInfoArr").val() +
			'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=580&width=800';
		popUpANewWin(url);
	}else if(pass){
		$.ajax({
			type: "POST",
			url: "?model=outsourcing_vehicle_rentalcar&action=toSeeCostExpense&fromPage=Edit",
			async: false,
			data : costCatchArr,
			success: function(data){
				$("#payInfoInner").html("");
				if(data != ""){
					$("#payInfoInner").html("<div id='payInfoInnerWrap'>" + data + "</div>");

					// ����ñ�
					$("#toAddCost").unbind("click");
					$("#toAddCost").bind("click",function(){
						setTimeout(function(){
							$("#payInfoInner").dialog("close");
						}, 200);
						var url = 'index1.php?model=outsourcing_vehicle_rentalcar&action=toAddCostExpense' +
							'&expenseTmpId='+$("#expenseTmpId").val() +
							'&useCarDate='+$("#useCarDate").val() +
							'&costInfo='+$("#costInfoArr").val() +
							'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800';
						popUpANewWin(url);
					});

					// ����
					$("#toPayCost").unbind("click");
					$("#toPayCost").bind("click",function(){
						var url = '';
						alert("���⳵����Ա��⳵֧�����á�");
					});
				};

				$("#payInfoInner").dialog({
					title : '����⳵����������Ϣ',
					height : 300,
					width : 600,
					modal: true
				}).dialog('open');
			}
		});

		// ��IE�Ϲرպ��ٴ�Dialogҳ��,����ҳ��������,��ʱ��������,ǿ�аѵ�����ʾ����
		$("#payInfoInner").parent('.panel.window').show();
		$("#payInfoInner").parent('.panel.window').next(".window-shadow").show();
		$("#payInfoInner").parent('.panel.window').next(".window-shadow").next(".window-mask").show();
	}
}

// ���´�һ������
var popUpANewWin = function(url){
	setTimeout(function(){
		showThickboxWin(url);
	}, 400);
};

// ���¼��������б�
var reloadList = function(){
	$("#registerInfo").yxeditgrid('processData');
};

// ����ۿ���Ϣ
var deductInfoChk = function (rowNum,forAct){
	var chkresult = true;
	var deductMoney = $("#registerInfo_cmp_deductMoney"+rowNum).val();
	var originalVal = $("#registerInfo_cmp_deductMoney"+rowNum).attr("originalVal");
	originalVal = (originalVal == '')? '' : Number(originalVal);
	var deductReason = $("#registerInfo_cmp_deductReason"+rowNum).val();
	$("#registerInfo_cmp_deductReason"+rowNum).removeAttr("isRequire");
	$("#registerInfo_cmp_deductReason"+rowNum).css("border-color","initial");
	var rentalCarCost = $("#registerInfo_cmp_rentalCarCost"+rowNum).text();// �⳵��
	rentalCarCost = Number(rentalCarCost.replace(/,/g,''));

	if(deductMoney != undefined){
		if((deductMoney == '' && originalVal !== '') || isNaN(deductMoney) || deductMoney < 0){
			alert("�ۿ���ֻ��Ϊ0����ֵ!");
			$("#registerInfo_cmp_deductMoney"+rowNum).val(originalVal);
			chkresult = false;
		}else if(deductMoney > 0){// ��ۿ����0����ô�ۿ�����Ϊ���
			if(deductMoney > rentalCarCost){
				alert("�ۿ���ô��ڴ˼�¼���⳵��!");
				$("#registerInfo_cmp_deductMoney"+rowNum).val(originalVal);
				chkresult = false;
			}else{
				$("#registerInfo_cmp_deductReason"+rowNum).attr("isRequire","1");
				if(deductReason == ''){
					$("#registerInfo_cmp_deductReason"+rowNum).css("border-color","red");
					if(forAct == "chkForm"){
						alert("���ۿ����ҷ�0ʱ, �ۿ����ɲ���Ϊ�ա�");
						$("#registerInfo_cmp_deductReason"+rowNum).focus();
					}
					chkresult = false;
				}
			}
		}else{
			chkresult = !(deductMoney == '');
			if(forAct == "chkForm" && !chkresult){
				alert("�ۿ�����ĿΪ������޿ۿ���0��");
				$("#registerInfo_cmp_deductMoney"+rowNum).focus();
			}
		}
	}
	return chkresult;
};

// �첽���¿ۿ���Ϣ
var updateDeductInfo = function(rowNum){
	var allregisterId = $("#id").val();
	var useCarDate = $("#useCarDateHide"+rowNum).val();
	var carNum = $("#carNumHide"+rowNum).val();
	var deductMoney = $("#registerInfo_cmp_deductMoney"+rowNum).val();
    var registerIds = $("#registerInfo_cmp_deductMoney"+rowNum).attr("data-registerIds");
	var deductReason = $("#registerInfo_cmp_deductReason"+rowNum).val();
	var rentalProperty = $("#registerInfo_cmp_rentalProperty"+rowNum).text();
	var expensetmpId = '';// �������ʳ��¼ID
	var payInfoIds = '';// ֧����ʽ��ӦID

	if(rentalProperty != "����"){// ����ʱ��,ƴ�ӵ�ǰ��¼�ڵ�����֧������ID
		payInfoIds += ($("#payInfo1Id"+rowNum).val() != '' && $("#payInfo1Id"+rowNum).val() != undefined)? $("#payInfo1Id"+rowNum).val() : '';
		if($("#payInfo2Id"+rowNum).val() != '' && $("#payInfo2Id"+rowNum).val() != undefined){
			payInfoIds += (payInfoIds != '')? ","+$("#payInfo2Id"+rowNum).val() : $("#payInfo2Id"+rowNum).val();
		}
	}

	var chkresult = deductInfoChk(rowNum);
	if(chkresult){
		var deductInfoId = $.ajax({
			type : 'POST',
			url : '?model=outsourcing_vehicle_deductinfo&action=ajaxUpdateDeductInfo',
			data : {
				allregisterId : (allregisterId == undefined)? '' : allregisterId,
				useCarDate : (useCarDate == undefined)? '' : useCarDate.substr(0, 7),
				carNum : (carNum == undefined)? '' : carNum,
				deductMoney : (deductMoney == undefined)? '' : deductMoney,
				deductReason : (deductReason == undefined)? '' : deductReason,
				expensetmpId : (expensetmpId == undefined)? '' : expensetmpId,
				payInfoIds :  (payInfoIds == undefined)? '' : payInfoIds,
                registerIds :  (registerIds == undefined)? '' : registerIds
			},
			async : false
		}).responseText;
		// console.log(deductInfoId);
		$("#registerInfo_cmp_deductInfoId"+rowNum).val(deductInfoId);

		// ���ϴ���ֻ���ۿ���ĸ���, �������������Ƿ�����µĴ���,�˴���ŵ��б����ʱ����
		reloadList();
	}
};

var toSeeInfoInPageInnerWin = function(url,title){
	$("#pageInnerWindowIframe").attr("src",url);
	$("#pageInnerWindow").dialog({
		title : title,
		height : 700,
		width : 1000,
		modal: true
	}).dialog('open');
	// ��IE�Ϲرպ��ٴ�Dialogҳ��,����ҳ��������,��ʱ��������,ǿ�аѵ�����ʾ����
	$("#pageInnerWindow").parent('.panel.window').show();
	$("#pageInnerWindow").parent('.panel.window').next(".window-shadow").show();
	$("#pageInnerWindow").parent('.panel.window').next(".window-shadow").next(".window-mask").show();
};