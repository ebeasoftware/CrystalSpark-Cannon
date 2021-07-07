<?php

namespace CsCannon\Blockchains\Substrate\Kusama;

use CsCannon\Blockchains\Blockchain;

use CsCannon\Blockchains\Substrate\RMRK\RmrkBlockchainOrderProcess;
use CsCannon\Blockchains\Substrate\RMRK\RmrkContractFactory;
use CsCannon\Blockchains\Substrate\SubstrateAddressFactory;
use CsCannon\Blockchains\Substrate\SubstrateBlockchain;
use CsCannon\Blockchains\Substrate\SubstrateContractFactory;
use CsCannon\Blockchains\Substrate\SubstrateEventFactory;

class KusamaBlockchain extends SubstrateBlockchain
{
    protected $name = 'kusama';
    const NAME = 'kusama';
    protected $nameShort = 'ksm';
    private static $staticBlockchain;

    public function __construct()
    {

        $this->orderFactory = new RmrkBlockchainOrderProcess();
        $this->contractFactory = new RmrkContractFactory();     //careful with this
        $this->eventFactory = new KusamaEventFactory();
        $this->addressFactory = new KusamaAddressFactory();
        $this->mainSourceCurrencyTicker = 'KSM';

    }

    public static function getStatic()
    {

        if (is_null(self::$staticBlockchain)){
            self::$staticBlockchain = new KusamaBlockchain();
        }

        return self::$staticBlockchain ;
    }
}
