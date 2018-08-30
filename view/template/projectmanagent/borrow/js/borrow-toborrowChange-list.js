// 计算方法
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
			|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// 获取当前数
		thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// 获取当前单价
		thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// 计算本行金额 - 不含税
		thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}

function linkmanList(customerId,flag) {

	var listObj = {
		objName : 'contract[linkman]',
		isAddOneRow:false,
		url : '?model=contract_contract_linkman&action=listJson',
		param : {
			'contractId' : $("#contractId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '客户联系人',
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
			display : '联系人ID',
			name : 'linkmanId',
			type : 'hidden'
		}, {
			display : '电话',
			name : 'telephone',
			tclass : 'txt'
		}, {
			display : 'QQ',
			name : 'QQ',
			tclass : 'txt'
		}, {
			display : '邮箱',
			name : 'Email',
			tclass : 'txt'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	}
	if(flag == 1){
	   listObj.url = '';
	   listObj.param = '';
	}
	// 客户联系人
	$("#linkmanListInfo").yxeditgrid(listObj);
}
// 合同新增从表t
$(function() {

	linkmanList($("#customerId").val(),0);
	// 产品清单
	$("#productInfo").yxeditgrid({
		objName : 'contract[product]',
		url:'?model=contract_contract_product&action=listJson',
        param:{
        	'contractId' : $("#contractId").val(),
        	'dir' : 'ASC',
			'isTemp' : '0',
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '产品名称',
			name : 'conProductName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
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
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			type : 'money'
		}
//		, {
//			display : '加密配置Id',
//			name : 'license',
//			type : 'hidden'
//		}, {
//			name : 'licenseButton',
//			display : '加密配置',
//			type : 'statictext',
//			event : {
//				'click' : function(e) {
//					var rowNum = $(this).data("rowNum");
//					// 获取licenseid
//					var licenseObj = $("#productInfo_cmp_license" + rowNum);
//
//					// 弹窗
//					url = "?model=yxlicense_license_tempKey&action=toSelectChange"+ "&licenseId=" + licenseObj.val()
//						+ "&productInfoId="
//						+ "productInfo_cmp_license"
//						+ rowNum;
//					var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");
//
//					if(returnValue){
//						licenseObj.val(returnValue);
//					}
//
//					// showThickboxWin("?model=yxlicense_license_tempKey&action=toSelectWin"
//					// + "&licenseId=" + license
//					// +
//					// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000");
//				}
//			},
//			html : '<input type="button"  value="加密配置"  class="txt_btn_a"  />'
//		}
		, {
			display : '原产品配置Id',
			name : 'orgDeploy',
			type : 'hidden'
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
					var conProductId = $("#productInfo_cmp_conProductId"
							+ rowNum).val();
					var conProductName = $("#productInfo_cmp_conProductName"
							+ rowNum).val();
					var deploy = $("#productInfo_cmp_deploy" + rowNum).val();
					var orgDeploy = $("#productInfo_cmp_orgDeploy" + rowNum).val();

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
									+ "&goodsName=" + conProductName;

							showModalDialog(url, '',
									"dialogWidth:1000px;dialogHeight:600px;");

							// showThickboxWin("?model=goods_goods_properties&action=toChoose"
							// + "&productInfoId="
							// + "productInfo_cmp_deploy"
							// + rowNum
							// + "&goodsId="
							// + conProductId
							// + "goodsName="
							// + conProductName
							// +
							// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
						} else {

							if(deploy == orgDeploy){
								var url = "?model=goods_goods_properties&action=toChooseChange"
										+ "&productInfoId="
										+ "productInfo_cmp_deploy" + rowNum
										+ "&goodsId="
										+ conProductId
										+ "&goodsName="
										+ conProductName
										+ "&cacheId=" + deploy
										+ "&rowNum=" + rowNum
										;

								var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

								if(returnValue){
									$("#goodsDetail_" + orgDeploy).remove();
									//选择产品后动态渲染下面的配置单
									getCacheInfo(returnValue,rowNum);
								}
							}else{
								var url = "?model=goods_goods_properties&action=toChooseAgain"
										+ "&productInfoId="
										+ "productInfo_cmp_deploy" + rowNum
										+ "&goodsId="
										+ conProductId
										+ "&goodsName="
										+ conProductName
										+ "&cacheId=" + deploy
										+ "&rowNum=" + rowNum
										;

								var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

								if(returnValue){
									$("#goodsDetail_" + returnValue).remove();
									//选择产品后动态渲染下面的配置单
									getCacheInfo(returnValue,rowNum);
								}
							}
							$("#productInfo_cmp_deploy" + rowNum).val(returnValue);
						}
					}

				}
			},
			html : '<input type="button"  value="产品配置"  class="txt_btn_a"  />'
		}],
		isAddOneRow : false,
		event : {
			'clickAddRow' : function(e, rowNum, g) {
				url = "?model=contract_contract_product&action=toProductIframe";
				var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");
				if (returnValue) {
					g.setRowColValue(rowNum, "conProductId", returnValue.goodsId, true);
					g.setRowColValue(rowNum, "conProductName", returnValue.goodsName, true);
					g.setRowColValue(rowNum, "number", returnValue.number, true);
					g.setRowColValue(rowNum, "price", returnValue.price, true);
					g.setRowColValue(rowNum, "money", returnValue.money, true);
					g.setRowColValue(rowNum, "warrantyPeriod", returnValue.warrantyPeriod, true);
					g.setRowColValue(rowNum, "deploy", returnValue.cacheId, true);
					g.setRowColValue(rowNum, "license", returnValue.licenseId, true);
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

	// 开票计划
	$("#invoiceListInfo").yxeditgrid({
		objName : 'contract[invoice]',
		url : '?model=contract_contract_invoice&action=listJson',
		param : {
			'contractId' : $("#contractId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '开票金额',
			name : 'money',
			tclass : 'txt'
		}, {
			display : '软件金额',
			name : 'softMoney',
			tclass : 'txt'
		}, {
			display : '开票类型',
			name : 'iType',
			type : 'select',
			datacode : 'FPLX'
		}, {
			display : '开票日期',
			name : 'invDT',
			type : 'date'
		}, {
			display : '开票内容',
			name : 'remark',
			tclass : 'txt'
		}]
	});

	// 收款计划
	$("#incomeListInfo").yxeditgrid({
		objName : 'contract[income]',
		url : '?model=contract_contract_receiptplan&action=listJson',
		param : {
			'contractId' : $("#contractId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '收款金额',
			name : 'money',
			tclass : 'txt'
		}, {
			display : '收款日期',
			name : 'payDT',
			type : 'date'
		}, {
			display : '收款方式',
			name : 'pType',
			tclass : 'txt'
		}, {
			display : '收款条件',
			name : 'collectionTerms',
			tclass : 'txtlong'
		}]
	});

	// 培训计划
	$("#trainListInfo").yxeditgrid({
		objName : 'contract[train]',
		url : '?model=contract_contract_trainingplan&action=listJson',
		param : {
			'contractId' : $("#contractId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '培训开始日期',
			name : 'beginDT',
			type : 'date'
		}, {
			display : '培训结束日期',
			name : 'endDT',
			type : 'date'
		}, {
			display : '参与人数',
			name : 'traNum',
			tclass : 'txtshort'
		}, {
			display : '培训地点',
			name : 'adress',
			tclass : 'txt'
		}, {
			display : '培训内容',
			name : 'content',
			tclass : 'txt'
		}, {
			display : '培训工程师要求',
			name : 'trainer',
			tclass : 'txt'
		}]
	});
});
