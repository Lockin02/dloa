$(document).ready(function() {
	$("#schemeTable").yxeditgrid({
		objName : 'scheme[schemeinfo]',
		url : '?model=hr_tutor_schemeinfo&action=listJson',
		param : {
			'tutorassessId' : $("#id").val()
		},
		type : 'view',
		colModel : [{
			display : '考评项目',
			name : 'appraisal',
			width : '8%'
		}, {
			display : '权重系数',
			name : 'coefficient',
			width : '2%'
		}, {
			display : '优秀：9(含)-10',
			name : 'scaleA',
			width : '130px'
		}, {
			display : '良好：7(含)-9',
			name : 'scaleB',
			width : '130px'
		}, {
			display : '一般：5(含)-7',
			name : 'scaleC',
			width : '130px'
		}, {
			display : '较差：3(含)-5',
			name : 'scaleD',
			width : '130px'
		}, {
			display : '极差：0-2',
			name : 'scaleE',
			width : '130px'
		}, {
			display : '导师自评分',
			name : 'selfgraded',
			width : '2%',
			process : function(v) {
				if (v == "0.00") {
					return "";
				} else {
					return v;
				}
			}
		},{
			display : '自评说明',
			name : 'selfRemark',
			type : 'statictext'
		}, {
			display : '学员直接上级评分',
			name : 'superiorgraded',
			width : '2%',
			process : function(v) {
				if (v == "0.00") {
					return "";
				} else {
					return v;
				}
			}
		},  {
			display : '上级评分说明',
			name : 'superiorRemark',
			type : 'statictext'
		}, {
			display : '人力资源部评分',
			name : 'hrgraded',
			width : '2%',
			process : function(v) {
				if (v == "0.00") {
					return "";
				} else {
					return v;
				}
			}
		},{
			display : '人力资源部评分说明',
			name : 'hrRemark',
			type : 'statictext'
		}, {
			display : '部门负责人评分',
			name : 'assistantgraded',
			width : '2%',
			process : function(v) {
				if (v == "0.00") {
					return "";
				} else {
					return v;
				}
			}
		}, {
			display : '部门负责人评分说明',
			name : 'assistantRemark',
			type : 'statictext'
		},{
			display : '新员工评分',
			name : 'staffgraded',
			width : '2%',
			process : function(v) {
				if (v == "0.00") {
					return "";
				} else {
					return v;
				}
			}
		},{
			display : '新员工评分说明',
			name : 'staffRemark',
			type : 'statictext',
			isSubmit : true
		}],
		event : {
			reloadData : function(data) {
//				var g = this, el = this.el, p = this.options;
					var divDocument = document.getElementById("schemeTable");
					var tbody = divDocument.getElementsByTagName("tbody");
					var $tbody = $(tbody);
				$.ajax({
					type : 'POST',
					url : '?model=hr_tutor_scheme&action=toScoreInfo',
					data : {
						schemeId : $("#id").val()
					},
					async : false,
					success : function(data) {
						var obj = eval("(" + data +")");
						if(obj[0].selfgraded>0){
							var selfgraded=obj[0].selfgraded*10;
						}else{
							var selfgraded=obj[0].selfgraded;
						}
						if(obj[0].superiorgraded>0){
							var superiorgraded=obj[0].superiorgraded*10;
						}else{
							var superiorgraded=obj[0].superiorgraded;
						}
						if(obj[0].hrgraded>0){
							var hrgraded=obj[0].hrgraded*10;
						}else{
							var hrgraded=obj[0].hrgraded;
						}
						if(obj[0].assistantgraded>0){
							var assistantgraded=obj[0].assistantgraded*10;
						}else{
							var assistantgraded=obj[0].assistantgraded;
						}
                        if(obj[0].staffgraded>0){
                            var staffgraded=obj[0].staffgraded*10;
                        }else{
                            var staffgraded=obj[0].staffgraded;
                        }
						$tbody.append("<tr><td colspan='8' rowspan='2'><b>考核评分</b></td><td><b>"+ selfgraded +"</b></td><td></td><td><b>"+ superiorgraded  +"</b></td><td></td><td><b>"+ hrgraded  +"</b></td><td></td><td><b>"+ assistantgraded +"</b></td><td></td><td><b>"+ staffgraded +"</b></td><td></td></tr>" +
								"<tr><td colspan='8'><b>"+ obj[0].assessmentScore +"</b></td><td></td><td></td></tr>");
					}
				});
			}
		}
	});
})
//
////评分合计
//$(function() {
//	var goodsTable = document.getElementById("schemeTable");
//	var tbody = goodsTable.getElementsByTagName("tbody");
//	var $tbody = $(tbody);
//
//	$.ajax({
//		type : 'POST',
//		url : '?model=hr_tutor_scheme&action=toScoreInfo',
//		data : {
//			schemeId : $("#id").val()
//		},
//		async : false,
//		success : function(data) {
//			$tbody.html(123);
//		}
//	});
//})
