<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2019
 * Time: 14:42
 */

namespace CsCannon\Blockchains;



abstract class Blockchain
{

   protected $name ;
   public static $blockchainConceptName = 'blockchain';
   public static $txidConceptName = 'txHash' ;
   public static $provider_opensea_enventId = 'openSeaId' ;

    public  $mainSourceCurrencyTicker = 'NULL' ;

    const NAME = 'genericBlockchain';
    const NETWORK_NAME = 'main';

   public  $addressFactory ;
    public  $contractFactory ;
    public  $eventFactory ;
    public  $blockFactory ;
    public $orderProcess;
    public static $network = array("null"=>array("explorer_tx"=>'null'));

    /**
     * @return BlockchainAddressFactory
     */
    public function getAddressFactory()
    {

        return $this->addressFactory;

    }

    public function getContractFactory():BlockchainContractFactory
    {

        return $this->contractFactory;

    }

    public function getEventFactory():BlockchainEventFactory
    {

        return $this->eventFactory;

    }

    public function getBlockFactory()
    {

       return new BlockchainBlockFactory($this);

    }

    public function getOrderFactory()
    {
        return $this->orderFactory;
    }

    public function getMainCurrencyTicker():string
    {

        return $this->mainSourceCurrencyTicker ;

    }

    public static function getNetworkData($network,$data)
    {

        if (isset(static::$network[$network][$data])){

            return static::$network[$network][$data] ;
        }

        return null ;


    }





}