var costpersonal_detail = function() {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
		openTab('../general/costmanage/reim','���˷���')
}
$(function() {
	$("#costpersonalGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getCostpersonalList",
		colModel : [{
			display : '��������',
			name : 'isProject',
			width : 150,
			process : function(v, row) {
			//�жϱ�������
				v = row.isProject==0 ? "���ű���":"��Ŀ����";
			if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}

				return "<a href='javascript:costpersonal_detail()'>" + v + "</a>";
			}
		}, {
			display : '�������',
			name : 'BillNo',
			width : 150
		}, {
			display : '����״̬',
			name : 'Status',
			width : 100
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});