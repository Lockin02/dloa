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
			display : '��Ʒ����',
			name : 'conProductName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
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
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'moneySix',
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
                        alert("��ѡ��ͬһִ������Ĳ�Ʒ��");
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
						//ѡ���Ʒ��̬��Ⱦ��������õ�
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
	 * ��֤��Ϣ
	 */
	validate({
	});
})

function sub() {
	$("form").bind("submit", function() {
		var newProjectDays = $("#newProjectDays").val();
		var extensionDate = $("#extensionDate").val();
		if(extensionDate == "" && newProjectDays == ""){
			alert("����д��Ŀ���ڻ�������Ŀ���ڣ�")
			return false;
		}
		return true;

	})

}