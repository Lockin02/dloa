<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 16:32:24
 * @version 1.0
 * @description:外包供应商库 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.busiCode ,c.systemCode ,c.officeId ,c.officeName ,c.country ,c.countryId ,c.province ,c.provinceId ,c.city ,c.cityId ,c.suppCode ,c.suppName ,c.shortName ,c.suppTypeCode ,c.suppTypeName ,c.registeredDate ,c.registeredFunds ,c.legalRepre ,c.employeesNum ,c.equityStructure ,c.mainBusiness ,c.mainBusinessCode ,c.adeptNetType ,c.adeptNetTypeCode ,c.adeptDevice ,c.adeptDeviceCode ,c.businessDistribute ,c.businessDistributeId ,c.taxPoint ,c.certifyNumber ,c.suppGrade ,c.companySize ,c.suppIntro ,c.companyNature ,c.fax ,c.suppSource ,c.requestType ,c.recomComments ,c.failureDate ,c.effectDate ,c.suppLevel ,c.registMark ,c.advantages ,c.taxRegistCode ,c.businRegistCode ,c.products ,c.businessCode ,c.plantArea ,c.address ,c.status ,c.trade ,c.companyType ,c.zipCode ,c.email ,c.plane ,c.manageDeptId ,c.manageDeptName ,c.manageUserId ,c.manageUserName ,c.vatTaxRate ,c.blackListReason ,c.changeExaStatus ,c.ExaStatus ,c.ExaDT ,c.approveId ,c.approveName ,c.approveTime ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.delFlag ,c.suppFailNumb ,c.suppCategory ,c.suppCategoryName ,c.blackReason ,c.isDel  from oa_outsourcesupp_supplib c where 1=1 ",
         "select_Outall"=>"select c.id ,c.suppCode ,c.suppName ,c.suppGrade ,c.mainBusiness ,c.registeredFunds ,c.legalRepre ,c.address ,d.bankName ,d.accountNum ,e.name ,e.jobName ,e.mobile from oa_outsourcesupp_supplib c left join oa_outsourcesupp_bankinfo d on c.id=d.suppId left join oa_outsourcesupp_linkman e on c.id = e.suppId and e.id in (select min(e.id) from oa_outsourcesupp_linkman e group by e.suppId) where 1=1  "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "busiCode",
   		"sql" => " and c.busiCode like CONCAT('%',#,'%')   "
   	  ),
   array(
   		"name" => "systemCode",
   		"sql" => " and c.systemCode=# "
   	  ),
   array(
   		"name" => "officeId",
   		"sql" => " and c.officeId=# "
   	  ),
   array(
   		"name" => "officeName",
   		"sql" => " and c.officeName like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "country",
   		"sql" => " and c.country=# "
   	  ),
   array(
   		"name" => "countryId",
   		"sql" => " and c.countryId=# "
   	  ),
   array(
   		"name" => "province",
   		"sql" => " and c.province like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "provinceId",
   		"sql" => " and c.provinceId=# "
   	  ),
   array(
   		"name" => "city",
   		"sql" => " and c.city=# "
   	  ),
   array(
   		"name" => "cityId",
   		"sql" => " and c.cityId=# "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "suppNameEq",
   		"sql" => " and c.suppName=# "
   	  ),
   array(
   		"name" => "shortName",
   		"sql" => " and c.shortName=# "
   	  ),
   array(
   		"name" => "suppTypeCode",
   		"sql" => " and c.suppTypeCode=# "
   	  ),
   array(
   		"name" => "suppTypeName",
   		"sql" => " and c.suppTypeName like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "registeredDate",
   		"sql" => " and c.registeredDate like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "registeredFunds",
   		"sql" => " and c.registeredFunds=# "
   	  ),
   array(
   		"name" => "legalRepre",
   		"sql" => " and c.legalRepre like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "employeesNum",
   		"sql" => " and c.employeesNum=# "
   	  ),
   array(
   		"name" => "equityStructure",
   		"sql" => " and c.equityStructure=# "
   	  ),
   array(
   		"name" => "mainBusiness",
   		"sql" => " and c.mainBusiness like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "mainBusinessCode",
   		"sql" => " and c.mainBusinessCode=# "
   	  ),
   array(
   		"name" => "adeptNetType",
   		"sql" => " and c.adeptNetType like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "adeptNetTypeCode",
   		"sql" => " and c.adeptNetTypeCode=# "
   	  ),
   array(
   		"name" => "adeptDevice",
   		"sql" => " and c.adeptDevice like CONCAT('%',#,'%')    "
   	  ),
   array(
   		"name" => "adeptDeviceCode",
   		"sql" => " and c.adeptDeviceCode=# "
   	  ),
   array(
   		"name" => "businessDistribute",
   		"sql" => " and c.businessDistribute=# "
   	  ),
   array(
   		"name" => "businessDistributeId",
   		"sql" => " and c.businessDistributeId=# "
   	  ),
   array(
   		"name" => "taxPoint",
   		"sql" => " and c.taxPoint=# "
   	  ),
   array(
   		"name" => "certifyNumber",
   		"sql" => " and c.certifyNumber=# "
   	  ),
   array(
   		"name" => "suppGrade",
   		"sql" => " and c.suppGrade=# "
   	  ),
   array(
   		"name" => "companySize",
   		"sql" => " and c.companySize=# "
   	  ),
   array(
   		"name" => "suppIntro",
   		"sql" => " and c.suppIntro=# "
   	  ),
   array(
   		"name" => "companyNature",
   		"sql" => " and c.companyNature=# "
   	  ),
   array(
   		"name" => "fax",
   		"sql" => " and c.fax=# "
   	  ),
   array(
   		"name" => "suppSource",
   		"sql" => " and c.suppSource=# "
   	  ),
   array(
   		"name" => "requestType",
   		"sql" => " and c.requestType=# "
   	  ),
   array(
   		"name" => "recomComments",
   		"sql" => " and c.recomComments=# "
   	  ),
   array(
   		"name" => "failureDate",
   		"sql" => " and c.failureDate=# "
   	  ),
   array(
   		"name" => "effectDate",
   		"sql" => " and c.effectDate=# "
   	  ),
   array(
   		"name" => "suppLevel",
   		"sql" => " and c.suppLevel=# "
   	  ),
   array(
   		"name" => "registMark",
   		"sql" => " and c.registMark=# "
   	  ),
   array(
   		"name" => "advantages",
   		"sql" => " and c.advantages=# "
   	  ),
   array(
   		"name" => "taxRegistCode",
   		"sql" => " and c.taxRegistCode=# "
   	  ),
   array(
   		"name" => "businRegistCode",
   		"sql" => " and c.businRegistCode=# "
   	  ),
   array(
   		"name" => "products",
   		"sql" => " and c.products=# "
   	  ),
   array(
   		"name" => "businessCode",
   		"sql" => " and c.businessCode=# "
   	  ),
   array(
   		"name" => "plantArea",
   		"sql" => " and c.plantArea=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  ),
   array(
   		"name" => "trade",
   		"sql" => " and c.trade=# "
   	  ),
   array(
   		"name" => "companyType",
   		"sql" => " and c.companyType=# "
   	  ),
   array(
   		"name" => "zipCode",
   		"sql" => " and c.zipCode=# "
   	  ),
   array(
   		"name" => "email",
   		"sql" => " and c.email=# "
   	  ),
   array(
   		"name" => "plane",
   		"sql" => " and c.plane=# "
   	  ),
   array(
   		"name" => "manageDeptId",
   		"sql" => " and c.manageDeptId=# "
   	  ),
   array(
   		"name" => "manageDeptName",
   		"sql" => " and c.manageDeptName=# "
   	  ),
   array(
   		"name" => "manageUserId",
   		"sql" => " and c.manageUserId=# "
   	  ),
   array(
   		"name" => "manageUserName",
   		"sql" => " and c.manageUserName=# "
   	  ),
   array(
   		"name" => "vatTaxRate",
   		"sql" => " and c.vatTaxRate=# "
   	  ),
   array(
   		"name" => "blackListReason",
   		"sql" => " and c.blackListReason=# "
   	  ),
   array(
   		"name" => "changeExaStatus",
   		"sql" => " and c.changeExaStatus=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "approveId",
   		"sql" => " and c.approveId=# "
   	  ),
   array(
   		"name" => "approveName",
   		"sql" => " and c.approveName=# "
   	  ),
   array(
   		"name" => "approveTime",
   		"sql" => " and c.approveTime=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "delFlag",
   		"sql" => " and c.delFlag=# "
   	  ),
   array(
   		"name" => "suppFailNumb",
   		"sql" => " and c.suppFailNumb=# "
   	  ),
   array(
   		"name" => "suppCategory",
   		"sql" => " and c.suppCategory=# "
   	  ),
   array(
   		"name" => "suppCategoryName",
   		"sql" => " and c.suppCategoryName=# "
   	  ),
   array(
   		"name" => "blackReason",
   		"sql" => " and c.blackReason=# "
   	  ),
   array(
   		"name" => "isDel",
   		"sql" => " and c.isDel=# "
   	  ),
	array(
			"name" => "suppGradeStr",
			"sql" => " and c.suppGrade in(arr) "
	)
)
?>