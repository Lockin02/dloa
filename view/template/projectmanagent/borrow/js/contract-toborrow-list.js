

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
function linkmanList(customerId){
// �ͻ���ϵ��
	$("#linkmanListInfo").yxeditgrid({
				objName : 'contract[linkman]',
				isAddOneRow:false,
				tableClass : 'form_in_table',
				colModel : [{
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
                                                     var $telephone = g.getCmpByRowAndCol(rowNum, 'telephone');
		                                              $telephone.val(rowData.mobile);
		                                             var $QQ = g.getCmpByRowAndCol(rowNum, 'QQ');
		                                              $QQ.val(rowData.QQ);
		                                             var $email = g.getCmpByRowAndCol(rowNum, 'Email');
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
							display : '��ע',
							name : 'remark',
							tclass : 'txt'
						}]

			});

}
// ��ͬ�����ӱ�
$(function() {

    linkmanList();

	// ��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		objName : 'contract[product]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '��Ʒ����',
			name : 'conProductName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
//			process : function($input, rowData) {
//				var rowNum = $input.data("rowNum");
//				var g = $input.data("grid");
//				$input.yxcombogrid_goods({
//							hiddenId : 'productInfo_cmp_conProductId' + rowNum,
//							height : 250,
//							gridOptions : {
//								showcheckbox : false,
//								isTitle : true,
//								event : {
//									"row_dblclick" : (function(rowNum) {
//										return function(e, row, rowData) {
//											// �����Ʒ���û���
//											$("#productInfo_cmp_deploy"
//													+ rowNum).val("");
//										}
//									})(rowNum)
//								}
//							}
//						});
//			}
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
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '���',
			name : 'money',
			tclass : 'txtshort',
			type : 'money'
//		}, {
//			display : '������',
//			name : 'warrantyPeriod',
//			tclass : 'txtshort'
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

	// ��Ʊ�ƻ�
	$("#invoiceListInfo").yxeditgrid({
				objName : 'contract[invoice]',
				tableClass : 'form_in_table',
				colModel : [{
							display : '��Ʊ���',
							name : 'money',
							tclass : 'txt'
						}, {
							display : '������',
							name : 'softMoney',
							tclass : 'txt'
						}, {
							display : '��Ʊ����',
							name : 'iType',
							type : 'select',
							datacode : 'FPLX'
						}, {
							display : '��Ʊ����',
							name : 'invDT',
							type : 'date'
						}, {
							display : '��Ʊ����',
							name : 'remark',
							tclass : 'txt'
						}],
				data : [{}]

			});

	// �տ�ƻ�
	$("#incomeListInfo").yxeditgrid({
				objName : 'contract[income]',
				tableClass : 'form_in_table',
				colModel : [{
							display : '�տ���',
							name : 'money',
							tclass : 'txt'
						}, {
							display : '�տ�����',
							name : 'payDT',
							type : 'date'
						}, {
							display : '�տʽ',
							name : 'pType',
							tclass : 'txt'
						}, {
							display : '�տ�����',
							name : 'collectionTerms',
							tclass : 'txtlong'
						}],
				data : [{}]

			});

	// ��ѵ�ƻ�
	$("#trainListInfo").yxeditgrid({
				objName : 'contract[train]',
				tableClass : 'form_in_table',
				colModel : [{
							display : '��ѵ��ʼ����',
							name : 'beginDT',
							type : 'date'
						}, {
							display : '��ѵ��������',
							name : 'endDT',
							type : 'date'
						}, {
							display : '��������',
							name : 'traNum',
							tclass : 'txtshort'
						}, {
							display : '��ѵ�ص�',
							name : 'adress',
							tclass : 'txt'
						}, {
							display : '��ѵ����',
							name : 'content',
							tclass : 'txt'
						}, {
							display : '��ѵ����ʦҪ��',
							name : 'trainer',
							tclass : 'txt'
						}],
				data : [{}]

			});
});
