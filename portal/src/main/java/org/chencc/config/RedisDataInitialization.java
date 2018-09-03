package org.chencc.config;

import com.google.gson.Gson;
import org.chencc.common.*;
import org.chencc.model.MainJob;
import org.chencc.model.MainStation;
import org.chencc.model.MainStruct;
import org.chencc.model.SystemPagesize;
import org.chencc.service.*;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.CommandLineRunner;
import org.springframework.data.redis.core.*;
import org.springframework.stereotype.Component;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 * @Description 初始化缓冲数据，如系统功能菜单、列表页面每页条数、组织结构/用户/岗位/职位等选择器数据等
 * @author:chencc
 * @Date:2018/7/26
 * @Time:下午11:32
 */
@Component
public class RedisDataInitialization implements CommandLineRunner {
    @Autowired
    private WelcomeService welcomeService;
    @Autowired
    private MainUserService mainUserService;
    @Autowired
    private MainStructService mainStructService;
    @Autowired
    private MainJobService mainJobService;
    @Autowired
    private MainStationService mainStationService;
    @Autowired
    private RedisTemplate redisTemplate;
    @Autowired
    private ValueOperations<String,Object> valueOperations;
    @Autowired
    private HashOperations<String, String, Object> hashOperations;
    @Autowired
    private ListOperations<String, Object> listOperations;
    @Autowired
    private SetOperations<String, Object> setOperations;
    @Autowired
    private ZSetOperations<String, Object> zSetOperations;

    @Override
    public void run(String... strings) throws Exception {
        //1、初始化功能菜单

        //2、初始化列表页面每页条数
        initPagesizeData();
        //3、初始化职位选择器数据
        initSelectorDataOfJob();
        //4、初始化组织结构选择器数据
        initSelectorDataOfDept();
        //5、初始化岗位选择器数据
        initSelectorDataOfStation();
        //6、初始化用户选择器数据
        initSelectorDataOfUser();
    }


    /**
     * 初始化列表页面每页条数
     */
    public void initPagesizeData(){
        List<SystemPagesize> pagesizeList = welcomeService.getPageSize();
        for(SystemPagesize systemPagesize : pagesizeList){
            String key = "user:"+systemPagesize.getUid()+":pagesize."+systemPagesize.getId();

            if(redisTemplate.hasKey(key)){
                redisTemplate.delete(key);
                valueOperations.set(key,systemPagesize);
            }else{
                valueOperations.set(key,systemPagesize);
            }
        }
    }


    /**
     * 初始化职位选择器数据
     */
    public void initSelectorDataOfJob(){
        List<SelectorOfJobWrapper> selectorOfJobWrapperList = new ArrayList<SelectorOfJobWrapper>();

        SelectorOfJobWrapper selectorOfJobWrapper = new SelectorOfJobWrapper();
        MainStruct topStruct = mainStructService.getTopStruct();

        selectorOfJobWrapper.setId("CNOA_main_struct_list_tree_node_"+topStruct.getId());
        selectorOfJobWrapper.setDeptId(String.valueOf(topStruct.getId()));
        selectorOfJobWrapper.setIconCls("icon-tree-root-cnoa");
        selectorOfJobWrapper.setDisabled(false);
        selectorOfJobWrapper.setExpanded(false);
        selectorOfJobWrapper.setDept(topStruct.getName());
        //部门下的角色
        List<Map> jobList = mainJobService.getJobListByDeptId(topStruct.getId());
        List<JobWrapper> jobWrapperList = new ArrayList<JobWrapper>();
        for(Map jobMap : jobList){
            JobWrapper jobWrapper = new JobWrapper();
            jobWrapper.setJid(String.valueOf(jobMap.get("jid")));
            jobWrapper.setName(String.valueOf(jobMap.get("name")));
            jobWrapperList.add(jobWrapper);
        }
        selectorOfJobWrapper.setJobs(jobWrapperList);
        //递归获取子集
        List<SubSelectorOfJobWrapper> subSelectList = getStructListByFidForJob(topStruct.getId());
        if(subSelectList.isEmpty()){
            selectorOfJobWrapper.setLeaf(true);
            selectorOfJobWrapper.setChildren(new ArrayList<SubSelectorOfJobWrapper>());
        }else{
            selectorOfJobWrapper.setLeaf(false);
            selectorOfJobWrapper.setChildren(subSelectList);
        }

        selectorOfJobWrapperList.add(selectorOfJobWrapper);

        Gson gson = new Gson();
        String key = "common:selector:job";
        if(redisTemplate.hasKey(key)){
            redisTemplate.delete(key);
            valueOperations.set(key,gson.toJson(selectorOfJobWrapperList));
        }else{
            valueOperations.set(key,gson.toJson(selectorOfJobWrapperList));
        }
    }


    /**
     * 递归获取组织结构子集,用于职位选择器使用
     * @param deptId
     * @return
     */
    public List<SubSelectorOfJobWrapper> getStructListByFidForJob(Integer deptId){
        List<SubSelectorOfJobWrapper> jobWrapperList = new ArrayList<SubSelectorOfJobWrapper>();

        List<MainStruct> structList = mainStructService.getChildrenStructList(deptId);

        for(MainStruct struct : structList){
            SubSelectorOfJobWrapper subStructWrapper = new SubSelectorOfJobWrapper();

            subStructWrapper.setId("CNOA_main_struct_list_tree_node_"+struct.getId());
            subStructWrapper.setDeptId(String.valueOf(struct.getId()));
            subStructWrapper.setIconCls("icon-style-tree");
            subStructWrapper.setDisabled(false);
            subStructWrapper.setDept(struct.getName());
            subStructWrapper.setSingleClickExpand(false);
            //部门下的角色
            List<Map> jobList = mainJobService.getJobListByDeptId(struct.getId());
            List<JobWrapper> jWrapperList = new ArrayList<JobWrapper>();
            for(Map jobMap : jobList){
                JobWrapper jobWrapper = new JobWrapper();
                jobWrapper.setJid(String.valueOf(jobMap.get("jid")));
                jobWrapper.setName(String.valueOf(jobMap.get("name")));
                jWrapperList.add(jobWrapper);
            }
            subStructWrapper.setJobs(jWrapperList);

            //子集
            List<SubSelectorOfJobWrapper> subSelectList = getStructListByFidForJob(struct.getId());
            if(subSelectList.isEmpty()){
                subStructWrapper.setLeaf(true);
                subStructWrapper.setChildren(new ArrayList<SubSelectorOfJobWrapper>());
            }else{
                subStructWrapper.setLeaf(false);
                subStructWrapper.setChildren(subSelectList);
            }
            jobWrapperList.add(subStructWrapper);
        }

        return jobWrapperList;
    }


    /**
     * 初始化岗位选择器数据
     * @return
     */
    public void initSelectorDataOfStation(){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState(null);
        dataWrapper.setPaterId(null);

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

        Gson gson = new Gson();
        String key = "common:selector:station";
        if(redisTemplate.hasKey(key)){
            redisTemplate.delete(key);
            valueOperations.set(key,gson.toJson(dataWrapper));
        }else{
            valueOperations.set(key,gson.toJson(dataWrapper));
        }
    }


    /**
     * 初始化组织结构选择器数据
     */
    public void initSelectorDataOfDept(){
        List<SelectorOfDeptWrapper> selectorOfDeptWrapperList = new ArrayList<SelectorOfDeptWrapper>();

        SelectorOfDeptWrapper selectorOfDeptWrapper = new SelectorOfDeptWrapper();
        MainStruct topStruct = mainStructService.getTopStruct();

        selectorOfDeptWrapper.setId("CNOA_main_struct_list_tree_node_"+topStruct.getId());
        selectorOfDeptWrapper.setSelfid(String.valueOf(topStruct.getId()));
        selectorOfDeptWrapper.setDeptId(String.valueOf(topStruct.getId()));
        selectorOfDeptWrapper.setPermit(1);
        selectorOfDeptWrapper.setText(topStruct.getName());
        selectorOfDeptWrapper.setIconCls("icon-tree-root-cnoa");
        selectorOfDeptWrapper.setHref("javascript:void(0);");
        selectorOfDeptWrapper.setDisabled(false);
        selectorOfDeptWrapper.setDs(false);
        selectorOfDeptWrapper.setExpanded(false);

        //递归获取子集
        List<SelectorOfDeptWrapper> subSelectList = getStructListByFidForDept(topStruct.getId());
        if(subSelectList.isEmpty()){
            selectorOfDeptWrapper.setCls("cls");
            selectorOfDeptWrapper.setLeaf(true);
            selectorOfDeptWrapper.setChildren(new ArrayList<SelectorOfDeptWrapper>());
        }else{
            selectorOfDeptWrapper.setCls("package");
            selectorOfDeptWrapper.setLeaf(false);
            selectorOfDeptWrapper.setChildren(subSelectList);
        }

        selectorOfDeptWrapperList.add(selectorOfDeptWrapper);

        Gson gson = new Gson();
        String key = "common:selector:dept";
        if(redisTemplate.hasKey(key)){
            redisTemplate.delete(key);
            valueOperations.set(key,gson.toJson(selectorOfDeptWrapperList));
        }else{
            valueOperations.set(key,gson.toJson(selectorOfDeptWrapperList));
        }
    }


    /**
     * 递归获取组织结构子集,用于部门选择器使用
     * @param deptId
     * @return
     */
    public List<SelectorOfDeptWrapper> getStructListByFidForDept(Integer deptId){
        List<SelectorOfDeptWrapper> deptWrapperList = new ArrayList<SelectorOfDeptWrapper>();

        List<MainStruct> structList = mainStructService.getChildrenStructList(deptId);

        for(MainStruct struct : structList){
            SelectorOfDeptWrapper subStructWrapper = new SelectorOfDeptWrapper();

            subStructWrapper.setId("CNOA_main_struct_list_tree_node_"+struct.getId());
            subStructWrapper.setSelfid(String.valueOf(struct.getId()));
            subStructWrapper.setDeptId(String.valueOf(struct.getId()));
            subStructWrapper.setPermit(1);
            subStructWrapper.setText(struct.getName());
            subStructWrapper.setIconCls("icon-style-page-key");
            subStructWrapper.setHref("javascript:void(0);");
            subStructWrapper.setDisabled(false);
            subStructWrapper.setDs(false);
            subStructWrapper.setExpanded(false);

            //子集
            List<SelectorOfDeptWrapper> subSelectList = getStructListByFidForDept(struct.getId());
            if(subSelectList.isEmpty()){
                subStructWrapper.setCls("cls");
                subStructWrapper.setLeaf(true);
                subStructWrapper.setChildren(new ArrayList<SelectorOfDeptWrapper>());
            }else{
                subStructWrapper.setCls("package");
                subStructWrapper.setLeaf(false);
                subStructWrapper.setChildren(subSelectList);
            }
            deptWrapperList.add(subStructWrapper);
        }

        return deptWrapperList;
    }


    /**
     * 初始化用户选择器数据
     */
    public void initSelectorDataOfUser(){
        List<SelectorOfUserWrapper> selectorOfUserWrapperList = new ArrayList<SelectorOfUserWrapper>();

        SelectorOfUserWrapper selectorOfUserWrapper = new SelectorOfUserWrapper();
        MainStruct topStruct = mainStructService.getTopStruct();

        selectorOfUserWrapper.setId("CNOA_main_struct_list_tree_node_"+topStruct.getId());
        selectorOfUserWrapper.setDeptId(String.valueOf(topStruct.getId()));
        selectorOfUserWrapper.setIconCls("icon-tree-root-cnoa");
        selectorOfUserWrapper.setDisabled(false);
        selectorOfUserWrapper.setExpanded(false);
        selectorOfUserWrapper.setDept(topStruct.getName());
        //部门下的用户
        List<Map> userList = mainUserService.getUserListByDeptId(topStruct.getId());
        List<UserWrapper> userWrapperList = new ArrayList<UserWrapper>();
        for(Map jobMap : userList){
            UserWrapper userWrapper = new UserWrapper();
            userWrapper.setUid(String.valueOf(jobMap.get("uid")));
            userWrapper.setName(String.valueOf(jobMap.get("name")));
            userWrapper.setEmail(String.valueOf(jobMap.get("email")));

            userWrapperList.add(userWrapper);
        }
        selectorOfUserWrapper.setUsers(userWrapperList);
        //递归获取子集
        List<SelectorOfUserWrapper> subSelectList = getStructListByFidForUser(topStruct.getId());
        if(subSelectList.isEmpty()){
            selectorOfUserWrapper.setLeaf(true);
            selectorOfUserWrapper.setChildren(new ArrayList<SelectorOfUserWrapper>());
        }else{
            selectorOfUserWrapper.setLeaf(false);
            selectorOfUserWrapper.setChildren(subSelectList);
        }

        selectorOfUserWrapperList.add(selectorOfUserWrapper);

        Gson gson = new Gson();
        String key = "common:selector:user";
        if(redisTemplate.hasKey(key)){
            redisTemplate.delete(key);
            valueOperations.set(key,gson.toJson(selectorOfUserWrapperList));
        }else{
            valueOperations.set(key,gson.toJson(selectorOfUserWrapperList));
        }
    }



    /**
     * 递归获取组织结构子集,用于用户选择器使用
     * @param deptId
     * @return
     */
    public List<SelectorOfUserWrapper> getStructListByFidForUser(Integer deptId){
        List<SelectorOfUserWrapper> userWrapperList = new ArrayList<SelectorOfUserWrapper>();

        List<MainStruct> structList = mainStructService.getChildrenStructList(deptId);

        for(MainStruct struct : structList){
            SelectorOfUserWrapper subStructWrapper = new SelectorOfUserWrapper();

            subStructWrapper.setId("CNOA_main_struct_list_tree_node_"+struct.getId());
            subStructWrapper.setDeptId(String.valueOf(struct.getId()));
            subStructWrapper.setIconCls("icon-style-tree");
            subStructWrapper.setDisabled(false);
            subStructWrapper.setExpanded(false);
            subStructWrapper.setDept(struct.getName());
            subStructWrapper.setSingleClickExpand(false);

            //部门下的用户
            List<Map> userList = mainUserService.getUserListByDeptId(struct.getId());
            List<UserWrapper> uWrapperList = new ArrayList<UserWrapper>();
            for(Map jobMap : userList){
                UserWrapper userWrapper = new UserWrapper();
                userWrapper.setUid(String.valueOf(jobMap.get("uid")));
                userWrapper.setName(String.valueOf(jobMap.get("name")));
                userWrapper.setEmail(String.valueOf(jobMap.get("email")));

                uWrapperList.add(userWrapper);
            }
            subStructWrapper.setUsers(uWrapperList);

            //子集
            List<SelectorOfUserWrapper> subSelectList = getStructListByFidForUser(struct.getId());
            if(subSelectList.isEmpty()){
                subStructWrapper.setLeaf(true);
                subStructWrapper.setChildren(new ArrayList<SelectorOfUserWrapper>());
            }else{
                subStructWrapper.setLeaf(false);
                subStructWrapper.setChildren(subSelectList);
            }
            userWrapperList.add(subStructWrapper);
        }

        return userWrapperList;
    }
}
