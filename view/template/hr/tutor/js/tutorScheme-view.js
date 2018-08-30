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
									display : '������Ŀ',
									name : 'appraisal',
									type : 'txt',
									width : '15%'
								}, {
									display : 'Ȩ��ϵ��',
									name : 'coefficient',
									type : 'txt',
									width : '5%'
								}, {
									display : '�����߶ȣ�����:9(��)-10��',
									name : 'scaleA',
									type : 'txt',
									align:'left',
									width : '15%'
								}, {
									display : '�����߶ȣ�����:7(��)-9��',
									name : 'scaleB',
									type : 'txt',
									align:'left',
									width : '15%'
								}, {
									display : '�����߶ȣ�һ��:5(��)-7��',
									name : 'scaleC',
									type : 'txt',
									align:'left',
									width : '15%'
								}, {
									display : '�����߶ȣ��ϲ�:3(��)-5��',
									name : 'scaleD',
									type : 'txt',
									align:'left',
									width : '15%'
								}, {
									display : '�����߶ȣ�����:0-2��',
									name : 'scaleE',
									type : 'txt',
									align:'left',
									width : '15%'
								}]
					});
		})