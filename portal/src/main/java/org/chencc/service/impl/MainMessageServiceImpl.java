package org.chencc.service.impl;

import org.chencc.mapper.MainMessageMapper;
import org.chencc.model.MainMessage;
import org.chencc.service.MainMessageService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/22
 * @Time:上午8:51
 */
@Service
@Transactional
public class MainMessageServiceImpl implements MainMessageService {

    @Autowired
    private MainMessageMapper mainMessageMapper;

    public List<MainMessage> getMessageList(){
        return mainMessageMapper.getMessageList();
    }
}
