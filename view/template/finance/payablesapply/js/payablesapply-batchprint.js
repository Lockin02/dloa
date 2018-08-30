// Array.prototype.distinct = function (){
// 	var arr = this,
// 		result = [],
// 		len = arr.length;
// 	arr.forEach(function(v, i ,arr){  //��������map��filter����Ҳ����ʵ��
// 		var bool = arr.indexOf(v,i+1);  //�Ӵ����������һ������ֵ��ʼѰ���Ƿ�����ظ�
// 		if(bool === -1){
// 			result.push(v);
// 		}
// 	})
// 	return result;
// };

//��ʼ������
$(function(){
	//������Ⱦ
	$.each($(".chinseMoney"),function(i,n){
		$(this).html( toChinseMoney($(this).attr("title")) );
	});

	//��ʼ����ӡ����
	initPrintTips();

	//��ʼ���������
	initApproval();

	//��ӡ��Ϣ��ֵ
	$("#headNum").html($("#allNum").html());
	$("#headMoney").html($("#allMoney").html());
});

//��ӡ�¼�
function changePrintCount(ids){
	// var idsArr = ids.split(",");
	// ids = idsArr.distinct();
	// ids = ids.toString();
	var userAgent = window.navigator.userAgent;
	if(userAgent.indexOf('NET') > 0){// IE �������ʱ��
		var printTimes = printBatch(ids);
		//���µ��ݴ�ӡ����
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
			//���µ��ݴ�ӡ����
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

//��ʼ���ظ���ӡ��Ϣ
function initPrintTips(){
	//�ظ���ӡ����
	$.each($("input[id^='printCount']"),function(i,n){
		var todayStr = ($("#todayStr").val() != undefined && $("#todayStr").val() != '')? "��ӡ����: "+$("#todayStr").val()+" " : '';
		var printCount = Number($(this).val()) + 1;
		$("#isReprint" + $(this).attr("title")).html(todayStr + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����ţ�'+$(this).attr("title")+' �� '+printCount+' �δ�ӡ ');
		$("#isReprint" + $(this).attr("title")).prev("span").hide();
	});
}

//��ӡ��ҳ��
function reinitPrintTips(times){
	//�ظ���ӡ����
	$.each($("input[id^='printCount']"),function(i,n){
		var printCount = parseInt(this.value) + parseInt(times)
		$(this).val(printCount);
		var todayStr = ($("#todayStr").val() != undefined && $("#todayStr").val() != '')? "��ӡ����: "+$("#todayStr").val()+" " : '';
		$("#isReprint" + $(this).attr("title")).html(todayStr + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����ţ�'+$(this).attr("title")+' �� '+printCount+' �δ�ӡ ');
		$("#isReprint" + $(this).attr("title")).prev("span").hide();
		// $("#isReprint" + $(this).attr("title")).html('ע�⣺�ظ���ӡ���� ');
	});
}

//��ʼ��������Ϣ
function initApproval(){
	$.each($("input[id^='pid']"),function(i,n){
		var pid = this.value;
		var applyId = $(this).attr("title");
		var itemType = $("#itemType" + applyId ).val();
		var gdbtable=getQueryString('gdbtable');
		var isChange=0;
		var appFormName = "";
		if($("#isPrint").length == 0){//�Ƿ��������������
			var isPrint=0;
		}else{
			var isPrint=$("#isPrint").val();
		}
		//���ر��
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
				if(data==0){   //û���������ʱ����ֵΪ����
					var datahtml="<tr><td></td></tr>";
				}
				if(data!=0){
				var $html=$('<table width="100%"  class="form_in_table" id="approvalTable">' +
							'<thead>'+
								'<tr  > ' +
									'<td width="100%" colspan="6" class="form_header"><B>'+ appFormName +'�������</B></td>' +
								'</tr>' +
								'<tr class="main_tr_header">'
				                   +'<th width="12%">������</th>' +
				                   	'<th width="12%">������</th>' +
				                   	'<th width="18%" nowrap="nowrap">��������</th>' +
				                   	'<th width="10%">�������</th>' +
				                   	'<th>�������</th>'+
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

//ȷ�ϸ����
function confirmPay(ids){
	if(confirm('ȷ�ϸ�����?')){
		$.ajax({
			type : "POST",
			url : "?model=finance_payables_payables&action=addInGroupOneKey",
			data : {
				"ids" : ids
			},
			success : function(msg) {
				if (msg == 1) {
					alert('¼��ɹ���');
					if(window.opener != undefined){
				    	window.opener.show_page();
				    }
				    window.close();
				}else{
					alert('¼��ʧ��!');
				}
			}
		});
	}
}

//��ӡ�¼�
function printAndPay(ids){
	// var idsArr = ids.split(",");
	// ids = idsArr.distinct();
	// ids = ids.toString();

	var userAgent = window.navigator.userAgent;
	if(userAgent.indexOf('NET') > 0){// IE �������ʱ��
		var printTimes = printBatch(ids);
		//���µ��ݴ�ӡ����
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
									alert('�����ɹ���');
									if(window.opener != undefined){
										window.opener.show_page();
									}
									window.close();
								}else{
									alert('����ʧ��!');
								}
							}
						});
					}
				}
			});
		}
	}else{
		printBatchNew(ids,'',function(printTimes){
			//���µ��ݴ�ӡ����
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
										alert('�����ɹ���');
										if(window.opener != undefined){
											window.opener.show_page();
										}
										window.close();
									}else{
										alert('����ʧ��!');
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

/**�������js*/
function getQueryString(name)
{
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}