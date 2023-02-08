<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chalhoub\ShopFinder\Api\Data;

interface ShopFinderInterface
{

    const IMAGE = 'Image';
    const COUNTRY = 'Country';
    const SHOPFINDER_ID = 'shopfinder_id';
    const IDENTIFIER = 'Identifier';
    const SHOP_NAME = 'shop_name';

    /**
     * Get shopfinder_id
     * @return string|null
     */
    public function getShopfinderId();

    /**
     * Set shopfinder_id
     * @param string $shopfinderId
     * @return \Chalhoub\ShopFinder\ShopFinder\Api\Data\ShopFinderInterface
     */
    public function setShopfinderId($shopfinderId);

    /**
     * Get shop_name
     * @return string|null
     */
    public function getShopName();

    /**
     * Set shop_name
     * @param string $shopName
     * @return \Chalhoub\ShopFinder\ShopFinder\Api\Data\ShopFinderInterface
     */
    public function setShopName($shopName);

    /**
     * Get Identifier
     * @return string|null
     */
    public function getIdentifier();

    /**
     * Set Identifier
     * @param string $identifier
     * @return \Chalhoub\ShopFinder\ShopFinder\Api\Data\ShopFinderInterface
     */
    public function setIdentifier($identifier);

    /**
     * Get Country
     * @return string|null
     */
    public function getCountry();

    /**
     * Set Country
     * @param string $country
     * @return \Chalhoub\ShopFinder\ShopFinder\Api\Data\ShopFinderInterface
     */
    public function setCountry($country);

    /**
     * Get Image
     * @return string|null
     */
    public function getImage();

    /**
     * Set Image
     * @param string $image
     * @return \Chalhoub\ShopFinder\ShopFinder\Api\Data\ShopFinderInterface
     */
    public function setImage($image);
}

