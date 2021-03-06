<?php

namespace XBMC\Libraries;

/**
 * @property \XBMC\Server XBMCServer
 */
class APINamespace
{
    /**
     * @var \XBMC\Server
     */
    private $XBMCServer;

    public function __construct( \XBMC\Server $xbmcServer )
    {
        $this->XBMCServer = $xbmcServer;
    }

    public function __get( $key )
    {
        // only predefined properties or API Method are available for this
        // object
        if( isset( $this->$key ))
        {
            return $this->$key;
        }
        elseif( class_exists( get_called_class() . "\\" . "{$key}" ) )
        {
            $classname = get_called_class() . "\\{$key}";
            $object = new $classname( $this->XBMCServer );
            if( is_subclass_of( $object, __NAMESPACE__ ."\\APIMethod" ) )
            {
                $this->$key = $object;
                return $this->$key;
            }
        }
        throw new \Exception( "{$key} is not accessible in " . __CLASS__ . " or is not a valid API Method" );
    }
}

?>
