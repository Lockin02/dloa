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
	/**
	 * ��֤��Ϣ
	 */
	validate({
				"returnCode" : {
					required : true
				}
			});
	//�˻�������ظ���֤
	$("#returnCode").ajaxCheck({
		url : "?model=projectmanagent_return_return&action=checkRepeat",
		alertText : "* ���˻�������Ѵ���",
		alertTextOk : "* ���˻�����ſ���"
	});
	// ��Ʒ�嵥
	$("#equinfo").yxeditgrid({
		objName : 'return[equ]',
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 200
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
			readonly : true,
			width : 400
		}, {
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '����ִ������',
			name : 'maxNum',
			type : 'hidden'
		}, {
			display : '�˻�����',
			name : 'number',
			tclass : 'txtshort',
			width : 100,
			event : {
				blur : function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					thisNumber = $("#equinfo_cmp_number" + rowNum).val()*1;
					maxNum = $("#equinfo_cmp_maxNum" + rowNum).val();
					if(!isNum(thisNumber)){
                         alert("������������")
                         var g = $(this).data("grid");
                         g.setRowColValue(rowNum, "number",maxNum, true);
                     }
					if(thisNumber <= 0 || thisNumber > maxNum){
                       alert("�������ô���"+maxNum+",��С�ڵ���0 ");
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
				$("#goodsDetail_" + rowData.deploy).remove();
			}
		}
	});
});
