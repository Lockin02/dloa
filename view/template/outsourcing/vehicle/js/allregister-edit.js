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
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			name : 'deductInformation',
			display : '<input type="button" id="dzAddCost" class="txt_btn_a"value="费用填报" onclick="toAddCost(this,\'batch\')" data-type="dz"/>',
			width : 30,
			type : 'statictext',
			process : function (v,row,tr){
				var rowNum = $(tr).attr("rownum");
				if(row.rentalProperty == '短租'){
					return (row.payInfoMoney1 == "0" || row.payInfoMoney1 == "" || row.payInfoMoney1 == "未生成")? "<input type='checkbox' class='recordCheckbox dz-chkebox' id='recordCheckbox"+rowNum+"' data-type='dz' data-registerIds='"+row.registerIds+"' onclick='recordCheckboxChange(this,"+rowNum+")'/>" : "-";
				}else{
				    if(row.payInfoMoney1 > 0 || row.payInfoMoney2 > 0){
                        return "-";
                    }else if((row.pay1payTypeCode == 'HETFK' && row.payInfoMoney2 == 0) || (row.pay2payTypeCode == 'HETFK' && row.payInfoMoney1 == 0)){
                    	// 当支付方式1或2为合同付款性质的,且另一个支付方式填报了金额为0的时候,不再显示可填报的勾选框
						return "-";
					}else{
                        return "<input type='checkbox' class='recordCheckbox cz-chkebox' id='recordCheckbox"+rowNum+"' data-type='cz' data-registerIds='"+row.registerIds+"' onclick='recordCheckboxChange(this,"+rowNum+")'/>";
                    }

				}
			}
		},{
			name : 'deductInformation',
			display : '扣款信息',
			width : 200,
			type : 'hidden'
		},{
			name : 'estimate',
			display : '评价',
			width : 200,
			type : 'textarea'
		},{
			name : 'useCarDate',
			display : '用车日期',
			width : 80,
			type : 'statictext',
			process : function (v,row,tr){
				var rowNum = $(tr).attr("rownum");
				return v.substr(0, 7)+"<input id='useCarDateHide"+rowNum+"' type='hidden' value='" + v + "'>";
			}
		},{
			name : 'createName',
			display : '录入人',
			width : 80,
			type : 'statictext'
		},{
			name : 'carNum',
			display : '车  牌',
			width : 80,
			type : 'statictext',
			process : function(v,row,tr){
				var rowNum = $(tr).attr("rownum");
				var needConDateFielt = (row.rentalContractId > 0)? '1' : '2';
				return "<a href='javascript:void(0)' onclick='toSeeInfoInPageInnerWin(\"?model=outsourcing_vehicle_register&action=pageView"
						+ "&needConDateFielt="+ needConDateFielt +"&carNum=" + row.carNum
						+ "&allregisterId=" + $("#id").val()
						+"&placeValuesBefore&TB_iframe=true&modal=false\",\"【" + v +"】"+ row.useCarDate.substr(0, 7) + " 的租车登记记录\")'>" + v + "</a><input id='carNumHide"+rowNum+"' type='hidden' value='" + v + "'>";
			}
		},{
			name : 'carModel',
			display : '车  型',
			width : 70,
			type : 'statictext'
		},{
			name : 'rentalContractNature',
			display : '合同性质',
			type : 'statictext',
			width : 70
		},{
			name : 'deductMoney',
			display : '扣款金额',
			width : 90,
			type : 'input',
			process : function (v,row,tr){
				var deductMoney = $(v).val();
				var rowNum = $(tr).attr("rownum");
				$("#registerInfo_cmp_deductMoney"+rowNum).after("<input type='hidden' id='registerInfo_cmp_deductInfoId"+rowNum+"' value='"+row.deductInfoId+"'>");
				if(row.rentalProperty == '短租'){
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
			display : '扣款理由',
			width : 200,
			type : 'textarea',
			process : function (v,row,tr){
				var deductReason = $(v).val();
				var rowNum = $(tr).attr("rownum");
				if(row.rentalProperty == '短租'){
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
			display : '支付金额1',
			type : 'statictext',
			width : 70,
			process : function(v,row,tr){
				var rowNum = $(tr).attr("rownum");
				var str = '';
				var realNeedPayCost = Number(row.realNeedPayCost1);// 支付方式1实际应填金额
				var belongGroup = (row.belongGroup != undefined)? row.belongGroup : '';
				if(row.rentalProperty != '短租' && row.isFirstCar == 1 && v > 0 && row.isFirstCzCar == 0){
					str = '-';
				}else{
					if(row.pay1payTypeCode == "HETFK"){
						str = "<input type='hidden' id='isNeedChange"+rowNum+"-1' value='0'><a href='javascript:void(0)' id='payInfo1Link"+rowNum+"' data-id='"+row.expenseTmpId1+"' data-val='"+v+"' " +
							"onclick='toAddCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\",\""+row.registerIds+"\")'>-</a>";
					}else if(v != '-' && v != ''){
						if(row.isFirstCar == 1 && v != 0){
							var showStr = "<input type='hidden' id='isNeedChange"+rowNum+"-1' value='0'><a href='javascript:void(0)' id='payInfo1Link"+rowNum+"' data-id='"+row.expenseTmpId1+"' data-val='"+v+"' " +
								"onclick='toAddCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"</a>";
							if(v != '未生成'){
								showStr = (row.realNeedPayCost1 > 0 && Number(v) != Number(row.realNeedPayCost1))?
								"<input type='hidden' id='isNeedChange"+rowNum+"-1' value='1'><a href='javascript:void(0)' id='payInfo1Link"+rowNum+"' data-id='"+row.expenseTmpId1+"' data-val='"+v+"' style='color:red' " +
								"onclick='toAddCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"<br>(需更新)</a>" :
								"<input type='hidden' id='isNeedChange"+rowNum+"-1' value='0'><a href='javascript:void(0)' id='payInfo1Link"+rowNum+"' data-id='"+row.expenseTmpId1+"' data-val='"+v+"' " +
								"onclick='toAddCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"</a>";
							}else if(Number(v) == 0 && Number(row.realNeedPayCost1) == 0){
								showStr = v;
							}

							str = (row.rentalProperty == '短租' && (row.payInfoMoney1 == "未生成" || row.payInfoMoney1 == "0" || row.payInfoMoney1 == ""))? v : showStr;
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
			display : '支付金额2',
			type : 'statictext',
			width : 70,
			process : function(v,row,tr){
				var rowNum = $(tr).attr("rownum");
				var str = '';
				var realNeedPayCost = Number(row.realNeedPayCost2);// 支付方2式实际应填金额
				var belongGroup = (row.belongGroup != undefined)? row.belongGroup : '';
				if(row.rentalProperty != '短租' && row.isFirstCar == 1 && v > 0 && row.isFirstCzCar == 0){
					str = '-';
				}else{
					if(row.pay2payTypeCode == "HETFK"){
						str = "<input type='hidden' id='isNeedChange"+rowNum+"-2' value='0'><a href='javascript:void(0)' id='payInfo2Link"+rowNum+"' data-id='"+row.expenseTmpId2+"' data-val='"+v+"' " +
							"onclick='toAddCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>-</a>";
					}else if(v != '-' && v != ''){
						var showStr = "<input type='hidden' id='isNeedChange"+rowNum+"-2' value='0'><a href='javascript:void(0)' id='payInfo2Link"+rowNum+"' data-id='"+row.expenseTmpId2+"' data-val='"+v+"' " +
							"onclick='toAddCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"</a>";
						if(v != '未生成' && v > 0){
							showStr = (row.realNeedPayCost2 >= 0 && Number(v) != Number(row.realNeedPayCost2))?
							"<input type='hidden' id='isNeedChange"+rowNum+"-2' value='1'><a href='javascript:void(0)' id='payInfo2Link"+rowNum+"' data-id='"+row.expenseTmpId2+"' data-val='"+v+"' style='color:red' " +
							"onclick='toAddCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"<br>(需更新)</a>" :
							"<input type='hidden' id='isNeedChange"+rowNum+"-2' value='0'><a href='javascript:void(0)' id='payInfo2Link"+rowNum+"' data-id='"+row.expenseTmpId2+"' data-val='"+v+"' " +
							"onclick='toAddCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\",\""+row.registerIds+"\",\""+belongGroup+"\")'>"+v+"</a>";
						}else if(Number(v) == 0 && Number(row.realNeedPayCost2) == 0){
							showStr = (row.rentalProperty == '短租' || v == 0)? '-' : v;
						}
						str = showStr;
					}else{
						str = "-";
					}
				}
				if(row.rentalProperty == '短租'){
					str = '-';
				}
				str = "<input type='hidden' id='realNeedPayCostTwo"+rowNum+"' value='"+realNeedPayCost+"'>" +
					"<input type='hidden' id='payInfo2Id"+rowNum+"' value='"+row.payInfoId2+"'>"+str;
				return str;
			}
		},{
			name : 'rentalProperty',
			display : '租车性质',
			width : 70,
			type : 'statictext'
		},{
			name : 'contractUseDay',
			display : '合同用车天数',
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
			display : '实际用车天数',
			width : 70,
			type : 'statictext'
		},{
			name : 'rentalPropertyCode',
			display : '隐藏的租车性质编码',
			type : 'hidden'
		},{
			name : 'suppName',
			display : '供应商名称',
			width : 130,
			type : 'statictext'
		},{
			display : '租车合同ID',
			name : 'rentalContractId',
			type : 'hidden'
		},{
			name : 'rentalContractCode',
			display : '隐藏的租车合同编号',
			type : 'hidden'
		},{
			name : 'rentalContractCode',
			display : '租车合同编号',
			width : 120,
			type : 'statictext',
			process : function (v,row){
				var str = '';
				if(row.rentalContractId != '' && v != ''){
					str = "<a href='javascript:void(0)' onclick='toSeeInfoInPageInnerWin(\"index1.php?model=outsourcing_contract_rentcar&action=viewTab&notCloseBtn=1&id="+row.rentalContractId+"\",\"租车合同信息\")'>"+v+"</a>";
					// str = "<a href='index1.php?model=outsourcing_contract_rentcar&action=viewTab&id="+row.rentalContractId+"' target='_blank'>"+v+"</a>"
				}
				return str;
			}
		},{
			name : 'effectMileage',
			display : '有效里程',
			width : 80,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolinePrice',
			display : '油价（元）',
			width : 80,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolineKMPrice',
			display : '按公里计价油费单价（元）',
			width : 140,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolineKMCost',
			display : '按公里计价油费（元）',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'reimbursedFuel',
			display : '实报实销油费（元）',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'parkingCost',
			display : '停车费（元）',
			type : 'statictext',
			width : 120,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'tollCost',
			display : '路桥费（元）',
			type : 'statictext',
			width : 120,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'rentalCarCost',
			display : '租车费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'mealsCost',
			display : '餐饮费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'accommodationCost',
			display : '住宿费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'overtimePay',
			display : '加班费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'specialGas',
			display : '特殊油费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'allCost',
			display : '总费用（元）',
			width : 90,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'effectLogTime',
			display : '有效LOG时长',
			width : 120,
			type : 'statictext'
		}]
	});

});

var recordCheckboxChange = function(obj,rowNum){
	var isChecked = $(obj).is(':checked');
	var chkBoxType = $(obj).attr('data-type');
	switch(chkBoxType){
		case 'dz':// 短租
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
		case 'cz':// 长租
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

// 检验是否存在需要更新的金额
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

//直接提交
function toSubmit(act){
	var pass = true;
	if(act == 'audit'){
		// 检验租车汇总记录费用填报结果
		$("div[id^='registerInfo_cmp_rentalContractNature']").each(function(){
			var contractType = $(this).text();
			var rowNum = $(this).parent("td").parent("tr").attr("rownum");
			if(contractType == "无款项合同" || contractType == "无"){
				var pay1Result = $("#registerInfo_cmp_payInfoMoney1"+rowNum).children("a").text();
				var pay1ResultT = $("#registerInfo_cmp_payInfoMoney1"+rowNum).text();
				var pay2Result = $("#registerInfo_cmp_payInfoMoney2"+rowNum).children("a").text();
				var pay2ResultT = $("#registerInfo_cmp_payInfoMoney2"+rowNum).text();
				if(pay1Result == "未生成" || pay2Result == "未生成" || pay1ResultT == "未生成" || pay2ResultT == "未生成"){
					alert("非款项合同,必须全部填报费用表后，方能提交租车登记汇总审批。");
					pass = false;
					return false;
				}
			}
		});

		if(pass){
			// 检验是否存在需要更新的金额
			var chkResult = chkOldCostUpdate();
			pass = chkResult;
			if(!chkResult){
				alert("含有需要更新的支付金额,请更新相应的填报费用后再提交。");
				return false;
			}
		}
	}

	// 检查扣款信息
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
		var nowYear = nowData.getFullYear(); //获取年
		var nowMonth = nowData.getMonth() + 1; //获取月
		var year = parseInt(dateArr.substr(0 ,4));
		var month = parseInt(dateArr.substr(5 ,2));
		var tmp = true; //判断是否满一个月
		if (nowYear < year) {
			tmp = false;
		}else if (nowMonth <= month &&　nowYear == year) {
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
			alert("当前记录还未满一个月时间！");
		}
	}
}

// 统计选中的短租的费用信息
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
		alert("无可操作的记录;");
		return false;
	}else{
		var carNum = '',useCarDate = '',rowIds = '',registerIds = '';
		$.each($(".recordCheckbox"),function(i,item){
			if($(item).attr("checked")){
				if($(item).attr("data-registerids") != undefined && $(item).attr("data-registerids") != ''){
					registerIds += (registerIds == '')? $(item).attr("data-registerids") : ","+$(item).attr("data-registerids");
				}

				// 获取当前行内的行数
				var index = $(item).parent("div").parent("td").parent("tr").attr("rownum");
				selectedRowNum += (selectedRowNum == '')? index : ','+index;
				var gasolinePrice = $("#registerInfo_cmp_gasolinePrice"+index).text();// 油费
				costCatchArr.gasolinePrice += (Number(gasolinePrice.replace(/,/g,'')) >= 0)? Number(gasolinePrice.replace(/,/g,'')) : 0;
				var gasolineKMPrice = $("#registerInfo_cmp_gasolineKMPrice"+index).text();// 按公里计价油费单价
				costCatchArr.gasolineKMPrice = (Number(gasolineKMPrice.replace(/,/g,'')) >= 0)? Number(gasolineKMPrice.replace(/,/g,'')) : 0;
				var effectMileage = $("#registerInfo_cmp_effectMileage"+index).text();// 有效里程
				costCatchArr.effectMileage = (Number(effectMileage.replace(/,/g,'')) >= 0)? Number(effectMileage.replace(/,/g,'')) : 0;
				var gasolineKMCost = $("#registerInfo_cmp_gasolineKMCost"+index).text();// 按公里计价油费
				costCatchArr.gasolineKMCost += (Number(gasolineKMCost.replace(/,/g,'')) >= 0)? Number(gasolineKMCost.replace(/,/g,'')) : 0;
				var reimbursedFuel = $("#registerInfo_cmp_reimbursedFuel"+index).text();// 实报实销油费
				costCatchArr.reimbursedFuel += (Number(reimbursedFuel.replace(/,/g,'')) >= 0)? Number(reimbursedFuel.replace(/,/g,'')) : 0;
				var parkingCost = $("#registerInfo_cmp_parkingCost"+index).text();// 停车费
				costCatchArr.parkingCost += (Number(parkingCost.replace(/,/g,'')) >= 0)? Number(parkingCost.replace(/,/g,'')) : 0;
				var tollCost = $("#registerInfo_cmp_tollCost"+index).text();// 路桥费
				costCatchArr.tollCost += (Number(tollCost.replace(/,/g,'')) >= 0)? Number(tollCost.replace(/,/g,'')) : 0;
				var rentalCarCost = $("#registerInfo_cmp_rentalCarCost"+index).text();// 租车费
				costCatchArr.rentalCarCost += (Number(rentalCarCost.replace(/,/g,'')) >= 0)? Number(rentalCarCost.replace(/,/g,'')) : 0;
				var mealsCost = $("#registerInfo_cmp_mealsCost"+index).text();// 餐饮费
				costCatchArr.mealsCost += (Number(mealsCost.replace(/,/g,'')) >= 0)? Number(mealsCost.replace(/,/g,'')) : 0;
				var accommodationCost = $("#registerInfo_cmp_accommodationCost"+index).text();// 住宿费
				costCatchArr.accommodationCost += (Number(accommodationCost.replace(/,/g,'')) >= 0)? Number(accommodationCost.replace(/,/g,'')) : 0;
				var overtimePay = $("#registerInfo_cmp_overtimePay"+index).text();// 加班费
				costCatchArr.overtimePay += (Number(overtimePay.replace(/,/g,'')) >= 0)? Number(overtimePay.replace(/,/g,'')) : 0;
				var specialGas = $("#registerInfo_cmp_specialGas"+index).text();// 特殊油费
				costCatchArr.specialGas += (Number(specialGas.replace(/,/g,'')) >= 0)? Number(specialGas.replace(/,/g,'')) : 0;
				costCatchArr.rentalPropertyCode = $("#registerInfo_cmp_rentalPropertyCode"+index).val();// 租车类型

				var thisCarNum = $("#registerInfo_cmp_carNum"+index).children("input").val();
				var thisUseCarDate = $("#registerInfo_cmp_useCarDate"+index).children("input").val();
				carNum += (thisCarNum != undefined)? ( (carNum == "")? thisCarNum : ","+thisCarNum) : '';// 车牌号
				useCarDate += (thisUseCarDate != undefined)? ( (useCarDate == "")? thisUseCarDate : ","+thisUseCarDate) : '';// 用车日期
				rowIds += (rowIds == "")? index : ","+index;
			}

		});
		costCatchArr.rowIds = rowIds;
		costCatchArr.carNum = carNum;
		costCatchArr.useCarDate = useCarDate;
		registeridsGlobal = registerIds;

		if(selectedRowNum == ''){
			alert("请至少选中一条短租的记录;");
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
		alert("无可操作的记录;");
		return false;
	}else{
		var retalContractId = '',carNum = '',useCarDate = '',rowIds = '',registerIds = '',payInfoId = '',deductInfoId = '',pass = true;
		$.each($(".recordCheckbox"),function(i,item){
			// 获取当前行内的行数
			if($(item).is(':checked')){
				var index = $(item).parent("div").parent("td").parent("tr").attr("rownum");

				// 所有选项都必须先填写扣款金额,因为扣款金额与费用填报是分开存的,如果放到填报流程里,填完扣款关闭网页后,会导致扣款信息关联关系断掉,以至于数据丢失
				if($("#registerInfo_cmp_deductInfoId"+index).val() == '' || $("#registerInfo_cmp_deductInfoId"+index).val() == 'undefined'){
					if(pass){
						alert("选项中含有未填写扣款金额栏目的，如无扣款，请填报0。");
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
					var gasolinePrice = $("#registerInfo_cmp_gasolinePrice"+index).text();// 油费
					costCatchArr.gasolinePrice += (Number(gasolinePrice.replace(/,/g,'')) >= 0)? Number(gasolinePrice.replace(/,/g,'')) : 0;
					var gasolineKMPrice = $("#registerInfo_cmp_gasolineKMPrice"+index).text();// 按公里计价油费单价
					costCatchArr.gasolineKMPrice = (Number(gasolineKMPrice.replace(/,/g,'')) >= 0)? Number(gasolineKMPrice.replace(/,/g,'')) : 0;
					var effectMileage = $("#registerInfo_cmp_effectMileage"+index).text();// 有效里程
					costCatchArr.effectMileage = (Number(effectMileage.replace(/,/g,'')) >= 0)? Number(effectMileage.replace(/,/g,'')) : 0;
					var gasolineKMCost = $("#registerInfo_cmp_gasolineKMCost"+index).text();// 按公里计价油费
					costCatchArr.gasolineKMCost += (Number(gasolineKMCost.replace(/,/g,'')) >= 0)? Number(gasolineKMCost.replace(/,/g,'')) : 0;
					var reimbursedFuel = $("#registerInfo_cmp_reimbursedFuel"+index).text();// 实报实销油费
					costCatchArr.reimbursedFuel += (Number(reimbursedFuel.replace(/,/g,'')) >= 0)? Number(reimbursedFuel.replace(/,/g,'')) : 0;
					var parkingCost = $("#registerInfo_cmp_parkingCost"+index).text();// 路桥停车费
					costCatchArr.parkingCost += (Number(parkingCost.replace(/,/g,'')) >= 0)? Number(parkingCost.replace(/,/g,'')) : 0;
					var tollCost = $("#registerInfo_cmp_tollCost"+index).text();// 路桥停车费
					costCatchArr.tollCost += (Number(tollCost.replace(/,/g,'')) >= 0)? Number(tollCost.replace(/,/g,'')) : 0;
					var rentalCarCost = $("#registerInfo_cmp_rentalCarCost"+index).text();// 租车费
					costCatchArr.rentalCarCost += (Number(rentalCarCost.replace(/,/g,'')) >= 0)? Number(rentalCarCost.replace(/,/g,'')) : 0;
					var mealsCost = $("#registerInfo_cmp_mealsCost"+index).text();// 餐饮费
					costCatchArr.mealsCost += (Number(mealsCost.replace(/,/g,'')) >= 0)? Number(mealsCost.replace(/,/g,'')) : 0;
					var accommodationCost = $("#registerInfo_cmp_accommodationCost"+index).text();// 住宿费
					costCatchArr.accommodationCost += (Number(accommodationCost.replace(/,/g,'')) >= 0)? Number(accommodationCost.replace(/,/g,'')) : 0;
					var overtimePay = $("#registerInfo_cmp_overtimePay"+index).text();// 加班费
					costCatchArr.overtimePay += (Number(overtimePay.replace(/,/g,'')) >= 0)? Number(overtimePay.replace(/,/g,'')) : 0;
					var specialGas = $("#registerInfo_cmp_specialGas"+index).text();// 特殊油费
					costCatchArr.specialGas += (Number(specialGas.replace(/,/g,'')) >= 0)? Number(specialGas.replace(/,/g,'')) : 0;
					costCatchArr.rentalPropertyCode = $("#registerInfo_cmp_rentalPropertyCode"+index).val();// 租车类型

					var thisCarNum = $("#registerInfo_cmp_carNum"+index).children("input").val();
					var thisUseCarDate = $("#registerInfo_cmp_useCarDate"+index).children("input").val();
					carNum += (thisCarNum != undefined)? ( (carNum == "")? thisCarNum : ","+thisCarNum) : '';// 车牌号
					useCarDate += (thisUseCarDate != undefined)? ( (useCarDate == "")? thisUseCarDate : ","+thisUseCarDate) : '';// 用车日期
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
			alert("请至少选中一条短租的记录;");
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

// 添加报销记录信息
function toAddCost(elmObj,payInfoId,expenseTmpId,registerIds,belongGroup){
	var isCzBatchAdd = false;// 长租批量填报标示
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
		// 获取当前行内的行数
		var indexVal= $(elmObj).parent("div").parent("td").parent("tr").attr("rownum");

		// 如果存在合并的情况, 统计同一组的相关记录行
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
			var gasolinePrice = $("#registerInfo_cmp_gasolinePrice"+index).text();// 油费
			costCatchArr.gasolinePrice += (Number(gasolinePrice.replace(/,/g,'')) >= 0)? Number(gasolinePrice.replace(/,/g,'')) : 0;
			var gasolineKMPrice = $("#registerInfo_cmp_gasolineKMPrice"+index).text();// 按公里计价油费单价
			costCatchArr.gasolineKMPrice += (Number(gasolineKMPrice.replace(/,/g,'')) >= 0)? Number(gasolineKMPrice.replace(/,/g,'')) : 0;
			var effectMileage = $("#registerInfo_cmp_effectMileage"+index).text();// 有效里程
			costCatchArr.effectMileage += (Number(effectMileage.replace(/,/g,'')) >= 0)? Number(effectMileage.replace(/,/g,'')) : 0;
			var gasolineKMCost = $("#registerInfo_cmp_gasolineKMCost"+index).text();// 按公里计价油费
			costCatchArr.gasolineKMCost += (Number(gasolineKMCost.replace(/,/g,'')) >= 0)? Number(gasolineKMCost.replace(/,/g,'')) : 0;
			var reimbursedFuel = $("#registerInfo_cmp_reimbursedFuel"+index).text();// 实报实销油费
			costCatchArr.reimbursedFuel += (Number(reimbursedFuel.replace(/,/g,'')) >= 0)? Number(reimbursedFuel.replace(/,/g,'')) : 0;
			var parkingCost = $("#registerInfo_cmp_parkingCost"+index).text();// 停车费
			costCatchArr.parkingCost += (Number(parkingCost.replace(/,/g,'')) >= 0)? Number(parkingCost.replace(/,/g,'')) : 0;
			var tollCost = $("#registerInfo_cmp_tollCost"+index).text();// 路桥费
			costCatchArr.tollCost += (Number(tollCost.replace(/,/g,'')) >= 0)? Number(tollCost.replace(/,/g,'')) : 0;
			var rentalCarCost = $("#registerInfo_cmp_rentalCarCost"+index).text();// 租车费
			costCatchArr.rentalCarCost += (Number(rentalCarCost.replace(/,/g,'')) >= 0)? Number(rentalCarCost.replace(/,/g,'')) : 0;
			var mealsCost = $("#registerInfo_cmp_mealsCost"+index).text();// 餐饮费
			costCatchArr.mealsCost += (Number(mealsCost.replace(/,/g,'')) >= 0)? Number(mealsCost.replace(/,/g,'')) : 0;
			var accommodationCost = $("#registerInfo_cmp_accommodationCost"+index).text();// 住宿费
			costCatchArr.accommodationCost += (Number(accommodationCost.replace(/,/g,'')) >= 0)? Number(accommodationCost.replace(/,/g,'')) : 0;
			var overtimePay = $("#registerInfo_cmp_overtimePay"+index).text();// 加班费
			costCatchArr.overtimePay += (Number(overtimePay.replace(/,/g,'')) >= 0)? Number(overtimePay.replace(/,/g,'')) : 0;
			var specialGas = $("#registerInfo_cmp_specialGas"+index).text();// 特殊油费
			costCatchArr.specialGas += (Number(specialGas.replace(/,/g,'')) >= 0)? Number(specialGas.replace(/,/g,'')) : 0;
			costCatchArr.rentalPropertyCode = $("#registerInfo_cmp_rentalPropertyCode"+index).val();// 租车类型

			costCatchArr.carNum = $("#registerInfo_cmp_carNum"+index).children("input").val();// 车牌号
			costCatchArr.useCarDate = $("#registerInfo_cmp_useCarDate"+index).children("input").val();// 用车日期
			costCatchArr.rowIds = index;

			// 检查是否填写扣款金额
			var rentalProperty = $("#registerInfo_cmp_rentalProperty"+index).text();
			if(rentalProperty != '短租'){
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

					// 填报费用表
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

					// 填报付款单
					$("#toPayCost").unbind("click");
					$("#toPayCost").bind("click",function(){
						var url = '';
						alert("由租车管理员填报租车支付费用。");
					});
				};

				$("#payInfoInner").dialog({
					title : '添加租车报销费用信息',
					height : 300,
					width : 600,
					modal: true
				}).dialog('open');
			}
		});

		// 在IE上关闭后再打开Dialog页面,弹框页面会出不来,暂时这样处理,强行把弹框显示出来
		$("#payInfoInner").parent('.panel.window').show();
		$("#payInfoInner").parent('.panel.window').next(".window-shadow").show();
		$("#payInfoInner").parent('.panel.window').next(".window-shadow").next(".window-mask").show();
	}
}

// 重新打开一个窗口
var popUpANewWin = function(url){
	setTimeout(function(){
		showThickboxWin(url);
	}, 400);
};

// 重新加载详情列表
var reloadList = function(){
	$("#registerInfo").yxeditgrid('processData');
};

// 检验扣款信息
var deductInfoChk = function (rowNum,forAct){
	var chkresult = true;
	var deductMoney = $("#registerInfo_cmp_deductMoney"+rowNum).val();
	var originalVal = $("#registerInfo_cmp_deductMoney"+rowNum).attr("originalVal");
	originalVal = (originalVal == '')? '' : Number(originalVal);
	var deductReason = $("#registerInfo_cmp_deductReason"+rowNum).val();
	$("#registerInfo_cmp_deductReason"+rowNum).removeAttr("isRequire");
	$("#registerInfo_cmp_deductReason"+rowNum).css("border-color","initial");
	var rentalCarCost = $("#registerInfo_cmp_rentalCarCost"+rowNum).text();// 租车费
	rentalCarCost = Number(rentalCarCost.replace(/,/g,''));

	if(deductMoney != undefined){
		if((deductMoney == '' && originalVal !== '') || isNaN(deductMoney) || deductMoney < 0){
			alert("扣款金额只能为0或正值!");
			$("#registerInfo_cmp_deductMoney"+rowNum).val(originalVal);
			chkresult = false;
		}else if(deductMoney > 0){// 如扣款金额非0，那么扣款理由为必填。
			if(deductMoney > rentalCarCost){
				alert("扣款金额不得大于此记录的租车费!");
				$("#registerInfo_cmp_deductMoney"+rowNum).val(originalVal);
				chkresult = false;
			}else{
				$("#registerInfo_cmp_deductReason"+rowNum).attr("isRequire","1");
				if(deductReason == ''){
					$("#registerInfo_cmp_deductReason"+rowNum).css("border-color","red");
					if(forAct == "chkForm"){
						alert("含扣款金额且非0时, 扣款理由不得为空。");
						$("#registerInfo_cmp_deductReason"+rowNum).focus();
					}
					chkresult = false;
				}
			}
		}else{
			chkresult = !(deductMoney == '');
			if(forAct == "chkForm" && !chkresult){
				alert("扣款金额栏目为必填，如无扣款，请填报0。");
				$("#registerInfo_cmp_deductMoney"+rowNum).focus();
			}
		}
	}
	return chkresult;
};

// 异步更新扣款信息
var updateDeductInfo = function(rowNum){
	var allregisterId = $("#id").val();
	var useCarDate = $("#useCarDateHide"+rowNum).val();
	var carNum = $("#carNumHide"+rowNum).val();
	var deductMoney = $("#registerInfo_cmp_deductMoney"+rowNum).val();
    var registerIds = $("#registerInfo_cmp_deductMoney"+rowNum).attr("data-registerIds");
	var deductReason = $("#registerInfo_cmp_deductReason"+rowNum).val();
	var rentalProperty = $("#registerInfo_cmp_rentalProperty"+rowNum).text();
	var expensetmpId = '';// 填报费用零食记录ID
	var payInfoIds = '';// 支付方式对应ID

	if(rentalProperty != "短租"){// 长租时候,拼接当前记录内的两个支付金额的ID
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

		// 以上处理只做扣款金额的更新, 不做其他如检查是否需更新的处理,此处理放到列表加载时处理
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
	// 在IE上关闭后再打开Dialog页面,弹框页面会出不来,暂时这样处理,强行把弹框显示出来
	$("#pageInnerWindow").parent('.panel.window').show();
	$("#pageInnerWindow").parent('.panel.window').next(".window-shadow").show();
	$("#pageInnerWindow").parent('.panel.window').next(".window-shadow").next(".window-mask").show();
};