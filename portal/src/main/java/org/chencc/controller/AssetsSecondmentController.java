package org.chencc.controller;

import org.chencc.service.AssetsSecondmentService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

/**
 * @Description 资产借调控制器
 * @author:chencc
 * @Date:2018/7/26
 * @Time:下午8:41
 */
@Controller
@RequestMapping("/assets/secondment")
public class AssetsSecondmentController {
    @Autowired
    private AssetsSecondmentService assetsSecondmentService;

    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/list")
    public String list(){
        return "assets/secondment";
    }
}
