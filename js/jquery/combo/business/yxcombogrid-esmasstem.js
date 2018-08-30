/**
 * Ԥ����Ŀ����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmasstem', {
		options : {
			hiddenId : 'id',
			nameCol : 'name',
			gridOptions : {
				model : 'engineering_assess_esmasstemplate',
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
					width : 100
				}, {
					name : 'indexIds',
					display : '����ָ��',
					sortable : true,
					hide : true
				}, {
					name : 'indexNames',
					display : '����ָ��',
					sortable : true,
					width : 200,
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
					width : 60
				}, {
					name : 'score',
					display : 'ģ���ܷ�ֵ',
					sortable : true,
					width : 60
				}, {
					name : 'needScore',
					display : '��ѡ�ϼ�',
					sortable : true,
					width : 60
				}, {
					name : 'remark',
					display : '��ע��Ϣ',
					sortable : true,
					width : 150
				}],
				searchitems : [{
					display : "ģ������",
					name : 'nameSearch'
				},{
					display : "��ע��Ϣ",
					name : 'remarkSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '���̿���ģ��'
			}
		}
	});
})(jQuery);
