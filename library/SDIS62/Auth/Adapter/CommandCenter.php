<?php

class SDIS62_Auth_Adapter_CommandCenter implements Zend_Auth_Adapter_Interface
{
    /**
     * L'adresse du command center
     *
     * @var string|null
     */
    private $uri_commandcenter;
    
    /**
     * L'email de l'utilisateur à authentifier
     *
     * @var string|null
     */
    private $email;
    
    /**
     * Le mot de passe de l'utilisateur à authentifier
     *
     * @var string|null
     */
    private $password;
    
    /**
     * Constructeur
     *
     * @param string $email
     * @param string $password
     */    
    public function __construct($uri_commandcenter, $email, $password)
    {
        $this->uri_commandcenter = $uri_commandcenter;
        $this->email = $email;
        $this->password = $password;
    }
    
    /**
     * @inheritdoc
     */    
    public function authenticate()
    {
        // Service Command Center
        $service = new SDIS62_Service_Bridge_Generic($this->uri_commandcenter . "/user");

        // Command Center valide moi les identifiants et renvoi l'utilisateur connecté
        $user = $service->verifyCredentials($this->email, $this->password);
        
        try
        {
            if($user !== false)
            {
                return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
            }
            else
            {
                return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null);
            }
        } 
        catch (NonUniqueResultException $e)
        {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null);
        }
        catch (NoResultException $e)
        {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null);
        }
        catch (Exception $e)
        {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null);
        }
    }
} 