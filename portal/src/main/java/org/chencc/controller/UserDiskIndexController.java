package org.chencc.controller;

import org.chencc.common.CurrentUserWrapper;
import org.chencc.config.CurrentUser;
import org.chencc.service.UserDiskIndexService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;

/**
 * @Description 我的硬盘控制器
 * @author:chencc
 * @Date:2018/7/30
 * @Time:下午10:03
 */
@Controller
@RequestMapping("user/disk")
public class UserDiskIndexController {
    @Autowired
    private UserDiskIndexService userDiskIndexService;

    /**
     * 跳转至"我的硬盘"页面
     * @return
     */
    @GetMapping(value = "/index")
    public String index(){
        return "user/disk_index";
    }


    /**
     * 获取指定父级下的目录，默认显示全部
     * @param pid
     * @return
     */
    @PostMapping(value="getDir")
    @ResponseBody
    public List<Map> getDir(@CurrentUser CurrentUserWrapper user, @RequestParam(value="pid",required=true,defaultValue="0") Integer pid){
        return userDiskIndexService.getUserDirByPid(user.getUid(),pid);
    }
}
