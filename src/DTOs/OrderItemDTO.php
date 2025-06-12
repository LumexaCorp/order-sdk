<?php

declare(strict_types=1);

namespace Lumexa\OrderSdk\DTOs;

readonly class OrderItemDTO
{
    public function __construct(
        public int $productId,
        public string $productName,
        public ?string $productDescription,
        public int $quantity,
        public float $unitPrice,
        public ?string $sku = null,
        public ?array $options = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['product_id'],
            productName: $data['product_name'],
            productDescription: $data['product_description'] ?? null,
            quantity: $data['quantity'],
            unitPrice: $data['unit_price'],
            sku: $data['sku'] ?? null,
            options: $data['options'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'product_description' => $this->productDescription,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'sku' => $this->sku,
            'options' => $this->options,
        ];
    }

    /**
     * Calculate the total price for this item
     */
    public function getTotalPrice(): float
    {
        return $this->unitPrice * $this->quantity;
    }
}
