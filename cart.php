<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function cart_add(int $productId, int $amount = 1): void {
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 0;
    }
    $_SESSION['cart'][$productId] += max(1, $amount);
}

function cart_set(int $productId, int $amount): void {
    if ($amount <= 0) {
        unset($_SESSION['cart'][$productId]);
    } else {
        $_SESSION['cart'][$productId] = $amount;
    }
}

function cart_remove(int $productId): void {
    unset($_SESSION['cart'][$productId]);
}

function cart_clear(): void {
    $_SESSION['cart'] = [];
}

function cart_get_items(): array {
    return $_SESSION['cart'];
}

function cart_get_count(): int {
    return array_sum($_SESSION['cart']);
}
