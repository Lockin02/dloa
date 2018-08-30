var show_page = function(page) {
	$("#esmasstemplateGrid").yxgrid("reload");
};
$(function() {
	$("#esmasstemplateGrid").yxgrid({
		model : 'engineering_assess_esmasstemplate',
		title : '����ģ���',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : 'ģ������',
			sortable : true,
			width : 150
		}, {
			name : 'indexIds',
			display : '����ָ��',
			sortable : true,
			hide : true
		}, {
			name : 'indexNames',
			display : '����ָ��',
			sortable : true,
			width : 400,
			process : function(v,row){
				var indexNamesArr = v.split(',');
				var needIndexNames = row.needIndexNames;
				var needIndexNamesArr = needIndexNames.split(',');
				var rtVal = "";
				//
				for(var i = 0; i < indexNamesArr.length ;i++){
					if(jQuery.inArray(indexNamesArr[i], needIndexNamesArr) != -1){
						if(i == 0){
							rtVal = "<span class='blue' title='��ѡָ��'>" + indexNamesArr[i] + "</span>";
						}else{
							rtVal += ",<span class='blue' title='��ѡָ��'>" + indexNamesArr[i] + "</span>";
						}
					}else{
						if(i == 0){
							rtVal = indexNamesArr[i];
						}else{
							rtVal += "," + indexNamesArr[i];
						}
					}
				}
				return rtVal;
			}
		}, {
			name : 'needIndexIds',
			display : '��ѡָ��',
			sortable : true,
			hide : true
		}, {
			name : 'needIndexNames',
			display : '��ѡָ��',
			sortable : true,
			width : 200,
			hide : true
		}, {
			name : 'baseScore',
			display : '�����ܷ�',
			sortable : true,
			width : 80
		}, {
			name : 'score',
			display : 'ģ���ܷ�ֵ',
			sortable : true,
			width : 80
		}, {
			name : 'needScore',
			display : '��ѡ�ϼ�',
			sortable : true,
			width : 80
		}, {
			name : 'remark',
			display : '��ע��Ϣ',
			sortable : true,
			width : 200
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "ģ������",
			name : 'nameSearch'
		},{
			display : "��ע��Ϣ",
			name : 'remarkSearch'
		}]
	});
});