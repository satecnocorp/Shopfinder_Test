<?php
namespace Chalhoub\Shopfinder\Api\Data;

/**
 * Shop interface.
 * @api
 * @since 100.0.2
 */
interface ShopInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const SHOP_ID      = 'shop_id';
    const NAME         = 'name';
    const IDENTIFIER    = 'identifier';
    const COUNTRY    = 'country';
    const IMAGE    = 'image';
    const GEOLOCATION  = 'geolocation';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const STATUS     = 'status';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();
    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();
    
    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry();

    /**
     * Get image
     *
     * @return string
     */
    public function getImage();

    /**
     * Get geolocation
     *
     * @return string
     */
    public function getGeolocation();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Set ID
     *
     * @param int $id
     * @return ShopInterface
     */
    public function setId($id);

    /**
     * Set title
     *
     * @param string $title
     * @return ShopInterface
     */
    public function setName($name);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return ShopInterface
     */
    public function setIdentifier($identifier);

        /**
     * Set country
     *
     * @param string $country
     * @return ShopInterface
     */
    public function setCountry($country);

    /**
     * Set image
     *
     * @param string $image
     * @return ShopInterface
     */
    public function setImage($image);

    /**
     * Set geolocation
     *
     * @param string $geolocation
     * @return ShopInterface
     */
    public function setGeolocation($geolocation);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return ShopInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return ShopInterface
     */
    public function setUpdateTime($updateTime);

}
