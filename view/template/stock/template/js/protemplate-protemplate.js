$(function() {
	initDetail();
	// ѡ��ģ������
	$("#templateName").yxcombogrid_protemplate({
		hiddenId : 'id',
		width : 980,
		height : 300,
		searchName : 'templateName',
		
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#templateName").val(data.templateName);
					var returnValue = $.ajax({
						type : 'POST',
						url : "?model=stock_template_protemplateitem&action=listJson",
						data : {
							mainId : data.id,
							isDel : '0'
						},
						async : false
					}).responseText;
					returnValue = eval("(" + returnValue + ")");
					if (returnValue.length>0) {
						var g = $("#itemTable").data("yxeditgrid");
						g.removeAll();
						//ѭ���������
						for (var i = 0; i < returnValue.length; i++) {
							outJson = {
								"id" : returnValue[i].id,
								"productId" : returnValue[i].productId,
								"productCode" : returnValue[i].productCode,
								"productName" : returnValue[i].productName,
								"pattern" : returnValue[i].pattern,
								"unitName" : returnValue[i].unitName,
								"actNum" : returnValue[i].actNum,
								"loadNum" : returnValue[i].actNum,
							};
							//��������
							g.addRow(i, outJson);
						}
					}
				}
			}
		}
	});
});

//��ʼ���ʼ���ϸ
function initDetail() {
    //�����ʼ����ݱ�
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        objName: 'stockout[items]',
        title: '�����嵥',
        isAddAndDel:false,
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        },
        {
            name: 'productId',
            display: '����id',
            type: 'hidden',
        },
        {
            name: 'productCode',
            display: '���ϱ���',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 100
        },
        {
            name: 'productName',
            display: '��������',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 180
        },
        {
            name: 'pattern',
            display: '����ͺ�',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 180
        },
        {
            name: 'unitName',
            display: '��λ',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 100
        },
        {
            name: 'actNum',
            display: '����',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 100            
        },
        {
            name: 'loadNum',
            display: '��������',
            tclass : 'txt',
            width : 100
        }]
    });
    validate({
        "templateName": {
            required: true
        }
    });
}

function countNum(){
	var itemTableObj = $("#itemTable");
	var number = $("#number").val();
	var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "actNum");
	if (itemArr.length > 0) {
         //ѭ��
         itemArr.each(function() {
             allCost = accMul(number, $(this).val());
             //�ӱ���ʾ������Ҫ��������
             itemTableObj.yxeditgrid("setRowColValue",$(this).data('rowNum'),"loadNum",allCost,true);
         });
	}
}


//ȷ�Ϸ���
function confirmTemplate(){
	//��ȡ�ӱ���Ϣ
	var itemTableObj = $("#itemTable");
    var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "productName");
    if (itemArr.length > 0) {
        //ѭ��
    	itemArr.each(function() {
    		//ѭ���������
    		productId   = itemTableObj.yxeditgrid("getCmpByRowAndCol",$(this).data('rowNum'),"productId").val();
    		productName = itemTableObj.yxeditgrid("getCmpByRowAndCol",$(this).data('rowNum'),"productName").val();
    		productCode = itemTableObj.yxeditgrid("getCmpByRowAndCol",$(this).data('rowNum'),"productCode").val();
    		pattern     = itemTableObj.yxeditgrid("getCmpByRowAndCol",$(this).data('rowNum'),"pattern").val();
    		unitName    = itemTableObj.yxeditgrid("getCmpByRowAndCol",$(this).data('rowNum'),"unitName").val();
    		loadNum     = itemTableObj.yxeditgrid("getCmpByRowAndCol",$(this).data('rowNum'),"loadNum").val();
    		var ids = parent.$("#itemscount").val();
			var num = parseInt(ids)-1;
    		if(parent.$("#productName"+num).val() == ""){
    			parent.$("#productId"+num).val(productId);
				parent.$("#productName"+num).val(productName);
				parent.$("#productCode"+num).val(productCode);
				parent.$("#pattern"+num).val(pattern);
				parent.$("#unitName"+num).val(unitName);
				parent.$("#actOutNum"+num).val(loadNum);
			}else{
				parent.addItems();
				parent.$("#productId"+ids).val(productId);
				parent.$("#productName"+ids).val(productName);
				parent.$("#productCode"+ids).val(productCode);
				parent.$("#pattern"+ids).val(pattern);
				parent.$("#unitName"+ids).val(unitName);
				parent.$("#actOutNum"+ids).val(loadNum);
			}
    	});
    };
    self.parent.tb_remove();
}

