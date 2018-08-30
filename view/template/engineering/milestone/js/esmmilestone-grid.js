var show_page = function(page) {
	$("#esmmilestoneGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
    $("#esmmilestoneGrid").yxgrid({
        model : 'engineering_milestone_esmmilestone',
        title : '��Ŀ��̱�',
        param : { "projectId" : projectId },
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }         ,{
            name : 'milestoneName',
            display : '��̱�����',
            sortable : true
        }         ,{
            name : 'planBeginDate',
            display : '�ƻ���ʼ����',
            sortable : true
        }         ,{
            name : 'planEndDate',
            display : '�ƻ��������',
            sortable : true
        }         ,{
            name : 'actBeginDate',
            display : 'ʵ�ʿ�ʼ����',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			}
        }         ,{
            name : 'actEndDate',
            display : 'ʵ�ʽ�������',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			}
        }         ,{
            name : 'preMilestoneName',
            display : 'ǰ����̱�',
            sortable : true
        }         ,{
            name : 'status',
            display : '״̬',
            sortable : true,
            datacode : 'LCBZT'
        }         ,{
            name : 'versionNo',
            display : '�汾��',
            sortable : true
        }         ,{
            name : 'projectId',
            display : '��ĿId',
            sortable : true,
            hide : true
        }         ,{
            name : 'projectCode',
            display : '��Ŀ���',
            sortable : true,
            hide : true
        }         ,{
            name : 'projectName',
            display : '��Ŀ����',
            sortable : true,
            hide : true
        }         ,{
            name : 'isUsing',
            display : '�Ƿ�ʹ��',
            sortable : true,
            process : function(v,row){
				if(v=="1"){
					return "��";
				}else{
					return "��";
				}
			},
            hide : true
        }         ,{
            name : 'effortRate',
            display : '�����',
            sortable : true,
            hide : true
        }         ,{
            name : 'warpRate',
            display : 'ƫ����',
            sortable : true,
            hide : true
        }         ,{
            name : 'preMilestoneId',
            display : 'ǰ����̱�id',
            sortable : true,
            hide : true
        }         ,{
            name : 'remark',
            display : '��ע˵��',
            sortable : true,
            width : 200
        }] ,
		toAddConfig : {
			plusUrl : "&projectId=" + projectId
		},
		// Ĭ�������ֶ���
		sortname : "planBeginDate",
		// Ĭ������˳�� ����
		sortorder : "ASC"
    });
});