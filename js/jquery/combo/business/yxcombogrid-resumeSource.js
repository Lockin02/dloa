
var Data = {
    "collection" : [
        {
        	"id" : "1",
            "sourceB" : "前程无忧"
        },
        {
        	"id" : "2",
            "sourceB" : "智联招聘"
        },
        {
        	"id" : "3",
            "sourceB" : "中华英才"
        },
        {
        	"id" : "4",
            "sourceB" : "珠海人力资源网"
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
						// 列信息
						colModel : [{
									name : 'id',
									display : 'id',
									sortable : true,
									hide : true
								},{
									name : 'sourceB',
									display : '简历来源',
									sortable : true,
									width : 150
								}],

						data :Data
					}
				}
			});
})(jQuery);
