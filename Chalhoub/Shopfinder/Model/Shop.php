<?php
namespace Chalhoub\Shopfinder\Model;

use Chalhoub\Shopfinder\Api\Data\ShopInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Validation\ValidationException;
use Magento\Framework\Validator\HTML\WYSIWYGValidatorInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Backend\Model\Validator\UrlKey\CompositeUrlKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Directory\Model\Country as CountryModel;

/**
 * Shopfinder shop model
 *
 * @method Shop setStoreId(int $storeId)
 * @method int getStoreId()
 */
class Shop extends AbstractModel implements ShopInterface, IdentityInterface
{

    /**
     * Shop cache tag
     */
    public const CACHE_TAG = 'shopfinder_sh';

    /**#@+
     * Shop's statuses
     */
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;
    /**
     * @var Magento\Directory\Model\Country
     */
    protected $countryModel;
    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'shopfinder_shop';

    /**
     * @var WYSIWYGValidatorInterface
     */
    private $wysiwygValidator;

    /**
     * @var CompositeUrlKey
     */
    private $compositeUrlValidator;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     * @param WYSIWYGValidatorInterface|null $wysiwygValidator
     * @param CompositeUrlKey|null $compositeUrlValidator
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = [],
        ?WYSIWYGValidatorInterface $wysiwygValidator = null,
        CompositeUrlKey $compositeUrlValidator = null,
        CountryModel $countryModel
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->wysiwygValidator = $wysiwygValidator
            ?? ObjectManager::getInstance()->get(WYSIWYGValidatorInterface::class);
        $this->compositeUrlValidator = $compositeUrlValidator
            ?? ObjectManager::getInstance()->get(CompositeUrlKey::class);
            $this->countryModel=$countryModel;
    }

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Chalhoub\Shopfinder\Model\ResourceModel\Shop::class);
    }

    /**
     * Prevent shops recursion
     *
     * @return AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        if ($this->hasDataChanges()) {
            $this->setUpdateTime(null);
        }

      /*  $needle = 'shop_id="' . $this->getId() . '"';
        $content = ($this->getContent() !== null) ? $this->getContent() : '';
        if (strpos($content, $needle) !== false) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Make sure that static block content does not reference the block itself.')
            );
        }

        $errors = $this->compositeUrlValidator->validate($this->getIdentifier());
        if (!empty($errors)) {
            throw new LocalizedException($errors[0]);
        }

        parent::beforeSave();

        //Validating HTML content.
        if ($content && $content !== $this->getOrigData(self::CONTENT)) {
            try {
                $this->wysiwygValidator->validate($content);
            } catch (ValidationException $exception) {
                throw new ValidationException(
                    __('Content field contains restricted HTML elements. %1', $exception->getMessage()),
                    $exception
                );
            }
        }
*/
        return $this;
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    /**
     * Retrieve shop id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::SHOP_ID);
    }

    /**
     * Retrieve shop name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Retrieve shop identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return (string)$this->getData(self::IDENTIFIER);
    }

     /**
     * Retrieve shop country
     *
     * @return string
     */
    public function getCountry()
    {
        return (string)$this->getData(self::COUNTRY);
    }

     /**
     * Retrieve shop image
     *
     * @return string
     */
    public function getImage()
    {
        return (string)$this->getData(self::IMAGE);
    }

    /**
     * Retrieve shop geolocation 
     *
     * @return string
     */
    public function getGeolocation()
    {
        return (string)$this->getData(self::GEOLOCATION);
    }
    /**
     * Retrieve shop creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Retrieve shop update time
     *
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return ShopInterface
     */
    public function setId($id)
    {
        return $this->setData(self::SHOP_ID, $id);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ShopInterface
     */
    public function setName($name)
    {
        //TODO validate input value
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return ShopInterface
     */
    public function setIdentifier($identifier)
    {
        //TODO validate input value
        return $this->setData(self::IDENTIFIER, $identifier);
    }
    
    /**
     * Set country
     *
     * @param string $country
     * @return ShopInterface
     */
    public function setCountry($country)
    {
        $cCollection=$this->countryModel->getCollection();
        $allCountryIds=array();
        foreach($cCollection as $c)
        {
        array_push($allCountryIds,$c->getCountryId());
        }
        if(!in_array($country,$allCountryIds))
        {
            throw new ValidationException(
                __('Invalid Country Code, Must follow ISO 3361-1')
            );

        }
        //TODO Improve this validation!!!
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * Set image
     *
     * @param string $image
     * @return ShopInterface
     */
    public function setImage($image)
    { 
        //TODO validate input value
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Set geolocation
     *
     * @param string $geolocation
     * @return ShopInterface
     */
    public function setGeolocation($geolocation)
    {
        //TODO validate input value
        return $this->setData(self::GEOLOCATION, $geolocation);
    }


    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return ShopInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return ShopInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Set is active
     *
     * @param bool|int $status
     * @return ShopInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Prepare Shop's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
