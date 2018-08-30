$(document).ready(function () {

	var applyObj = $("#applyInfo");
	applyObj.yxeditgrid({
		url: '?model=produce_apply_produceapply&action=productListJson',
		param: {
			productId: $("#productId").val()
		},
		type: 'view',
		colModel: [ {
			name: 'relDocCode',
			display: '��ͬ��ţ�Դ����ţ�'
		},{
			name: 'docCode',
			display: '����������',
			sortable: true,
			width: 100,
			process: function ($input, rowData) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toViewTab&id=" +
					rowData.id + "\",1)'>" + rowData.docCode + "</a>";
			}
		}, {
			name: 'relDocName',
			display: '��ͬ���ƣ�Դ�����ƣ�'
		}, {
			name: 'relDocType',
			display: '��ͬ���ͣ�Դ�����ͣ�'
		}, {
			name: 'produceNum',
			display: '����'
		}]
	});
});