<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

use StackSite\Core\SqlHandler;

class TokenPersistence {
    private Token $token;

    public function __construct(
        private readonly TokenFactory $tokenFactory
    ) {
    }

    public function upload(): int
    {
        $query =
            "INSERT INTO token (
                   user_id,
                   token,
                   type,
                   expires_at,
                   created_at
                   ) 
                  VALUES (
                          '" . $this->token->getUserId() . "',
                          '" . SqlHandler::cleanString($this->token->getToken()) . "',
                          '" . SqlHandler::cleanString($this->token->getType()) . "',
                          '" . $this->token->getExpiresAt() . "',
                          " . time() . "
                          )";

        return SqlHandler::insert($query);
    }

    public function fetchByTokenAndType(Token $token): ?Token
    {
        $query = "SELECT * FROM token
                    WHERE token = '" . SqlHandler::cleanString($token->getToken()) . "'
                    AND type = '" . SqlHandler::cleanString($token->getType()) . "'
                    LIMIT 1";

        $result = SqlHandler::fetch($query);

        return !empty($result) ? $this->tokenFactory->fromArray($result[0]) : null;
    }

    public function fetchByUserIdAndType(int $userId, string $type): ?Token
    {
        $query = "SELECT * FROM token
                    WHERE user_id = '" . $userId . "'
                    AND type = '" . SqlHandler::cleanString($type) . "'
                    LIMIT 1";

        $result = SqlHandler::fetch($query);

        return !empty($result) ? $this->tokenFactory->fromArray($result[0]) : null;
    }

    public function deleteAllTokensByUserIdAndType(int $userId, string $type): void
    {
        $query = "DELETE FROM token"
            . " WHERE user_id = '" . $userId . "'"
            . " AND type = '" . SqlHandler::cleanString($type) . "'";

        SqlHandler::update($query);
    }

    public function deleteById(int $tokenId): bool
    {
        $query = "DELETE FROM token WHERE id = '" . $tokenId . "'";

        $result = SqlHandler::update($query);

        return $result > 0;
    }


    public function setToken(Token $token): self
    {
        $this->token = $token;
        return $this;
    }
}