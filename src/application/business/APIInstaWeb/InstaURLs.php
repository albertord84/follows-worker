<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb;

/**
 * Description of InstaURLs
 *
 * @author jose
 */
class InstaURLs  extends SplEnum{
     const __default = self::Instagram;
    
     const Instagram = 'https://www.instagram.com';
     
     const GraphqlQuery = '"https://www.instagram.com/graphql/query/"';
     
     const MakePost = '';
}

