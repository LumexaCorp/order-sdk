<?php

declare(strict_types=1);

namespace Lumexa\OrderSdk\DTOs;

readonly class OrderItemDTO
{
    public function __construct(
        public int $id,
        public int $order_id,
        public int $quantity,
        public float $unit_price,
        public float $total_price,
        public array $attributes,
        public readonly ?ProductVariantDTO $product_variant,
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
            order_id: $data['order_id'],
            quantity: $data['quantity'],
            unit_price: (float) $data['unit_price'],
            total_price: (float) $data['total_price'],
            attributes: $data['attributes'],
            product_variant: isset($data['product_variant']) ? ProductVariantDTO::fromArray($data['product_variant']) : null,
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
            'order_id' => $this->order_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
            'attributes' => $this->attributes,
            'product_variant' => $this->product_variant,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
