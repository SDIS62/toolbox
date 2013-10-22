<?php

class SDIS62_Db_Type_UUID extends Doctrine\DBAL\Types\Type
{
    const BINARY = 'binary';
    
    /**
     * {@inheritdoc}
     */
    public function getSqlDeclaration(array $fieldDeclaration, Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return sprintf('BINARY(%d)', $fieldDeclaration['length']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {       
        return self::BINARY;
    }   
    
    /**
     * {@inheritdoc}
     */
    public function convertToPhpValue($value, Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        if ($value !== null)
        {
            $value= unpack('H*', $value);
            $hash = array_shift($value);
            
            $uuid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) .
                '-' . substr($hash, 16, 4) . '-' . substr($hash, 20, 12);
                Zend_Debug::Dump($uuid);
            return $uuid;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        if ($value !== null)
        {
            Zend_Debug::Dump(pack('H*', str_replace('-', '', $value)));
            return pack('H*', str_replace('-', '', $value));
            // return "0x" . str_replace('-', '', $value);
        }
    }
}