

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
//��ϵ�˴ӱ�
function linkmanList() {
	var customerId = $("#customerId").val();
	// �ͻ���ϵ��
	$("#linkmanListInfo").yxeditgrid({
		objName : 'chance[linkman]',
		isAddOneRow : false,
		url : '?model=projectmanagent_chance_linkman&action=listJson',
		param : {
			'chanceId' : $("#chanceId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '�ͻ���ϵ��',
			name : 'linkmanName',
			tclass : 'txt',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_linkman({
					hiddenId : 'linkmanListInfo_cmp_linkmanId' + rowNum,
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						param : {
							'customerId' : customerId
						},
						event : {
							"row_dblclick" : (function(rowNum) {
								return function(e, row, rowData) {
									var $telephone = g.getCmpByRowAndCol(
											rowNum, 'telephone');
									$telephone.val(rowData.mobile);
									var $QQ = g.getCmpByRowAndCol(rowNum, 'QQ');
									$QQ.val(rowData.QQ);
									var $email = g.getCmpByRowAndCol(rowNum,
											'Email');
									$email.val(rowData.email);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '��ϵ��ID',
			name : 'linkmanId',
			type : 'hidden'
		}, {
			display : '�绰',
			name : 'telephone',
			tclass : 'txt'
		}, {
			display : 'QQ',
			name : 'QQ',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'Email',
			tclass : 'txt'
		}, {
			display : '��ɫ',
			name : 'roleName',
			type : 'select',
			datacode : 'ROLE',
			tclass : 'txtmiddle'
		}, {
			display : '�Ƿ�ؼ���ϵ��',
			name : 'isKeyMan',
			type : 'checkbox',
			tclass : 'txtmin'
		}],
		event : {
			'clickAddRow' : function(e, rowNum, g) {
				if (customerId == '') {
					alert("����ѡ��һ���ͻ�");
					g.removeRow(rowNum);
				}
			}
		}

	});

}
// ��ͬ�����ӱ�
$(function() {

	linkmanList();

	// ��Ʒ�嵥
//	$("#productInfo").yxeditgrid({
//		objName : 'chance[product]',
//		url : '?model=projectmanagent_chance_product&action=listJson',
//		param : {
//			'chanceId' : $("#chanceId").val(),
//			'isTemp' : '0',
//			'isDel' : '0'
//		},
//		type : 'view',
//		tableClass : 'form_in_table',
//		colModel : [{
//			display : 'id',
//			name : 'id',
//			type : 'hidden'
//		}, {
//            name: 'newProLineName',
//            display: '��Ʒ��',
//            width: 100
//        }, {
//			display : '��Ʒ����',
//			name : 'conProductName',
//			tclass : 'readOnlyTxtNormal',
//			readonly : true
//		}, {
//			display : '��ƷId',
//			name : 'conProductId',
//			type : 'hidden'
//		}, {
//			display : '��Ʒ����',
//			name : 'conProductDes',
//			tclass : 'txt'
//		}, {
//			display : '����',
//			name : 'number',
//			tclass : 'txtshort',
//			type : 'money',
//			event : {
//				blur : function() {
//					countAll($(this).data("rowNum"));
//				}
//			}
//		}, {
//			display : '����',
//			name : 'price',
//			tclass : 'txtshort',
//			type : 'money',
//			event : {
//				blur : function() {
//					countAll($(this).data("rowNum"));
//				}
//			}
//		}, {
//			display : '���',
//			name : 'money',
//			tclass : 'txtshort',
//			type : 'money'
//		}, {
//			display : '��������Id',
//			name : 'license',
//			type : 'hidden'
//		}, {
//			name : 'licenseButton',
//			display : '��������',
//			type : 'statictext',
//			event : {
//				'click' : function(e) {
//					var rowNum = $(this).data("rowNum");
//					// ��ȡlicenseid
//					var licenseObj = $("#productInfo_cmp_license" + rowNum);
//
//					// ����
//					url = "?model=yxlicense_license_tempKey&action=toSelectWin"
//							+ "&licenseId=" + licenseObj.val()
//							+ "&productInfoId=" + "productInfo_cmp_license"
//							+ rowNum;
//					var returnValue = showModalDialog(url, '',
//							"dialogWidth:1000px;dialogHeight:600px;");
//
//					if (returnValue) {
//						licenseObj.val(returnValue);
//					}
//				}
//			},
//			html : '<input type="button"  value="��������"  class="txt_btn_a"  />'
//		}, {
//			display : '��Ʒ����Id',
//			name : 'deploy',
//			type : 'hidden'
//		}, {
//			name : 'deployButton',
//			display : '��Ʒ����',
//			type : 'statictext',
//			event : {
//				'click' : function(e) {
//					var rowNum = $(this).data("rowNum");
//					// �����Ʒ��Ϣ
//					var conProductId = $("#productInfo_cmp_conProductId"
//							+ rowNum).val();
//					var conProductName = $("#productInfo_cmp_conProductName"
//							+ rowNum).val();
//					var deploy = $("#productInfo_cmp_deploy" + rowNum).val();
//
//					if (conProductId == "") {
//						alert('����ѡ����ز�Ʒ!');
//						return false;
//					} else {
//						if (deploy == "") {
//
//							var url = "?model=goods_goods_properties&action=toChoose"
//									+ "&productInfoId="
//									+ "productInfo_cmp_deploy"
//									+ rowNum
//									+ "&goodsId="
//									+ conProductId
//									+ "&goodsName=" + conProductName;
//
//							showModalDialog(url, '',
//									"dialogWidth:1000px;dialogHeight:600px;");
//						} else {
//
//							var url = "?model=goods_goods_properties&action=toChooseAgain"
//									+ "&productInfoId="
//									+ "productInfo_cmp_deploy"
//									+ rowNum
//									+ "&goodsId="
//									+ conProductId
//									+ "&goodsName="
//									+ conProductName
//									+ "&cacheId=" + deploy;
//
//							var returnValue = showModalDialog(url, '',
//									"dialogWidth:1000px;dialogHeight:600px;");
//
//							if (returnValue) {
//								$("#goodsDetail_" + returnValue).remove();
//								//ѡ���Ʒ��̬��Ⱦ��������õ�
//								getCacheInfo(returnValue, rowNum);
//							}
//						}
//					}
//				}
//			},
//			html : '<input type="button"  value="��Ʒ����"  class="txt_btn_a"  />'
//		}],
//		isAddOneRow : false,
//		event : {
//			'clickAddRow' : function(e, rowNum, g) {
//				url = "?model=contract_contract_product&action=toProductIframe";
//				var returnValue = showModalDialog(url, '',
//						"dialogWidth:1000px;dialogHeight:600px;");
//
//				if (returnValue) {
//					g.setRowColValue(rowNum, "conProductId",
//							returnValue.goodsId, true);
//					g.setRowColValue(rowNum, "conProductName",
//							returnValue.goodsName, true);
//					g
//							.setRowColValue(rowNum, "number",
//									returnValue.number, true);
//					g.setRowColValue(rowNum, "price", returnValue.price, true);
//					g.setRowColValue(rowNum, "money", returnValue.money, true);
//					g.setRowColValue(rowNum, "warrantyPeriod",
//							returnValue.warrantyPeriod, true);
//					g.setRowColValue(rowNum, "deploy", returnValue.cacheId,
//							true);
//					g.setRowColValue(rowNum, "license", returnValue.licenseId,
//							true);
//					returnValue.deploy = returnValue.cacheId;
//					var $tr = g.getRowByRowNum(rowNum);
//					$tr.data("rowData", returnValue);
//					//ѡ���Ʒ��̬��Ⱦ��������õ�
//					getCacheInfo(returnValue.cacheId, rowNum);
//				} else {
//					g.removeRow(rowNum);
//				}
//
//				return false;
//			},
//			'reloadData' : function(e) {
//				initCacheInfo();
//			},
//			'removeRow' : function(e, rowNum, rowData) {
//				if (typeof(rowData) != 'undefined') {
//					$("#goodsDetail_" + rowData.deploy).remove();
//				}
//			}
//		}
//	});
   //������Ϣ
	$("#competitorList").yxeditgrid({
		objName : 'chance[competitor]',
		isAddOneRow : false,
		url : '?model=projectmanagent_chance_competitor&action=listJson',
		param : {
			'chanceId' : $("#chanceId").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '��������',
			name : 'competitor',
			tclass : 'txt'
		}, {
			display : '��������',
			name : 'superiority',
			tclass : 'txt'
		}, {
			display : '��������',
			name : 'disadvantaged',
			tclass : 'txt'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
});

//������װ��Ʒѡ��
(function($) {
	// ��Ʒ�嵥
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'chance[product]',
		url : '?model=projectmanagent_chance_product&action=listJson',
		tableClass: 'form_in_table',
		colModel: [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
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
			name: 'exeDeptId',
			type: 'hidden'
		}, {
			display: 'ִ������Name',
			name: 'exeDeptName',
			type: 'hidden'
		}, {
			display: '��Ʒ����',
			tclass: 'readOnlyTxtMiddle',
			name: 'proType',
			type: 'hidden'
		}, {
			display: '��Ʒ����id',
			name: 'proTypeId',
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
			type: 'number',
			event: {
				blur: function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '����',
			name: 'price',
			tclass: 'txtshort',
			type: 'money',
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
			display: '��Ʒ����Id',
			name: 'deploy',
			type: 'hidden'
		}, {
			name: 'deployButton',
			display: '��Ʒ����',
			type: 'statictext',
			event: {
				click: function(e) {
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
			html: '<input type="button" value="��Ʒ����" class="txt_btn_a"/>'
		}, {
			display: '��Ʒ����ΨһId',
			name: 'onlyProductId',
			type: 'hidden'
		}],
		isAddOneRow: false,
		event: {
			clickAddRow: function(e, rowNum, g) {
				rowNum = g.allAddRowNum;
				var url = "?model=contract_contract_product&action=toProductIframe&isCon=1"
					+ "&componentId=productInfo"
					+ "&rowNum="
					+ rowNum;

				window.open(url, '',
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
			},
			reloadData: function(e, g ,data) {
				initCacheInfo();
				// ִ�в��Ŵ���
//				initExeDept(data, g);
			},
			removeRow: function(e, rowNum, rowData) {
				if (typeof(rowData) != 'undefined') {
					$("#goodsDetail_" + rowData.deploy).remove();
					var matArr = $("#materialInfo").yxeditgrid("getCmpByCol", "onlyProductId");
					matArr.each(function() {
						if ($(this).val() == rowData.onlyProductId) {
							var $tr = $(this).parent("td").parent("tr");
							$tr.remove();
						}
					});
//					createProArr();
				}
			}
		},
		addBtnClick: function() {
			return false;
		},
		setData: function(returnValue, rowNum) {
			var g = this;
			if (returnValue) {
				// ����һ��
				g.addRow(g.allAddRowNum);
				//��Ʒ
				var proArr = returnValue[0];
				g.setRowColValue(rowNum, "proType", proArr.proType);
				g.setRowColValue(rowNum, "proTypeId", proArr.proTypeId);
				g.setRowColValue(rowNum, "proExeDeptId", proArr.proExeDeptId);
				g.setRowColValue(rowNum, "proExeDeptName", proArr.proExeDeptName);
				g.setRowColValue(rowNum, "newExeDeptCode", proArr.newExeDeptCode);
				g.setRowColValue(rowNum, "newExeDeptName", proArr.newExeDeptName);
				g.setRowColValue(rowNum, "newProLineCode", proArr.newExeDeptCode);
				g.setRowColValue(rowNum, "newProLineName", proArr.newExeDeptName);
//				initExeDeptByRow(g, rowNum);

				g.setRowColValue(rowNum, "conProductId", proArr.goodsId, true);
				g.setRowColValue(rowNum, "conProductName", proArr.goodsName, true);
				g.setRowColValue(rowNum, "number", proArr.number, true);
				g.setRowColValue(rowNum, "price", proArr.price, true);
				g.setRowColValue(rowNum, "money", proArr.money, true);
				g.setRowColValue(rowNum, "warrantyPeriod", proArr.warrantyPeriod, true);
				g.setRowColValue(rowNum, "deploy", proArr.cacheId, true);
				g.setRowColValue(rowNum, "license", proArr.licenseId, true);
				g.setRowColValue(rowNum, "onlyProductId", proArr.onlyProductId, true);
				proArr.deploy = proArr.cacheId;
				var $tr = g.getRowByRowNum(rowNum);
				$tr.data("rowData", proArr);
				//ѡ���Ʒ��̬��Ⱦ��������õ�
				getCacheInfo(proArr.cacheId, rowNum);
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

$(function() {
	// ��Ʒ�嵥
	$("#productInfo").productInfoGrid({
		param : {
			'chanceId' : $("#chanceId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		}
	});
});
