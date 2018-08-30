// ������
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
	// ��Ҫ�˵�����
	$("#backequinfo").yxeditgrid({
		objName : 'exchange[backequ]',
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��ͬid',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '�ӱ�id',
			name : 'contractequId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '����ִ������',
			name : 'maxNum',
			type : 'hidden'
		}, {
			display : '�˻�����',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					thisNumber = $("#backequinfo_cmp_number" + rowNum).val();
					maxNum = $("#backequinfo_cmp_maxNum" + rowNum).val();
					if(thisNumber <= 0 || thisNumber > parseFloat(maxNum)){
                       alert("�������ô���"+maxNum+",��С�ڵ���0 ");
                       var g = $(this).data("grid");
                       g.setRowColValue(rowNum, "number",maxNum, true);
					}

				}
			}
		}, {
			display : 'ִ������',
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

					//ѡ���Ʒ��̬��Ⱦ��������õ�
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
	// ��Ʒ�嵥
	$("#productinfo").yxeditgrid({
		objName : 'exchange[product]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '��Ʒ����',
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
											// �����Ʒ���û���
											$("#productInfo_cmp_deploy"
													+ rowNum).val("");
										}
									})(rowNum)
								}
							}
						});
			}
		}, {
			display : '��ƷId',
			name : 'conProductId',
			type : 'hidden'
		}, {
			display : '��Ʒ����',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '��������Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '��������',
			type : 'statictext',
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// ��ȡlicenseid
					var licenseObj = $("#productInfo_cmp_license" + rowNum);

					// ����
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
			html : '<input type="button"  value="��������"  class="txt_btn_a"  />'
		}, {
			display : '��Ʒ����Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '��Ʒ����',
			type : 'statictext',
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// �����Ʒ��Ϣ
					var conProductId = $("#productInfo_cmp_conProductId"+ rowNum).val();
					var conProductName = $("#productInfo_cmp_conProductName"+ rowNum).val();
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
								//ѡ���Ʒ��̬��Ⱦ��������õ�
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
			html : '<input type="button"  value="��Ʒ����"  class="txt_btn_a"  />'
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

					//ѡ���Ʒ��̬��Ⱦ��������õ�
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
	//��������
	$("#equinfo").yxeditgrid({
		objName : 'exchange[equ]',
		tableClass : 'form_in_table',
		isAddOneRow:false,
//		isAdd : false,
		colModel : [{
			display : '���ϱ��',
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
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��������',
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
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��������',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : 'ִ������',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]

	});
});
