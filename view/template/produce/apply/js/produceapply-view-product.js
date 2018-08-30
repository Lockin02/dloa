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
			display: '合同编号（源单编号）'
		},{
			name: 'docCode',
			display: '生产需求编号',
			sortable: true,
			width: 100,
			process: function ($input, rowData) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toViewTab&id=" +
					rowData.id + "\",1)'>" + rowData.docCode + "</a>";
			}
		}, {
			name: 'relDocName',
			display: '合同名称（源单名称）'
		}, {
			name: 'relDocType',
			display: '合同类型（源单类型）'
		}, {
			name: 'produceNum',
			display: '数量'
		}]
	});
});