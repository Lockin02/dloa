var bbs_detail = function(topic_title) {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
		openTab('general/bbs/read.php?DIA_ID='+topic_title,'ÂÛÌ³')

}
$(function() {

	$.ajax({
		type:"GET",
		url:"index1.php?model=system_oaportal_oaportlet&action=getBBSList",
		//dataType:"json",
		success: function(data){
			o=eval("("+data+")");
			//alert(data);

			var html=' ';
			//alert(o.collection[1].topic_title)
			for(var i=0;i<o.totalSize;i++){
			  //alert(o.collection[i].topic_title)
				html +='<tr><td width="250" align="center"><a href="javascript:bbs_detail('+o.collection[i].topic_title+')">'
				+o.collection[i].topic_title+'</a></td><td width="150" align="center">'
				+o.collection[i].topic_last_post_time+'</td><td width="150" align="center">'
				+o.collection[i].topic_last_poster_name+'</td></tr>';
			};
			$("#bbsTable").append(html);
		}
	});
});