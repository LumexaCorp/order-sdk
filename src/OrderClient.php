<?php

declare(strict_types=1);

namespace Lumexa\OrderSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Lumexa\OrderSdk\Exceptions\OrderException;
use Lumexa\OrderSdk\Exceptions\ValidationException;
use Lumexa\OrderSdk\DTOs\OrderDTO;
use Lumexa\OrderSdk\DTOs\OrderItemDTO;

class OrderClient
{
    private Client $httpClient;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $storeToken,
        ?Client $httpClient = null
    ) {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'X-Store-Token' => $this->storeToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Handle API errors and transform them into appropriate exceptions
     *
     * @throws ValidationException|OrderException
     */
    private function handleApiError(\Throwable $e): never
    {
        if ($e instanceof ClientException) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode((string) $response->getBody(), true);

            if ($statusCode === 422 && isset($body['errors'])) {
                throw new ValidationException(
                    $body['message'] ?? 'Validation failed',
                    $body['errors'],
                    $statusCode,
                    $e
                );
            }

            if (isset($body['message'])) {
                throw new OrderException($body['message'], $statusCode, $e);
            }
        }

        throw new OrderException($e->getMessage(), (int) $e->getCode(), $e);
    }

    /**
     * Create a new order
     *
     * @param array<OrderItemDTO> $items
     * @throws OrderException|ValidationException
     */
    public function createOrder(array $data): OrderDTO
    {
        try {
            $response = $this->httpClient->post('/api/orders', [
                'json' => $data,
            ]);

            $data = json_decode((string) $response->getBody(), true);
            return OrderDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get order by ID
     *
     * @throws OrderException|ValidationException
     */
    public function getOrder(int $orderId): OrderDTO
    {
        try {
            $response = $this->httpClient->get("/api/orders/{$orderId}");
            $data = json_decode((string) $response->getBody(), true);
            return OrderDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get orders by store ID
     *
     * @return array<OrderDTO>
     * @throws OrderException|ValidationException
     */
    public function getStoreOrders(int $storeId): array
    {
        try {
            $response = $this->httpClient->get("/api/orders/store/{$storeId}");
            $data = json_decode((string) $response->getBody(), true);

            return array_map(
                fn (array $orderData) => OrderDTO::fromArray($orderData),
                $data['data'] ?? []
            );
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Update order status
     *
     * @throws OrderException|ValidationException
     */
    public function updateOrderStatus(int $orderId, string $status): OrderDTO
    {
        try {
            $response = $this->httpClient->patch("/api/orders/{$orderId}", [
                'json' => ['status' => $status],
            ]);

            $data = json_decode((string) $response->getBody(), true);
            return OrderDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get all orders
     *
     * @return array<OrderDTO>
     * @throws OrderException|ValidationException
     */
    public function getAllOrders(): array
    {
        try {
            $response = $this->httpClient->get('/api/orders');
            $data = json_decode((string) $response->getBody(), true);
            return array_map(
                fn (array $orderData) => OrderDTO::fromArray($orderData),
                $data['data'] ?? []
            );
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get orders for a specific user
     *
     * @return array<OrderDTO>
     * @throws OrderException|ValidationException
     */
    public function getUserOrders(int $userId): array
    {
        try {
            $response = $this->httpClient->get("/api/orders/user/{$userId}");
            $data = json_decode((string) $response->getBody(), true);
            return array_map(
                fn (array $orderData) => OrderDTO::fromArray($orderData),
                $data['data'] ?? []
            );
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Update an order
     *
     * @param array<string, mixed> $data
     * @throws OrderException|ValidationException
     */
    public function updateOrder(int $orderId, array $data): OrderDTO
    {
        try {
            $response = $this->httpClient->put("/api/orders/{$orderId}", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return OrderDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Add an item to an order
     *
     * @param array<string, mixed> $data
     * @throws OrderException|ValidationException
     */
    public function addOrderItem(int $orderId, array $data): OrderItemDTO
    {
        try {
            $response = $this->httpClient->post("/api/orders/{$orderId}/items", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return OrderItemDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    public function getOrderById(int $orderId): OrderDTO
    {
        try {
            $response = $this->httpClient->get("/api/orders/{$orderId}");
            $data = json_decode((string) $response->getBody(), true);
            return OrderDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }
}
