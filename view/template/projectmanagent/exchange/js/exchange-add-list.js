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
$(function() {
	// 需要退的物料
	$("#backequinfo").yxeditgrid({
		objName : 'exchange[backequ]',
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '合同id',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '从表id',
			name : 'contractequId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '最大可执行数量',
			name : 'maxNum',
			type : 'hidden'
		}, {
			display : '退货数量',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					thisNumber = $("#backequinfo_cmp_number" + rowNum).val();
					maxNum = $("#backequinfo_cmp_maxNum" + rowNum).val();
					if(thisNumber <= 0 || thisNumber > parseFloat(maxNum)){
                       alert("数量不得大于"+maxNum+",或小于等于0 ");
                       var g = $(this).data("grid");
                       g.setRowColValue(rowNum, "number",maxNum, true);
					}

				}
			}
		}, {
			display : '执行数量',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}],
		event : {
			'clickAddRow' : function(e, rowNum, g) {
				url = "?model=contract_contract_product&action=toProductIframe";
				var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

				if (returnValue) {
					g.setRowColValue(rowNum, "conProductId",returnValue.goodsId, true);
					g.setRowColValue(rowNum, "conProductName",returnValue.goodsName, true);
					g.setRowColValue(rowNum, "number",returnValue.number, true);
					g.setRowColValue(rowNum, "price", returnValue.price, true);
					g.setRowColValue(rowNum, "money", returnValue.money, true);
					g.setRowColValue(rowNum, "warrantyPeriod",returnValue.warrantyPeriod, true);
					g.setRowColValue(rowNum, "deploy", returnValue.cacheId,true);
					g.setRowColValue(rowNum, "license", returnValue.licenseId,true);
					returnValue.deploy= returnValue.cacheId;
					var $tr=g.getRowByRowNum(rowNum);
					$tr.data("rowData",returnValue);

					//选择产品后动态渲染下面的配置单
					getCacheInfo(returnValue.cacheId,rowNum);
				} else {
					g.removeRow(rowNum);
				}

				return false;
			},
			'reloadData' : function(e){
				initCacheInfo();
			},
			'removeRow' : function(e, rowNum, rowData){
				if(typeof(rowData) != 'undefined'){
			    	$("#goodsDetail_" + rowData.deploy).remove();
				}
			}
		}
	});
	// 产品清单
	$("#productinfo").yxeditgrid({
		objName : 'exchange[product]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '产品名称',
			name : 'conProductName',
			tclass : 'txt',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_goods({
							hiddenId : 'productInfo_cmp_conProductId' + rowNum,
							height : 250,
							gridOptions : {
								showcheckbox : false,
								isTitle : true,
								event : {
									"row_dblclick" : (function(rowNum) {
										return function(e, row, rowData) {
											// 清除产品配置缓存
											$("#productInfo_cmp_deploy"
													+ rowNum).val("");
										}
									})(rowNum)
								}
							}
						});
			}
		}, {
			display : '产品Id',
			name : 'conProductId',
			type : 'hidden'
		}, {
			display : '产品描述',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '加密配置Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '加密配置',
			type : 'statictext',
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// 获取licenseid
					var licenseObj = $("#productInfo_cmp_license" + rowNum);

					// 弹窗
					url = "?model=yxlicense_license_tempKey&action=toSelectWin"+ "&licenseId=" + licenseObj.val()
						+ "&productInfoId="
						+ "productInfo_cmp_license"
						+ rowNum;
					var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

					if(returnValue){
						licenseObj.val(returnValue);
					}

//					showThickboxWin("?model=yxlicense_license_tempKey&action=toSelectWin"
//						+ "&licenseId=" + license
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000");
				}
			},
			html : '<input type="button"  value="加密配置"  class="txt_btn_a"  />'
		}, {
			display : '产品配置Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '产品配置',
			type : 'statictext',
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// 缓存产品信息
					var conProductId = $("#productInfo_cmp_conProductId"+ rowNum).val();
					var conProductName = $("#productInfo_cmp_conProductName"+ rowNum).val();
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
							;

							showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

//							showThickboxWin("?model=goods_goods_properties&action=toChoose"
//									+ "&productInfoId="
//									+ "productInfo_cmp_deploy"
//									+ rowNum
//									+ "&goodsId="
//									+ conProductId
//									+ "goodsName="
//									+ conProductName
//									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
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
							;

							var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

							if(returnValue){
								$("#goodsDetail_" + returnValue).remove();
								//选择产品后动态渲染下面的配置单
								getCacheInfo(returnValue,rowNum);
							}

//							showThickboxWin("?model=goods_goods_properties&action=toChooseAgain"
//									+ "&productInfoId="
//									+ "productInfo_cmp_deploy"
//									+ rowNum
//									+ "&goodsId="
//									+ conProductId
//									+ "&goodsName="
//									+ conProductName
//									+ "&cacheId="
//									+ deploy
//									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
						}
					}

				}
			},
			html : '<input type="button"  value="产品配置"  class="txt_btn_a"  />'
		}],
		isAddOneRow:false,
		event : {
			'clickAddRow' : function(e, rowNum, g) {
				url = "?model=contract_contract_product&action=toProductIframe";
				var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

				if (returnValue) {
					g.setRowColValue(rowNum, "conProductId",returnValue.goodsId, true);
					g.setRowColValue(rowNum, "conProductName",returnValue.goodsName, true);
					g.setRowColValue(rowNum, "number",returnValue.number, true);
					g.setRowColValue(rowNum, "price", returnValue.price, true);
					g.setRowColValue(rowNum, "money", returnValue.money, true);
					g.setRowColValue(rowNum, "warrantyPeriod",returnValue.warrantyPeriod, true);
					g.setRowColValue(rowNum, "deploy", returnValue.cacheId,true);
					g.setRowColValue(rowNum, "license", returnValue.licenseId,true);

					//选择产品后动态渲染下面的配置单
					getCacheInfo(returnValue.cacheId,rowNum);
				} else {
					g.removeRow(rowNum);
				}

				return false;
			},
			'reloadData' : function(e){
				initCacheInfo();
			},
			'removeRow' : function(e, rowNum, rowData){
				if(typeof(rowDate) != 'undefined'){
				  $("#goodsDetail_" + rowData.deploy).remove();
				}
			}
		}
	});
	//换货物料
	$("#equinfo").yxeditgrid({
		objName : 'exchange[equ]',
		tableClass : 'form_in_table',
		isAddOneRow:false,
//		isAdd : false,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					nameCol: 'productCode',
					isFocusoutCheck : false,
					width : 600,
					event : {
						'clear' : (function(rowNum) {
							return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val('');
									g.getCmpByRowAndCol(rowNum, 'productCode').val('');
				                    g.getCmpByRowAndCol(rowNum, 'productName').val('');
									g.getCmpByRowAndCol(rowNum, 'productModel').val('');
				                    g.getCmpByRowAndCol(rowNum, 'number').val('');
				                    g.getCmpByRowAndCol(rowNum, 'executedNum').val('');
							}
						})(rowNum)
					},
					gridOptions : {
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
									g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
									g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'number').val("1");
                                    g.getCmpByRowAndCol(rowNum, 'executedNum').val("0");
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					nameCol: 'productName',
					isFocusoutCheck : false,
					width : 600,
					event : {
						'clear' : (function(rowNum) {
							return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val('');
									g.getCmpByRowAndCol(rowNum, 'productCode').val('');
									g.getCmpByRowAndCol(rowNum, 'productName').val('');
									g.getCmpByRowAndCol(rowNum, 'productModel').val('');
				                    g.getCmpByRowAndCol(rowNum, 'number').val('');
				                    g.getCmpByRowAndCol(rowNum, 'executedNum').val('');
							}
						})(rowNum)
					},
					gridOptions : {
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
									g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
									g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'number').val("1");
                                    g.getCmpByRowAndCol(rowNum, 'executedNum').val("0");
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '换货数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '执行数量',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]

	});
});
