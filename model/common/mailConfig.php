<?php
/*�����ʼ��������ļ�
 * Created on 2011-9-20
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 $mailUser=array(
 		//����֪ͨ��  (�ɹ�����֪ͨ)  Ŀǰ�ͻ�Ҫ��Ĭ���ռ���Ϊ��������,��Ȩ�ޣ������
 		"oa_purchase_arrival_info"=>array(
				'sendUserId'=>'xi.zhou,yanhong.liang,yangxin.zou,quanzhou.luo,honghui.liu,bi.huang,dyj,jinhua.huang,jin.yang,juanjuan.zheng,dongdong.lv,yanxia.xie,hua.he,quanzhou.luo,yanhua.liu,jianjing1.lin,hao.yuan',//�û�ID ����admin,hadmin
				'sendName'=>'����,�����,������,��Ȩ��,�����,�Ʊ�,������,�ƽ�,���,֣���,������,л��ϼ,�λ�,��Ȩ��,���޻�,�ֽ���1,Ԭ��'  //�û�����  �������ֽ�ADMIN,��ͬ����Ա
		),
		//��ͬ������ �����Զ����嵥��Ĭ���ռ���
		//���ۺ�ͬ
		"oa_sale_order"=>array(
				'sendUserId'=>'honghui.liu,caifeng.lao',  //�û�ID ����admin,hadmin
				'sendName'=>'�����,�Ͳʷ�'  //�û�����  �������ֽ�ADMIN,��ͬ����Ա
		),
		//�����ͬ
		"oa_sale_service"=>array(
				'sendUserId'=>'honghui.liu,caifeng.lao',  //�û�ID ����admin,hadmin
				'sendName'=>'�����,�Ͳʷ�'  //�û�����  �������ֽ�ADMIN,��ͬ����Ա
		),
		//���޺�ͬ
		"oa_sale_lease"=>array(
				'sendUserId'=>'honghui.liu,caifeng.lao',  //�û�ID ����admin,hadmin
				'sendName'=>'�����,�Ͳʷ�'  //�û�����  �������ֽ�ADMIN,��ͬ����Ա
		),
		//�з���ͬ
		"oa_sale_rdproject"=>array(
				'sendUserId'=>'honghui.liu,caifeng.lao',  //�û�ID ����admin,hadmin
				'sendName'=>'�����,�Ͳʷ�'  //�û�����  �������ֽ�ADMIN,��ͬ����Ա
		),
		//�ʲ��ɹ��´�ʱ�����ʼ���֪ͨ�ɹ���������
		"asset_pushPurch"=>array(
				'sendUserId'=>'wenqin.huang',
				'sendName'=>'������'
		),
		//�ʲ��ɹ�����ͨ��ʱ��֪ͨ�ʲ��ɹ������ˣ���������
		"asset_approval"=>array(
				'sendUserId'=>'jianyi.huang,dehu.zhang',
				'sendName'=>'�ƽ���,�ŵ»�'
		),
		//��Ʒ�����˺��ʼ�������
    "productinstock"=>array(
      array(
      'sendUserId'=>'dyj,honghui.liu,quanzhou.luo,jin.yang,yanhua.liu,yanhong.liang,hao.yuan,dongdong.lv,jianjing1.lin,juanjuan.zheng,jun.du',
      'sendName'=>'������,�����,��Ȩ��,���,���޻�,�����,Ԭ��,������,�ֽ���1,֣���,�ž�'
      )
    ),
    //�����ƻ��ʼ�֪ͨ
		"oa_stock_outplan"=>array(
			array(
				'responsibleId'=>'honghui.liu',
				'responsible'=>'�����'
			),
			array(
				'responsibleId'=>'juanjuan.zheng',
				'responsible'=>'֣���'
			),
			array(
				'responsibleId'=>'jin.yang',
				'responsible'=>'���'
			),
			array(
				'responsibleId'=>'yanhong.liang',
				'responsible'=>'�����'
			),
			array(
				'responsibleId'=>'quanzhou.luo',
				'responsible'=>'��Ȩ��'
			)
		),
		//����Ĭ�ϴ����ʼ������� - ��ϸ�������ȷ��
		"oa_finance_income"=>array(
			array(
				'sendUserId'=>'',
				'sendName'=>''
			)
		),
		//Ա�������òֿ�ȷ�� �����ʼ�Ĭ����Ա
    "oa_borrow_borrow"=>array(
            'tostorageNameId' => 'honghui.liu,bi.huang',
            'tostorageName' => '�����,�Ʊ�'
   ),
   //Ա�������� ���� ת��ִ�в�ʱ���ʼ�������
   "borrow_execute" =>array(
            'executeNameId' => 'quanzhou.luo',
            'executeName' => '��Ȩ��'
   ),
   //ִ�в� ���� �ֿ�ʱ���ʼ�������
   "exeBackStorage" => array(
           'exeNameId' => 'honghui.liu,bi.huang',
           'exeName' => '�����,�Ʊ�'
   ),
   /*�����ʼ��������ļ�
   * Created on 2011-9-20
   *
   * To change the template for this generated file go to
   * Window - Preferences - PHPeclipse - PHP - Code Templates
   */
   "tosubtenancyBorrow" => array(
           'tosubtenancyNameId' => 'honghui.liu,bi.huang',
           'tosubtenancyName' => '�����,�Ʊ�'
   ),
   //��Ʊ�޸�Ĭ��֪ͨ��
	"oa_finance_invoice"=>array(
		array(
			'sendUserId'=>'xiaoyin.yu',
			'sendName'=>'������'
		)
	),
		//��ͬת��ʱ�����ʼ�֪ͨ������Ա
	"contractBecome"=>array(
			'sendUserId'=>'xinping.gou,zhizhen.su,jingyu.li,xiaoyin.yu',
			'sendName'=>'����ƽ,��־��,��Z�,������'
	),
	//������ת���� ����ͨ���� �����ʼ� ֪ͨ�ֹ�¼�� �黹������ʱ ���ʼ�������
   "borrowToOrder" => array(
           'borrowToOrderNameId' => 'honghui.liu,bi.huang',
           'borrowToOrderName' => '�����,�Ʊ�'
   ),
	//��ͬ�ɹ�����Ĭ��֪ͨ��
	"oa_purch_plan_basic"=>array(
		array(
			'TO_ID'=>'yangxin.zou,hao.yuan',
			'TO_NAME'=>'������,Ԭ��'
		)
	),
	//�ɹ����ȷ���    ��Ӳɹ�����
	"oa_purchase_speed"=>array(
			'sendUserId'=>'yangxin.zou,quanzhou.luo,hao.yuan',  //�û�ID ����admin,hadmin
			'sendName'=>'������,��Ȩ��,Ԭ��'  //�û�����  �������ֽ�ADMIN,��ͬ����Ա
	),
	//�ɹ�������֪ͨ    ��Ӳɹ�����
	"oa_purchase_planChange"=>array(
			'sendUserId'=>'yangxin.zou,hao.yuan',  //�û�ID ����admin,hadmin
			'sendName'=>'������,Ԭ��'  //�û�����  �������ֽ�ADMIN,��ͬ����Ա
	),
	//��������ر��ʼ�Ĭ��֪ͨ
	"payablesapply_close"=>array(
		array(
			'sendUserId'=>'yangxin.zou,wenqin.huang',
			'sendName'=>'������,������'
		)
	),
	//�ɹ���������ر��ʼ�Ĭ��֪ͨ
	"payablesapply_close"=>array(
		array(
			'sendUserId'=>'yangxin.zou,hao.yuan',
			'sendName'=>'������,Ԭ��'
		)
	),
	//�����������ر��ʼ�Ĭ��֪ͨ
	"payablesapply_outsourcing_close"=>array(
		array(
			'sendUserId'=>'liji.zhang',
			'sendName'=>'������'
		)
	),
	//������������ر��ʼ�Ĭ��֪ͨ
	"payablesapply_other_close"=>array(
		array(
			'sendUserId'=>'liji.zhang',
			'sendName'=>'������'
		)
	),
	//��Դ����������ر��ʼ�Ĭ��֪ͨ--ȡ��
	"payablesapply_none_close"=>array(
		array(
			'sendUserId'=>'admin',
			'sendName'=>'���ֽ�ADMIN'
		)
	),
	//������Ŀ����ͨ����֪ͨ���Ų������Ա
   "trialproject"=>array(
			'sendUserId'=>'jianwei.su,dafa.yu,minliang.yu,jianmin.chen,heng.yin,jianping.luo',
			'sendName'=>'�ս���,����,������,�½���,����,�޽�ƽ'
	),
	//���������ύ����֧���ʼ����� - ������ �� ����
	"payablesapply_handUpPay"=>array(
		array(
			'sendUserId'=>'liji.zhang',
			'sendName'=>'������'
		)
	),
	//�ڲ��Ƽ�֪ͨ��Ƹ����
   "oa_hr_recruitment_recommend"=>array(
			'sendUserId'=>'sina.zheng,duo.wen',
			'sendName'=>'֣˹��,�¶�'
	),
	//��ͬ��������ɾ�����������´�ɹ��ķ����ʼ����ɹ�
	"contractChangepurchase"=>array(
			'sendUserId'=>'yangxin.zou,wenqin.huang,quanzhou.luo,hao.yuan',
			'sendName'=>'������,������,��Ȩ��,Ԭ��'
	),
	//��ְ����ͨ����֪ͨ������
	'leave'=>array(
			'sendUserId'=>'xuedi.niu,jiqing.yang,yu.long,xiufang.tang,yuling.zheng',
			'sendName'=>'ţѩ��,�����,����,���㷼,֣����'
	),
	//��Ʊ����֪ͨ��
	'financeInvoiceapply'=>array(
			'sendUserId'=>'zhizhen.su',
			'sendName'=>'��־��'
	),
	//��Ʊ����֪ͨ�� - ��ؿ�Ʊ
	'financeInvoiceapplyOffSite'=>array(
			'sendUserId'=>'zhizhen.su',
			'sendName'=>'��־��'
	),
	//ָ����ʦ�ʼ�֪ͨ
	'tutor'=>array(
			'sendUserId'=>'jue.tang',
			'sendName'=>'�ƾ�'
	),
	//��ʦ��������ͨ�����͸�HR
	'tutorReward'=>array(
			'sendUserId'=>'jue.tang',
			'sendName'=>'�ƾ�'
	),
	//��֪ͬͨ����Ĭ��֪ͨ��
	'shipconditon'=>array(
			'sendUserId'=>'quanzhou.luo,angel.lee',
			'sendName'=>'��Ȩ��,������'
	),
	//Ա������������ȷ�����Ĭ��֪ͨ�ˣ��ֿ���Ա��
	'personnelBorrow'=>array(
			'sendUserId'=>'quanzhou.luo',
			'sendName'=>'��Ȩ��'
	),
	//��ͬ��� Ĭ��֪ͨ��
	"oa_contract_change"=>array(
			'TO_ID'=>'qiangwu.luo,xinping.gou,xiaoyin.yu,yingyan.cai,zhizhen.su,jingyu.li',
			'TO_NAME'=>'��ǿ��,����ƽ,������,��ӱ��,��־��,��Z�'
	),
	//��ͬ������ɺ� Ĭ��֪ͨ��
	"oa_contract_contract"=>array(
			'TO_ID'=>'qiangwu.luo,xinping.gou,xiaoyin.yu,yingyan.cai,zhizhen.su,jingyu.li',
			'TO_NAME'=>'��ǿ��,����ƽ,������,��ӱ��,��־��,��Z�'
	),
	//��ͬ�ύ���̲�ȷ�� �ɱ����� �����ʼ�
	"cost_estimates"=>array(
			'TO_ID'=>'jianwei.su,minliang.yu',
			'TO_NAME'=>'jianwei.su,minliang.yu'
	),
	//��ͬ���迪ƱĬ�� �����ʼ�
	"oa_contract_uninvoice"=>array(
		'sendUserId'=>'jingyu.li,yuling.zheng,oazy',
		'sendName'=>'��Z�,֣����'
	)	,
	//��ͬ�쳣�ر�֪ͨ��
	"contractClose"=>array(
			'TO_ID'=>'qiangwu.luo,xinping.gou,xiaoyin.yu,yingyan.cai,zhizhen.su',
			'TO_NAME'=>'��ǿ��,����ƽ,������,��ӱ��,��־��'
	),
  //��ӹ̶��ʲ� �����ʼ�
	"oa_asset_card_temp_admin"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang,xiaoyin.yu',
		'TO_NAME'=>'Τ˳��,�ŵ»�,������'
	),
	//ȷ�Ϲ̶��ʲ� �����ʼ�
	"oa_asset_card_temp_finance"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang',
		'TO_NAME'=>'Τ˳��,�ŵ»�'
	),
	//�⹺����˻�����ʼ�������(���ɹ�Ա)
	"purchinstock"=>array(
		array(
			'sendUserId'=>'admin',
			'sendName'=>'���ֽ�ADMIN'
		)
	),	
	//����������Ϣ��֪ͨ��Ա
	"oa_stock_product_info"=>array(
			'TO_ID'=>'quanzhou.luo,honghui.liu',
			'TO_NAME'=>'��Ȩ��,�����'
	),
	//�������ύ���ż��ʱ���͵��ʼ�
	"cost_summary_list"=>array(
		'TO_ID'=>'weiying.li',
		'TO_NAME'=>'��ΰӨ'
	),
	//ȷ�Ϲ̶��ʲ� �����ʼ�
	"oa_asset_requirement"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang',
		'TO_NAME'=>'Τ˳��,�ŵ»�'
	),//��ͬ�쳣�ر�֪ͨ��
  "contractClose"=>array(
			'TO_ID'=>'admin,zhizhen.su,qiangwu.luo,xinping.gou',
			'TO_NAME'=>'���ֽ�ADMIN,��־��,��ǿ��,����ƽ'
	),
	//ְλ���� Ĭ��֪ͨ��
	"oa_hr_recruitment_employment"=>array(
			'TO_ID'=>'sina.zheng,duo.wen',
			'TO_NAME'=>'֣˹��,�¶�'
	),
	//��ְ֪ͨ�ʼ�֪ͨ���²���¼��֪ͨ��
   "oa_hr_recruitment_entrynotice"=>array(
			'sendUserId'=>'duo.wen,yu.long,deyi.liu,xuedi.niu,dan.yin,jiqing.yang',
			'sendName'=>'�¶�,����,������,ţѩ��,ӡ��,�����'
	),
	//ת������Ա��ȷ��֪ͨ�б�
	'oa_hr_permanent_examine'=>array(
			'sendUserId'=>'jiqing.yang',
			'sendName'=>'�����'
	),
	//����Ա������ �ʼ�֪ͨ����
	'deptsuggest'=>array(
			'sendUserId'=>'yunxia.zhu',
			'sendName'=>'����ϼ'
	),
	//�ɹ��ʼ���˺��ʼ�������
	"purchquality"=>array(
			'sendUserId'=>'yangxin.zou,honghui.liu,dyj,ljh,hao.yuan',
			'sendName'=>'������,�����,������,½���,Ԭ��'
	),//�̶��ʲ��ύ���� �����ʼ�
	"oa_asset_requirement_add"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang',
		'TO_NAME'=>'Τ˳��,�ŵ»�'
	),
	//ȷ�Ϲ̶��ʲ� �����ʼ�
	"oa_asset_requirement"=>array(
		'TO_ID'=>'shunbin.wei,dehu.zhang',
		'TO_NAME'=>'Τ˳��,�ŵ»�'
	),
	//��ͬǩ�շ����ʼ�
	"contractSignEdit"=>array(
		'TO_ID'=>'admin,yingchao.zhang,zhizhen.su,zhang.rui',
		'TO_NAME'=>'���ֽ�ADMIN,��Ө��,��־��,����'
	),
	//������Ŀ�ύ��ɱ�ȷ���ʼ�������
	"trialprojectCon"=>array(
		'TO_ID'=>'minliang.yu',
		'TO_NAME'=>'������'
	),
	//��Ա�����ύ�����ʼ�
	"oa_hr_recruitment_apply"=>array(
		'TO_ID'=>'sina.zheng,duo.wen',
		'TO_NAME'=>'֣˹��,�¶�'
	),
	//��Ա�����ύ�����ʼ����¶�
	"oa_hr_recruitment_apply_duo"=>array(
		'TO_ID'=>'duo.wen,chuyuan.lin',
		'TO_NAME'=>'�¶�,�ֳ�Դ'
	),
		//�������ʼ�����
  "oa_stock_ship"=>array(
		'sendUserId'=>'daqiao.pei,ljh,chunxiong.chen',
		'sendName'=>'�����,½���,�´���'
	),
	//�������´�ɹ���������
	"oa_asset_apply_requireAdd"=>array(
			'sendUserId'=>'shunbin.wei,hao.yuan',  //�û�ID ����admin,hadmin
			'sendName'=>'Τ˳��,Ԭ��'  //�û�����  �������ֽ�ADMIN,��ͬ����Ա
	),//������Ϣ�ύ��ȷ�ϡ���ط�����
	'oa_stock_product_info_temp'=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'���ֽ�ADMIN'
	),
	//��Ѷר���ʼ�������--ȡ��
	"contract_bx"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'���ֽ�ADMIN'
	),
	//���յ����� �����ʼ�
	"oa_asset_receive"=>array(
		'sendUserId'=>'eadmin,admin',
		'sendUserName'=>'���̹���Ա,���ֽ�ADMIN'
	),
	//��ְ�����ύ֪ͨHR
	"hr_leave_apply_submit"=>array(
		'TO_ID'=>'yunxia.zhu,yu.long,',
		'TO_NAME'=>'����ϼ,����'
	),
	//��ְ-��Դ����֪ͨ��Ա
	'leave_shiyuan'=>array(
			'sendUserId'=>'gaowen.liang',
			'sendName'=>'�����'
	),
	//��ְ-�������֪ͨ��Ա
	'leave_beiruan'=>array(
			'sendUserId'=>'02001977',
			'sendName'=>'����'
	),
	//��ְ-��������֪ͨ��Ա
	'leave_dingli'=>array(
			'sendUserId'=>'yuling.zheng,xiufang.tang',
			'sendName'=>'֣����,���㷼'
	),
	//��ְ-����������֪ͨ��Ա
	'leave_fuwu'=>array(
			'sendUserId'=>'shuanglan.wang,wenhao.li,siyu.chen',
			'sendName'=>'��˫��,���ĺ�,��˼�'
	),
	//��ְ-����Ӫ����֪ͨ��Ա
	'leave_yingxiao'=>array(
			'sendUserId'=>'huan.hu',
			'sendName'=>'����'
	),
	//��ְ-��Ԫ��ͱ���֪ͨ��Ա
	'leave_dingyuan'=>array(
			'sendUserId'=>'yunxia.zhu,yu.long,yulan.liu',
			'sendName'=>'����ϼ,����,������'
	),
	//����֪ͨ��Ա
	"oa_stockup_apply"=>array(
		'TO_ID'=>'yangxin.zou',
		'TO_NAME'=>'������,'
	),
	//����ת���ύHR
	"oa_permanent_examie"=>array(
		'TO_ID'=>'yunxia.zhu,yu.long,yulan.liu',
		'TO_NAME'=>'����ϼ,����,������'
	),//����/���������ύ��
	"oa_production_replenishment"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'���ֽ�ADMIN'
	),
	//���������ύ��
	"oa_hr_transfer"=>array(
		'TO_ID'=>'yu.long,xiujie.zeng',
		'TO_NAME'=>'����,�����'
	),
	//�ҵĵ���-�����ı�֪ͨ��
	"oa_hr_personnel"=>array(
		'TO_ID'=>'yu.long,xuedi.niu',
		'TO_NAME'=>'����,ţѩ��'
	),//�������-������Ӫ��
	"oa_outsourcing_operations"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'���ֽ�ADMIN'
	),
	//�������-����ӿ���
	"oa_outsourcing_interface"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'���ֽ�ADMIN'
	),
	//�������-�ύȷ��֪ͨ��
	"oa_outsourcing_account"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'���ֽ�ADMIN'
	),
	//����⳵ �⳵����-����ͨ��֪ͨ�� �⳵����������
	"oa_outsourcing_rentalcar"=>array(
		'TO_ID'=>'xiaoxia.zhu,yafeng.zhu,minxian.zhong,haiyan.xu',
		'TO_NAME'=>'ףСϼ,���Ƿ�,������,�캣��'
	),
	//�⳵�Ǽ�-����ͨ��֪ͨ��
	"oa_outsourcing_allregister"=>array(
		'TO_ID'=>'admin',
		'TO_NAME'=>'���ֽ�ADMIN'
	),
	//��ְ֪ͨ�ʼ�֪ͨ���²�(���ݱ�Ѷ)��¼��֪ͨ��
	"oa_hr_recruitment_entrynotice_beixun" => array(
		'sendUserId'=>'duo.wen,yu.long,deyi.liu,xuedi.niu,dan.yin,xiujie.zeng',
		'sendName'=>'�¶�,����,������,ţѩ��,ӡ��,�����'
	)
 );
