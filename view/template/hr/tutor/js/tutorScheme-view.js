$(document).ready(function() {

			$("#schemeDetailList").yxeditgrid({
						objName : 'tutorScheme[attrvals]',
						type : 'view',
						url : '?model=hr_tutor_schemeDetail&action=listJson',
						param : {
							parentId : $("#id").val(),
							dir : 'ASC'
						},


						tableClass : 'form_in_table',
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									type : 'hidden'
								}, {
									display : 'parentId',
									name : 'parentId',
									sortable : true,
									type : 'hidden'
								},{
									display : '考评项目',
									name : 'appraisal',
									type : 'txt',
									width : '15%'
								}, {
									display : '权重系数',
									name : 'coefficient',
									type : 'txt',
									width : '5%'
								}, {
									display : '考评尺度（优秀:9(含)-10）',
									name : 'scaleA',
									type : 'txt',
									align:'left',
									width : '15%'
								}, {
									display : '考评尺度（良好:7(含)-9）',
									name : 'scaleB',
									type : 'txt',
									align:'left',
									width : '15%'
								}, {
									display : '考评尺度（一般:5(含)-7）',
									name : 'scaleC',
									type : 'txt',
									align:'left',
									width : '15%'
								}, {
									display : '考评尺度（较差:3(含)-5）',
									name : 'scaleD',
									type : 'txt',
									align:'left',
									width : '15%'
								}, {
									display : '考评尺度（极差:0-2）',
									name : 'scaleE',
									type : 'txt',
									align:'left',
									width : '15%'
								}]
					});
		})