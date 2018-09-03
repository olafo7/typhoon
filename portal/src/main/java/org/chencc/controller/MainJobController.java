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
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.redis.core.RedisTemplate;
import org.springframework.data.redis.core.ValueOperations;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/28
 * @Time:上午9:55
 */
@Controller
@RequestMapping("/main/job")
public class MainJobController {
    @Autowired
    private MainJobService mainJobService;
    @Autowired
    private CommonService commonService;
    @Autowired
    private RedisTemplate redisTemplate;
    @Autowired
    private ValueOperations<String,Object> valueOperations;

    /**
     * 职位列表
     * @return
     */
    @GetMapping(value = "/list")
    public String list(){
        return "main/job";
    }


    /**
     * 加载职位数据
     * @param currentUser
     * @param start
     * @param limit
     * @param deptId
     * @param widthSon 是否显示所有下级
     * @param word
     * @return
     */
    @RequestMapping(value = "/getJsonData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getJsonData(@CurrentUser CurrentUserWrapper currentUser, @RequestParam(value="start",required=false,defaultValue="0") Integer start,
                                       @RequestParam(value="limit",required=false,defaultValue="15") Integer limit,
                                       @RequestParam(value="deptId",required=false,defaultValue="0") Integer deptId,
                                       @RequestParam(value="widthSon",required=false,defaultValue="true") Boolean widthSon,
                                       String word){
        DataListWrapper dataListWrapper = new DataListWrapper();

        dataListWrapper.setTotal(1);
        dataListWrapper.setCpage(1);
        dataListWrapper.setRows(10);
        dataListWrapper.setState("");
        dataListWrapper.setPaterId("");
        dataListWrapper.setPageId("main_job_list_getJsonData");

        ValueOperations<String,Object> operations = redisTemplate.opsForValue();
        SystemPagesize systemPagesize = (SystemPagesize)operations.get("user:"+currentUser.getUid()+":pagesize.main_job_list_getJsonData");
        if(limit != systemPagesize.getPagesize()){
            redisTemplate.delete("user:"+currentUser.getUid()+":pagesize.main_job_list_getJsonData");
            valueOperations.set("user:"+currentUser.getUid()+":pagesize.main_job_list_getJsonData",systemPagesize);
            //更新数据库中存储的记录
            commonService.updatePagesize(currentUser.getUid(),"main_job_list_getJsonData",limit);
        }else{
            limit = systemPagesize.getPagesize();
        }
        start = (start/limit)+1;
        dataListWrapper.setLimit(limit);

        try{
            PageInfo<Map> jobList = mainJobService.getJobList(start,limit,deptId,word,widthSon);
            dataListWrapper.setTotal(jobList.getTotal());
            dataListWrapper.setData(jobList.getList());

            dataListWrapper.setSuccess(true);
            dataListWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataListWrapper.setSuccess(false);
            dataListWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataListWrapper;
    }
}
