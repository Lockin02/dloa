var isShow = false;
function showReportData(dataSql) {
	//ѭ������ֵ
	var thisGrid = $("#advsearchgrid");
	var $cmps = thisGrid.yxeditgrid('getCmpByCol','searchField');
	var v,thisYearVal,productCodeVal,productNameVal;
	$cmps.each(function(i){
		var thisValue = strTrim(thisGrid.yxeditgrid('getCmpByRowAndCol',i,'value').val());
		//���
		if(this.value  =='g.thisYear' && thisValue != ''){
			$("#thisYearView").html(thisValue);
			thisYearVal = 1;
		}
		//���ϱ��
		if($(this).val()=='g.productCode' && thisValue != ''){
			$("#productCodeView").html(thisValue);
			productCodeVal = 1;
		}
		//��������
		if($(this).val()=='g.productName' && thisValue != ''){
			$("#productNameView").html(thisValue);
			productNameVal = 1;
		}
	});

	if(thisYearVal != 1){
		$("#thisYearView").html($("#thisYear").val());
	}

	if(productCodeVal != 1){
		$("#productCodeView").html('');
	}

	if(productNameVal != 1){
		$("#productNameView").html('');
	}

	$("#reportDiv").empty();
	$("#reportDiv").show();


	// �ò�ѯ��ʾ�ؼ�չ�ֱ�����URL��../grf/1a.grf����ȡ����Ĥ�嶨�壬��URL��../data/xmlCustomer.php����ȡXML��ʽ�ı������ݣ�
	var html = getDisplayViewerHtml(
			"view/template/stock/report/stockreport-prooutsub1.grf",
			"view/template/stock/report/stockreport-prooutsubdetail.php?"
				+ "conditionSql="
				+ encodeURIComponent(dataSql));
	$("#reportDiv").html(html);
	isShow = true;
}

function searchBtn() {
	$("#reportDiv").hide();
	if (isShow) {
		$("body").yxadvsearch("show");
	} else {
		$("body").yxadvsearch({
			afterConfirmAction : 'hide',
			windowOptions : {
				showModal : false,
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
				modelName : 'stockreportdetail',
				// ѡ���ֶκ��������ֵ����
				selectFn : function($valInput) {
					// $valInput.yxcombogrid_product("remove");
					// $valInput.yxcombogrid_customer("remove");
				},

				searchConfig : [{
							name : '���ϱ��',
							value : 'g.productCode'
					}, {
							name : '��������',
							value : 'g.productName'
					},{
							name : 'ͳ�����',
							value : 'g.thisYear'
					}]
			},
			event : {
				confirmAdvsearch : function(e, g) {
					var searchArr = g.getAdvSearchArr();
					var dataSql = $.ajax({
						url : 'index1.php?model=system_adv_advcase&action=getAdvsearchSql',
						type : 'POST',
						data : {
							advArr : searchArr
						},
						async : false
					}).responseText;
					showReportData(dataSql);
				}
			}
		});
	}
}

$(function() {
	searchBtn();
	//��ʼ����

	//��ֵ�߶�
	var thisHeight = document.documentElement.clientHeight - 40;
	$("#reportDiv").height(thisHeight);
});