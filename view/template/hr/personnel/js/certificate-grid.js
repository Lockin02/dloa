var show_page = function(page) {
$("#certificateGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [
//        {
//			name : 'view',
//			text : "�߼���ѯ",
//			icon : 'view',
//			action : function() {
//				alert('������δ�������');
////				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
////					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        }
    ];


	//��ͷ��ť����
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_certificate&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	 excelOutSelect = {
			name : 'excelOutAllArr',
			text : "�Զ��嵼����Ϣ",
			icon : 'excel',
			action : function() {
				if($("#totalSize").val()<1){
					alert("û�пɵ����ļ�¼");
				}else{
					document.getElementById("form2").submit();
				}
			}
        };

	excelOutArr2 = {
		name : 'exportOut',
		text : "�߼���ѯ������",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_certificate&action=toExcelOut"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_certificate&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data = 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutArr2);
				buttonsArr.push(excelOutSelect);
			}
		}
	});
			$("#certificateGrid").yxgrid({
				model : 'hr_personnel_certificate',
               	title : '�ʸ�֤����Ϣ',
				isOpButton:false,
				bodyAlign:'center',
			    event:{'afterload':function(data,g){
			      $("#listSql").val(g.listSql);
          	 		$("#totalSize").val(g.totalSize);
			    }},
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
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_certificate&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'certificates',
                  					display : '֤������',
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_certificate&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'level',
                  					display : '�ȼ�',
                  					sortable : true
                              },{
                    					name : 'certifying',
                  					display : '��֤����',
                  					sortable : true
                              },{
                    					name : 'certifyingDate',
                  					display : '��֤ʱ��',
                  					sortable : true
                              }],

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
						display : "֤������",
						name : 'certificatesSearch'
					},{
						display : "��֤����",
						name : 'certifyingSearch'
					},{
						display : "��֤ʱ��",
						name : 'certifyingDateSearch'
					}]
 		});
 });