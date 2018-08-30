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
				//���ù��������Ϣ
				$("#relProductCode").val('');
			}
		}
	});
});

/**
 * ����Ƿ����ͬ�������������Լ��ͺŵ�����
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
		alert("�Ѵ�����������Ϊ��"+name+"���Լ��ͺ�/�汾��Ϊ��"+pattern+"�������ϡ�");
		return false;
	}else{
		return true;
	}

}