<?php

class SDIS62_Rest_Server extends Zend_Rest_Server
{
    /**
     * Implement Zend_Server_Interface::handle()
     *
     * @param  array $request
     * @throws Zend_Rest_Server_Exception
     * @return string|void
     */
    public function handle($request = false)
    {
        // On annonce qu'on va envoyer du Json
        $this->_headers = array('Content-Type: application/json');
        
        // On paramètre le detecteur de mise en défaut
        $failed = false;
        
        // Copié collé de Zend_Rest_Server::handle() ... (mais un peu custom quand même !)
        if (!$request)
        {
            $request = $_REQUEST;
        }
        
        if (isset($request['method']))
        {
            $this->_method = $request['method'];
            
            if (isset($this->_functions[$this->_method]))
            {
                if ($this->_functions[$this->_method] instanceof Zend_Server_Reflection_Function || $this->_functions[$this->_method] instanceof Zend_Server_Reflection_Method && $this->_functions[$this->_method]->isPublic())
                {
                    $request_keys = array_keys($request);
                    array_walk($request_keys, array(__CLASS__, "lowerCase"));
                    $request = array_combine($request_keys, $request);

                    $func_args = $this->_functions[$this->_method]->getParameters();

                    $calling_args = array();
                    $missing_args = array();
                    
                    foreach ($func_args as $arg)
                    {
                        if(isset($request[strtolower($arg->getName())]))
                        {
                            $calling_args[] = $request[strtolower($arg->getName())];
                        }
                        elseif($arg->isOptional())
                        {
                            $calling_args[] = $arg->getDefaultValue();
                        }
                        else
                        {
                            $missing_args[] = $arg->getName();
                        }
                    }

                    foreach ($request as $key => $value)
                    {
                        if (substr($key, 0, 3) == 'arg')
                        {
                            $key = str_replace('arg', '', $key);
                            $calling_args[$key] = $value;
                            
                            if (($index = array_search($key, $missing_args)) !== false)
                            {
                                unset($missing_args[$index]);
                            }
                        }
                    }

                    // Sort arguments by key -- @see ZF-2279
                    ksort($calling_args);

                    $result = false;
                    
                    if (count($calling_args) < count($func_args))
                    {
                        require_once 'Zend/Rest/Server/Exception.php';
                        $result = $this->fault(new Zend_Rest_Server_Exception('Invalid Method Call to ' . $this->_method . '. Missing argument(s): ' . implode(', ', $missing_args) . '.'), 400);
                        $failed = true;
                    }

                    if (!$result && $this->_functions[$this->_method] instanceof Zend_Server_Reflection_Method)
                    {
                        // Get class
                        $class = $this->_functions[$this->_method]->getDeclaringClass()->getName();

                        if ($this->_functions[$this->_method]->isStatic())
                        {
                            // for some reason, invokeArgs() does not work the same as
                            // invoke(), and expects the first argument to be an object.
                            // So, using a callback if the method is static.
                            $result = $this->_callStaticMethod($class, $calling_args);
                        }
                        else
                        {
                            // Object method
                            $result = $this->_callObjectMethod($class, $calling_args);
                        }
                    }
                    elseif(!$result)
                    {
                        try {
                            $result = call_user_func_array($this->_functions[$this->_method]->getName(), $calling_args); //$this->_functions[$this->_method]->invokeArgs($calling_args);
                        } catch (Exception $e) {
                            $result = $this->fault($e);
                            $failed = true;
                        }
                    }
                }
                else
                {
                    require_once "Zend/Rest/Server/Exception.php";
                    $result = $this->fault(new Zend_Rest_Server_Exception("Unknown Method '$this->_method'."), 404);
                    $failed = true;
                }
            } else {
                require_once "Zend/Rest/Server/Exception.php";
                $result = $this->fault(new Zend_Rest_Server_Exception("Unknown Method '$this->_method'."), 404);
                $failed = true;
            }
        } else {
            require_once "Zend/Rest/Server/Exception.php";
            $result = $this->fault(new Zend_Rest_Server_Exception("No Method Specified."), 404);
            $failed = true;
        }
        
        // On parse la réponse en json
        $response = Zend_Json::Encode(array(
            'response' => $result,
            'status' => $failed ? 'failed' : 'success'
        ));

        // On gère la fonction returnResponse()
        if (!$this->returnResponse()) {
            if (!headers_sent()) {
                foreach ($this->_headers as $header) {
                    header($header);
                }
            }

            echo $response;
            return;
        }

        // On envoie le json
        return $response;
     }

    /**
     * Implement Zend_Server_Interface::fault()
     *
     * Creates XML error response, returning DOMDocument with response.
     *
     * @param string|Exception $fault Message
     * @param int $code Error Code
     * @return DOMDocument
     */
    public function fault($exception = null, $code = null)
    {
        // Copié collé de Zend_Server_Rest::fault() ...
        if (isset($this->_functions[$this->_method]))
        {
            $function = $this->_functions[$this->_method];
        }
        elseif (isset($this->_method))
        {
            $function = $this->_method;
        }
        else
        {
            $function = 'rest';
        }

        if ($function instanceof Zend_Server_Reflection_Method)
        {
            $class = $function->getDeclaringClass()->getName();
        }
        else
        {
            $class = false;
        }

        if ($function instanceof Zend_Server_Reflection_Function_Abstract)
        {
            $method = $function->getName();
        }
        else
        {
            $method = $function;
        }

        // Message et code d'erreur
        $exception_message = null;
        $code_message = null;
        
        if($exception instanceof Exception)
        {
            $code_message = $exception->getCode();
            $exception_message = $exception->getMessage();
        }
        elseif(($exception !== null) || 'rest' == $function)
        {
            $exception_message = 'An unknown error occured. Please try again.';
        }
        else
        {
            $exception_message = 'Call to ' . $method . ' failed.';
        }

        // Headers to send
        if ($code_message === null || (404 != $code_message))
        {
            $this->_headers[] = 'HTTP/1.0 400 Bad Request';
        }
        else
        {
            $this->_headers[] = 'HTTP/1.0 404 File Not Found';
        }
        
        // On envoie le message d'erreur
        return $exception_message;
    }

    /**
     * Implement Zend_Server_Interface::loadFunctions()
     *
     * @todo Implement
     * @param array $functions
     */
    public function loadFunctions($functions)
    {
        throw new Exception('Not implemented');
    }

    /**
     * Implement Zend_Server_Interface::setPersistence()
     *
     * @todo Implement
     * @param int $mode
     */
    public function setPersistence($mode)
    {
        throw new Exception('Not implemented');
    }
}
