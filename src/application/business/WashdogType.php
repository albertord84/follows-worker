<?php

namespace business{

  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an WashdogType business static class.
   * 
   */
class WashdogType extends Business {
    const PURCHASE = 'SUCCESSFUL PURCHASE';
    const ACTIVE_STATUS = 'FOR ACTIVE STATUS';
    const FOR_STATUS = 'FOR STATUS';
    const VERIFY_ACCOUNT = 'FOR VERIFY ACCOUNT STATUS';
    const DID_LOGIN =  'DID LOGIN';
    const SATISFYING_SHOPPING = 'DID SATISFYING SHOPPING';
    const TOTAL_UNFOLLOW = 'TOTAL UNFOLLOW ';
    const AUTOLIKE = 'AUTOLIKE';
    const CARD_UPDATE = 'CORRECT CARD UPDATE';
    const INCORRECT_CARD_UPDATE = 'INCORRECT CARD UPDATE';
    const GEOCALIATION_INSERTED = 'GEOCALIZATION INSERTED';
    const GEOCALIATION_ELIMINATED = 'GEOCALIZATION ELIMINATED';
    const REFERENCE_PROFILE = 'REFERENCE PROFILE INSERTED';
    const REFERENCE_PROFILE_ELIMINATED = 'REFERENCE PROFILE ELIMINATED';
    const CLOSING_SESSION = 'CLOSING SESSION';
    const LOOKING_GEOCALIATION_TIPS = 'LOOKING AT GEOCALIZATION TIPS';
    const LOOKING_REFERENCE_PROFILES_TIPS = 'LOOKING AT REFERENCE PROFILES TIPS';
    const INSERTING_PROFILE_BLACK_LIST ='INSERTING PROFILE IN BLACK LIST';
    const DELETING_PROFILE_BLACK_LIST = 'DELETING PROFILE IN BLACK LIST';
    const INSERTING_PROFILE_WHITE_LIST = 'INSERTING PROFILE IN WHITE LIST';
    const DELETING_PROFILE_WHITE_LIST ='DELETING PROFILE IN WHITE LIST';
    const BLOCKED_BY_TIME = 'BLOCKED BY TIME';
    const SET_TO_UNFOLLOW = 'SET TO UNFOLLOW';
    const BLOCKED_BY_INSTA = 'BLOCKED BY INSTA';
    const ROBOT_VERIFY_ACCOUNT = 'VERIFY ACCOUNT';
    const TO_MANY_REQUESTS = 'TO MANY REQUESTS';
    const BLOCKED_CONTENT = 'BLOCKED CONTENT';
    const ERROR_PROCESSING_REQUEST = 'ERROR PROCESSING REQUEST';
    const PROBLEM_WITH_YOUR_REQUEST = 'PROBLEM_WITH_YOUR_REQUEST';
    const SET_PROXY ="SET PROXY";
    const ERROR_IN_PR = "ERROR IN REFERENCE PROFILE";
    
    static public function Defines($const) {
            $cls = new ReflectionClass(__CLASS__);
            foreach ($cls->getConstants() as $key => $value) {
                if ($value == $const) {
                    return true;
                }
            }

            return false;
        }

  }
}
