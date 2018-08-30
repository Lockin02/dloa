$(document).ready(function() {

	/*
	 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' } });
	 */


	$("#templateAttrList").yxeditgrid({
		objName : 'template[attrvals]',
		url:'?model=hr_inventory_templateattr&action=listJson',
		param:{
			templateId :$("#id").val(),
			sort : 'orderIndex',
			dir : 'ASC'
		},
		tableClass : 'form_in_table',
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					type : 'hidden'
				},
				{
					display : 'attrId',
					name : 'attrId',
					sortable : true,
					type : 'hidden'
				}, {
					display : '属性名称',
					name : 'attrName',
					type : 'txt',
					width : '70%',
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_attrvals({
									hiddenId : 'templateAttrList_cmp_attrId'
											+ rowNum,
									isFocusoutCheck : false,
									gridOptions : {
										showcheckbox : false,
										event : {
											"row_dblclick" : (function(rowNum) {
												return function(e, row, rowData) {
													var $attrName = g
															.getCmpByRowAndCol(
																	rowNum,
																	'attrName');
													$attrName
															.val(rowData.attrName);
												}
											})(rowNum)
										}
									}
								});

					}
				}, {
					display : '顺序',
					name : 'orderIndex',
					type : 'txt',
					width : '20%',
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '备注',
					name : 'remark',
					type : 'txt',
					width : '100%'
				}]
	});


	$("#templateSummaryList").yxeditgrid({
		objName : 'template[summary]',
		param:{
			templateId :$("#id").val(),
			sort : 'orderIndex',
			dir : 'ASC'
		},
		url:'?model=hr_inventory_templateSummary&action=listJson',
		tableClass : 'form_in_table',
		sort :'orderIndex',
		dir : 'ASC',
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					type : 'hidden'
				},{
					display : '问题',
					name : 'question',
					type : 'txt',
					width : '70%',
					validation : {
						required : true
					}
				}, {
					display : '顺序',
					name : 'orderIndex',
					type : 'txt',
					width : '20%',
					validation : {
						required : true
					}
				}, {
					display : '备注',
					name : 'remark',
					type : 'txt',
					width : '100%'
				}]
	});

	/**
	 * 验证信息
	 */
	validate({
				"templateName" : {
					required : true
				}
			});

})