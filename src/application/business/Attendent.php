<?php

namespace business {

  require_once config_item('business-user-class');

  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an Attendent business class.
   * 
   */
  class Attendent extends Business {
    /** Aggregations: */
    /** Compositions: */
    /*     * * Attributes: ** */

    /**
     * 
     *
     * @return Attendent
     * @access public
     */
    function __construct() {
      parent::__construct();

      require_once config_item('business-attendent-class');
    }

    public function add_attendent() {
      
    }

// end of member function add_attendent

    /**
     * 
     *
     * @return bool
     * @access public
     */
    public function delete_attendent() {
      
    }

// end of member function delete_attendent

    /**
     * 
     *
     * @return Attendent
     * @access public
     */
    public function update_attendent() {
      
    }

// end of member function update_attendent
//        
//        function __set($name, $value) {
//            if (method_exists($this, $name)) {
//                $this->$name($value);
//            } else {
//                // Getter/Setter not defined so set as property of object
//                $this->$name = $value;
//            }
//        }
//
//        function __get($name) {
//            if (method_exists($this, $name)) {
//                return $this->$name();
//            } elseif (property_exists($this, $name)) {
//                // Getter/Setter not defined so return property if it exists
//                return $this->$name;
//            }
//            return null;
//        }
    // end of generic setter an getter definition
  }

  // end of Attendent
}

?>
