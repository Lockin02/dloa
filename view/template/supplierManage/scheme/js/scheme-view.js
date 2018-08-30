$(function() {
	$("#schemeTable").yxeditgrid({
		objName : 'scheme[schemeItem]',
		delTagName : 'isDelTag',
		type : 'view',
		url : '?model=supplierManage_scheme_schemeItem&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [ {
            display : '评估部门',
            name : 'assesDept'
        },{
			display : '评估项目',
			name : 'assesProName',
			validation : {
				required : true
			}
		}, {
			display : '评估指标',
			name : 'assesStandard',
			validation : {
				required : true
			}
		}, {
            display : '负责人',
            name : 'assesMan'
        },{
			display : '指标权重',
			name : 'assesProportion',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '评估说明',
			name : 'assesExplain',
			tclass : 'txt'
		}]
	})

	// 判断是否显示关闭按钮
	// alert($("#showBtn").val());
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});