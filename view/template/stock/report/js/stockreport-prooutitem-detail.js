var isShow = false;
function showReportData(dataSql) {
	$("#reportDiv").empty();
	$("#reportDiv").show();
	// �ò�ѯ��ʾ�ؼ�չ�ֱ�����URL��../grf/1a.grf����ȡ����Ĥ�嶨�壬��URL��../data/xmlCustomer.php����ȡXML��ʽ�ı������ݣ�
	var html = getDisplayViewerHtml(
			"view/template/stock/report/stockreport-prooutitem.grf",
			"view/template/stock/report/stockreport-prooutitemdetail.php?conditionSql="
					+ encodeURIComponent(dataSql));
	$("#reportDiv").html(html);
	isShow = true;
}

function searchBtn() {
	// window.open("?model=stock_report_stockreport&action=toProOutSubSearch"
	// + "&beginDate=" + document.getElementById("beginDate").value
	// + "&endDate=" + document.getElementById("endDate").value
	// + "&productId=" + document.getElementById("productId").value
	// + "&productCode=" + document.getElementById("productCode").value
	// + "&productName=" + document.getElementById("productName").value
	// ,'newwindow','height=500,width=800');
	$("#reportDiv").hide();
	if (isShow) {
		$("body").yxadvsearch("show");
	} else {
		$("body").yxadvsearch({
			afterConfirmAction : 'hide',
			windowOptions : {
				showModal:false,
				onClose : function() {
//					if (!isShow) {
//						showReportData("");
//					}
					isShow=false;
					$("#reportDiv").show();
				}
			},
			// �߼�����
			advSearchOptions : {
				modelName : 'prooutitemdetail',
				// ѡ���ֶκ��������ֵ����
				selectFn : function($valInput) {
					$valInput.yxcombogrid_product("remove");
					// $valInput.yxcombogrid_customer("remove");
				},

				searchConfig : [{
							name : '���ϱ��',
							value : 'g.productCode'
						}, {
							name : '��������',
							value : 'g.productName'
						}, {
							name : 'ͳ�����',
							value : 'g.thisYear'
						}, {
							name : 'ͳ���·�',
							value : 'g.thisMonth'
						}, {
							name : '��������',
							value : 'g.actType',
							type : 'select',
							options : [{
									'dataName' : '��ͬ����',
									'dataCode' : '��ͬ����'
								},{
									'dataName' : '��ͬ�˿�',
									'dataCode' : '��ͬ�˿�'
								},{
									'dataName' : '����',
									'dataCode' : '����'
								},{
									'dataName' : '�黹',
									'dataCode' : '�黹'
							}]
						}, {
//							name : '����',
//							value : 'g.actDate',
//							changeFn : function($t, $valInput) {
//								$valInput.click(function() {
//								WdatePicker({
//									dateFmt : 'yyyy-MM-dd'
//								});
//							});
//							}
//						}, {
							name : '�ͻ�����',
							value : 'g.customerName'
						}, {
							name : '��ͬ���',
							value : 'g.contractCode'
						}]
			},
			event : {
				confirmAdvsearch : function(e, g) {
					// alert(123)
					var searchArr = g.getAdvSearchArr();
					$.dump(searchArr);
					var dataSql = $.ajax({
						url : 'index1.php?model=system_adv_advcase&action=getAdvsearchSql',
						type : 'POST',
						data : {
							advArr : searchArr
						},
						async : false
					}).responseText;
					// alert(dataSql)
					showReportData(dataSql);
				}
			}
		});
	}
}

$(function() {
	searchBtn();

	//��ֵ�߶�
	var thisHeight = document.documentElement.clientHeight - 40;
	$("#reportDiv").height(thisHeight);
});