var diary_detail = function(rowId,randKey) {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
		openTab('general/diary/read.php?DIA_ID='+rowId,'������־')

}
$(function() {
	$("#diaryGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getDiaryList",
		// ��
		colModel : [{
			display : '��־����',
			name : 'DIA_TYPE',
			width : 100,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}
				//�ж���־����
				v = row.DIA_TYPE=="1" ? "������־":"������־";
				return "<a href='javascript:diary_detail(\""+row.DIA_ID+"\")'>" + v + "</a>";
			}
		}, {
			display : '����ʱ��',
			name : 'DIA_DATE',
			width : 250
		},{
			display : '��־����',
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