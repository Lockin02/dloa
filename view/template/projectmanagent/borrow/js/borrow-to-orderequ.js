//������ת�������� �ӱ���Ϣ��ʾ
$(function(){
	var orderId = $("#orderId").val();
	var orderType = $("#orderType").val();

	$.post(
		"?model=projectmanagent_borrow_borrow&action=getBorrowToOrderequ",
		{
			orderId : orderId,
			orderType : orderType
		}, function(data) {
			if(data == 0){ //û������ʱ
				$("#borrowOrderequ").append("");
			}else {
               var $html = $("<table class='form_main_table'>" +
    		               "<thead><tr align='left'><td colspan='11' class='form_header'>������ת���۲�Ʒ�嵥</td></tr></thead>" +
    		               "<tbody id='borrowbody'><tr class='main_tr_header'>" +
    		                                   "<th>���</th>"+
    		                                   "<th>��Ʒ���</th>"+
    		                                   "<th>��Ʒ����</th>"+
    		                                   "<th>��Ʒ�ͺ�</th>"+
    		                                   "<th>����</th>"+
    		                                   "<th>����</th>"+
    		                                   "<th>���</th>"+
    		                                   "<th>�ƻ���������</th>"+
    		                                   "<th>������</th>"+
    		                                   "<th>��������</th>"+
    		                                   "<th>��ͬ��</th></tr>"
    		                  );
    		    var $str = $(data);
    		    var $html2 = $("</tbody></table>");
    		    $html.append($str);
    		    $html.append($html2);
    		    $("#borrowOrderequ").append($html);
			}
		}
	);
});