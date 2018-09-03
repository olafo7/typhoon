package org.chencc.controller;

import com.github.pagehelper.PageInfo;
import org.chencc.common.CurrentUserWrapper;
import org.chencc.common.DataListWrapper;
import org.chencc.common.DataWrapper;
import org.chencc.config.CurrentUser;
import org.chencc.model.MainJob;
import org.chencc.model.MainStation;
import org.chencc.model.SystemPagesize;
import org.chencc.service.CommonService;
import org.chencc.service.MainJobService;
import org.chencc.service.MainStationService;
import org.chencc.service.MainUserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.redis.core.RedisTemplate;
import org.springframework.data.redis.core.ValueOperations;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;

/**
 * @Description 系统用户管理控制器
 * @author:chencc
 * @Date:2018/7/27
 * @Time:下午11:10
 */
@Controller
@RequestMapping("/main/user")
public class MainUserController {
    @Autowired
    private MainUserService mainUserService;
    @Autowired
    private MainStationService mainStationService;
    @Autowired
    private MainJobService mainJobService;
    @Autowired
    private CommonService commonService;
    @Autowired
    private RedisTemplate redisTemplate;
    @Autowired
    private ValueOperations<String,Object> valueOperations;

    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/list")
    public String list(){
        return "main/user";
    }



    /**
     * 加载系统中所有的在职用户数据
     * @return
     */
    @RequestMapping(value = "/getUsersJsonData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getUsersJsonData(@CurrentUser CurrentUserWrapper currentUser, String type, @RequestParam(value="start",required=false,defaultValue="0") Integer start,
                                            @RequestParam(value="limit",required=false,defaultValue="15") Integer limit,
                                            @RequestParam(value="widthSon",required=false,defaultValue="true") Boolean widthSon,
                                            @RequestParam(value="deptId",required=false,defaultValue="0") Integer deptId,
                                            String trueName, String username, String mobile, String isSystemUser, String workStatusType,
                                            String stationId, String atjod){

        DataListWrapper dataListWrapper = new DataListWrapper();

        dataListWrapper.setCpage(1);
        dataListWrapper.setRows(10);
        dataListWrapper.setState("");
        dataListWrapper.setPaterId("");

        dataListWrapper.setPageId("main_user_list_getJsonData");
        ValueOperations<String,Object> operations = redisTemplate.opsForValue();
        SystemPagesize systemPagesize = (SystemPagesize)operations.get("user:"+currentUser.getUid()+":pagesize.main_user_list_getJsonData");
        if(limit != systemPagesize.getPagesize()){
            redisTemplate.delete("user:"+currentUser.getUid()+":pagesize.main_user_list_getJsonData");
            valueOperations.set("user:"+currentUser.getUid()+":pagesize.main_user_list_getJsonData",systemPagesize);
            //更新数据库中存储的记录
            commonService.updatePagesize(currentUser.getUid(),"main_user_list_getJsonData",limit);
        }else{
            limit = systemPagesize.getPagesize();
        }
        start = (start/limit)+1;
        dataListWrapper.setLimit(limit);

        try{
            PageInfo<Map> userInfoList = mainUserService.getUserListByType(type,start,limit,deptId,widthSon,trueName,username,mobile,
                    isSystemUser,workStatusType,stationId,atjod);
            dataListWrapper.setTotal(userInfoList.getTotal());
            dataListWrapper.setData(userInfoList.getList());
            dataListWrapper.setSuccess(true);
            dataListWrapper.setMsg("操作成功！");

        }catch (Exception ex){
            dataListWrapper.setSuccess(false);
            dataListWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataListWrapper;
    }




    /**
     * 加载岗位数据
     * @return
     */
    @RequestMapping(value = "/getStationList", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataWrapper getStationList(){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            List<MainStation> stationList = mainStationService.getStationList();

            dataWrapper.setSuccess(true);
            dataWrapper.setData(stationList);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }




    /**
     * 加载指定部门下的职位数据
     * @return
     */
    @RequestMapping(value = "/getJobListByDeptId", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataWrapper getJobListByDeptId(Integer deptId){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            List<MainJob> jobList = mainJobService.getJobListInDept(deptId);

            dataWrapper.setSuccess(true);
            dataWrapper.setData(jobList);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }


    /*uid: 0
    partTimeData: noEditParTime
    isClickUserPermit: no
    truename: 张三
    deptId: 13
    workStatusId: 1
    stationid: 1
    jobId: 31
    parttimeList:
    isSystemUser: on
    username: zhangs
    password: 974904
    sex: 1
    mobile:
    qq:
    birthday:
    workphone:
    wangwang:
    email:
    address:
    idcard:
    partTime: 0
    qyusername:
    maxsize: 1
    fssize: 2
    insidesize: 3
    usemessager: on
    usesend: on
    usereceive: on





    uid: 0
    partTimeData:
    isClickUserPermit: no
    truename: 张三
    deptId: 13
    workStatusId: 1
    stationid: 1
    jobId: 31
    parttimeList:
    isSystemUser: on
    username: zhangs
    password: 974904
    sex: 1
    mobile:
    qq:
    birthday:
    workphone:
    wangwang:
    email:
    address:
    idcard:
    partTime: 1
    qyusername:
    maxsize: 1
    fssize: 2
    insidesize: 3
    usemessager: on
    usesend: on
    usereceive: on
    partTimeDeptId:
    ptstationid:
    partTimeJobId:




    uid: 3
    partTimeData: 4-4,6-3
    isClickUserPermit: no
    truename: 副总经理
    deptId: 1
    workStatusId: 1
    stationid: 5
    jobId: 3
    parttimeList:
    isSystemUser: on
    username: 副总经理
    password:
    sex: 2
    mobile:
    qq:
    birthday: 1985-08-26
    workphone:
    wangwang:
    email:
    address:
    idcard:
    partTime: 1
    qyusername:
    maxsize: 0
    fssize: 0
    insidesize: 0
    usemessager: on
    usesend: on
    usereceive: on
    partTimeDeptId: 2
    ptstationid: 3
    partTimeJobId: 6*/
}
