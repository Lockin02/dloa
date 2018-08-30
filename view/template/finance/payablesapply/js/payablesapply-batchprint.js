// Array.prototype.distinct = function (){
// 	var arr = this,
// 		result = [],
// 		len = arr.length;
// 	arr.forEach(function(v, i ,arr){  //这里利用map，filter方法也可以实现
// 		var bool = arr.indexOf(v,i+1);  //从传入参数的下一个索引值开始寻找是否存在重复
// 		if(bool === -1){
// 			result.push(v);
// 		}
// 	})
// 	return result;
// };

//初始化单据
$(function(){
	//金额部分渲染
	$.each($(".chinseMoney"),function(i,n){
		$(this).html( toChinseMoney($(this).attr("title")) );
	});

	//初始化打印提醒
	initPrintTips();

	//初始化审批情况
	initApproval();

	//打印信息赋值
	$("#headNum").html($("#allNum").html());
	$("#headMoney").html($("#allMoney").html());
});

//打印事件
function changePrintCount(ids){
	// var idsArr = ids.split(",");
	// ids = idsArr.distinct();
	// ids = ids.toString();
	var userAgent = window.navigator.userAgent;
	if(userAgent.indexOf('NET') > 0){// IE 浏览器的时候
		var printTimes = printBatch(ids);
		//更新单据打印次数
		if(printTimes > 0){
			$.ajax({
				type: "POST",
				url: "?model=finance_payablesapply_payablesapply&action=changePrintCountIds",
				data: {"ids" : ids ,'printTimes' : printTimes },
				async: false,
				success: function(data){
					if(data > 0){
						reinitPrintTips(data);
					}

					if(window.opener != undefined){
						window.opener.show_page();
					}
				}
			});
		}
	}else{
		printBatchNew(ids,'',function(printTimes){
			//更新单据打印次数
			if(printTimes > 0){
				$.ajax({
					type: "POST",
					url: "?model=finance_payablesapply_payablesapply&action=changePrintCountIds",
					data: {"ids" : ids ,'printTimes' : printTimes },
					async: false,
					success: function(data){
						if(data > 0){
							reinitPrintTips(data);
						}

						if(window.opener != undefined){
							window.opener.show_page();
						}
					}
				});
			}
		});
	}
}

//初始化重复打印信息
function initPrintTips(){
	//重复打印提醒
	$.each($("input[id^='printCount']"),function(i,n){
		var todayStr = ($("#todayStr").val() != undefined && $("#todayStr").val() != '')? "打印日期: "+$("#todayStr").val()+" " : '';
		var printCount = Number($(this).val()) + 1;
		$("#isReprint" + $(this).attr("title")).html(todayStr + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 付款单号：'+$(this).attr("title")+' 第 '+printCount+' 次打印 ');
		$("#isReprint" + $(this).attr("title")).prev("span").hide();
	});
}

//打印后页面
function reinitPrintTips(times){
	//重复打印提醒
	$.each($("input[id^='printCount']"),function(i,n){
		var printCount = parseInt(this.value) + parseInt(times)
		$(this).val(printCount);
		var todayStr = ($("#todayStr").val() != undefined && $("#todayStr").val() != '')? "打印日期: "+$("#todayStr").val()+" " : '';
		$("#isReprint" + $(this).attr("title")).html(todayStr + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 付款单号：'+$(this).attr("title")+' 第 '+printCount+' 次打印 ');
		$("#isReprint" + $(this).attr("title")).prev("span").hide();
		// $("#isReprint" + $(this).attr("title")).html('注意：重复打印单据 ');
	});
}

//初始化审批信息
function initApproval(){
	$.each($("input[id^='pid']"),function(i,n){
		var pid = this.value;
		var applyId = $(this).attr("title");
		var itemType = $("#itemType" + applyId ).val();
		var gdbtable=getQueryString('gdbtable');
		var isChange=0;
		var appFormName = "";
		if($("#isPrint").length == 0){//是否带出变更审批意见
			var isPrint=0;
		}else{
			var isPrint=$("#isPrint").val();
		}
		//加载表格
		$.post(
			"?model=common_approvalView&action=getApproval",
			{
				pid : pid,
				itemtype : itemType,
				isChange : isChange,
				gdbtable : gdbtable,
				isPrint : isPrint,
				changeContent : "1"
			}, function(data) {
				if(data==0){   //没有审批意见时，赋值为空行
					var datahtml="<tr><td></td></tr>";
				}
				if(data!=0){
				var $html=$('<table width="100%"  class="form_in_table" id="approvalTable">' +
							'<thead>'+
								'<tr  > ' +
									'<td width="100%" colspan="6" class="form_header"><B>'+ appFormName +'审批情况</B></td>' +
								'</tr>' +
								'<tr class="main_tr_header">'
				                   +'<th width="12%">步骤名</th>' +
				                   	'<th width="12%">审批人</th>' +
				                   	'<th width="18%" nowrap="nowrap">审批日期</th>' +
				                   	'<th width="10%">审批结果</th>' +
				                   	'<th>审批意见</th>'+
			                   	'</tr>' +
		                   	'</thead>');
				var $html2= $('</table>');
				if(data==0){
					var $tr=$(datahtml);
				}else{
					var $tr=$(data);
				}
				$html.append($tr);
				$html.append($html2);
					$("#approvalView" + applyId).append($html);
				}
			}
		);
	});
}

//确认付款功能
function confirmPay(ids){
	if(confirm('确认付款吗?')){
		$.ajax({
			type : "POST",
			url : "?model=finance_payables_payables&action=addInGroupOneKey",
			data : {
				"ids" : ids
			},
			success : function(msg) {
				if (msg == 1) {
					alert('录入成功！');
					if(window.opener != undefined){
				    	window.opener.show_page();
				    }
				    window.close();
				}else{
					alert('录入失败!');
				}
			}
		});
	}
}

//打印事件
function printAndPay(ids){
	// var idsArr = ids.split(",");
	// ids = idsArr.distinct();
	// ids = ids.toString();

	var userAgent = window.navigator.userAgent;
	if(userAgent.indexOf('NET') > 0){// IE 浏览器的时候
		var printTimes = printBatch(ids);
		//更新单据打印次数
		if(printTimes > 0){
			$.ajax({
				type: "POST",
				url: "?model=finance_payablesapply_payablesapply&action=changePrintCountIds",
				data: {"ids" : ids ,'printTimes' : printTimes },
				async: false,
				success: function(data){
					if(data > 0){
						$.ajax({
							type : "POST",
							url : "?model=finance_payables_payables&action=addInGroupOneKey",
							data : {
								"ids" : ids
							},
							success : function(msg) {
								if (msg == 1) {
									alert('操作成功！');
									if(window.opener != undefined){
										window.opener.show_page();
									}
									window.close();
								}else{
									alert('操作失败!');
								}
							}
						});
					}
				}
			});
		}
	}else{
		printBatchNew(ids,'',function(printTimes){
			//更新单据打印次数
			if(printTimes > 0){
				$.ajax({
					type: "POST",
					url: "?model=finance_payablesapply_payablesapply&action=changePrintCountIds",
					data: {"ids" : ids ,'printTimes' : printTimes },
					async: false,
					success: function(data){
						if(data > 0){
							$.ajax({
								type : "POST",
								url : "?model=finance_payables_payables&action=addInGroupOneKey",
								data : {
									"ids" : ids
								},
								success : function(msg) {
									if (msg == 1) {
										alert('操作成功！');
										if(window.opener != undefined){
											window.opener.show_page();
										}
										window.close();
									}else{
										alert('操作失败!');
									}
								}
							});
						}
					}
				});
			}
		});
	}
}

/**审批意见js*/
function getQueryString(name)
{
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}