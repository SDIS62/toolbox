<?php
/**
 * SDIS 62
 *
 * @category   SDIS62
 * @package    SDIS62_View_Helper
 */
 
 /**
 * Class for Flash Messenger View Helper.
 *
 * @category   SDIS62
 * @package    SDIS62_View_Helper
 */
class SDIS62_View_Helper_FlashMessenger extends Zend_View_Helper_Abstract  implements Countable
{

    /**
     * Default Zend_Controller_Action_Helper_FlashMessenger object.
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $flashMessenger;

    /**
     * Construct and get the flash messenger action helper
     *
     * @return void
     */
    public function __construct()
    {
        $this->flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    }

    /**
     * Function wich provide fluent interface
     *
     * @return SDIS62_View_Helper_FlashMessenger
     */
    public function flashMessenger()
    {
        return $this;
    }

    /**
     * Output the messages.
     *
     * @param string $my_key (Optional) The key wich represents the messages to retrieve.
     * @param string $template (Optional) The template wich represents a message in the output list
     * @return string
     */
    public function output($my_key = null, $template = '<li class="alert alert-%s" ><button data-dismiss="alert" class="close">&times;</button><strong class="alert-%s">%s</strong> %s</li>')
    {
        // On récupère les messages
        $array_messages = $this->getMessages();

        // On initialise la chaine de sortie
        $output = '';

        // On stocke les messages
        foreach ($array_messages as $row_message) {

            $key = $row_message["context"];

            if($my_key == null || $key == $my_key ) {

                $output .= sprintf($template, $key, $key, $row_message["title"], $row_message["message"]);
            }
        }

        return $output;
    }

    /**
     * Get messages from flash messenger action helper
     *
     * @return array
     */
    private function getMessages()
    {
        // Messages
        $array_messages = $this->flashMessenger->getMessages();
        
        // Current Messages
        $array_currentMessages = $this->flashMessenger->getCurrentMessages();

        return array_merge($array_currentMessages, $array_messages);
    }

    /**
     * Check if there are messages
     *
     * @return int
     */
    public function hasMessages()
    {
        return count($this->flashMessenger) + count($this->flashMessenger->getCurrentMessages());
    }

    /**
     * Implements Countable
     *
     * @return int
     */
    public function count()
    {
        return $this->flashMessenger->count();
    }
}