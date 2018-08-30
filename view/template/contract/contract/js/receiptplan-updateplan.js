$(function(){
   var isFinance = $("#isfinance").val();
   if($("#isfinance").val() == '1'){
     param = {
			'contractId' : $("#contractId").val(),
			'isDel' : 0,
			'isfinance' : 1
		}
   }else{
     param = {
			'contractId' : $("#contractId").val(),
			'isDel' : 0,
			'isfinance' : 0
		}
   }
	$("#paymentListInfo").yxeditgrid({
		objName : 'receiptplan[payment]',
		tableClass : 'form_in_table',
		url : '?model=contract_contract_receiptplan&action=listJson',
		param : param,
		event : {
			beforeAddRow : function(e, rowNum, rowData, g) {
				var cmps = $("#paymentListInfo").yxeditgrid("getCmpByCol", "paymentPer");
				var num = 0;
				cmps.each(function(i,n) {
					num++;
				});
				if(num > 14){
					alert("回款条款不得大于15条");
					g.removeRow(rowNum);
				}
			},
			"addRow" : function(e,rowNum){
				if($("#isfinance").val()==1){
					var itemTableObj = $("#paymentListInfo");
	            	var itemArr = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum, "isfinance");
	            	itemArr.val(1);
				}
				$(".longTd").parent("td").css("width","180px");
			},
			"removeRow" : function (e,rowNum) {
				chkSchlPerColShow(rowNum);
			}
		},
		colModel : [
					    {
							display : '付款条件ID',
							name : 'paymenttermId',
							tclass : 'txt',
							type : 'hidden'
						}, {
							display : '回款条件',
							name : 'paymentterm',
							 validation: {
				                    required: true
				                },
				            width: 150,
							tclass : 'txt longTd',
							process : function($input, rowData) {
								var rowNum = $input.data("rowNum");
								var g = $input.data("grid");
								$input.yxcombogrid_payconfig({
									hiddenId : 'paymentListInfo_cmp_paymenttermId' + rowNum,
									isFocusoutCheck : false,
									gridOptions : {
										showcheckbox : false,
										event : {
											"row_dblclick" : (function(rowNum) {
												return function(e, row, rowData) {
                                                     var $days = g.getCmpByRowAndCol(rowNum, 'dayNum');
		                                              $days.val(rowData.days);
//		                                             var $QQ = g.getCmpByRowAndCol(rowNum, 'QQ');
//		                                              $QQ.val(rowData.QQ);
//		                                             var $email = g.getCmpByRowAndCol(rowNum, 'Email');
//		                                              $email.val(rowData.email);
													if((rowData.dateCode == 'esmPercentage' || rowData.dateCode == 'shipPercentage' || rowData.dateCode == 'schePercentage') && rowData.schePct == 1){
														var selectObj = $('#paymentListInfo_cmp_schedulePer'+rowNum);
														var optStr = '';
														for(var i = 10;i <= 100;i += 10){
															optStr += '<option title="'+i+'%" value="'+i+'">'+i+'%</option>';
														}
														selectObj.removeAttr("disabled");
														selectObj.removeClass("schedulePerCol-hide");
														$(".schedulePerCol-show").show();
														selectObj.attr("dataCode",rowData.dateCode);
														selectObj.html(optStr);
													}else{
														var selectObj = $('#paymentListInfo_cmp_schedulePer'+rowNum);
														var sltStr = '<select id="paymentListInfo_cmp_schedulePer'+rowNum+'" class="txtshort schedulePerCol-hide" name="receiptplan[payment]['+rowNum+'][schedulePer]" dataCode="'+rowData.dateCode+'" disabled></select>';
														selectObj.after(sltStr);
														chkSchlPerColShow();
														selectObj.remove();
													}
												}
											})(rowNum)
										}
									}
								});
							},
							event: {
								"clear" : function(v){
									var rowNum = $(this).data("rowNum");
									var selectObj = $('#paymentListInfo_cmp_schedulePer'+rowNum);
									var sltStr = '<select id="paymentListInfo_cmp_schedulePer'+rowNum+'" class="txtshort schedulePerCol-hide" name="receiptplan[payment]['+rowNum+'][schedulePer]" disabled></select>';
									selectObj.after(sltStr);
									selectObj.remove();
									chkSchlPerColShow();
								}
							}
						}, {
							display : '<span class="schedulePerCol-show">进度百分比（%）</span>',
							name : 'schedulePer',
							type : 'select',
							tclass : 'txtshort',
							process : function($input, rowData) {
								var rowNum = $input.data("rowNum");

								if(rowData && rowData.schedulePer != undefined && rowData.schedulePer > 0){
									var selectObj = $('#paymentListInfo_cmp_schedulePer'+rowNum);

									var optStr = '';
									for(var i = 10;i <= 100;i += 10){
										optStr += ((rowData.schedulePer - i) == 0)? '<option title="'+i+'%" value="'+i+'" selected>'+i+'%</option>' : '<option title="'+i+'%" value="'+i+'">'+i+'%</option>';
									}

									var responseText = $.ajax({
										url:'index1.php?model=contract_config_payconfig&action=getByAjax&id='+rowData.paymenttermId,
										type : "POST",
										async : false
									}).responseText;
									var dateCode = '';
									if(responseText != '' || responseText != 'false'){
										var payConfigData = eval("(" + responseText + ")");
										dateCode = payConfigData.dateCode
										if(payConfigData.schePct == 1){
											$(".schedulePerCol-show").show();
											selectObj.attr("dataCode",dateCode);
											selectObj.html(optStr);
										}else{
											var sltStr = '<select id="paymentListInfo_cmp_schedulePer'+rowNum+'" class="txtshort schedulePerCol-hide" name="receiptplan[payment]['+rowNum+'][schedulePer]" disabled></select>';
											selectObj.after(sltStr);
											selectObj.remove();
										}
									}
								}else{
									var selectObj = $('#paymentListInfo_cmp_schedulePer'+rowNum);
									var sltStr = '<select id="paymentListInfo_cmp_schedulePer'+rowNum+'" class="txtshort schedulePerCol-hide" name="receiptplan[payment]['+rowNum+'][schedulePer]" disabled></select>';
									selectObj.after(sltStr);
									selectObj.remove();
								}
							}
						}, {
							display : '回款百分比（%）',
							name : 'paymentPer',
							 validation: {
				                    required: true
				                },
							tclass : 'txtshort',
							event: {
			                    'blur': function(v) {
			                    	var percent = $(this).val();
			                    	var rowNum = $(this).data("rowNum");
			                    	if(percent < 0 || percent > 100){
			                    		alert("回款百分比已超出范围");
			                    	   $("#paymentListInfo_cmp_paymentPer" + rowNum).val("");
			                    	   $("#paymentListInfo_cmp_money" + rowNum+ "_v").val("");
			                    	}else{
				                        $("#paymentListInfo_cmp_money" + rowNum)
				                                .val($("#contractMoney").val() * percent / 100);
				                        $("#paymentListInfo_cmp_money" + rowNum + "_v")
				                                .val($("#contractMoney").val() * percent / 100);
				                        $("#paymentListInfo_cmp_money" + rowNum + "_v").trigger('blur');

				                        $("#paymentListInfo_cmp_planInvoiceMoney" + rowNum)
				                                .val($("#contractMoney").val() * percent / 100);
				                        $("#paymentListInfo_cmp_planInvoiceMoney" + rowNum + "_v")
				                                .val($("#contractMoney").val() * percent / 100);
				                        $("#paymentListInfo_cmp_planInvoiceMoney" + rowNum + "_v").trigger('blur');
			                    	}
			                    }
			                }

						},{
							display : '计划付款金额',
							name : 'money',
							tclass : 'txtshort',
							event: {
			                    'blur': function(v) {
			                    	var percentMoney = $(this).val();
			                    	var contractMoney = $("#contractMoney").val();
			                    	var rowNum = $(this).data("rowNum");
			                    	if(percentMoney < 0 || parseFloat(percentMoney) > parseFloat(contractMoney)){
			                    		alert("回款金额已超出范围");
			                    	   $("#paymentListInfo_cmp_paymentPer" + rowNum).val("");
			                    	   $("#paymentListInfo_cmp_money" + rowNum+ "_v").val("");
			                    	   $("#paymentListInfo_cmp_money" + rowNum).val("");
			                    	}else{
				                        $("#paymentListInfo_cmp_paymentPer" + rowNum)
				                                .val(percentMoney / $("#contractMoney").val() * 100);
			                    	}
			                    }
			                }
						},{
							display : '计划开票金额',
							name : 'planInvoiceMoney',
							tclass : 'txtshort',
							event: {
			                    'blur': function(v) {
//			                    	var percentMoney = $(this).val();
//			                    	var contractMoney = $("#contractMoney").val();
//			                    	var rowNum = $(this).data("rowNum");
//			                    	if(percentMoney < 0 || parseFloat(percentMoney) > parseFloat(contractMoney)){
//			                    		alert("回款金额已超出范围");
//			                    	   $("#paymentListInfo_cmp_paymentPer" + rowNum).val("");
//			                    	   $("#paymentListInfo_cmp_money" + rowNum+ "_v").val("");
//			                    	   $("#paymentListInfo_cmp_money" + rowNum).val("");
//			                    	}else{
//				                        $("#paymentListInfo_cmp_paymentPer" + rowNum)
//				                                .val(percentMoney / $("#contractMoney").val() * 100);
//			                    	}
			                    }
			                }
						}, {
							display : '缓冲天数',
							name : 'dayNum',
							tclass : 'txtshort'
						}, {
							display : '备注',
							name : 'remark',
				            width: 120,
							type: 'textarea'
						}, {
							display : '合同文本',
							name : 'conType',
							type : 'select',
							options : [{
								name : '纸质合同',
								value: '纸质合同'
							}, {
								name : '电子合同',
								value: '电子合同'
							}, {
								name : '无合同',
								value: '无合同'
							}],
							tclass : 'txtshort'
						}, {
							display : '详细收款条款',
							name : 'paymenttermInfo',
				            width: 120,
							type: 'textarea'
						}, {
							display : 'isfinance',
							name : 'isfinance',
							width : 50,
							type : "hidden"
						}]
	});

});

function subApp (){
	// 此处用于检测进度百分比的值,如有需要的话
	var schedulePer = $("#paymentListInfo").yxeditgrid("getCmpByCol", "schedulePer");
	schedulePer.each(function(i,n) {
		var paymenttermObj = $("#paymentListInfo_cmp_paymentterm"+i);
		var schedulePerObj = $("#paymentListInfo_cmp_schedulePer"+i);
		// console.log(schedulePerObj.attr("dataCode"));
	});

	//计算付款条件百分比总和
	var cmps = $("#paymentListInfo").yxeditgrid("getCmpByCol", "paymentPer");
	var paymentNum = 0;
	var num = 0;
	cmps.each(function(i,n) {
		num++;
		//过滤掉删除的行
		if($("#contract[payment][_" + i +"_isDelTag").length == 0){
			paymentNum = accAdd($(this).val() , paymentNum);
		}
	});
	if(num > 15){
		alert("回款条款不得大于15条");
		return false;
	}
	if(paymentNum != '100'){
		alert("回款条件占比之和为【" + paymentNum + "%】 请将占比之和调整为 100% ");
		return false;
	};
};

function chkSchlPerColShow(rowNum,beforeNum){
	var schedulePer = $("#paymentListInfo select[id^='paymentListInfo_cmp_schedulePer']");
	var hasPerNum = 0;
	var cachArr = [];
	schedulePer.each(function(i,n) {
		var schedulePerObj = $(n);
		var rowId = $(n).attr("id").split("paymentListInfo_cmp_schedulePer");
		rowId = rowId[1];

		// console.log("row:"+i+" rowNum:"+rowNum+" display:"+schedulePerObj.css("display")+" beforeNum:"+beforeNum+" rowId:"+rowId);

		if(rowId != rowNum && $("#paymentListInfo_cmp_isDelTag"+rowId).val() != 1){
			if(schedulePerObj.css("display") == "inline-block"){
				hasPerNum += 1;
			}
		}

		if(cachArr[rowId] == "y" && schedulePerObj.css("display") == "none"){
			hasPerNum -= 1;
		}else if(schedulePerObj.css("display") == "inline-block"){
			cachArr[rowId] = "y";
		}
	});

	if(hasPerNum > 0){
		$(".schedulePerCol-show").css("display","inline-block");
	}else{
		$(".schedulePerCol-show").css("display","none");
	}
}