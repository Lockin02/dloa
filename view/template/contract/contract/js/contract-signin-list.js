// ���㷽��
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
		|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// ��ȡ��ǰ��
		var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// ��ȡ��ǰ����
		var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// ���㱾�н�� - ����˰
		var thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}

// ������װ��Ʒѡ��
(function($) {
	// ��Ʒ�嵥
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'contract[product]',
		url: '?model=contract_contract_product&action=listJson',
		tableClass: 'form_in_table',
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '��Ʒ��',
			name: 'newProLineCode',
			type: 'select',
            datacode: 'HTCPX',
            event: {
                change: function () {
                    var g = $(this).data("grid");
                    var rowNum = $(this).data('rowNum');
                    g.getCmpByRowAndCol(rowNum, 'newProLineName').val($(this).find("option:selected").text());
                }
            }
		}, {
			display: '��Ʒ��Name',
			name: 'newProLineName',
			type: 'hidden'
		}, {
			display: 'ִ������',
			name: 'exeDeptId',
			type: 'select',
			datacode: 'GCSCX'
		}, {
			display: 'ִ������Name',
			name: 'exeDeptName',
			type: 'hidden'
		}, {
			display: '��Ʒ����',
			tclass: 'readOnlyTxtMiddle',
			name: 'proType',
			readonly: true
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
			event: {
				blur: function () {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '����',
			name: 'price',
			tclass: 'txtshort',
			type: 'money',
			event: {
				blur: function () {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '���',
			name: 'money',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: 'ԭ��Ʒ����Id',
			name: 'orgDeploy',
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
				click: function (e) {
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
			html: '<input type="button"  value="��Ʒ����"  class="txt_btn_a"/>'
		}, {
			display: '��Ʒ����ΨһId',
			name: 'onlyProductId',
			type: 'hidden'
		}],
		isAddOneRow: false,
		event: {
			clickAddRow: function (e, rowNum, g) {
				rowNum = g.allAddRowNum;
				var url = "?model=contract_contract_product&action=toProductIframe&isCon=1"
					+ "&componentId=productInfo"
					+ "&rowNum="
					+ rowNum;

				window.open(url, '',
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
			},
			reloadData: function (e, g ,data) {
				if ($("#proflag").val() == '0') {
					changeEqu();
				}
				initCacheInfo();

				// ִ�в��Ŵ���
//				initExeDept(data, g);
			},
			removeRow: function (e, rowNum, rowData) {
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
//				initExeDeptByRow(g, rowNum);
				setProExeDeptByRow(rowNum);

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

function linkmanList(customerId, flag) {

	var listObj = {
		objName: 'contract[linkman]',
		isAddOneRow: false,
		url: '?model=contract_contract_linkman&action=listJson',
		param: {
			'contractId': $("#contractId").val(),
			'isTemp': '0',
			'isDel': '0'
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '�ͻ���ϵ��',
			name: 'linkmanName',
			tclass: 'txt',
			process: function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_linkman({
					hiddenId: 'linkmanListInfo_cmp_linkmanId' + rowNum,
					isFocusoutCheck: false,
					gridOptions: {
						showcheckbox: false,
						param: {
							'customerId': customerId
						},
						event: {
							"row_dblclick": (function(rowNum) {
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
			display: '��ϵ��ID',
			name: 'linkmanId',
			type: 'hidden'
		}, {
			display: '�绰',
			name: 'telephone',
			tclass: 'txt'
		}, {
			display: 'QQ',
			name: 'QQ',
			tclass: 'txt'
		}, {
			display: '����',
			name: 'Email',
			tclass: 'txt'
		}, {
			display: '��ע',
			name: 'remark',
			tclass: 'txt'
		}]
	};
	if (flag == 1) {
		listObj.url = '';
		listObj.param = '';
	}
	// �ͻ���ϵ��
	$("#linkmanListInfo").yxeditgrid(listObj);
}
// ��ͬ�����ӱ�t
$(function() {

	linkmanList($("#customerId").val(), 0);
	// ��Ʒ�嵥
	$("#productInfo").productInfoGrid({
		objName: 'contract[product]',
		url: '?model=contract_contract_product&action=listJson',
		param: {
			'contractId': $("#contractId").val(),
			'dir': 'ASC',
			'isTemp': '0',
			'isDel': '0'
		}
	});

	// ��Ʊ�ƻ�
	$("#invoiceListInfo").yxeditgrid({
		objName: 'contract[invoice]',
		url: '?model=contract_contract_invoice&action=listJson',
		param: {
			'contractId': $("#contractId").val(),
			'isTemp': '0',
			'isDel': '0'
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '��Ʊ���',
			name: 'money',
			tclass: 'txt'
		}, {
			display: '������',
			name: 'softMoney',
			tclass: 'txt'
		}, {
			display: '��Ʊ����',
			name: 'iType',
			type: 'select',
			datacode: 'FPLX'
		}, {
			display: '��Ʊ����',
			name: 'invDT',
			type: 'date'
		}, {
			display: '��Ʊ����',
			name: 'remark',
			tclass: 'txt'
		}]
	});

	// �տ�ƻ�
	$("#incomeListInfo").yxeditgrid({
		objName: 'contract[income]',
		url: '?model=contract_contract_receiptplan&action=listJson',
		param: {
			'contractId': $("#contractId").val(),
			'isTemp': '0',
			'isDel': '0'
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '�տ���',
			name: 'money',
			tclass: 'txt'
		}, {
			display: '�տ�����',
			name: 'payDT',
			type: 'date'
		}, {
			display: '�տʽ',
			name: 'pType',
			tclass: 'txt'
		}, {
			display: '�տ�����',
			name: 'collectionTerms',
			tclass: 'txtlong'
		}]
	});

	// ��ѵ�ƻ�
	$("#trainListInfo").yxeditgrid({
		objName: 'contract[train]',
		url: '?model=contract_contract_trainingplan&action=listJson',
		param: {
			'contractId': $("#contractId").val(),
			'isTemp': '0',
			'isDel': '0'
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '��ѵ��ʼ����',
			name: 'beginDT',
			type: 'date'
		}, {
			display: '��ѵ��������',
			name: 'endDT',
			type: 'date'
		}, {
			display: '��������',
			name: 'traNum',
			tclass: 'txtshort'
		}, {
			display: '��ѵ�ص�',
			name: 'adress',
			tclass: 'txt'
		}, {
			display: '��ѵ����',
			name: 'content',
			tclass: 'txt'
		}, {
			display: '��ѵ����ʦҪ��',
			name: 'trainer',
			tclass: 'txt'
		}]
	});
});
