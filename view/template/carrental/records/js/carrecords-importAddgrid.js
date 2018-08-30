$(document).ready(function() {
	$("#importTable").yxeditgrid({
		objName : 'carrecords[carrecordsdetail]',
		title : '用车明细',
		colModel : [  {
			display : '用车记录Id',
			name : 'recordsId',
			type : 'hidden'
		},{
			display : '使用日期',
			name : 'useDate',
			type : 'date'
		}, {
			display : '起始公里数',
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
							alert("开始公里数不能大于结束公里数");
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
			display : '结束公里数',
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
						alert("开始公里数不能大于结束公里数");
					}else {
						mileage.val(sum);
					}
				}
			}
		}, {
			display : '里程数',
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
					alert("开始公里数不能大于结束公里数");
				}else {
					mileage.val(sum);
				}

			}
		}, {
			display : '使用时长',
			name : 'useHours',
			tclass : 'txtshort'
		}, {
			display : '用途',
			name : 'useReson',
			tclass : 'txt'
		}, {
			display : '乘车费',
			name : 'travelFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '油费',
			name : 'fuelFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '路桥费',
			name : 'roadFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '有效LOG',
			name : 'effectiveLog',
			tclass : 'txt'
		}]
	})

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});

});