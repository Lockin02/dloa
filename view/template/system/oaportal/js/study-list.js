var study_detail = function(rowId,randKey) {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
		openTab('general/education/training/study/index.php?data_id='+rowId,'�γ�ѧϰ')
}
$(function() {
	$("#studyGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getStudyList",
		// ��
		colModel : [{
			display : 'ѧϰ����',
			name : 'name',
			width : 100,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}
				return "<a href='javascript:study_detail(\""+row.pid+"\")'>" + v + "</a>";
			}
		}, {
			display : '����',
			name : 'type',
			width : 100,
			process : function(v, row){
				v = row.type==0 ? "�ĵ���":"��Ƶ��";
				return v;
			}
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});