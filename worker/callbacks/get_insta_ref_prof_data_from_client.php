<?php

    require_once '../class/Worker.php';
    require_once '../class/Robot.php';
    require_once '../class/system_config.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    $Robot = new follows\cls\Robot();
    
    $cookies = json_decode(urldecode($_POST['cookies']));    
    $profile_name = urldecode($_POST['profile_name']);
    $dumbu_id_profile = urldecode($_POST['dumbu_id_profile']);
    
//    $cookies = json_decode('{"sessionid":"IGSC52abfa7163f944b76b3fc7aa6485389597d8a9e6e1e249f145604889730075ed%3AYvegHPD6EBl8vJvPUPB4Td8z6YBtwy2E%3A%7B%22_auth_user_id%22%3A3858629065%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_auth_user_hash%22%3A%22%22%2C%22_platform%22%3A1%2C%22_token_ver%22%3A2%2C%22_token%22%3A%223858629065%3AdbvJvAjNx1VxS5tChRYpMp7vSNtSlxHN%3A7d8e486839700c1dafdd8b10bb0884a283cccac8d6f41e6569beea87f5fa17da%22%2C%22last_refreshed%22%3A1537500468.2019324303%7D","csrftoken":"ooGbbFwK6OfIN9vgggfQOT8f1YSQ7n6H","ds_user_id":"3858629065","mid":"WwhO9wABAAHl2oOasN-evIVaU3S1","json_response":{"status":"ok","authenticated":true}}');
//    $profile_name = "josergm86";
//    $dumbu_id_profile = "";
    
    
    
    
//    if($dumbu_id_profile=="")
//        $dumbu_id_profile = NULL;
//    $result = $Robot->get_insta_ref_prof_data_from_client($cookies, $profile_name, $dumbu_id_profile);
//    echo json_encode($result);
    echo json_encode($profile_name);
    
 ?>
