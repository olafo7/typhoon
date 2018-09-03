package org.chencc.controller;

import org.chencc.common.SelectorOfJobWrapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.redis.core.RedisTemplate;
import org.springframework.data.redis.core.ValueOperations;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/28
 * @Time:下午5:30
 */
@Controller
@RequestMapping("/common/")
public class CommonController {

    @Autowired
    private RedisTemplate redisTemplate;
    @Autowired
    private ValueOperations<String,Object> valueOperations;



    /**
     * 加载职位选择器数据
     * @return
     */
    @RequestMapping(value = "/getSelectorDataOfJob", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getSelectorDataOfJob(){
        ValueOperations<String,String> operations = redisTemplate.opsForValue();
        String jobInfo = operations.get("common:selector:job");

        return jobInfo;
    }




    /**
     * 加载部门选择器数据
     * @return
     */
    @RequestMapping(value = "/getSelectorDataOfDept", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getSelectorDataOfDept(){
        ValueOperations<String,String> operations = redisTemplate.opsForValue();
        String deptInfo = operations.get("common:selector:dept");

        return deptInfo;
    }



    /**
     * 加载岗位选择器数据
     * @return
     */
    @RequestMapping(value = "/getSelectorDataOfStation", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getSelectorDataOfStation(){
        ValueOperations<String,String> operations = redisTemplate.opsForValue();
        String stationInfo = operations.get("common:selector:station");

        return stationInfo;
    }




    /**
     * 加载用户选择器数据
     * @return
     */
    @RequestMapping(value = "/getSelectorDataOfUser", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getSelectorDataOfUser(){
        ValueOperations<String,String> operations = redisTemplate.opsForValue();
        String userInfo = operations.get("common:selector:user");

        return userInfo;
    }



    /**
     * 加载部门选择器数据
     * @param target 根据参数加载对应数据
     * @return
     */
    @RequestMapping(value = "/getSelectorData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getSelectorData(String target){
        ValueOperations<String,String> operations = redisTemplate.opsForValue();
        String result = "";
        if("user".equals(target)){
            result = operations.get("common:selector:user");
        }else if("job".equals(target)){
            result = operations.get("common:selector:job");
        }else if("dept".equals(target)){
            result = operations.get("common:selector:dept");
        }else if("station".equals(target)){
            result = operations.get("common:selector:station");
        }
        return result;
    }
}
