$(document).ready(function() {
	$("#schemeTable").yxeditgrid({
		objName : 'scheme[schemeinfo]',
		url : '?model=hr_tutor_schemeinfo&action=listJson',
		param : {
			'tutorassessId' : $("#id").val()
		},
		type : 'view',
		colModel : [{
			display : '������Ŀ',
			name : 'appraisal',
			width : '8%'
		}, {
			display : 'Ȩ��ϵ��',
			name : 'coefficient',
			width : '2%'
		}, {
			display : '���㣺9(��)-10',
			name : 'scaleA',
			width : '130px'
		}, {
			display : '���ã�7(��)-9',
			name : 'scaleB',
			width : '130px'
		}, {
			display : 'һ�㣺5(��)-7',
			name : 'scaleC',
			width : '130px'
		}, {
			display : '�ϲ3(��)-5',
			name : 'scaleD',
			width : '130px'
		}, {
			display : '���0-2',
			name : 'scaleE',
			width : '130px'
		}, {
			display : '��ʦ������',
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
			display : '����˵��',
			name : 'selfRemark',
			type : 'statictext'
		}, {
			display : 'ѧԱֱ���ϼ�����',
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
			display : '�ϼ�����˵��',
			name : 'superiorRemark',
			type : 'statictext'
		}, {
			display : '������Դ������',
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
			display : '������Դ������˵��',
			name : 'hrRemark',
			type : 'statictext'
		}, {
			display : '���Ÿ���������',
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
			display : '���Ÿ���������˵��',
			name : 'assistantRemark',
			type : 'statictext'
		},{
			display : '��Ա������',
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
			display : '��Ա������˵��',
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
						$tbody.append("<tr><td colspan='8' rowspan='2'><b>��������</b></td><td><b>"+ selfgraded +"</b></td><td></td><td><b>"+ superiorgraded  +"</b></td><td></td><td><b>"+ hrgraded  +"</b></td><td></td><td><b>"+ assistantgraded +"</b></td><td></td><td><b>"+ staffgraded +"</b></td><td></td></tr>" +
								"<tr><td colspan='8'><b>"+ obj[0].assessmentScore +"</b></td><td></td><td></td></tr>");
					}
				});
			}
		}
	});
})
//
////���ֺϼ�
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
