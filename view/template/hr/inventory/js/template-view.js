$(document).ready(function() {

			/*
			 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' }
			 * });
			 */

			$("#templateAttrList").yxeditgrid({
						objName : 'template[attrvals]',
						type : 'view',
						url : '?model=hr_inventory_templateattr&action=listJson',
						param : {
							templateId : $("#id").val(),
							sort : 'orderIndex',
							dir : 'ASC'
						},
						tableClass : 'form_in_table',

						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									type : 'hidden'
								}, {
									display : 'attrId',
									name : 'attrId',
									sortable : true,
									type : 'hidden'
								}, {
									display : '属性名称',
									name : 'attrName',
									type : 'txt',
									width : '250'
								}, {
									display : '顺序',
									name : 'orderIndex',
									type : 'txt',
									width :20,
									validation : {
										required : true
									}
								}, {
									display : '备注',
									name : 'remark',
									type : 'txt',
									width : 250
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
					display : '问题',
					name : 'question',
					type : 'statictext',
					width : '70%',
					validation : {
						required : true
					}
				}, {
					display : '顺序',
					name : 'orderIndex',
					type : 'statictext',
					width : '20%',
					validation : {
						required : true
					}
				}, {
					display : '备注',
					name : 'remark',
					type : 'statictext',
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