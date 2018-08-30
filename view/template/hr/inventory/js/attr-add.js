$(document).ready(function() {

			/*
			 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' }
			 * });
			 */
			$("#attrType").bind('change', function() {
						if ($("#attrType option:selected").val() == 1) {
							$("#attrVal").show();
						} else
							$("#attrVal").hide();
					})

			$("#attrValList").yxeditgrid({
						objName : 'attr[attrvals]',
						tableClass : 'form_in_table',
						colModel : [{
									display : '属性值',
									name : 'valName',
									type : 'txt',
									validation : {
										required : true
									}
								}]
					});

			/**
			 * 验证信息
			 */
			validate({
						"attrName" : {
							required : true
						}
					});
		})