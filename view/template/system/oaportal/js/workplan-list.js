var plan_detail = function(rowId) {

		URL="general/work_plan/show/plan_detail.php?PLAN_ID="+rowId;
 		myleft=(screen.availWidth-500)/2;
 		parent.window.open(URL,"read_work_plan","height=400,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
$(function() {
	$("#workplanGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getWorkplanList",
		// 表单
		colModel : [{
			display : '工作计划',
			name : 'NAME',
			width : 150,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}

				return "<a href='javascript:plan_detail(\""+row.PLAN_ID+"\")'>" + v + "</a>";
			}
		}, {
			display : '发布时间',
			name : 'BEGIN_DATE',
			width : 100
		}, {
			display : '截止时间',
			name : 'END_DATE',
			width : 100
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});