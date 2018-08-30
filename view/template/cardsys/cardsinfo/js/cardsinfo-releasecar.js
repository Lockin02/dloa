$(document).ready(function() {
	$("#releaseTable").yxeditgrid({
		objName : 'cardsinfo[release]',
		url : '?model=cardsys_cardsinfo_cardsinfo&action=listJson',
		type:'edit',
		isDel : false,
		param : {
			ownerId : $("#ownerId").val()
		},
		colModel : [
		{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '����',
			name : 'carNo',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_cardsinfo({
					hiddenId : 'releaseTable_cmp_id' + rowNum,
					width : 600,
					isFocusoutCheck : true,
					gridOptions : {
						showcheckbox : false,
						param : {
							'ownerId' : $("#ownerId").val()
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var tprojectName = g.getCmpByRowAndCol(rowNum,'projectName');
									tprojectName.val(rowData.projectName);
									var townerName = g.getCmpByRowAndCol(rowNum,'ownerName');
									townerName.val(rowData.ownerName);
									var topenDate = g.getCmpByRowAndCol(rowNum,'openDate');
									topenDate.val(rowData.openDate);
									var tstatus = g.getCmpByRowAndCol(rowNum,'status');
									tstatus.val(rowData.status);
								}
							})(rowNum)
						}
					}
				});
			},
			width : 100
		},{
			display : '������Ŀ',
			name : 'projectName',
			readonly : true,
			tclass : "readOnlyText"
		},{
			display : '�ֿ���Id',
			name : 'ownerId',
			type : 'hidden'
		}, {
			display : '�ֿ���',
			name : 'ownerName',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxselect_user({
					hiddenId : 'releaseTable_cmp_ownerId' + rowNum,
					nameCol : 'ownerName'
				});
			}
		},{
			display : '״̬',
			name : 'status',
			type : "select",
			datacode : 'CSKZT'
        }]
	});

	validate({

	});
});
