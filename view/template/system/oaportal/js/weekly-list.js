var weekly_detail = function(rowId) {
		  URL="general/weekly/read_weekly.php?read_id="+rowId;
    	  myleft=(screen.availWidth-500)/2;
    	  parent.window.open(URL,"read_notify","height=550,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
$(function() {
	$("#weeklyGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getWeeklyList",
		// ��
		colModel : [{
			display : '�ܱ�����',
			name : 'type',
			width : 100,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}
				//�ж��ܱ�����
				if(row.type==0)
					v="���ܱ�";
				else if(row.type==1)
					v="˫�ܱ�";
				else
					v="�±�";

				return "<a href='javascript:weekly_detail(\""+row.id+"\")'>" + v + "</a>";
			}
		}, {
			display : '�ܴ�',
			name : 'w_num',
			width : 100
		},{
			display : '�ܱ�����',
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