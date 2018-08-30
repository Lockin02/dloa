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
	/**
	 * 验证信息
	 */
	validate({
				"returnCode" : {
					required : true
				}
			});
	//退货单编号重复验证
	$("#returnCode").ajaxCheck({
		url : "?model=projectmanagent_return_return&action=checkRepeat",
		alertText : "* 该退货单编号已存在",
		alertTextOk : "* 该退货单编号可用"
	});
	// 产品清单
	$("#equinfo").yxeditgrid({
		objName : 'return[equ]',
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 200
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
			readonly : true,
			width : 400
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '最大可执行数量',
			name : 'maxNum',
			type : 'hidden'
		}, {
			display : '退货数量',
			name : 'number',
			tclass : 'txtshort',
			width : 100,
			event : {
				blur : function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					thisNumber = $("#equinfo_cmp_number" + rowNum).val()*1;
					maxNum = $("#equinfo_cmp_maxNum" + rowNum).val();
					if(!isNum(thisNumber)){
                         alert("请输入正整数")
                         var g = $(this).data("grid");
                         g.setRowColValue(rowNum, "number",maxNum, true);
                     }
					if(thisNumber <= 0 || thisNumber > maxNum){
                       alert("数量不得大于"+maxNum+",或小于等于0 ");
                       var g = $(this).data("grid");
                        g.setRowColValue(rowNum, "number",maxNum, true);
					}

				}
			}
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
				$("#goodsDetail_" + rowData.deploy).remove();
			}
		}
	});
});
