$(document).ready(function() {
	$("#bankListInfo").yxeditgrid({
		objName : 'personnel[areaSkill]',
		url : '?model=outsourcing_supplier_personLevel&action=listJson',
		param : {
			dir : 'ASC',
			parentId :$("#id").val()
		},
		type : 'view',
		dir : 'ASC',
		colModel : [{
        				name : 'skillTypeName',
      					display : '技能类型',
      					width:200,
      					sortable : true
                  },{
        				name : 'levelName',
      					display : '级别',
      					width:80,
      					sortable : true
                  },{
        				name : 'remark',
      					display : '备注',
      					width:300,
      					sortable : true
                  }]
	});

    })