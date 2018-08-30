<?php
/*发送邮件的配置文件
 * Created on 2011-9-20
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 $mailUser=array(
 		//收料通知单  (采购到货通知)  目前客户要求默认收件人为：李琳娜,罗权洲，刘红辉
 		"oa_purchase_arrival_info"=>array(
				'sendUserId'=>'xi.zhou,yanhong.liang,yangxin.zou,quanzhou.luo,honghui.liu,bi.huang,dyj,jinhua.huang,jin.yang,juanjuan.zheng,dongdong.lv,yanxia.xie,hua.he,quanzhou.luo,yanhua.liu,jianjing1.lin,hao.yuan',//用户ID 例：admin,hadmin
				'sendName'=>'周玺,梁燕红,邹阳鑫,罗权洲,刘红辉,黄碧,邓永杰,黄金华,杨锦,郑娟娟,吕冬冬,谢艳霞,何花,罗权洲,刘艳华,林建敬1,袁浩'  //用户名称  例：名字叫ADMIN,合同管理员
		),
		//合同审批后 处理自定义清单的默认收件人
		//销售合同
		"oa_sale_order"=>array(
				'sendUserId'=>'honghui.liu,caifeng.lao',  //用户ID 例：admin,hadmin
				'sendName'=>'刘红辉,劳彩凤'  //用户名称  例：名字叫ADMIN,合同管理员
		),
		//服务合同
		"oa_sale_service"=>array(
				'sendUserId'=>'honghui.liu,caifeng.lao',  //用户ID 例：admin,hadmin
				'sendName'=>'刘红辉,劳彩凤'  //用户名称  例：名字叫ADMIN,合同管理员
		),
		//租赁合同
		"oa_sale_lease"=>array(
				'sendUserId'=>'honghui.liu,caifeng.lao',  //用户ID 例：admin,hadmin
				'sendName'=>'刘红辉,劳彩凤'  //用户名称  例：名字叫ADMIN,合同管理员
		),
		//研发合同
		"oa_sale_rdproject"=>array(
				'sendUserId'=>'honghui.liu,caifeng.lao',  //用户ID 例：admin,hadmin
				'sendName'=>'刘红辉,劳彩凤'  //用户名称  例：名字叫ADMIN,合同管理员
		),
		//资产采购下达时发送邮件，通知采购部负责人
		"asset_pushPurch"=>array(
				'sendUserId'=>'wenqin.huang',
				'sendName'=>'黄文钦'
		),
		//资产采购审批通过时，通知资产采购负责人（行政部）
		"asset_approval"=>array(
				'sendUserId'=>'jianyi.huang,dehu.zhang',
				'sendName'=>'黄剑艺,张德虎'
		),
		//产品入库审核后邮件发送人
    "productinstock"=>array(
      array(
      'sendUserId'=>'dyj,honghui.liu,quanzhou.luo,jin.yang,yanhua.liu,yanhong.liang,hao.yuan,dongdong.lv,jianjing1.lin,juanjuan.zheng,jun.du',
      'sendName'=>'邓永杰,刘红辉,罗权洲,杨锦,刘艳华,梁燕红,袁浩,吕冬冬,林建敬1,郑娟娟,杜君'
      )
    ),
    //发货计划邮件通知
		"oa_stock_outplan"=>array(
			array(
				'responsibleId'=>'honghui.liu',
				'responsible'=>'刘红辉'
			),
			array(
				'responsibleId'=>'juanjuan.zheng',
				'responsible'=>'郑娟娟'
			),
			array(
				'responsibleId'=>'jin.yang',
				'responsible'=>'杨锦'
			),
			array(
				'responsibleId'=>'yanhong.liang',
				'responsible'=>'梁燕红'
			),
			array(
				'responsibleId'=>'quanzhou.luo',
				'responsible'=>'罗权洲'
			)
		),
		//到款默认带出邮件抄送人 - 详细请跟邱爱民确认
		"oa_finance_income"=>array(
			array(
				'sendUserId'=>'',
				'sendName'=>''
			)
		),
		//员工借试用仓库确人 发送邮件默认人员
    "oa_borrow_borrow"=>array(
            'tostorageNameId' => 'honghui.liu,bi.huang',
            'tostorageName' => '刘红辉,黄碧'
   ),
   //员工借试用 处理 转至执行部时的邮件接收人
   "borrow_execute" =>array(
            'executeNameId' => 'quanzhou.luo',
            'executeName' => '罗权洲'
   ),
   //执行部 回致 仓库时的邮件接收人
   "exeBackStorage" => array(
           'exeNameId' => 'honghui.liu,bi.huang',
           'exeName' => '刘红辉,黄碧'
   ),
   /*发送邮件的配置文件
   * Created on 2011-9-20
   *
   * To change the template for this generated file go to
   * Window - Preferences - PHPeclipse - PHP - Code Templates
   */
   "tosubtenancyBorrow" => array(
           'tosubtenancyNameId' => 'honghui.liu,bi.huang',
           'tosubtenancyName' => '刘红辉,黄碧'
   ),
   //发票修改默认通知人
	"oa_finance_invoice"=>array(
		array(
			'sendUserId'=>'xiaoyin.yu',
			'sendName'=>'余晓银'
		)
	),
		//合同转正时发送邮件通知财务人员
	"contractBecome"=>array(
			'sendUserId'=>'xinping.gou,zhizhen.su,jingyu.li,xiaoyin.yu',
			'sendName'=>'苟新平,苏志珍,李璟妤,余晓银'
	),
	//借试用转销售 审批通过后 发送邮件 通知仓管录入 归还调拨单时 的邮件接收人
   "borrowToOrder" => array(
           'borrowToOrderNameId' => 'honghui.liu,bi.huang',
           'borrowToOrderName' => '刘红辉,黄碧'
   ),
	//合同采购申请默认通知人
	"oa_purch_plan_basic"=>array(
		array(
			'TO_ID'=>'yangxin.zou,hao.yuan',
			'TO_NAME'=>'邹阳鑫,袁浩'
		)
	),
	//采购进度反馈    添加采购经理
	"oa_purchase_speed"=>array(
			'sendUserId'=>'yangxin.zou,quanzhou.luo,hao.yuan',  //用户ID 例：admin,hadmin
			'sendName'=>'邹阳鑫,罗权洲,袁浩'  //用户名称  例：名字叫ADMIN,合同管理员
	),
	//采购申请变更通知    添加采购经理
	"oa_purchase_planChange"=>array(
			'sendUserId'=>'yangxin.zou,hao.yuan',  //用户ID 例：admin,hadmin
			'sendName'=>'邹阳鑫,袁浩'  //用户名称  例：名字叫ADMIN,合同管理员
	),
	//付款申请关闭邮件默认通知
	"payablesapply_close"=>array(
		array(
			'sendUserId'=>'yangxin.zou,wenqin.huang',
			'sendName'=>'邹阳鑫,黄文钦'
		)
	),
	//采购付款申请关闭邮件默认通知
	"payablesapply_close"=>array(
		array(
			'sendUserId'=>'yangxin.zou,hao.yuan',
			'sendName'=>'邹阳鑫,袁浩'
		)
	),
	//外包付款申请关闭邮件默认通知
	"payablesapply_outsourcing_close"=>array(
		array(
			'sendUserId'=>'liji.zhang',
			'sendName'=>'张丽姬'
		)
	),
	//其他付款申请关闭邮件默认通知
	"payablesapply_other_close"=>array(
		array(
			'sendUserId'=>'liji.zhang',
			'sendName'=>'张丽姬'
		)
	),
	//无源单付款申请关闭邮件默认通知--取消
	"payablesapply_none_close"=>array(
		array(
			'sendUserId'=>'admin',
			'sendName'=>'名字叫ADMIN'
		)
	),
	//试用项目审批通过后通知网优部相关人员
   "trialproject"=>array(
			'sendUserId'=>'jianwei.su,dafa.yu,minliang.yu,jianmin.chen,heng.yin,jianping.luo',
			'sendName'=>'苏健威,喻大发,俞敏良,陈建民,尹恒,罗建平'
	),
	//付款申请提交财务支付邮件配置 - 请设置 成 姬姐
	"payablesapply_handUpPay"=>array(
		array(
			'sendUserId'=>'liji.zhang',
			'sendName'=>'张丽姬'
		)
	),
	//内部推荐通知招聘经理
   "oa_hr_recruitment_recommend"=>array(
			'sendUserId'=>'sina.zheng,duo.wen',
			'sendName'=>'郑斯娜,温朵'
	),
	//合同变更是如果删除的物料有下达采购的发送邮件给采购
	"contractChangepurchase"=>array(
			'sendUserId'=>'yangxin.zou,wenqin.huang,quanzhou.luo,hao.yuan',
			'sendName'=>'邹阳鑫,黄文钦,罗权洲,袁浩'
	),
	//离职审批通过后通知财务处理
	'leave'=>array(
			'sendUserId'=>'xuedi.niu,jiqing.yang,yu.long,xiufang.tang,yuling.zheng',
			'sendName'=>'牛雪迪,杨继清,龙宇,唐秀芳,郑郁玲'
	),
	//开票申请通知人
	'financeInvoiceapply'=>array(
			'sendUserId'=>'zhizhen.su',
			'sendName'=>'苏志珍'
	),
	//开票申请通知人 - 异地开票
	'financeInvoiceapplyOffSite'=>array(
			'sendUserId'=>'zhizhen.su',
			'sendName'=>'苏志珍'
	),
	//指定导师邮件通知
	'tutor'=>array(
			'sendUserId'=>'jue.tang',
			'sendName'=>'唐爵'
	),
	//导师奖励方案通过后发送给HR
	'tutorReward'=>array(
			'sendUserId'=>'jue.tang',
			'sendName'=>'唐爵'
	),
	//合同通知发货默认通知人
	'shipconditon'=>array(
			'sendUserId'=>'quanzhou.luo,angel.lee',
			'sendName'=>'罗权洲,李琳娜'
	),
	//员工借试用物料确认添加默认通知人（仓库人员）
	'personnelBorrow'=>array(
			'sendUserId'=>'quanzhou.luo',
			'sendName'=>'罗权洲'
	),
	//合同变更 默认通知人
	"oa_contract_change"=>array(
			'TO_ID'=>'qiangwu.luo,xinping.gou,xiaoyin.yu,yingyan.cai,zhizhen.su,jingyu.li',
			'TO_NAME'=>'罗强武,苟新平,余晓银,蔡颖妍,苏志珍,李璟妤'
	),
	//合同审批完成后 默认通知人
	"oa_contract_contract"=>array(
			'TO_ID'=>'qiangwu.luo,xinping.gou,xiaoyin.yu,yingyan.cai,zhizhen.su,jingyu.li',
			'TO_NAME'=>'罗强武,苟新平,余晓银,蔡颖妍,苏志珍,李璟妤'
	),
	//合同提交工程不确认 成本概算 发送邮件
	"cost_estimates"=>array(
			'TO_ID'=>'jianwei.su,minliang.yu',
			'TO_NAME'=>'jianwei.su,minliang.yu'
	),
	//合同不需开票默认 发送邮件
	"oa_contract_uninvoice"=>array(
		'sendUserId'=>'jingyu.li,yuling.zheng,oazy',
		'sendName'=>'李璟妤,郑郁玲'
	)	,
	//合同异常关闭通知人
	"contractClose"=>array(
			'TO_ID'=>'qiangwu.luo,xinping.gou,xiaoyin.yu,yingyan.cai,zhizhen.su',
			'TO_NAME'=>'罗强武,苟新平,余晓银,蔡颖妍,苏志珍'
	),
  //添加固定资产 发送邮件
	"oa_asset_card_temp_admin"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang,xiaoyin.yu',
		'TO_NAME'=>'韦顺彬,张德虎,余晓银'
	),
	//确认固定资产 发送邮件
	"oa_asset_card_temp_finance"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang',
		'TO_NAME'=>'韦顺彬,张德虎'
	),
	//外购入库退货后后邮件发送人(除采购员)
	"purchinstock"=>array(
		array(
			'sendUserId'=>'admin',
			'sendName'=>'名字叫ADMIN'
		)
	),	
	//新增物料信息后通知人员
	"oa_stock_product_info"=>array(
			'TO_ID'=>'quanzhou.luo,honghui.liu',
			'TO_NAME'=>'罗权洲,刘红辉'
	),
	//报销单提交部门检查时发送的邮件
	"cost_summary_list"=>array(
		'TO_ID'=>'weiying.li',
		'TO_NAME'=>'李伟莹'
	),
	//确认固定资产 发送邮件
	"oa_asset_requirement"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang',
		'TO_NAME'=>'韦顺彬,张德虎'
	),//合同异常关闭通知人
  "contractClose"=>array(
			'TO_ID'=>'admin,zhizhen.su,qiangwu.luo,xinping.gou',
			'TO_NAME'=>'名字叫ADMIN,苏志珍,罗强武,苟新平'
	),
	//职位申请 默认通知人
	"oa_hr_recruitment_employment"=>array(
			'TO_ID'=>'sina.zheng,duo.wen',
			'TO_NAME'=>'郑斯娜,温朵'
	),
	//入职通知邮件通知人事部（录用通知）
   "oa_hr_recruitment_entrynotice"=>array(
			'sendUserId'=>'duo.wen,yu.long,deyi.liu,xuedi.niu,dan.yin,jiqing.yang',
			'sendName'=>'温朵,龙宇,刘德益,牛雪迪,印丹,杨继清'
	),
	//转正申请员工确认通知列表
	'oa_hr_permanent_examine'=>array(
			'sendUserId'=>'jiqing.yang',
			'sendName'=>'杨继清'
	),
	//试用员工辞退 邮件通知人资
	'deptsuggest'=>array(
			'sendUserId'=>'yunxia.zhu',
			'sendName'=>'朱云霞'
	),
	//采购质检审核后邮件发送人
	"purchquality"=>array(
			'sendUserId'=>'yangxin.zou,honghui.liu,dyj,ljh,hao.yuan',
			'sendName'=>'邹阳鑫,刘红辉,邓永杰,陆金红,袁浩'
	),//固定资产提交申请 发送邮件
	"oa_asset_requirement_add"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang',
		'TO_NAME'=>'韦顺彬,张德虎'
	),
	//确认固定资产 发送邮件
	"oa_asset_requirement"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang',
		'TO_NAME'=>'韦顺彬,张德虎'
	),
	//合同签收发送邮件
	"contractSignEdit"=>array(
		'TO_ID'=>'admin,yingchao.zhang,zhizhen.su,zhang.rui',
		'TO_NAME'=>'名字叫ADMIN,张莹超,苏志珍,张蕊'
	),
	//试用项目提交后成本确认邮件接收人
	"trialprojectCon"=>array(
		'TO_ID'=>'minliang.yu',
		'TO_NAME'=>'俞敏良'
	),
	//增员申请提交发送邮件
	"oa_hr_recruitment_apply"=>array(
		'TO_ID'=>'sina.zheng,duo.wen',
		'TO_NAME'=>'郑斯娜,温朵'
	),
	//增员申请提交发送邮件给温朵
	"oa_hr_recruitment_apply_duo"=>array(
		'TO_ID'=>'duo.wen,chuyuan.lin',
		'TO_NAME'=>'温朵,林楚源'
	),
		//发货单邮件提醒
  "oa_stock_ship"=>array(
		'sendUserId'=>'daqiao.pei,ljh,chunxiong.chen',
		'sendName'=>'裴大桥,陆金红,陈春雄'
	),
	//行政部下达采购到交付部
	"oa_asset_apply_requireAdd"=>array(
			'sendUserId'=>'shunbin.wei,hao.yuan',  //用户ID 例：admin,hadmin
			'sendName'=>'韦顺彬,袁浩'  //用户名称  例：名字叫ADMIN,合同管理员
	),//物料信息提交、确认、打回发送人
	'oa_stock_product_info_temp'=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'名字叫ADMIN'
	),
	//贝讯专区邮件发送人--取消
	"contract_bx"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'名字叫ADMIN'
	),
	//验收单撤回 发送邮件
	"oa_asset_receive"=>array(
		'sendUserId'=>'eadmin,admin',
		'sendUserName'=>'工程管理员,名字叫ADMIN'
	),
	//离职申请提交通知HR
	"hr_leave_apply_submit"=>array(
		'TO_ID'=>'yunxia.zhu,yu.long,',
		'TO_NAME'=>'朱云霞,龙宇'
	),
	//离职-世源编制通知人员
	'leave_shiyuan'=>array(
			'sendUserId'=>'gaowen.liang',
			'sendName'=>'梁杲雯'
	),
	//离职-贝软编制通知人员
	'leave_beiruan'=>array(
			'sendUserId'=>'02001977',
			'sendName'=>'李珍'
	),
	//离职-鼎利编制通知人员
	'leave_dingli'=>array(
			'sendUserId'=>'yuling.zheng,xiufang.tang',
			'sendName'=>'郑郁玲,唐秀芳'
	),
	//离职-鼎利服务线通知人员
	'leave_fuwu'=>array(
			'sendUserId'=>'shuanglan.wang,wenhao.li,siyu.chen',
			'sendName'=>'王双兰,李文豪,陈思瑜'
	),
	//离职-鼎利营销线通知人员
	'leave_yingxiao'=>array(
			'sendUserId'=>'huan.hu',
			'sendName'=>'胡欢'
	),
	//离职-鼎元丰和编制通知人员
	'leave_dingyuan'=>array(
			'sendUserId'=>'yunxia.zhu,yu.long,yulan.liu',
			'sendName'=>'朱云霞,龙宇,刘玉兰'
	),
	//备货通知人员
	"oa_stockup_apply"=>array(
		'TO_ID'=>'yangxin.zou',
		'TO_NAME'=>'邹阳鑫,'
	),
	//试用转正提交HR
	"oa_permanent_examie"=>array(
		'TO_ID'=>'yunxia.zhu,yu.long,yulan.liu',
		'TO_NAME'=>'朱云霞,龙宇,刘玉兰'
	),//补货/生产审批提交人
	"oa_production_replenishment"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'名字叫ADMIN'
	),
	//调动申请提交人
	"oa_hr_transfer"=>array(
		'TO_ID'=>'yu.long,xiujie.zeng',
		'TO_NAME'=>'龙宇,曾秀洁'
	),
	//我的档案-户籍改变通知人
	"oa_hr_personnel"=>array(
		'TO_ID'=>'yu.long,xuedi.niu',
		'TO_NAME'=>'龙宇,牛雪迪'
	),//外包立项-服务运营部
	"oa_outsourcing_operations"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'名字叫ADMIN'
	),
	//外包立项-外包接口人
	"oa_outsourcing_interface"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'名字叫ADMIN'
	),
	//外包结算-提交确认通知人
	"oa_outsourcing_account"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'名字叫ADMIN'
	),
	//外包租车 租车申请-审批通过通知人 租车申请受理结果
	"oa_outsourcing_rentalcar"=>array(
		'TO_ID'=>'xiaoxia.zhu,yafeng.zhu,minxian.zhong,haiyan.xu',
		'TO_NAME'=>'祝小霞,朱亚锋,钟敏贤,徐海燕'
	),
	//租车登记-审批通过通知人
	"oa_outsourcing_allregister"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'名字叫ADMIN'
	),
	//入职通知邮件通知人事部(广州贝讯)（录用通知）
	"oa_hr_recruitment_entrynotice_beixun" => array(
		'sendUserId'=>'duo.wen,yu.long,deyi.liu,xuedi.niu,dan.yin,xiujie.zeng',
		'sendName'=>'温朵,龙宇,刘德益,牛雪迪,印丹,曾秀洁'
	)
 );
