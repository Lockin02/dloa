var exedeptArr = new Array();

// ֱ���ύ����
function toApp() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_trialproject_trialproject&action=add&act=app";
}

//�ύ��֤
function toSub() {
	$("form").bind("submit", function() {
		//��ʼʱ��
		var beginDate = $("#beginDate").val();
		//����ʱ��
		var closeDate = $("#closeDate").val();
		//����
		var projectDays = $("#projectDays").val();
		if (projectDays == "" && closeDate == "" && beginDate == "") {
			alert("������д��Ŀ���ڻ��߹���!");
			return false;
		} else {
			var productInfoObj = $("#productInfo");
			var rowNum = productInfoObj.productInfoGrid('getCurShowRowNum');
			var reFlag = 0;
			if (rowNum == '0') {
				alert("��Ʒ�嵥����Ϊ��!");
				return false;
			} else {
	        	// ��Ʒ�ߴ���
	        	var newProLineArr = [];
	            var proLineAllSelected = true;
	            productInfoObj.productInfoGrid("getCmpByCol", "newProLineCode").each(function(){
	                if ($(this).val() == "") {
	                    alert("��ѡ���Ʒ�Ĳ�Ʒ�ߣ�");
	                    proLineAllSelected = false;
	                    return false;
	                } else {
//	                    var rowNum = $(this).data('rowNum');
//	                    productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'newProLineName').
//	                        val($(this).find("option:selected").text());

	                    if ($.inArray($(this).val(), newProLineArr) == -1) {
	                    	newProLineArr.push($(this).val());
							if (newProLineArr.length > 1) {
								reFlag = 1;
							}
	                    }
	                }
	            });
	            if (proLineAllSelected == false) {
	                return false;
	            }
				if (reFlag == 1) {
					alert("������Ŀ��������ڶ��Ʒ�ߣ�");
					return false;
				}
				// ִ��������
				var exeDeptArr = [];
				var exeDeptAllSelected = true;
				productInfoObj.productInfoGrid("getCmpByCol", "exeDeptCode").each(function() {
					if ($(this).val() == "") {
						alert("��ѡ���Ʒ��ִ������");
						exeDeptAllSelected = false;
						return false;
					} else {
						var rowNum = $(this).data('rowNum');
						productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').
							val($(this).find("option:selected").text());

						if ($.inArray($(this).val(), exeDeptArr) == -1) {
							exeDeptArr.push($(this).val());
//							if (exeDeptArr.length > 1) {
//								reFlag = 1;
//							}
						}
					}
				});
				if (exeDeptAllSelected == false) {
					return false;
				}
			}
		}
	});
}

// Դ������
$(function() {
	var SingleTypeT = $("#SingleTypeT").val();
	switch (SingleTypeT) {
		case "" :
			document.getElementById("SingleType").options.length = 0;
			//document.getElementById("SingleType").options
			//	.add(new Option("��Դ��", "��Դ��"));
			document.getElementById("SingleType").options
				.add(new Option("�̻�", "�̻�"));
//					document.getElementById("SingleType").options
//							.add(new Option("��ͬ", "��ͬ"));
			singleSelect();
			break;
		case "chance" :
			document.getElementById("SingleType").options.length = 0;
			document.getElementById("SingleType").options
				.add(new Option("�̻�", "�̻�"));
			singleSelect();
			break;
	}

	$("form").bind("submit", function() {
		var rowNum = $("#productInfo").productInfoGrid('getCurShowRowNum');
		if (rowNum == '0') {
			alert("��Ʒ�嵥����Ϊ��!");
			return false;
		}
	})
	// ����ִ������
	setAreaInfo();
	// �л����ʱ����ִ������
    $("#module").change(function () {
        setAreaInfo();
    });
});
// ��ȡԴ������ѡ��
function singleSelect() {
	var SingleType = $("#SingleType").val();
	var chanceCode = $("#chanceCodeT").val();
	var chanceId = $("#chanceIdT").val();
	switch (SingleType) {
		case "��Դ��" :
			$("#contractNum").yxcombogrid_allcontract('remove');
			$("#chanceCode").yxcombogrid_chance('remove');
			$("#single").html("<input type='text' class='readOnlyText' readonly='readonly'>");
			$("#SingleTypeT").val("");
			break;
		case "�̻�" :
			$("#contractNum").yxcombogrid_allcontract('remove');
			$("#single").html("<input type='text' class='txt' name='trialproject[chanceCode]' id='chanceCode' >"
			+ "<input type='hidden' class='txt' name='trialproject[chanceId]' id='chanceId'>");
			if (chanceCode != '') {
				$("#chanceCode").val(chanceCode);
			}
			if (chanceId != '') {
				$("#chanceId").val(chanceId);
			}
			$("#SingleTypeT").val("chance");
			$("#chanceCode").yxcombogrid_chance({
				nameCol: 'chanceCode',
				hiddenId: 'chanceId',
				isShowButton: false,
				gridOptions: {
					showcheckbox: false,
					event: {
						'row_dblclick': function(e, row, data) {
							$("#customerName").val(data.customerName);
							$("#customerId").val(data.customerId);
							$('#areaCode').val(data.areaCode);
							$('#areaName').val(data.areaName);
							var exeDeptInfo = getDeptCode(data.areaCode);
							if(exeDeptInfo){
								var exeDeptCode = exeDeptInfo[0].exeDeptCode;
								var exeDeptName = exeDeptInfo[0].exeDeptName;
								$('#exeDeptCode').val(exeDeptCode);
								$('#exeDeptName').val(exeDeptName);

								var productInfoObj = $("#productInfo");
								var exeDeptObj = productInfoObj.productInfoGrid("getCmpByCol", "exeDeptCode");
								if(exeDeptName != '' && productInfoObj.length > 0){
									exeDeptObj.each(function(){
										var rowNum = $(this).data('rowNum');
										$(this).find("option:[value='"+ exeDeptCode + "']").attr("selected",true);
										productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').val(exeDeptName);
									});
								}
							}
//							updateCustomerInfo();
//							getAreaMoneyByCustomer(data.customerId,data.customerName);
						}
					}
				}
			});
			break;
//		case "��ͬ" :
//		    $("#chanceCode").yxcombogrid_chance('remove');
//			$("#single")
//					.html("<input type='text' class='txt' name='trialproject[contractNum]' id='contractNum'>"
//							+ "<input type='hidden' class='txt' name='trialproject[contractName]' id='contractName'>"
//							+ "<input type='hidden' class='txt' name='trialproject[contractId]' id='contractId'>"
//							+ "<input type='hidden' class='txt' name='trialproject[contractType]' id='contractType'>");
//			$("#SingleTypeT").val("order");
//			$("#contractNum").yxcombogrid_allcontract({
//				hiddenId : 'id',
//				searchName : 'contractCode',
//				isShowButton : false,
//				gridOptions : {
//					param : {'isTemp' : '0' ,'prinvipalId' : $("#createId").val(),'states' : '2,4'},
//					showcheckbox : false,
//					event : {
//						'row_dblclick' : function(e, row, data) {
//							$("#contractNum").val(data.contractCode);
//							$("#contractName").val(data.contractName);
//							$("#contractId").val(data.id);
//							$("#customerName").val(data.customerName);
//							$("#customerId").val(data.customerId);
//							getAreaMoneyByCustomer(data.customerId,data.customerName);
//							$("#contractType").val(data.contractType);
////							// ��ȡ�ӱ�����
////							$.get('index1.php', {
////										model : 'projectmanagent_trialproject_trialproject',
////										action : 'ajaxSingle',
////										id : data.orgid,
////										type : 'order',
////										orderType : data.tablename,
////										dataType : "html"
////									}, function(pro) {
////										if (pro) {
////											$("#invbody").html(pro);
////											var rowNums = $("tr[id^='equTab_']").length;
////											$("#productNumber").val(rowNums);
////											recount("invbody");
////										} else {
////										}
////									})
//						}
//					}
//				}
//			});
//			break;
	}
}

/**
 * �����̻�ԭ�����������ȡ��Ӧ��ִ������
 * ���һ�θ��£�2016-12-29 PMS 2313
 */
function getDeptCode(areaCode){
	var returnData = $.ajax({
		type: 'POST',
		url: "?model=system_region_region&action=ajaxChkExeDept",
		data: {
			areaCode: areaCode,
		},
		async: false,
		success: function (data) {
		}
	}).responseText;
	returnData = eval("(" + returnData + ")");
	return returnData;
}

// ������װ��Ʒѡ��
(function($) {
	// ��Ʒ�嵥
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'trialproject[product]',
		tableClass: 'form_in_table',
		colModel: [
			{
				display: '��Ʒ��',
				name: 'newProLineName',
				tclass: 'readOnlyTxtNormal',
				width: 80,
				readonly: true
			}, {
				display: '��Ʒ�߱��',
				name: 'newProLineCode',
				type: 'hidden'
			}, {
				display: 'ִ������',
				name: 'exeDeptCode',
				type: 'select'
			}, {
				display: 'ִ������Name',
				name: 'exeDeptName',
				type: 'hidden'
			}, {
				display: 'proExeDeptId',
				name: 'proExeDeptId',
				type: 'hidden'
			}, {
				display: 'proExeDeptName',
				name: 'proExeDeptName',
				type: 'hidden'
			}, {
				display: 'newExeDeptCode',
				name: 'newExeDeptCode',
				type: 'hidden'
			}, {
				display: 'newExeDeptName',
				name: 'newExeDeptName',
				type: 'hidden'
			}, {
				display: '��Ʒ����',
				name: 'conProductName',
				tclass: 'readOnlyTxtNormal',
				readonly: true
			}, {
				display: '��ƷId',
				name: 'conProductId',
				type: 'hidden'
			}, {
				display: '��Ʒ����',
				name: 'conProductDes',
				tclass: 'txt'
			}, {
				display: '����',
				name: 'number',
				tclass: 'txtshort',
				type: 'money',
				event: {
					blur: function() {
						countAll($(this).data("rowNum"));
					}
				}
			}, {
				display: '����',
				name: 'price',
				tclass: 'txtshort',
				type: 'moneySix',
				event: {
					blur: function() {
						countAll($(this).data("rowNum"));
					}
				}
			}, {
				display: '���',
				name: 'money',
				tclass: 'txtshort',
				type: 'money'
			}, {
				display: '��������Id',
				name: 'license',
				type: 'hidden'
			}, {
				name: 'licenseButton',
				display: '��������',
				event: {
					'click': function(e) {
						var rowNum = $(this).data("rowNum");
						// ��ȡlicenseid
						var licenseObj = $("#productInfo_cmp_license" + rowNum);

						// ����
						url = "?model=yxlicense_license_tempKey&action=toSelectWin" + "&licenseId=" + licenseObj.val()
						+ "&productInfoId="
						+ "productInfo_cmp_license"
						+ rowNum;
						var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");

						if (returnValue) {
							licenseObj.val(returnValue);
						}
					}
				},
				html: '<input type="button"  value="��������"  class="txt_btn_a"  />',
				type: 'hidden'
			}, {
				display: '��Ʒ����Id',
				name: 'deploy',
				type: 'hidden'
			}, {
				name: 'deployButton',
				display: '��Ʒ����',
				type: 'statictext',
				event: {
					'click': function(e) {
						var rowNum = $(this).data("rowNum");
						// �����Ʒ��Ϣ
						var conProductId = $("#productInfo_cmp_conProductId" + rowNum).val();
						var conProductName = $("#productInfo_cmp_conProductName" + rowNum).val();
						var deploy = $("#productInfo_cmp_deploy" + rowNum).val();

						if (conProductId == "") {
							alert('����ѡ����ز�Ʒ!');
							return false;
						} else {
							if (deploy == "") {

								var url = "?model=goods_goods_properties&action=toChoose"
										+ "&productInfoId="
										+ "productInfo_cmp_deploy"
										+ rowNum
										+ "&goodsId="
										+ conProductId
										+ "&goodsName="
										+ conProductName
										+ "&rowNum="
										+ rowNum
										+ "&componentId=productInfo"
									;

								window.open(url, '',
									'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
							} else {

								var url = "?model=goods_goods_properties&action=toChooseAgain"
										+ "&productInfoId="
										+ "productInfo_cmp_deploy"
										+ rowNum
										+ "&goodsId="
										+ conProductId
										+ "&goodsName="
										+ conProductName
										+ "&cacheId="
										+ deploy
										+ "&rowNum="
										+ rowNum
										+ "&componentId=productInfo"
									;

								window.open(url, '',
									'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
							}
						}

					}
				},
				html: '<input type="button"  value="��Ʒ����"  class="txt_btn_a"  />'
			}
		],
		isAddOneRow: false,
		event: {
			clickAddRow: function(e, rowNum, g) {
				rowNum = g.allAddRowNum;
				var url = "?model=contract_contract_product&action=toProductIframe"
					+ "&componentId=productInfo&notEquSlt=1"
					+ "&rowNum="
					+ rowNum;

				window.open(url, '',
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
			},
			reloadData: function() {
				initCacheInfo();
			},
			removeRow: function(e, rowNum, rowData) {
				if (typeof(rowData) != 'undefined') {
					$("#goodsDetail_" + rowData.deploy).remove();
				}
			}
		},
		addBtnClick: function() {
			return false;
		},
		setData: function(returnValue, rowNum) {
			var g = this;
			if (returnValue) {
				returnValue = returnValue[0];
				// ����һ��
				g.addRow(g.allAddRowNum);
				//��Ʒ
				g.setRowColValue(rowNum, "proExeDeptId", returnValue.proExeDeptId);
				g.setRowColValue(rowNum, "proExeDeptName", returnValue.proExeDeptName);
				g.setRowColValue(rowNum, "newExeDeptCode", returnValue.exeDeptCode);
				g.setRowColValue(rowNum, "newExeDeptName", returnValue.exeDeptName);
				g.setRowColValue(rowNum, "newProLineCode", returnValue.exeDeptCode);
				g.setRowColValue(rowNum, "newProLineName", returnValue.exeDeptName);
				initExeDeptByRow(g, rowNum);
				setProExeDeptByRow(rowNum);
				
				g.setRowColValue(rowNum, "conProductId", returnValue.goodsId, true);
				g.setRowColValue(rowNum, "conProductName", returnValue.goodsName, true);
				g.setRowColValue(rowNum, "number", returnValue.number, true);
				g.setRowColValue(rowNum, "price", returnValue.price, true);
				g.setRowColValue(rowNum, "money", returnValue.money, true);
				g.setRowColValue(rowNum, "warrantyPeriod", returnValue.warrantyPeriod, true);
				g.setRowColValue(rowNum, "deploy", returnValue.cacheId, true);
				g.setRowColValue(rowNum, "license", returnValue.licenseId, true);
				var $tr = g.getRowByRowNum(rowNum);
				returnValue.deploy = returnValue.cacheId;
				$tr.data("rowData", returnValue);
				//ѡ���Ʒ��̬��Ⱦ��������õ�
				getCacheInfo(returnValue.cacheId, rowNum);
			}
		},
		reloadCache: function(cacheId, rowNum) {
			if (cacheId) {
				$("#goodsDetail_" + cacheId).remove();
				//ѡ���Ʒ��̬��Ⱦ��������õ�
				getCacheInfo(cacheId, rowNum);
			}
		}
	});
})(jQuery);

// ���������б�
$(function() {
	// ��Ʒ�嵥
	$("#productInfo").productInfoGrid();
});

// ��������
function setData(data, componentId, rowNum) {
	$("#" + componentId).productInfoGrid('setData', data, rowNum);
}

// ˢ�²�Ʒ����
function reloadCache(cacheId, componentId, rowNum) {
	$("#" + componentId).productInfoGrid('reloadCache', cacheId, rowNum);
}

// ���������ֵ���
$(function() {
	// ��ͬ����
	contractTypeArr = getData('HTLX');
	addDataToSelect(contractTypeArr, 'contractType');
	// ��Ʊ����
	invoiceTypeArr = getData('FPLX');
	addDataToSelect(invoiceTypeArr, 'invoiceType');
	// ǩԼ����
	signSubjectTypeArr = getData('QYZT');
	addDataToSelect(signSubjectTypeArr, 'signSubject');

	// �̻���Ӧ��ִ������
	var areaCode = $("#areaCode").val();//�̻���������ID
	var exeDeptInfo = getDeptCode(areaCode);
	if(exeDeptInfo){
		$('#exeDeptCode').val(exeDeptInfo[0].exeDeptCode);
		$('#exeDeptName').val(exeDeptInfo[0].exeDeptName);
	}
});
//�ж�����ʱ����
function timeInterval() {
	//��ʼʱ��
	var beginDate = $("#beginDate").val();
	//����ʱ��
	var closeDate = $("#closeDate").val();
	if (beginDate != '' && closeDate != '') {
		if (closeDate >= beginDate) {
			var days = daysBetween(beginDate, closeDate);
			if (days > 31) {
				alert("������Ŀʱ�䲻�ó���һ���£�31�죩��");
				$("#closeDate").val("");
			} else {
				$("#projectDays").blur(function() {
					//��ʼʱ��
					var beginDates = $("#beginDate").val();
					//����ʱ��
					var closeDates = $("#closeDate").val();
					var newdays = daysBetween(beginDates, closeDates);
					if ($(this).val() != "") {
						if (!(/^(\+|-)?\d+$/.test($(this).val()))) {
							alert("������������");
							$(this).val("");
						} else {
							if ($(this).val() < 0 || $(this).val() > newdays) {
								alert("������Ŀʱ�䲻�ó���һ���£�" + newdays + "�죩��");
								$(this).val("");
							}
						}
					}
				});
			}
		} else {
			alert("�������ڲ���С�ڿ�ʼ���ڣ�");
			$("#closeDate").val("");
		}
	}
}

//�жϹ���
function timeIntervals() {
	var projectDays = $("#projectDays");
	if (projectDays.val() != "") {
		if (!(/^(\+|-)?\d+$/.test(projectDays.val()))) {
			alert("������������");
			projectDays.val("");
		} else {
			if (projectDays.val() < 0 || projectDays.val() > 31) {
				alert("������Ŀʱ�䲻�ó���һ���£�31�죩��");
				projectDays.val("");
			}
		}
	}
}

$(function() {
	// �ύ��֤
	$("#form1").validationEngine({
		inlineValidation: false,
		success: function() {
			var SingleType = $("#SingleType").val();
			var chanceCode = $("#chanceCode").val();
			validate({
				"chanceCode": {
					required: true
				}
			});
			toSub();
			$("#form1").submit();//������֤�����ύ���������Ҫ����������ΰ�ť�����ύ����bug

		},
		failure: false
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"projectName": {
			required: true
		},
		"customerName": {
			required: true
		},
		"projectDescribe": {
			required: true

		},
		"budgetMoney_v": {
			required: true

		},
		"chanceCode": {
			required: true

		}

	});
});

//�ص������Ʒ��Ϣ �� ����
function getCacheInfo(cacheId, rowNum) {
	$.ajax({
		type: "POST",
		url: "?model=goods_goods_goodscache&action=getCacheConfig",
		data: {"id": cacheId},
		async: false,
		success: function(data) {
			if (data != "") {
				$("#productInfo table tr[rowNum=" + rowNum + "]").after(data);
			}

		}
	});
}


//�ص������Ʒ��Ϣ - ����/�����
function getCacheInfoChange(cacheId, beforeCacheId, rowNum) {
	$.ajax({
		type: "POST",
		url: "?model=goods_goods_goodscache&action=getCacheChange",
		data: {"id": cacheId, "beforeId": beforeCacheId},
		async: false,
		success: function(data) {
			if (data != "") {
				$("#productInfo table tr[rowNum=" + rowNum + "]").after(data);
			}
		}
	});
}

//����ҳ��ʱ��Ⱦ��Ʒ������Ϣ
function initCacheInfo() {
	//���������
	var thisGrid = $("#productInfo");

	var colObj = thisGrid.productInfoGrid("getCmpByCol", "deploy");
	colObj.each(function(i, n) {
		//�ж��Ƿ��б��ǰֵ
		var beforeDeployObj = $("#productInfo_cmp_beforeDeploy" + i);
		if (beforeDeployObj.length == 1) {
			if (beforeDeployObj.val()) {
				getCacheInfoChange(this.value, beforeDeployObj.val(), i);
			} else {
				getCacheInfo(this.value, i);
			}
		} else {
			getCacheInfo(this.value, i);
		}
	});
}


// ���㷽��
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
		|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// ��ȡ��ǰ��
		thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// ��ȡ��ǰ����
		thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// ���㱾�н�� - ����˰
		thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}

// ���ò�Ʒִ�����򼰲�Ʒ��
function initExeDept(data, g) {
	if (data) {
		for (var i = 0; i < data.length; i++) {
			initExeDeptByRow(g, i);
		}
	}
}

// ���ò�Ʒִ�����򼰲�Ʒ��- ��
function initExeDeptByRow(g, i) {
	// ִ������
	var productInfoObj = $("#productInfo");
	var productLineName = productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'proExeDeptName').val();
	exeDeptCodeArr = getData('GCSCX');
	$('#productInfo_cmp_exeDeptCode' + i).append("<option value=''>..��ѡ��..</option>");
    addDataToSelect(exeDeptCodeArr, 'productInfo_cmp_exeDeptCode' + i);
	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptCode')
		.find("option:[text='"+ productLineName + "']").attr("selected",true);
	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val(productLineName);
	// ��Ʒ��
//	var exeDeptCode = g.getCmpByRowAndCol(i, 'newExeDeptCode').val();
//	if (exeDeptCode != "") {
//		var exeDeptName = g.getCmpByRowAndCol(i, 'newExeDeptName').val();
//		var newProLineName = g.getCmpByRowAndCol(i, 'newProLineName').val();
//		var exeDeptCodeArr = exeDeptCode.split(',');
//		var exeDeptNameArr = exeDeptName.split(',');
//		var optionStr = "";
//
//		for (var j = 0; j < exeDeptCodeArr.length; j++) {
//			if (newProLineName == exeDeptNameArr[j] || exeDeptCodeArr.length == 1) {
//				optionStr += "<option value='" + exeDeptCodeArr[j] + "' selected='selected'>" +
//				exeDeptNameArr[j] + "</option>";
//			} else {
//				optionStr += "<option value='" + exeDeptCodeArr[j] + "'>" +
//				exeDeptNameArr[j] + "</option>";
//			}
//		}
//		g.getCmpByRowAndCol(i, 'newProLineCode').append(optionStr);
//	}
}

// ����ִ������
function setAreaInfo() {
	// �޸Ĺ���,���ͬ��ͳһ,�������������������Ӧ��ִ������ PMS2313 2016-12-29
	var areaCode = $("#areaCode").val();//�̻���������ID
	var exeDeptInfo = getDeptCode(areaCode);
	if(exeDeptInfo){
		$('#exeDeptCode').val(exeDeptInfo[0].exeDeptCode);
		$('#exeDeptName').val(exeDeptInfo[0].exeDeptName);
	}else{
		return false;
	}

	// var customerType = $("#customerType").val();
	// var province = $("#provinceId").val();
	// var module = $("#module").val();
	// if (customerType != '' && province != '' && module != '') {
	//     var returnValue = $.ajax({
	//         type: 'POST',
	//         url: "?model=system_region_region&action=ajaxConRegion",
	//         data: {
	//             customerType: customerType,
	//             province: province,
	//             module: module
	//         },
	//         async: false,
	//         success: function (data) {
	//         }
	//     }).responseText;
	//     returnValue = eval("(" + returnValue + ")");
	//     if (returnValue) {
	//         $("#exeDeptCode").val(returnValue[0].exeDeptCode);// ִ��������
	//         $("#exeDeptName").val(returnValue[0].exeDeptName);// ִ������
	//     }else{
     //        $("#exeDeptCode").val("");// ִ��������
     //        $("#exeDeptName").val("");// ִ������
	//     }
	// } else {
	//     return false;
	// }
}

// ����ĳ����Ʒִ������
function setProExeDeptByRow(i) {
	var exeDeptCode = $("#exeDeptCode").val();
	var exeDeptName = $("#exeDeptName").val();
    if (exeDeptCode !== undefined && exeDeptCode !== "") {
    	var productInfoObj = $("#productInfo");
    	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptCode')
    		.find("option:[value='"+ exeDeptCode + "']").attr("selected",true);
    	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val(exeDeptName);
    } else {
        return false;
    }
}