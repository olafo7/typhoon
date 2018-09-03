package org.chencc.service.impl;

import com.github.pagehelper.PageHelper;
import com.github.pagehelper.PageInfo;
import org.apache.commons.lang.StringUtils;
import org.chencc.mapper.MainSignatureMapper;
import org.chencc.model.MainSignature;
import org.chencc.service.MainSignatureService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/31
 * @Time:下午6:32
 */
@Service
@Transactional
public class MainSignatureServiceImpl implements MainSignatureService {
    @Autowired
    private MainSignatureMapper mainSignatureMapper;

    @Override
    public PageInfo<Map> getSignatureList(Integer start, Integer limit, Integer uid, String sname) {
        StringBuffer sqlBuffer = new StringBuffer("select ms.id,ms.uid,ms.signature,ms.url,ms.is_use_pwd,(select a.true_name from db_main_user as a where ms.uid=a.uid) as username " +
                " from db_main_signature as ms where 1=1");

        if(uid != 0){
            sqlBuffer.append(" and ms.uid = "+uid+"");
        }

        if(StringUtils.isNotBlank(sname)){
            sqlBuffer.append(" and ms.signature like '%"+sname+"%'");
        }

        PageHelper.startPage(start,limit);
        List<Map> signatureList = mainSignatureMapper.getSignatureList(sqlBuffer.toString());
        PageInfo<Map> pageInfo = new PageInfo<Map>(signatureList);

        return pageInfo;
    }
}
