$(document).ready(function() {

			/*
			 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' }
			 * });
			 */
			var attrType = $("#attrType").text().replace(/\s+/g, "");

			if (attrType == "下拉框") {
				$("#attrVal").show();
			} else
				$("#attrVal").hide();

			$("#attrValList").yxeditgrid({
						objName : 'attr[attrvals]',
						type : 'view',
						url : '?model=hr_inventory_attrval&action=listJson',
						param : {
							attrId : $("#id").val(),
							dir : 'ASC'
						},
						tableClass : 'form_in_table',
						colModel : [{
									display : 'id',
									name : 'id',
									type : 'hidden'
								}, {
									display : '属性值',
									name : 'valName',
									type : 'txt'
								}]
					});
		})