<?php

namespace CsCannon\Blockchains;

use CsCannon\AssetCollection;
use CsCannon\AssetCollectionFactory;
use CsCannon\AssetSolvers\LocalSolver;
use CsCannon\Blockchains\BlockchainAddressFactory;

use CsCannon\Blockchains\Interfaces\UnknownStandard;
use CsCannon\BlockchainStandardFactory;
use CsCannon\SandraManager;
use SandraCore\CommonFunctions;
use SandraCore\Entity;
use SandraCore\EntityFactory;
use SandraCore\ForeignEntity;
use SandraCore\ForeignEntityAdapter;

class BlockchainContractFactory extends EntityFactory
{

const TOKENID = 'tokenId';
public static $file = 'blockchainContractFile';
protected static $isa = null;
protected static $className = 'CsCannon\Blockchains\Generic\GenericContract';
public const  MAIN_IDENTIFIER = 'id';
public  const JOIN_COLLECTION = 'inCollection';
public  const JOIN_ASSET = 'joinAsset';
public  const CONTRACT_STANDARD = 'contractStandard';
public  const ON_BLOCKCHAIN_VERB = 'onBlockchain';
public  const DECIMALS = 'decimals';


public $blockchain ;

    public function __construct(){

        parent::__construct(static::$isa,static::$file,SandraManager::getSandra());



        $this->generatedEntityClass = static::$className ;


    }

    public static function getContract($identifier,$autocreate = false,BlockchainContractStandard $standard = null){

        $factoryOfSelf = new static();

        return $factoryOfSelf->get($identifier,$autocreate,$standard);


    }


    public function populateFromCollection(AssetCollection $collection,$limit = 10000, $offset = 0, $asc = 'ASC')
    {

        $this->setFilter(self::JOIN_COLLECTION,$collection);

        return $this->populateLocal($limit, $offset, $asc);


    }


    public function populateLocal($limit = 1000, $offset = 0, $asc = 'DESC',$sortByRef = null, $numberSort = false)
    {

        $return = parent::populateLocal($limit, $offset, $asc, $sortByRef, $numberSort);

        $standardFactory = new BlockchainStandardFactory(SandraManager::getSandra());
        $collectionFactory = AssetCollectionFactory::getStaticCollection();

        $this->populateBrotherEntities(self::CONTRACT_STANDARD);
        $this->populateBrotherEntities(self::ON_BLOCKCHAIN_VERB);



        $this->joinFactory(static::JOIN_COLLECTION,$collectionFactory);
        $this->joinFactory(self::CONTRACT_STANDARD,$standardFactory);
        $this->joinPopulate();

        return $return ;


    }

    public function get($identifier,$autoCreate=false,BlockchainContractStandard $contractStandard = null):?BlockchainContract
    {

        $return = $this->first(self::MAIN_IDENTIFIER,$identifier);
        /** @var BlockchainContract $return */



        $identifierName = self::MAIN_IDENTIFIER;
        $entity = $this->first($identifierName,$identifier);



        if ($contractStandard == null){

            $contractStandard = UnknownStandard::init();
        }

        $contractStandardEnt = $contractStandard::getEntity();




        $foreignAdapter = new ForeignEntityAdapter(null,'',SandraManager::getSandra());


        if(is_null($entity) && !$autoCreate){
            if (is_null(static::$isa)) return null ; //in case we don't have a blockchain and the contract doens't exists
            $refConceptId = CommonFunctions::somethingToConceptId(static::$isa,SandraManager::getSandra());
            $entity = new static::$className("foreignContract:$identifier",array($identifierName => $identifier),$foreignAdapter,$this->entityReferenceContainer, $this->entityContainedIn, "foreign$identifier",$this->system);
            $this->addNewEtities($entity,array($refConceptId=>$entity));

            //dd($entity);

        }

        if(is_null($entity) && $autoCreate){

            if(empty($identifier)){
                die("empty identifier");

            }

            $testVariable = $this->blockchain::NAME ;


            $timstamp['creationTimestamp'] = time();
            $array = [self::CONTRACT_STANDARD => [$contractStandardEnt->subjectConcept->idConcept=>$timstamp],
                self::ON_BLOCKCHAIN_VERB => [$this->blockchain::NAME => ['creationTimestamp'=>time()]]
                ];


            $entity = $this->createNew(array(self::MAIN_IDENTIFIER=>$identifier),
                $array
            );



            //dd($entity);

        }

        return $entity ;


    }











}
