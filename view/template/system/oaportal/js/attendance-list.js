var attendance_detail = function() {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
		openTab('general/attendance/personal/','¸öÈË¿¼ÇÚ');

}
$(function() {

		$.ajax({
		type:"GET",
		url:"index1.php?model=system_oaportal_oaportlet&action=getAttendanceList",
		//dataType:"json",
		success: function(data){
			o=eval("("+data+")");
			//alert(data);

			var html=' ';
			//alert(o.collection[1].topic_title)
			for(var i=0;i<o.totalSize;i++){
			  //alert(o.collection[i].topic_title)
				html +='<tr><td width="150" align="center"><a href="javascript:bbs_detail('+o.collection[i].topic_title+')">'
				+o.collection[i].LEAVE_TYPE+'</a></td><td width="150" align="center">'
				+o.collection[i].LEAVE_DATE1+'</td><td width="150" align="center">'
				+o.collection[i].LEAVE_DATE2+'</td><td width="150" align="center">'
				+o.collection[i].DAYS+'</td>';
			};
			$("#attendanceTable").append(html);
		}
	});
});