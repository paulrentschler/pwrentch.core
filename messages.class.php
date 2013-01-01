<?php
  /**
   * Define the message types.
   * 
   * @author Paul Rentschler <par117@psu.edu>
   */
  define('MSG_TYPE_INFO', 'info');
  define('MSG_TYPE_WARN', 'warn');
  define('MSG_TYPE_ERROR', 'error');
  define('MSG_TYPE_VALIDATION', 'validation');
  
  

  /**
   * Singleton object for keeping track of all messages.
   * 
   * This object collects all messages generated by the
   *   the code that need to be passed back to the user.
   *   
   * filename: /includes/messages.class.php
   * 
   * @author Paul Rentschler <par117@psu.edu>
   * @since 18 February 2011
   */
  class Msg {

    /**
     * Array of messages to return to the user.
     * 
     * The messages are grouped into categories which
     *   serve as the keys to this associative array.
     * The keys are defined as constants.
     * The keys are defined here to set the order in which
     *   they are displayed to the user.
     */
    protected static $messages = array( MSG_TYPE_ERROR => array(), 
                                        MSG_TYPE_WARN => array(), 
                                        MSG_TYPE_INFO => array(),
                                        MSG_TYPE_VALIDATION => array(),
                                      );
    
    

    /**
     * Prevent anyone from creating an instance of this.
     */
    private function __construct() {} 
    
    /**
     * Prevent anyone from creating an instance of this.
     */
    private function __clone() {} 
    
    
    /**
     * Determine if a message type is valid.
     *
     * @param string $messageType The message type string to check for validity.
     * @return boolean Whether or not the message type is a valid one.
     * @author Paul Rentschler <par117@psu.edu>
     */
    protected static function _isMessageTypeValid ($messageType) {

      // assume the worst
      $result = false;
      
      // see if there is a message type constant with this valid
      $constants = get_defined_constants(true);
      foreach ($constants['user'] as $constant => $value) {
        if (substr($constant, 0, 9) == 'MSG_TYPE_' && $value == $messageType) {
          $result = true;
        }
      }
      
      return $result;
      
    }  // end of function _isMessageTypeValid
      
    
    
    /**
     * Add a message to the collection.
     * 
     * @param string $type The type of message we are adding.
     * @param string $message The message to be added.
     * @param string $field The field that the message applies to.
     * @return boolean Whether or not the message was added to the collection.
     * @author Paul Rentschler <par117@psu.edu>
     */
    public static function add ($type, $message, $field = '') {
      
      // assume the worst
      $result = false;
      
      // determine if the message type is valid
      if (isset($type) && self::_isMessageTypeValid($type)) {
        // the message type is valid, check to make sure the message is not blank
        if (isset($message) && trim($message) <> '') {
          // make sure the message type index exists
          if (!array_key_exists($type, self::$messages)) {
            self::$messages[$type] = array();
          }
          
          // the message is not blank, add it
          if (isset($field) && $field <> '') {
            self::$messages[$type][$field] = trim($message);
          } else {
            self::$messages[$type][] = trim($message);
          }
          $result = true;
          
        // if a validation message is blank, the remove the message
        } elseif ($type == MSG_TYPE_VALIDATION && isset($message) && $message == '' && isset($field)) {
          unset(self::$messages[$type][$field]);
        }
      } 
      
      return $result;
      
    }  // end of function add
    
    
    
    /**
     * Get the messages from the collection.
     * 
     * You can get all of the messages by type or just
     *   get the messages for one type.
     *   
     * @param string $type The type of messages to retreive. If blank, gets all types.
     * @return array An array of messages.
     * @author Paul Rentschler <par117@psu.edu>
     */
    public static function get ($type = '') {
      
      $result = array();
      if (trim($type) == '') {
        $result = self::$messages;
        
      } elseif (self::_isMessageTypeValid($type) && array_key_exists($type, self::$messages)) {
        $result = self::$messages[$type];
      }
      
      return $result;
      
    }  // end of function get
    
  }  // end of class Msg