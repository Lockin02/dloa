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
	var id = $("#id").val();
	var docType = $("#docType").val();
	var rowNum = $('#rowNum').val();
	var perm = 'change';
	var arrayObj = new Array(
		'oa_contract_contract'
	);
//TODO:
	if( in_array(docType,arrayObj) ){
		$.post(
			"?model=stock_outplan_outplan&action=getBToOEqu",
			{
				id : id,
				docType : docType
				,rowNum : rowNum
				,perm : perm
			}, function(data) {
				if(!strTrim(data)){ // û������ʱ
					$("#borrowTr").append("");
					$("#borrowTr").hide();
				}else {
	               var $html = $("<table class='form_main_table' id='borrowTable'>" +
	    		               "<thead><tr align='left'><td colspan='10'>������ת���۲�Ʒ�嵥   <img src='images/verpic_open.gif' onclick=\"dis('borrowbody')\" title='����' /></td></tr></thead>" +
	    		               "<tbody id='borrowbody'><tr class='main_tr_header'>" +
	    		                                   "<th>���</th>"+
	    		                                   "<th>��Ʒ���</th>"+
	    		                                   "<th>��Ʒ����</th>"+
	    		                                   "<th>����ͺ�</th>"+
	    		                                   "<th>��λ</th>"+
	    		                                   "<th>���μƻ���������</th>"+
	    		                                   "<th>����</th></tr>"
	    		                  );
	    		    var $str = $(data);
	    		    var $html2 = $("</tbody></table>");
	    		    $html.append($str);
	    		    $html.append($html2);
	    		    $("#BToOEqu").append($html);
//	    		    $('#rowNum').val($('#borrowNum').val()*1);
				}
			}
		);
	}else{
		$("#borrowTr").hide();
	}
});