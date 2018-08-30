$(document).ready(function() {
	$("#bankListInfo").yxeditgrid({
		objName : 'personnel[areaSkill]',
		url : '?model=outsourcing_supplier_personLevel&action=listJson',
		param : {
			dir : 'ASC',
			parentId :$("#id").val()
		},
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
				},
				"identityCard" : {
					required : true
				}
			});
   })