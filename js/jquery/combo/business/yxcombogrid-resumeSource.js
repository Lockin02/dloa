
var Data = {
    "collection" : [
        {
        	"id" : "1",
            "sourceB" : "ǰ������"
        },
        {
        	"id" : "2",
            "sourceB" : "������Ƹ"
        },
        {
        	"id" : "3",
            "sourceB" : "�л�Ӣ��"
        },
        {
        	"id" : "4",
            "sourceB" : "�麣������Դ��"
        }
    ],
    "totalSize" : "4",
    "page" : "1"
};
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_resumeSource', {
				options : {
					nameCol : 'sourceB',
					gridOptions : {
						showcheckbox : false,
						// ����Ϣ
						colModel : [{
									name : 'id',
									display : 'id',
									sortable : true,
									hide : true
								},{
									name : 'sourceB',
									display : '������Դ',
									sortable : true,
									width : 150
								}],

						data :Data
					}
				}
			});
})(jQuery);
