$(function () {

	var url = "?model=hr_permanent_scheme&action=checkRepeat";
	if ($("#parentId").val()) {
				url += "&id=" + $("#parentId").val();
			}
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
	$("#schemeTypeCode").bind('change', function () {
		$("#assessName").yxcombogrid_scheme("remove");
		//alert("This is a text");
		$("#assessName").yxcombogrid_scheme({
			hiddenId : 'assessId',
			width : 500,
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function (e, row, data) {
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
								removeRow : function (t, rowNum, rowData) {
									check_all();
								}
							},
							colModel : [ {
									type : 'hidden',
									name : 'id'
								}, {
									display : '考核项目',
									name : 'standard',
									process : function ($input, rowData) {
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
													row_dblclick : (function (rowNum) {
														return function (e, row, rowData) {}
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
									width : 60
								},{
									display : '考核权重',
									name : 'standardProportion',
									tclass : 'txtshort',
									event : {
										blur : function () {
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
									cols : '40',
									rows : '4'
								}, {
									type : 'hidden',
									name : 'id',
									display : 'id'
								}
							]
						});
					}
				}
			}
		});
		$("#assessName1").yxcombogrid_scheme("showCombo");

	});

	$("#schemeTable").yxeditgrid({
		objName : 'scheme[standard]',
		url : '?model=hr_permanent_schemelist&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		event : {
			removeRow : function (t, rowNum, rowData) {
				check_all();
			}
		},
		colModel : [{
				display : '考核项目',
				name : 'standard',
				process : function ($input, rowData) {
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
								row_dblclick : (function (rowNum) {
									return function (e, row, rowData) {}
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
				event : {
					blur : function () {
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
				cols : '60',
				rows : '4'
			}, {
				type : 'hidden',
				name : 'id',
				display : 'id'
			}
		]
	});
	validate();
	/*
	validate({
	"orderNum" : {
	required : true,
	custom : 'onlyNumber'
	}
	});
	 */
})
// 根据从表的总权重必须为100
function check_all() {
	var rowAmountVa = 0;
	var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "standardProportion");
	cmps.each(function () {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#schemeSum").val(rowAmountVa);
	return false;
}
function check_form() {
	check_all();
	if ($("#schemeSum").val() != "100") {
		alert("权重总和必须为100！");
		return false;
	}
	return true;
}