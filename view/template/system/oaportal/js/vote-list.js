var openurl= function(rowId) {

	URL="general/vote/vote_action.php?Pid="+rowId;
 	myleft=(screen.availWidth-100)/2;
 	parent.window.open(URL,"投票","height=600,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
var openradiourl= function(rowId) {

	URL="general/vote/vote_radio.php?Pid="+rowId;
 	myleft=(screen.availWidth-100)/2;
 	parent.window.open(URL,"投票","height=600,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
$(function() {
	$("#voteGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getVoteList",
		// 表单
		colModel : [{
			display : '投票主题',
			name : 'SUBJECT',
			width : 150,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}
				if(row.TYPE==0)
					return "<a href='javascript:openradiourl(\""+row.Pid+"\")'>" + v + "</a>";
				else
					return "<a href='javascript:openurl(\""+row.Pid+"\")'>"+ v +"</a>";
			}
		}, {
			display : '发布时间',
			name : 'BEGIN_DATE',
			width : 100
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});