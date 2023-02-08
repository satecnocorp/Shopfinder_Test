
    ``chalhoub/module-shopfinder``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Chalhoub ShopFinder Module

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Chalhoub`
 - Enable the module by running `php bin/magento module:enable Chalhoub_ShopFinder`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration
   
 You should be able to see the ShopFinder module in admin at Content > ShopFinder

## GraphQl Sample Request 
  ### Endpoint -> http://magento2.local/graphql
  
 - To fetch all the shops
    ```
    {
       fetchShop(
        pageSize: 10
        currentPage: 1
      ) {
        total_count
        items {
          shop_name
          Identifier
          Country
          Image
        }
      }
    }
    ```

- To fetch a single Shop using Identifier
   ```
    {
      fetchShop(
        filter: { Identifier: { like: "test" } }
        pageSize: 10
        currentPage: 1
      ) {
        total_count
        items {
          shop_name
          Identifier
          Country
          Image
        }
      }
    }
    
    ```
 - To Update Shop
   ```
   mutation {
    updateShop(
        id: 2,  
        input: {
          shop_name: "Chalhoub Store"
          Identifier: "new-identifier"
        }
      ) {
        shop {
          shop_name
          Identifier
        }
      }
   }
   
   ``` 
 - To Create Shop
   ```
   mutation {
      createShop(
        input: {
          shop_name: "Ilyas big Store"
          Identifier: "snicestftssss"
          Country: "AQ"
          Image: "test.png"

        }
      ) {
        shop {
          shop_name
        }
      }
   }

   ``` 
 - To delete a Shop
  
  ```
  mutation {
  deleteShop(id: 2)
  } 
  ```  
   




