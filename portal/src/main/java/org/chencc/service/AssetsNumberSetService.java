package org.chencc.service;

import org.chencc.model.AssetsNumber;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午11:25
 */
public interface AssetsNumberSetService {
    AssetsNumber getSortList();

    void saveEditRecord(Integer status, String zimu,String fuhao,String num, Integer zimuCheck,
                   Integer fuhaoCheck,String nowNum,String numShow);

}
