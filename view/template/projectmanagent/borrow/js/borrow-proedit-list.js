function borrowTime() {
	var beginTime = $("#beginTime").val();
	var closeTime = $("#closeTime").val();
	var borrowTime = daysBetween(closeTime, beginTime);
	if (borrowTime <= 5) {
		document.getElementById("timeType").options.length = 0;
		document.getElementById("timeType").options.add(new Option("短期借用",
			"短期借用"));
	} else if (borrowTime > 5) {
		document.getElementById("timeType").options.length = 0;
		document.getElementById("timeType").options.add(new Option("长期借用",
			"长期借用"));
	}
}

// 单独封装产品选择
(function($) {
	// 产品清单
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'borrow[product]',
		url: '?model=projectmanagent_borrow_product&action=listJson',
		tableClass: 'form_in_table',
		isAddOneRow: false,
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '产品线',
			name: 'newProLineName',
			tclass: 'readOnlyTxtNormal',
			width: 80,
			readonly: true
		}, {
			display: '产品线编号',
			name: 'newProLineCode',
			type: 'hidden'
		}, {
			display: '执行区域code',
			name: 'exeDeptCode',
			type: 'hidden'
		}, {
			display: '执行区域',
			name: 'exeDeptName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
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
			tclass: 'txtshort'
		}, {
			display: '产品配置Id',
			name: 'deploy',
			type: 'hidden'
		}, {
			name: 'deployButton',
			display: '产品配置',
			type: 'statictext',
			event: {
				click: function() {
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
			html: '<input type="button" value="产品配置"  class="txt_btn_a"/>'
		}],
		event: {
			clickAddRow: function(e, rowNum, g) {
				rowNum = g.allAddRowNum;
				var url = "?model=contract_contract_product&action=toProductIframe&isMoney=1&isSale=1"
					+ "&componentId=productInfo&notEquSlt=1"
					+ "&rowNum="
					+ rowNum;

				window.open(url, '',
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
			},
			reloadData: function(e, g ,data) {
				initCacheInfo();
				// 产品线处理
//				initExeDept(data, g);
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
				// 新增一行
				g.addRow(g.allAddRowNum);
				// 产品
				g.setRowColValue(rowNum, "newExeDeptCode", returnValue.exeDeptCode);
				g.setRowColValue(rowNum, "newExeDeptName", returnValue.exeDeptName);
				g.setRowColValue(rowNum, "newProLineCode", returnValue.exeDeptCode);
				g.setRowColValue(rowNum, "newProLineName", returnValue.exeDeptName);
//				initExeDeptByRow(g, rowNum);

				g.setRowColValue(rowNum, "conProductId", returnValue.goodsId, true);
				g.setRowColValue(rowNum, "conProductName", returnValue.goodsName, true);
				g.setRowColValue(rowNum, "number", returnValue.number, true);
				g.setRowColValue(rowNum, "exeDeptCode", returnValue.auditDeptCode, true);
				g.setRowColValue(rowNum, "exeDeptName", returnValue.auditDeptName, true);
				g.setRowColValue(rowNum, "warrantyPeriod", returnValue.warrantyPeriod, true);
				g.setRowColValue(rowNum, "deploy", returnValue.cacheId, true);
				g.setRowColValue(rowNum, "license", returnValue.licenseId, true);
				returnValue.deploy = returnValue.cacheId;
				var $tr = g.getRowByRowNum(rowNum);
				$tr.data("rowData", returnValue);

				//选择产品后动态渲染下面的配置单
				getCacheInfo(returnValue.cacheId, rowNum);
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

// 合同新增从表
$(function() {
	var Type = $("#borrowType").val();
	if (Type == '试用') {
		document.getElementById("Type").options.add(new Option("试用", "试用"));
		document.getElementById("Type").options.add(new Option("借用", "借用"));
	} else {
		document.getElementById("Type").options.add(new Option("借用", "借用"));
		document.getElementById("Type").options.add(new Option("试用", "试用"));
	}
	var limits = $("#borrowLimits").val();
	if (limits == '员工') {
		borrowTime();
	}

	// 产品清单
	$("#productInfo").productInfoGrid({
		param: {
			borrowId: $("#borrowId").val(),
			isTemp: '0',
			isDel: '0'
		}
	});
});
