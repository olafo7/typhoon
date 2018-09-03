package org.chencc.service;


import com.github.pagehelper.PageInfo;

import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/31
 * @Time:下午6:28
 */
public interface MainSignatureService{
    PageInfo<Map> getSignatureList(Integer start, Integer limit, Integer uid, String sname);

}
