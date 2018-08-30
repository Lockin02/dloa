$(document).ready(function() {
	$("#importTable").yxeditgrid({
		objName : 'esmperson[person]',
		colModel : [{
			display : '��Ա����id',
			name : 'personLevelId',
			type : 'hidden'
		}, {
			display : '��Ա�ȼ�',
			name : 'personLevel',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_eperson({
					hiddenId : 'importTable_cmp_personLevelId' + rowNum,
					nameCol : 'personLevel',
					width : 600,
					gridOptions : {
						showcheckbox : false,
					isTitle : true,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'coefficient').val(rowData.coefficient);
//									g.getCmpByRowAndCol(rowNum,'price').val(rowData.price);
									setMoney("importTable_cmp_price" +  rowNum,rowData.price,2);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '����ʼ����',
			name : 'planBeginDate',
			type : 'date',
			tclass : 'txtmiddle Wdate',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = $(this).val();
					var planEndDate = g.getCmpByRowAndCol(rowNum,'planEndDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'days').val(days);
						calPersonBatch(rowNum);
					}
				}
			},
			value : $("#planBeginDate").val()
		}, {
			display : '�����������',
			name : 'planEndDate',
			type : 'date',
			tclass : 'txtmiddle Wdate',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planEndDate = $(this).val();
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'days').val(days);
						calPersonBatch(rowNum);
					}
				}
			},
			value : $("#planEndDate").val()
		}, {
			display : '����',
			name : 'days',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			validation : {
				required : true
			},
			tclass : 'readOnlyTxtShort',
			readonly : true,
			value : $("#days").val()
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					calPersonBatch($(this).data("rowNum"));
				}
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '����ϵ��',
			name : 'coefficient',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			type : 'hidden'
		}, {
			display : '�˹�����',
			name : 'personDays',
			tclass : 'txtshort'
		}, {
			display : '�����ɱ�',
			name : 'personCostDays',
			tclass : 'txtshort'
		}, {
			display : '�����ɱ����',
			name : 'personCost',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '��ע˵��',
			name : 'remark'
		}]
	})

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({

	});
});
