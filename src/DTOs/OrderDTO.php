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
        public int $user_id,
        public string $status,
        public array $shipping_address,
        public array $billing_address,
        public ?string $notes,
        public array $items,
        public int $total_items,
        public float $total_price,
        public \DateTimeImmutable $created_at,
        public \DateTimeImmutable $updated_at,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            user_id: $data['user_id'],
            items: array_map(
                fn (array $item) => OrderItemDTO::fromArray($item),
                $data['items'] ?? []
            ),
            status: $data['status'],
            shipping_address: $data['shipping_address'],
            billing_address: $data['billing_address'],
            notes: $data['notes'],
            total_items: (int) ($data['total_items'] ?? 0),
            total_price: (float) ($data['total_price'] ?? 0),
            created_at: isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'items' => array_map(fn (OrderItemDTO $item) => $item->toArray(), $this->items),
            'status' => $this->status,
            'total_price' => $this->total_price,
            'user_id' => $this->user_id,
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'notes' => $this->notes,
            'total_items' => $this->total_items,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
