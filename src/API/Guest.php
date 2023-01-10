<?php

namespace Nihilsen\BoxBilling\API;

/**
 * @method array{id: int, email: string, name: string, role: string} client_login(string $email, string $password)
 * @method array{id: int, title: string, slug: string, description: string, pricing: array} product_get(int $id, string $slug)
 * @method string support_ticket_create(string $name, string $email, string $subject, string $message) Submit new public ticket
 * @method array{hash: string, author: array{name: string, email: string}, messages: array<array{content: string, author: array{name: string, email: string}, created_at: string, updated_at: string}>} support_ticket_get(string $hash)
 * @method string support_ticket_reply(string $hash, string $message)
 * @method string system_version() Get BoxBilling version
 */
class Guest extends API
{
    //
}
