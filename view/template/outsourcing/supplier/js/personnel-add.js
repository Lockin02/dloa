$(document).ready(function() {
	 /**
			 * 编码唯一性验证
			 */

			var url = "?model=outsourcing_supplier_personnel&action=checkRepeat";
//			if ($("#id").val()) {
//				url += "&id=" + $("#id").val();
//			}
			$("#identityCard").ajaxCheck({
						url : url,
						alertText : "* 该人员信息已存在",
						alertTextOk : "* OK"
					});




	$("#bankListInfo").yxeditgrid({
		objName : 'personnel[areaSkill]',
		dir : 'ASC',
		colModel : [{
        				name : 'skillTypeCode',
      					display : '技能类型',
      					type:'select',
      					datacode:'WBJNLX',
      					width:200,
      					sortable : true
                  },{
        				name : 'levelCode',
      					display : '级别',
      					type:'select',
      					datacode:'WBRYJB',
      					sortable : true
                  },{
        				name : 'remark',
      					display : '备注',
      					width:300,
      					sortable : true
                  }]
	});

	validate({
				"userName" : {
					required : true
				}
			});
 })