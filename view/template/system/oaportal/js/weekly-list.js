var weekly_detail = function(rowId) {
		  URL="general/weekly/read_weekly.php?read_id="+rowId;
    	  myleft=(screen.availWidth-500)/2;
    	  parent.window.open(URL,"read_notify","height=550,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
$(function() {
	$("#weeklyGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getWeeklyList",
		// 表单
		colModel : [{
			display : '周报类型',
			name : 'type',
			width : 100,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}
				//判断周报类型
				if(row.type==0)
					v="单周报";
				else if(row.type==1)
					v="双周报";
				else
					v="月报";

				return "<a href='javascript:weekly_detail(\""+row.id+"\")'>" + v + "</a>";
			}
		}, {
			display : '周次',
			name : 'w_num',
			width : 100
		},{
			display : '周报标题',
			name : 'title',
			width : 250
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});