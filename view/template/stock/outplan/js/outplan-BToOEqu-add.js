function in_array(needle, haystack) {
type = typeof needle
 if(type == 'string' || type =='number') {
  for(var i in haystack) {
   if(haystack[i] == needle) {
     return true;
   }
  }
 }
 return false;
}



// ������ת�������� �ӱ���Ϣ��ʾ
$(function(){
	

	$("#stockName").yxcombogrid_stockinfo('remove').yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			model : 'stock_stockinfo_stockinfo',
			action : 'pageJson',
			event : {
				'row_dblclick' : function(e, row, data) {
                	var itemTableObj = $("#itemTable");
	            	var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "outStockName");
	            	var itemIDArr = itemTableObj.yxeditgrid("getCmpByCol", "stockId");
	            	var itemCodeArr = itemTableObj.yxeditgrid("getCmpByCol", "outStockCode");
	            	if (itemArr.length > 0) {
	            		itemArr.each(function() {
	            			$(this).val(data.stockName);
	            		});
	            	}
	            	if (itemIDArr.length > 0) {
	            		itemIDArr.each(function() {
	            			$(this).val(data.id);
	            		});
	            	}
	            	if (itemCodeArr.length > 0) {
	            		itemCodeArr.each(function() {
	            			$(this).val(data.stockCode);
	            		});
	            	}
                
				}
			}
		}
	})
	
	var docId = $("#docId").val();
	var docType = $("#docType").val();
	var rowNum = $('#rowNum').val();
	var arrayObj = new Array(
		'oa_contract_contract'
	);
	if( in_array(docType,arrayObj) ){
		$.post(
			"?model=common_contract_allsource&action=getBorrwoToOrderequ",
			{
				docId : docId,
				docType : docType
				,rowNum : rowNum
			}, function(data) {
				if(!strTrim(data)){ // û������ʱ,strTrim(ȥ���ո�)
					$("#BToOEqu").append("");
					$("#borrowTr").hide();
				}else {
	               var $html = $("<table class='form_main_table' id='borrowTable'>" +
	    		               "<thead><tr align='left'><td colspan='9'>������ת���۲�Ʒ�嵥   <img src='images/verpic_open.gif' onclick=\"dis('borrowbody')\" title='����' /></td></tr></thead>" +
	    		               "<tbody id='borrowbody'><tr class='main_tr_header'>" +
	    		                                   "<th>���</th>"+
	    		                                   "<th>��Ʒ���</th>"+
	    		                                   "<th>��Ʒ����</th>"+
	    		                                   "<th>����ͺ�</th>"+
	    		                                   "<th>��λ</th>"+
//	    		                                   "<th>�����ֿ�����</th>"+
	    		                                   "<th>��ͬ����</th>"+
	    		                                   "<th>���´﷢������</th>"+
	    		                                   "<th>���μƻ���������</th>"+
	    		                                   "<th>����</th></tr>"
	    		                  );
	    		    var $str = $(data);
	    		    var $html2 = $("</tbody></table>");
	    		    $html.append($str);
	    		    $html.append($html2);
	    		    $("#BToOEqu").append($html);
				}
			}
		);
	}else{
		$("#borrowTr").hide();
	}
});