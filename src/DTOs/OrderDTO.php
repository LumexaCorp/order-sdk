<?php

declare(strict_types=1);

namespace Lumexa\OrderSdk\DTOs;

readonly class OrderDTO
{
    /**
     * @param array<OrderItemDTO> $items
     */
    public function __construct(
        public int $id,
        public int $storeId,
        public array $items,
        public string $status,
        public float $totalAmount,
        public ?string $customerName = null,
        public ?string $customerEmail = null,
        public ?string $customerPhone = null,
        public ?string $shippingAddress = null,
        public ?string $billingAddress = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            storeId: $data['store_id'],
            items: array_map(
                fn (array $item) => OrderItemDTO::fromArray($item),
                $data['items'] ?? []
            ),
            status: $data['status'],
            totalAmount: $data['total_amount'],
            customerName: $data['customer_name'] ?? null,
            customerEmail: $data['customer_email'] ?? null,
            customerPhone: $data['customer_phone'] ?? null,
            shippingAddress: $data['shipping_address'] ?? null,
            billingAddress: $data['billing_address'] ?? null,
            createdAt: isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            updatedAt: isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->storeId,
            'items' => array_map(fn (OrderItemDTO $item) => $item->toArray(), $this->items),
            'status' => $this->status,
            'total_amount' => $this->totalAmount,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'shipping_address' => $this->shippingAddress,
            'billing_address' => $this->billingAddress,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
