<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2019
 * Time: 14:42
 */

namespace CsCannon\Blockchains\Counterparty;





use CsCannon\AssetCollectionFactory;
use CsCannon\AssetFactory;
use CsCannon\Blockchains\Blockchain;
use CsCannon\Blockchains\BlockchainEventFactory;
use CsCannon\Blockchains\Ethereum\EthereumEvent;
use CsCannon\SandraManager;

class XcpEventFactory extends BlockchainEventFactory
{


    public static $className = 'CsCannon\Blockchains\Counterparty\XcpEvent' ;
    public static $isa = 'blockchainEvent';

   // protected static $isa = 'blockchainEvent';



    public function __construct(){

        //Verify data validity


        parent::__construct();

        $this->generatedEntityClass = static::$className ;
        $this->setFilter(self::ON_BLOCKCHAIN_EVENT,XcpBlockchain::NAME);

        $this->addressFactory = new XcpAddressFactory();
        $this->contractFactory = new XcpContractFactory();



    }

    public function populateLocal($limit = 1000, $offset = 0, $asc = 'DESC',$sortByRef = null, $numberSort = false)
    {

        $return = parent::populateLocal($limit, $offset, $asc, $sortByRef, $numberSort);

        $sandra = SandraManager::getSandra();

        /** @var Blockchain $blockchain */




        $this->joinFactory(self::EVENT_SOURCE_ADDRESS,$this->addressFactory);
        $this->joinFactory(self::EVENT_DESTINATION_VERB,$this->addressFactory);
        $this->joinFactory(self::EVENT_CONTRACT,$this->contractFactory);

        $this->joinPopulate();

        $assetFactory = new AssetFactory($sandra);
        $assetCollection = new AssetCollectionFactory($sandra);





        //on counterparty we join the asset factory to the contract
        //$contractFactory->joinAsset($assetFactory);
        $this->contractFactory->joinPopulate();


        $assetFactory->getTriplets();
        $assetFactory->joinCollection($assetCollection);

        $assetFactory->joinPopulate();

        $this->populateBrotherEntities(self::EVENT_CONTRACT);



        return $return ;
    }











}