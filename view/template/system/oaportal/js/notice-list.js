var viewNotice = function(rowId,randKey) {
	parent.showThickboxWin("?model=info_notice&action=showinfo&id="
			+ rowId
			+ "&rand_key="
			+ randKey
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");

}
$(function() {
	$("#noticeGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getNoticeList",
		// ��
		colModel : [{
			display : '�������',
			name : 'title',
			width : 250,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}

				return "<a href='javascript:viewNotice(\""+row.id+"\",\""+row.rand_key+"\")'>" + v + "</a>";
			}
		}, {
			display : '����ʱ��',
			name : 'datef',
			width : 150
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});