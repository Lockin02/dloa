$(document).ready(function() {

	/*
	 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' } });
	 */


	$("#templateAttrList").yxeditgrid({
		objName : 'template[attrvals]',
		tableClass : 'form_in_table',
		sort :'orderIndex',
		dir : 'ASC',
		colModel : [{
					display : 'attrId',
					name : 'attrId',
					sortable : true,
					type : 'hidden'
				}, {
					display : '��������',
					name : 'attrName',
					type : 'txt',
					width : '70%',
					readonly:true,
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
					display : '˳��',
					name : 'orderIndex',
					type : 'txt',
					width : '20%',
					validation : {
						required : true
					}
				}, {
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : '100%'
				}]
	});

	$("#templateSummaryList").yxeditgrid({
		objName : 'template[summary]',
		tableClass : 'form_in_table',
		sort :'orderIndex',
		dir : 'ASC',
		colModel : [{
					display : '����',
					name : 'question',
					type : 'txt',
					width : '70%',
					validation : {
						required : true
					}
				}, {
					display : '˳��',
					name : 'orderIndex',
					type : 'txt',
					width : '20%',
					validation : {
						required : true
					}
				}, {
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : '100%'
				}]
	});


	/**
	 * ��֤��Ϣ
	 */
	validate({
				"templateName" : {
					required : true
				}
			});
})