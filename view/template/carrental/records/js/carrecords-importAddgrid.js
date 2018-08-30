$(document).ready(function() {
	$("#importTable").yxeditgrid({
		objName : 'carrecords[carrecordsdetail]',
		title : '�ó���ϸ',
		colModel : [  {
			display : '�ó���¼Id',
			name : 'recordsId',
			type : 'hidden'
		},{
			display : 'ʹ������',
			name : 'useDate',
			type : 'date'
		}, {
			display : '��ʼ������',
			name : 'beginNum',
			tclass : 'txtshort',
			validation : {
				required : false,
				custom : ['onlyNumber']
			},
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var endNum = g.getCmpByRowAndCol(rowNum,'endNum');
					var mileage = g.getCmpByRowAndCol(rowNum,'mileage');
					var beginNum = $(this).val();
					var sum = endNum.val() - beginNum;
					var mileage_v = "cmp_mileage"+rowNum+"_v";
					if( endNum.val() !='') {
						if( sum < 0 ) {
							alert("��ʼ���������ܴ��ڽ���������");
							return false;
						} else {
							mileage.val(sum);
							return true;
						}
					}else {
						 return false;
					}
				}
			}
		}, {
			display : '����������',
			name : 'endNum',
			tclass : 'txtshort',
			validation : {
				required : false,
				custom : ['onlyNumber']
			},
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var beginNum = g.getCmpByRowAndCol(rowNum,'beginNum');
					var mileage = g.getCmpByRowAndCol(rowNum,'mileage');
					var endNum = $(this).val();
					var sum = endNum - beginNum.val();
					var mileage_v = "cmp_mileage"+rowNum+"_v";
					if( sum < 0 ) {
						alert("��ʼ���������ܴ��ڽ���������");
					}else {
						mileage.val(sum);
					}
				}
			}
		}, {
			display : '�����',
			name : 'mileage',
			tclass : 'txtshort',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var beginNum = g.getCmpByRowAndCol(rowNum,'beginNum');
				var endNum = g.getCmpByRowAndCol(rowNum,'endNum');
				var mileage = g.getCmpByRowAndCol(rowNum,'mileage');
				var sum = endNum.val() - beginNum.val();
				if( sum < 0 ) {
					alert("��ʼ���������ܴ��ڽ���������");
				}else {
					mileage.val(sum);
				}

			}
		}, {
			display : 'ʹ��ʱ��',
			name : 'useHours',
			tclass : 'txtshort'
		}, {
			display : '��;',
			name : 'useReson',
			tclass : 'txt'
		}, {
			display : '�˳���',
			name : 'travelFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '�ͷ�',
			name : 'fuelFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '·�ŷ�',
			name : 'roadFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '��ЧLOG',
			name : 'effectiveLog',
			tclass : 'txt'
		}]
	})

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({

	});

});