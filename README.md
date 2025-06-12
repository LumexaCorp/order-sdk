# Order SDK Lumexa

Ce SDK fournit une interface pour gérer les commandes dans la plateforme Lumexa.

## Installation

```bash
composer require lumexa/order-sdk
```

## Configuration

Pour utiliser le SDK, vous devez d'abord initialiser le client avec votre token de store :

```php
use Lumexa\OrderSdk\OrderClient;

$orderClient = new OrderClient(
    baseUrl: 'https://api.lumexa.com',
    storeToken: 'votre-store-token'
);
```

## Fonctionnalités disponibles

### Gestion des commandes

#### Créer une nouvelle commande
```php
use Lumexa\OrderSdk\DTOs\OrderItemDTO;

$items = [
    new OrderItemDTO([
        'product_id' => 1,
        'quantity' => 2,
        'price' => 99.99,
        'variant_id' => 3 // Optionnel
    ]),
    new OrderItemDTO([
        'product_id' => 2,
        'quantity' => 1,
        'price' => 49.99
    ])
];

$order = $orderClient->createOrder(
    storeId: 1,
    items: $items
);
```

#### Obtenir une commande par son ID
```php
$order = $orderClient->getOrder(orderId: 123);
```

#### Obtenir toutes les commandes d'une boutique
```php
$orders = $orderClient->getStoreOrders(storeId: 1);
```

#### Obtenir toutes les commandes
```php
$orders = $orderClient->getAllOrders();
```

#### Obtenir les commandes d'un utilisateur
```php
$orders = $orderClient->getUserOrders(userId: 123);
```

#### Mettre à jour le statut d'une commande
```php
$updatedOrder = $orderClient->updateOrderStatus(
    orderId: 123,
    status: 'processing' // Exemple de statut
);
```

#### Mettre à jour une commande
```php
$updatedOrder = $orderClient->updateOrder(
    orderId: 123,
    data: [
        'status' => 'processing',
        'customer_name' => 'John Doe',
        'customer_email' => 'john@example.com',
        // autres champs à mettre à jour
    ]
);
```

#### Ajouter un article à une commande
```php
$orderItem = $orderClient->addOrderItem(
    orderId: 123,
    data: [
        'product_id' => 456,
        'quantity' => 1,
        'unit_price' => 99.99,
        'product_name' => 'Nom du produit',
        'sku' => 'SKU123', // optionnel
        'options' => [] // optionnel
    ]
);
```

## Gestion des erreurs

Le SDK utilise deux types d'exceptions pour la gestion des erreurs :

### OrderException
Exception générale pour les erreurs liées aux commandes :

```php
use Lumexa\OrderSdk\Exceptions\OrderException;

try {
    $order = $orderClient->getOrder(123);
} catch (OrderException $e) {
    // Gérer l'erreur
    echo $e->getMessage();
}
```

### ValidationException
Exception spécifique pour les erreurs de validation, avec accès aux détails des erreurs :

```php
use Lumexa\OrderSdk\Exceptions\ValidationException;

try {
    $order = $orderClient->createOrder(
        storeId: 1,
        items: $items
    );
} catch (ValidationException $e) {
    // Accéder aux erreurs de validation
    $errors = $e->getErrors();
    foreach ($errors as $field => $messages) {
        echo "Erreur pour {$field}: " . implode(', ', $messages);
    }
} catch (OrderException $e) {
    // Gérer les autres erreurs
    echo $e->getMessage();
}
```

## Structure des données

### OrderDTO
Le DTO `OrderDTO` contient les informations suivantes :
- `id` : Identifiant unique de la commande
- `store_id` : Identifiant de la boutique
- `status` : Statut de la commande
- `items` : Liste des articles commandés (tableau de `OrderItemDTO`)
- `total` : Montant total de la commande
- `created_at` : Date de création
- `updated_at` : Date de dernière modification

### OrderItemDTO
Le DTO `OrderItemDTO` contient les informations suivantes :
- `product_id` : Identifiant du produit
- `variant_id` : Identifiant de la variante (optionnel)
- `quantity` : Quantité commandée
- `price` : Prix unitaire
- `total` : Prix total pour cet article
