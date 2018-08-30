$(function() {
	initDetail();
	// 选择模板名称
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
						//循环拆分数组
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
							//插入数据
							g.addRow(i, outJson);
						}
					}
				}
			}
		}
	});
});

//初始化质检明细
function initDetail() {
    //缓存质检内容表
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        objName: 'stockout[items]',
        title: '物料清单',
        isAddAndDel:false,
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        },
        {
            name: 'productId',
            display: '物料id',
            type: 'hidden',
        },
        {
            name: 'productCode',
            display: '物料编码',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 100
        },
        {
            name: 'productName',
            display: '物料名称',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 180
        },
        {
            name: 'pattern',
            display: '规格型号',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 180
        },
        {
            name: 'unitName',
            display: '单位',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 100
        },
        {
            name: 'actNum',
            display: '数量',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            width : 100            
        },
        {
            name: 'loadNum',
            display: '载入数量',
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
         //循环
         itemArr.each(function() {
             allCost = accMul(number, $(this).val());
             //从表显示方法需要这样调用
             itemTableObj.yxeditgrid("setRowColValue",$(this).data('rowNum'),"loadNum",allCost,true);
         });
	}
}


//确认方法
function confirmTemplate(){
	//获取从表信息
	var itemTableObj = $("#itemTable");
    var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "productName");
    if (itemArr.length > 0) {
        //循环
    	itemArr.each(function() {
    		//循环拆分数组
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

