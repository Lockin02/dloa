var costpersonal_detail = function() {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
		openTab('../general/costmanage/reim','个人费用')
}
$(function() {
	$("#costpersonalGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getCostpersonalList",
		colModel : [{
			display : '报销类型',
			name : 'isProject',
			width : 150,
			process : function(v, row) {
			//判断报销类型
				v = row.isProject==0 ? "部门报销":"项目报销";
			if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}

				return "<a href='javascript:costpersonal_detail()'>" + v + "</a>";
			}
		}, {
			display : '报销编号',
			name : 'BillNo',
			width : 150
		}, {
			display : '报销状态',
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