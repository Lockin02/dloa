var show_page = function(page) {	   $("#healthGrid").yxgrid("reload");};
$(function() {
	buttonsArr = [];
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_health&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};


	excelOutArr2 = {
		name : 'exportOut',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_health&action=toExcelOut"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutArr2);
			}
		}
	});

	$("#healthGrid").yxgrid({
			    model : 'hr_personnel_health',
               	title : '������Ϣ',
				bodyAlign:'center',
						//����Ϣ

						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'userNo',
                  					display : 'Ա�����',
                  					width:80,
                  					sortable : true,
                  					process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_health&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					width:80,
                  					sortable : true,
                  					process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_health&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'hospital',
                  					display : '���ҽԺ',
                  					sortable : true
                              },{
                    					name : 'checkDate',
                  					display : '�������',
                  					sortable : true
                              },{
                    					name : 'checkResult',
                  					display : '�����',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '����',
                  					width:400,
                  					sortable : true
                              },{
                    					name : 'hospitalOpinion',
                  					display : 'ҽԺ���',
                  					width:300,
                  					sortable : true
                              }],
                     lockCol:['userNo','userName'],//����������
      	buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
				// Ĭ�������ֶ���
				sortname : "userNo",
				// Ĭ������˳��
				sortorder : "asc",
		searchitems : [{
					display : "Ա�����",
					name : 'userNoSearch'
				},{
					display : "Ա������",
					name : 'userNameSearch'
				},{
                    name : 'checkDate',
                  	display : '�������'
                },{
                    name : 'checkResult',
                  	display : '�����'
                }]
 		});
 });