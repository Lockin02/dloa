$(document).ready(function() {
$("#productInfo").yxeditgrid({
		objName : 'trialproject[product]',
		url:'?model=projectmanagent_trialproject_trialprojectEqu&action=listJson',
        param:{
        	'trialprojectId' : $("#trialprojectId").val()
        },
        type : "view",
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},
			{
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
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'moneySix',
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
//		}, {
//			display : '保修期',
//			name : 'warrantyPeriod',
//			tclass : 'txtshort'
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
					dataTip = $.ajax({
						type : "POST",
						url : "?model=goods_goods_goodsbaseinfo&action=getExeDeptCodeById",
						data : {
							"pid" : returnValue.goodsId
						},
						async : false,
						success : function(data) {

						}
					}).responseText;
					if($.inArray(dataTip,exedeptArr) == "-1" && exedeptArr.length != '0'){
                        alert("请选择同一执行区域的产品！");
                        g.removeRow(rowNum);
					}else{
						exedeptArr.push(dataTip);
					    g.setRowColValue(rowNum, "conProductId",returnValue.goodsId, true);
						g.setRowColValue(rowNum, "conProductName",returnValue.goodsName, true);
						g.setRowColValue(rowNum, "number",returnValue.number, true);
						g.setRowColValue(rowNum, "price", returnValue.price, true);
						g.setRowColValue(rowNum, "money", returnValue.money, true);
						g.setRowColValue(rowNum, "warrantyPeriod",returnValue.warrantyPeriod, true);
						g.setRowColValue(rowNum, "deploy", returnValue.cacheId,true);
						g.setRowColValue(rowNum, "license", returnValue.licenseId,true);
						var $tr=g.getRowByRowNum(rowNum);
						$tr.data("rowData",returnValue);
						//选择产品后动态渲染下面的配置单
						getCacheInfo(returnValue.cacheId,rowNum);
					}
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
	$.formValidator.initConfig({
				theme : "Default",
				submitOnce : true,
				formID : "form1",
				onError : function(msg, obj, errorlist) {
					alert(msg);
				}
			});
  })



  $(function(){


    /**
	 * 验证信息
	 */
	validate({
	});
})

function sub() {
	$("form").bind("submit", function() {
		var newProjectDays = $("#newProjectDays").val();
		var extensionDate = $("#extensionDate").val();
		if(extensionDate == "" && newProjectDays == ""){
			alert("请填写项目延期或者是项目工期！")
			return false;
		}
		return true;

	})

}