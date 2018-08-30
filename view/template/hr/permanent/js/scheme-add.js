$(function() {
	GongArr = getData('HRKHJB');
	addDataToSelect(GongArr, 'schemeTypeCode');
	var url = "?model=hr_permanent_scheme&action=checkRepeat";
	$("#schemeName").ajaxCheck({
		url : url,
		alertText : "* 该名称已存在",
		alertTextOk : "* 该名称可用"
	});
//	$("#schemeTypeCode").ajaxCheck({
//		url : url,
//		alertText : "* 该对象方案已存在",
//		alertTextOk : "* 该对象方案可用"
//	});
	$("#schemeCode").ajaxCheck({
		url : url,
		alertText : "* 该编码已存在",
		alertTextOk : "* 该编码可用"
	});
	$("#schemeTypeCode").bind('change', function() {
		$("#assessName").yxcombogrid_scheme("remove");
		//alert("This is a text");
		$("#assessName").yxcombogrid_scheme({
			hiddenId : 'assessId',
			width : 500,
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
						$("#assessCode").val(data.schemeCode);
						$("#schemeTable").html("");
						$("#schemeTable").yxeditgrid({
							objName : 'scheme[standard]',
							url : '?model=hr_permanent_schemelist&action=listJson',
							param : {
								parentId : data.id
							},
							isAddOneRow : true,
							realDel : true,
							event : {
								removeRow : function(t, rowNum, rowData) {
									check_all();
								},
								reloadData : function(){
									var rowAmountVa = 0;
									var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "standardProportion");
									cmps.each(function() {
										rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
									});
									$("#schemeSum").val(rowAmountVa);
								}
							},
							colModel : [{
								display : '考核项目',
								name : 'standard',
								process : function($input, rowData) {
									var rowNum = $input.data("rowNum");
									var g = $input.data("grid");
									$input.yxcombogrid_schemeproject({
										hiddenId : 'schemeTable_cmp_standardId' + rowNum,
										nameCol : 'standard',
										width : 450,
										gridOptions : {
											showcheckbox : false,
											param : {
												'isDel' : '0',
												'isScrap' : '0'
											},
											event : {
												row_dblclick : (function(rowNum) {
													return function(e, row, rowData) {
													}
												})(rowNum)
											}
										}
									});
								},
								validation : {
									required : true
								},
								width : 120
							}, {
								display : '考核分数',
								name : 'standarScore',
								tclass : 'txtshort',
								validation : {
									custom : ['onlyNumber']
								},
								width : 60
							},{
								display : '考核权重',
								name : 'standardProportion',
								tclass : 'txtshort',
								validation : {
									custom : ['onlyNumber']
								},
								event : {
									blur : function() {
										check_all();
									}
								},
								width : 60
							}, {
								display : '考核内容',
								name : 'standardContent',
								tclass : 'txtlongt'
							}, {
								display : '考核要点',
								name : 'standardPoint',
								type : 'textarea',
								cols : '50',
								rows : '4'
							}]
						});

					}
				}
			}
		});
		$("#assessName").yxcombogrid_scheme("showCombo");

	});
	$("#schemeTable").yxeditgrid({
		objName : 'scheme[standard]',
		isAddOneRow : true,
		event : {
			removeRow : function(t, rowNum, rowData) {
				check_all();
			}
		},
		colModel : [{
			display : '考核项目',
			name : 'standard',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_schemeproject({
					hiddenId : 'schemeTable_cmp_standardId' + rowNum,
					nameCol : 'standard',
					width : 450,
					gridOptions : {
						showcheckbox : false,
						param : {
							'isDel' : '0',
							'isScrap' : '0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
								}
							})(rowNum)
						}
					}
				});
			},
			validation : {
				required : true
			},
			width : 120
		},  {
			display : '考核分数',
			name : 'standarScore',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			},
			staticVal:'10',
			width : 60
		},{
			display : '考核权重',
			name : 'standardProportion',
			tclass : 'txtshort',
			event : {
				blur : function() {
					check_all();
				}
			},
			validation : {
				custom : ['onlyNumber']
			},
			width : 60
		}, {
			display : '考核内容',
			name : 'standardContent',
			tclass : 'txtlong'
		}, {
			display : '考核要点',
			name : 'standardPoint',
			type : 'textarea',
			cols : '50',
			rows : '4'
		}]
	});
	validate();
})
// 根据从表的总权重必须为100
function check_all() {
	var rowAmountVa = 0;
	var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "standardProportion");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	if(rowAmountVa>100){
		alert("权重总和不能超过100！")
	}else{
		$("#schemeSum").val(rowAmountVa);
	}
}

function check_form() {
	//		alert($("#schemeSum").val());
	if ($("#schemeSum").val() != "100") {
		alert("权重总和必须为100！");
		return false;
	}
	return true;
}