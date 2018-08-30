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

// 借试用转销售物料 从表信息显示
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
				if(!strTrim(data)){ // 没有物料时
					$("#borrowTr").append("");
					$("#borrowTr").hide();
				}else {
	               var $html = $("<table class='form_main_table' id='borrowTable'>" +
	    		               "<thead><tr align='left'><td colspan='10'>借试用转销售产品清单   <img src='images/verpic_open.gif' onclick=\"dis('borrowbody')\" title='缩放' /></td></tr></thead>" +
	    		               "<tbody id='borrowbody'><tr class='main_tr_header'>" +
	    		                                   "<th>序号</th>"+
	    		                                   "<th>产品编号</th>"+
	    		                                   "<th>产品名称</th>"+
	    		                                   "<th>规格型号</th>"+
	    		                                   "<th>单位</th>"+
	    		                                   "<th>本次计划发货数量</th>"+
	    		                                   "<th>操作</th></tr>"
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