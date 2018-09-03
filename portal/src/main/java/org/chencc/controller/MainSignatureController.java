package org.chencc.controller;

import com.github.pagehelper.PageInfo;
import org.chencc.common.CurrentUserWrapper;
import org.chencc.common.DataListWrapper;
import org.chencc.config.CurrentUser;
import org.chencc.model.MainSignature;
import org.chencc.model.SystemPagesize;
import org.chencc.service.CommonService;
import org.chencc.service.MainSignatureService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.redis.core.RedisTemplate;
import org.springframework.data.redis.core.ValueOperations;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;

/**
 * @Description 签章管理控制器
 * @author:chencc
 * @Date:2018/7/17
 * @Time:下午2:19
 */

@Controller
@RequestMapping("/main/signature")
public class MainSignatureController {
    @Autowired
    private MainSignatureService mainSignatureService;
    @Autowired
    private CommonService commonService;
    @Autowired
    private RedisTemplate redisTemplate;
    @Autowired
    private ValueOperations<String,Object> valueOperations;


    @GetMapping(value = "/list")
    public String list(){
        return "main/signature";
    }



    /**
     * 加载列表数据
     * @return
     */
    @RequestMapping(value = "/getJsonDatas", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getJsonDatas(@CurrentUser CurrentUserWrapper currentUser, @RequestParam(value="start",required=false,defaultValue="0") Integer start,
                                             @RequestParam(value="limit",required=false,defaultValue="15") Integer limit, Integer uid, String sname){

        DataListWrapper dataListWrapper = new DataListWrapper();

        dataListWrapper.setCpage(1);
        dataListWrapper.setRows(10);
        dataListWrapper.setState("");
        dataListWrapper.setPaterId("");
        dataListWrapper.setPageId("main_signature_index_graph_getJsonDatas");
        ValueOperations<String,Object> operations = redisTemplate.opsForValue();
        SystemPagesize systemPagesize = (SystemPagesize)operations.get("user:"+currentUser.getUid()+":pagesize.main_signature_index_graph_getJsonDatas");
        if(limit != systemPagesize.getPagesize()){
            redisTemplate.delete("user:"+currentUser.getUid()+":pagesize.main_signature_index_graph_getJsonDatas");
            valueOperations.set("user:"+currentUser.getUid()+":pagesize.main_signature_index_graph_getJsonDatas",systemPagesize);
            //更新数据库中存储的记录
            commonService.updatePagesize(currentUser.getUid(),"main_signature_index_graph_getJsonDatas",limit);
        }else{
            limit = systemPagesize.getPagesize();
        }
        start = (start/limit)+1;
        dataListWrapper.setLimit(limit);

        try{
            PageInfo<Map> signatureList = mainSignatureService.getSignatureList(start,limit,uid,sname);
            dataListWrapper.setTotal(signatureList.getTotal());
            dataListWrapper.setData(signatureList.getList());
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
