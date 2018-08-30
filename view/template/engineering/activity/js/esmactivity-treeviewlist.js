$(function() {
	var thisHeight = document.documentElement.clientHeight - 5;
    $('#esmactivityGrid').treegrid({
		url : '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + $("#parentId").val(),
		title : '��Ŀ����',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		animate : true,
		collapsible : true,
		idField : 'id',
		treeField : 'activityName',//��������
		fitColumns : false,//�����Ӧ
		pagination : false,//��ҳ
		showFooter : true,//��ʾͳ��
		columns : [[
			{
				title : '��������',
				field : 'activityName',
				width : 210,
				formatter : function(v,row) {
					if(row.id == 'noId') return v;
					if((row.rgt - row.lft) == 1){
						return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1,650,1000," + row.id + ")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewNode&id=" + row.id + '&skey=' + row.skey_  + "\",1,650,1000," + row.id + ")'>" + v + "</a>";
					}
				}
			},{
                field : 'workRate',
                title : '����ռ��',
                width : 65,
                formatter : function(v,row) {
                    if(!row._parentId){
                        return "<font style='font-weight:bold;'>" + v + " %</font>";
                    }else{
                        return v + " %";
                    }
                }
            },{
                field : 'process',
                title : '��������',
                width : 70,
                formatter : formatProgress
            },{
                field : 'waitConfirmProcess',
                title : '��ȷ�Ͻ���',
                width : 70,
                formatter : function(v) {
                    if(v){
                        return v + " %";
                    }
                }
            },{
                field : 'countProcess',
                title : '�ۼƽ���',
                width : 65,
                formatter : function(v) {
                    if(v){
                        return v + " %";
                    }
                }
            },{
                field : 'planProcess',
                title : '�ƻ�����',
                width : 65,
                formatter : function(v) {
                    if(v){
                        return v + " %";
                    }
                }
            },{
                field : 'diffProcess',
                title : '���Ȳ���',
                width : 65,
                formatter : function(v) {
                    v = ($("#isACatWithFallOutsourcing").val() == "1")? 0 : v;
                    if(v){
                        if(v*1 > 0){
                            return "<span class='red'>" + v + " %</span>";
                        }else{
                            return v + " %";
                        }
                    }
                }
            },{
                field : 'status',
                title : '����״̬',
                width : 60,
                formatter : function(v){
                    switch (v) {
                        case '0' : return '����';
                        case '1' : return '<span class="blue">�ر�</span>';
                        case '2' : return '<span class="red">��ͣ</span>';
                    }
                }
            },{
                field : 'planBeginDate',
                title : 'Ԥ�ƿ�ʼ',
                width : 80
            },{
                field : 'planEndDate',
                title : 'Ԥ�ƽ���',
                width : 80
            },{
                field : 'days',
                title : 'Ԥ�ƹ���',
                width : 60
            },{
                field : 'workload',
                title : '������',
                width : 50,
                formatter : function(v,row) {
                    return row.isTrial == '1' ? '--' : v;
                }
            },{
                field : 'workloadDone',
                title : '�����',
                width : 50,
                formatter : function(v,row) {
                    if($("#isACatWithFallOutsourcing").val() == "1"){
                        return row.workloadCount;
                    }else {
                        if(row.isTrial == '1'){
                            return '--';
                        }
                        if((row.rgt - row.lft) == 1){
                            if(row.confirmDays*1 != 0){
                                return '<span class="blue" style="font-weight:bold;" title="�����������ڣ�'
                                    + row.confirmDate +'\n�����ˣ�'+ row.confirmName +'\n����ֵ��'+ row.confirmDays +'">' + v + '</span>';
                            }else{
                                return v;
                            }
                        }
                    }
                }
            },{
                field : 'workloadUnitName',
                title : '��λ',
                width : 50
            },{
                field : 'workContent',
                title : '��������',
                width : 200
            }
        ]]
	});
});