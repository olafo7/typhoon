package org.chencc.controller;

import com.google.gson.Gson;
import org.chencc.common.CurrentUserWrapper;
import org.chencc.config.CurrentUser;
import org.chencc.model.MainSignature;
import org.chencc.service.MainSignatureService;
import org.chencc.service.MainUserService;
import org.chencc.utils.PasswordUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.ResponseBody;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;
import java.util.List;
import java.util.Map;

/**
 *
 * @Description 系统登陆控制器，用于身份验证
 * @author:chencc
 * @Date:2018/7/11
 * @Time:下午07:19
 *
 * */

@Controller
public class LoginController {
    @Autowired
    private MainUserService mainUserService;

    /**
     * 跳转至登录页面
     * @return
     */
    @GetMapping(value = "/")
    public String index(){
        return "../static/login.html";
    }


    /**
     * 校验当前登陆用户的合法性，通过后跳转至欢迎页，否则给予错误提示
     * @param username
     * @param password
     * @return
     */
    @PostMapping(value = "/loginCheck")
    @ResponseBody
    public String loginCheck(@RequestParam(value = "username", required = true) String username,
                             @RequestParam(value = "password", required = true) String password,
                             HttpSession session, HttpServletRequest request) {

        String result = "";
        //1、根据用户名获取salt及加密后的密码
        List<Map> userInfo = mainUserService.getInfoByUsername(username);
        if(userInfo.isEmpty()){
            result = "nameFalse";
        }else{
            //2、校验用户
            boolean correct = PasswordUtil.checkPassword(String.valueOf(userInfo.get(0).get("salt")),String.valueOf(userInfo.get(0).get("password")),password);
            if(!correct){
                result = "pwdFalse";
            }else{ //存储当前登录用户信息
                CurrentUserWrapper currentUser = new CurrentUserWrapper();

                currentUser.setUid(Integer.parseInt(String.valueOf(userInfo.get(0).get("uid"))));
                currentUser.setUsername(String.valueOf(userInfo.get(0).get("username")));
                currentUser.setTrueName(String.valueOf(userInfo.get(0).get("true_name")));
                currentUser.setSalt(String.valueOf(userInfo.get(0).get("salt")));
                currentUser.setPassword(String.valueOf(userInfo.get(0).get("password")));
                currentUser.setEmail(String.valueOf(userInfo.get(0).get("email")));
                currentUser.setJobId(Integer.parseInt(String.valueOf(userInfo.get(0).get("job_id"))));
                currentUser.setJob(String.valueOf(userInfo.get(0).get("job")));
                currentUser.setJobType(String.valueOf(userInfo.get(0).get("job_type")));
                currentUser.setDeptId(Integer.parseInt(String.valueOf(userInfo.get(0).get("dept_id"))));
                currentUser.setDept(String.valueOf(userInfo.get(0).get("dept")));
                currentUser.setStationId(Integer.parseInt(String.valueOf(userInfo.get(0).get("station_id"))));
                currentUser.setStation(String.valueOf(userInfo.get(0).get("station")));
                currentUser.setTheme(String.valueOf(userInfo.get(0).get("theme")));
                currentUser.setWfNewWindow(Integer.parseInt(String.valueOf(userInfo.get(0).get("wf_new_window"))));
                currentUser.setMenuCollapsed(Integer.parseInt(String.valueOf(userInfo.get(0).get("menu_collapsed"))));

                session.setAttribute("currentUser", currentUser);
                System.out.println("session失效时间为：：：：："+request.getSession().getMaxInactiveInterval());
            }
        }

        return new Gson().toJson(result);
    }


    /**
     * 跳转至欢迎界面
     * @return
     */
    @PostMapping(value = "/welcome")
    public String welcome(@CurrentUser CurrentUserWrapper user,Model model) {

        model.addAttribute("currentUserInfo",user);

        return "welcome";
    }
}
