

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
		}, {
			display: 'originalId',
			name: 'originalId',
			type: 'hidden'
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
			beforeRemoveRow : function(e, rowNum, rowData, g) {// 借试用产品底下的物料如果下达了发货计划或已出库,不得删除
				var isTemp = $("#borrowId").val() == $("#oldId").val() ? '0' : '1';
				if(rowData.id){
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrowequ&action=getRelativeEqu",
						data : {
							"isTemp" : isTemp,
							"borrowId" : $("#borrowId").val(),
							"isDel" : 0,
							"conProductId" : rowData.id
						},
						async : false,
						success : function(data) {
							var issuedShipNum = 0;
							var executedNum = 0;
							if (data != "") {
								data = eval("(" + data + ")");
								$.each(data,function(i,item){
									executedNum += parseInt(item.executedNum);
									issuedShipNum += parseInt(item.issuedShipNum);
								});

								if( executedNum>0 ){//如果有已出库不能删
									alert("产品无法删除，此产品已有物料出库了。");
									g.isRemoveAction=false;
									return false;
								}else if(issuedShipNum > 0){//如果没有已出库,但是有发货计划的也不能删
									alert("产品无法删除，此产品已有物料下达了发货计划。");
									g.isRemoveAction=false;
									return false;
								}else{
									// 连带删除关联的物料信息
									if(confirm("确定删除该产品以及关联的所有物料吗?")){
										var idsArr = [];
										$.each(data,function(i,item){
											if(item.id){
												idsArr.push(item.id);
											}
										});
										var equIds = $("#equInfo").yxeditgrid("getCmpByCol" ,"id");
										for (var i = 0 ;i < equIds.length ;i++) {
											if($.inArray(equIds[i].value,idsArr) >= 0){
												var rowNum = $(equIds[i]).parent("td").parent("tr").attr("rownum");
												$(equIds[i]).parent("td").parent("tr").hide();
												$(equIds[i]).after('<input type="hidden" id="equInfo_cmp_isDelTag'+rowNum+'" name="borrow[borrowequ]['+rowNum+'][isDelTag]" value="1" class="">');
											}
										}
									}else{
										g.isRemoveAction=false;
										return false;
									}
								}
							}

						}
					});
				}
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
	//声明一个变量，用来判断当前应该加载源单记录还是临时变更记录
	//合同id等于源单id，则加载源单记录，否则加载临时变更记录
	var isTemp = $("#borrowId").val() == $("#oldId").val() ? '0' : '1';
	
	// 产品清单
	$("#productInfo").productInfoGrid({
		param: {
			'borrowId': $("#borrowId").val(),
			'isTemp': isTemp,
			'isDel': '0'
		}
	});

	//物料
	$("#equInfo").yxeditgrid({
		objName: 'borrow[borrowequ]',
		url: '?model=projectmanagent_borrow_borrowequ&action=listJson',
		param: {
			'borrowId': $("#borrowId").val(),
			'isTemp': isTemp,
			'isDel': '0'
		},
		isAddAndDel: false,
		tableClass: 'form_in_table',
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '物料编号',
			name: 'productNo',
			tclass: 'readOnlyTxtNormal',
			readonly: true,
			process: function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var isEqu = g.getCmpByRowAndCol(rowNum, 'productNo').val();
				if (isEqu == '') {
					$input.yxcombogrid_product({
						hiddenId: 'itemTable_cmp_productId' + rowNum,
						nameCol: 'productCode',
//					closeCheck : true,// 关闭状态,不可选择
						closeAndStockCheck: true,
						width: 600,
						gridOptions: {
							event: {
								row_dblclick: (function(rowNum) {
									return function(e, row, rowData) {
										g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
										g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
										g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
										g.getCmpByRowAndCol(rowNum, 'number').val("1");
									}
								})(rowNum)
							}
						}
					});
				}
			}
		}, {
			display: '物料Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '物料名称',
			name: 'productName',
			tclass: 'readOnlyTxtNormal',
			readonly: true,
			process: function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var isEqu = g.getCmpByRowAndCol(rowNum, 'productNo').val();
				if (isEqu == '') {
					$input.yxcombogrid_product({
						hiddenId: 'itemTable_cmp_productId' + rowNum,
						nameCol: 'productName',
//					closeCheck : true,// 关闭状态,不可选择
						closeAndStockCheck: true,
						width: 600,
						gridOptions: {
							event: {
								row_dblclick: (function(rowNum) {
									return function(e, row, rowData) {
										g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
										g.getCmpByRowAndCol(rowNum, 'productNo').val(rowData.productCode);
										g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
										g.getCmpByRowAndCol(rowNum, 'number').val("1");
									}
								})(rowNum)
							}
						}
					});
				}
			}
		}, {
			display: '型号/版本',
			name: 'productModel',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '数量',
			name: 'number',
			tclass: 'readOnlyTxtNormal',
			readonly: true
			// tclass: 'txtshort',
			// event: {
			// 	blur: function() {
			// 		countAll($(this).data("rowNum"), "equinfo");
			// 	}
			// }
		}, {
			display: '单价',
			name: 'price',
			tclass: 'readOnlyTxtItem',
			type: 'money',
			readonly: true
			// tclass: 'txtshort',
			// event: {
			// 	blur: function() {
			// 		countAll($(this).data("rowNum"), "equinfo");
			// 	}
			// }
		}, {
			display: '金额',
			name: 'money',
			// tclass: 'txtshort',
			tclass: 'readOnlyTxtItem',
			type: 'money',
			readonly: true
		}, {
			display: '加密配置Id',
			name: 'license',
			type: 'hidden'
		}, {
			name: 'licenseButton',
			display: '加密配置',
			type: 'hidden',
			// type: 'statictext',
			event: {
				'click': function(e) {
					var rowNum = $(this).data("rowNum");
					// 获取licenseid
					var licenseObj = $("#equInfo_cmp_license" + rowNum);
					if (licenseObj.val() == '') {
						// 弹窗
						var url = "?model=yxlicense_license_tempKey&action=toSelectWin"
						+ "&productInfoId="
						+ "equInfo_cmp_license"
						+ rowNum;
						var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");
						if (returnValue) {
							licenseObj.val(returnValue);
						}
					} else {
						// 弹窗
						var url = "?model=yxlicense_license_tempKey&action=toSelectChange" + "&licenseId=" + licenseObj.val()
						+ "&productInfoId="
						+ "equInfo_cmp_license"
						+ rowNum;
						var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");

						if (returnValue) {
							licenseObj.val(returnValue);
						}
					}
				}
			},
			html: '<input type="button"  value="加密配置"  class="txt_btn_a"  />'
		}, {
			display: 'originalId',
			name: 'originalId',
			type: 'hidden'
		}],
		event: {
			beforeRemoveRow : function(e, rowNum, rowData, g) {
				if( rowData.executedNum>0 ){//如果有已出库不能删
					alert("该物料已部分出库，禁止直接删除，请走退货流程！");
					g.isRemoveAction=false;
					return false;
				}else if(rowData.issuedShipNum > 0){//如果没有已出库,但是有发货计划的也不能删
					alert("该物料已下达发货计划，禁止直接删除！");
					g.isRemoveAction=false;
					return false;
				}
			}
		}
	});
});

// 表单收缩
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		tempH.style.display = "";
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		tempH.style.display = 'none';
	}
}
function changeEqu() {
	var rowNum = $("#productInfo").yxeditgrid('getCurShowRowNum');
	if (rowNum == '0') {
		document.getElementById("equ").style.display = "none";
	} else {
		document.getElementById("equ").style.display = "none";
	}
}