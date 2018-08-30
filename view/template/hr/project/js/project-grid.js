var show_page = function(page) {
	$("#projectGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [
        {
			name : 'view',
			text : "�߼���ѯ",
			icon : 'view',
			action : function() {
				showThickboxWin("?model=hr_project_project&action=toSearch&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
			}
        }];

	//��ͷ��ť����
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_project_project&action=toExcelIn"
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
        }

	$.ajax({
		type : 'POST',
		url : '?model=hr_project_project&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutSelect);
			}
		}
	});
	$("#projectGrid").yxgrid({
		model : 'hr_project_project',
		title : '��Ŀ����',
		isAddAction : true,
		isEditAction : true,
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
		}, {
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
  			width:80,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_project_project&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'userName',
			display : 'Ա������',
  			width:80,
			sortable : true
		}, {
			name : 'deptName',
			display : '����',
			sortable : true
		}, {
			name : 'jobName',
			display : 'ְλ',
			sortable : true
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width:150
		}, {
			name : 'projectManager',
			display : '��Ŀ����',
			sortable : true
		},  {
			name : 'beginDate',
			display : '�μ���Ŀ��ʼʱ��',
			sortable : true
		}, {
			name : 'closeDate',
			display : '�μ���Ŀ����ʱ��',
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
		/**
		 * ��������
		 */
		searchitems : [{
						display : "Ա�����",
						name : 'userNoSearch'
					},{
						display : "Ա������",
						name : 'userNameSearch'
					},{
						display : "����",
						name : 'deptNameSearch'
					},{
						display : "ְλ",
						name : 'jobNameSearch'
					},{
						display : "��Ŀ����",
						name : 'projectNameSearch'
					},{
						display : "��Ŀ����",
						name : 'projectManagerSearch'
					},{
						display : "�μ���Ŀ��ʼʱ��",
						name : 'beginDateSearch'
					},{
						display : "�μ���Ŀ����ʱ��",
						name : 'closeDateSearch'
					},{
						display : "����Ŀ�е���Ҫ��������",
						name : 'projectContent'
					}]
	});
});