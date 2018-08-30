// 计算方法
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
		|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// 获取当前数
		var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// 获取当前单价
		var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// 计算本行金额 - 不含税
		var thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}

// 单独封装产品选择
(function($) {
	// 产品清单
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'contract[product]',
		url: '?model=contract_contract_product&action=listJson',
		tableClass: 'form_in_table',
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '产品线',
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
			display: '产品线Name',
			name: 'newProLineName',
			type: 'hidden'
		}, {
			display: '执行区域',
			name: 'exeDeptId',
			type: 'select',
			datacode: 'GCSCX'
		}, {
			display: '执行区域Name',
			name: 'exeDeptName',
			type: 'hidden'
		}, {
			display: '产品类型',
			tclass: 'readOnlyTxtMiddle',
			name: 'proType',
			readonly: true
		}, {
			display: '产品类型id',
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
			display: '产品名称',
			name: 'conProductName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '产品Id',
			name: 'conProductId',
			type: 'hidden'
		}, {
			display: '产品描述',
			name: 'conProductDes',
			tclass: 'txt'
		}, {
			display: '数量',
			name: 'number',
			tclass: 'txtshort',
			event: {
				blur: function () {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '单价',
			name: 'price',
			tclass: 'txtshort',
			type: 'money',
			event: {
				blur: function () {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '金额',
			name: 'money',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: '原产品配置Id',
			name: 'orgDeploy',
			type: 'hidden'
		}, {
			display: '产品配置Id',
			name: 'deploy',
			type: 'hidden'
		}, {
			name: 'deployButton',
			display: '产品配置',
			type: 'statictext',
			event: {
				click: function (e) {
					var rowNum = $(this).data("rowNum");
					// 缓存产品信息
					var conProductId = $("#productInfo_cmp_conProductId" + rowNum).val();
					var conProductName = $("#productInfo_cmp_conProductName" + rowNum).val();
					var deploy = $("#productInfo_cmp_deploy" + rowNum).val();

					if (conProductId == "") {
						alert('请先选择相关产品!');
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
			html: '<input type="button"  value="产品配置"  class="txt_btn_a"/>'
		}, {
			display: '产品物料唯一Id',
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

				// 执行部门处理
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
				// 新增一行
				g.addRow(g.allAddRowNum);
				//产品
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
				//选择产品后动态渲染下面的配置单
				getCacheInfo(proArr.cacheId, rowNum);
			}
		},
		reloadCache: function(cacheId, rowNum) {
			if (cacheId) {
				$("#goodsDetail_" + cacheId).remove();
				//选择产品后动态渲染下面的配置单
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
			display: '客户联系人',
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
			display: '联系人ID',
			name: 'linkmanId',
			type: 'hidden'
		}, {
			display: '电话',
			name: 'telephone',
			tclass: 'txt'
		}, {
			display: 'QQ',
			name: 'QQ',
			tclass: 'txt'
		}, {
			display: '邮箱',
			name: 'Email',
			tclass: 'txt'
		}, {
			display: '备注',
			name: 'remark',
			tclass: 'txt'
		}]
	};
	if (flag == 1) {
		listObj.url = '';
		listObj.param = '';
	}
	// 客户联系人
	$("#linkmanListInfo").yxeditgrid(listObj);
}
// 合同新增从表t
$(function() {

	linkmanList($("#customerId").val(), 0);
	// 产品清单
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

	// 开票计划
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
			display: '开票金额',
			name: 'money',
			tclass: 'txt'
		}, {
			display: '软件金额',
			name: 'softMoney',
			tclass: 'txt'
		}, {
			display: '开票类型',
			name: 'iType',
			type: 'select',
			datacode: 'FPLX'
		}, {
			display: '开票日期',
			name: 'invDT',
			type: 'date'
		}, {
			display: '开票内容',
			name: 'remark',
			tclass: 'txt'
		}]
	});

	// 收款计划
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
			display: '收款金额',
			name: 'money',
			tclass: 'txt'
		}, {
			display: '收款日期',
			name: 'payDT',
			type: 'date'
		}, {
			display: '收款方式',
			name: 'pType',
			tclass: 'txt'
		}, {
			display: '收款条件',
			name: 'collectionTerms',
			tclass: 'txtlong'
		}]
	});

	// 培训计划
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
			display: '培训开始日期',
			name: 'beginDT',
			type: 'date'
		}, {
			display: '培训结束日期',
			name: 'endDT',
			type: 'date'
		}, {
			display: '参与人数',
			name: 'traNum',
			tclass: 'txtshort'
		}, {
			display: '培训地点',
			name: 'adress',
			tclass: 'txt'
		}, {
			display: '培训内容',
			name: 'content',
			tclass: 'txt'
		}, {
			display: '培训工程师要求',
			name: 'trainer',
			tclass: 'txt'
		}]
	});
});
