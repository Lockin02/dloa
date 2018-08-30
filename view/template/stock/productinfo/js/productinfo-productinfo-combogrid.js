$(function() {
	$("#relProductName").yxcombogrid_product( {
		hiddenId : 'relProductId',
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#relProductName").val(data.productName);
					$("#relProductCode").val(data.productCode);
				}
			}
		},
		event : {
			'clear' : function() {
				//重置关联裸机信息
				$("#relProductCode").val('');
			}
		}
	});
});

/**
 * 检查是否存在同样的物料名称以及型号的物料
 * @returns {boolean}
 */
var chkDuplicate = function(){
	var name = $("#productName").val(),pattern = $("#pattern").val(),id = $("#id").val();
	var condition = " and c.pattern = '"+pattern+"' and c.productName='"+name+"'";
	if(id && id != "" && id != undefined){
		condition += " and c.id <> "+id;
	}
	var redult = $.ajax({
		type : "POST",
		url : "?model=stock_productinfo_productinfo&action=ajaxchkDuplicate",
		data : {
			condition : condition
		},
		async : false
	}).responseText;

	if(redult == "yes"){
		alert("已存在物料名称为【"+name+"】以及型号/版本号为【"+pattern+"】的物料。");
		return false;
	}else{
		return true;
	}

}