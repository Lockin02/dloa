var diary_detail = function(rowId,randKey) {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
		openTab('general/diary/read.php?DIA_ID='+rowId,'工作日志')

}
$(function() {
	$("#diaryGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getDiaryList",
		// 表单
		colModel : [{
			display : '日志类型',
			name : 'DIA_TYPE',
			width : 100,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}
				//判断日志类型
				v = row.DIA_TYPE=="1" ? "工作日志":"个人日志";
				return "<a href='javascript:diary_detail(\""+row.DIA_ID+"\")'>" + v + "</a>";
			}
		}, {
			display : '发布时间',
			name : 'DIA_DATE',
			width : 250
		},{
			display : '日志标题',
			name : 'SUBJECT',
			width : 100
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});