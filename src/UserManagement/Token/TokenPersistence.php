<?php

namespace StackSite\UserManagement\Token;

use StackSite\Core\SqlHandler;

class TokenPersistence {
    private Token $token;

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
                          '" . SqlHandler::cleanString($this->token->getUserId()) . "',
                          '" . SqlHandler::cleanString($this->token->getToken()) . "',
                          '" . SqlHandler::cleanString($this->token->getType()) . "',
                          '" . SqlHandler::cleanString($this->token->getExpiresAt()) . "',
                          " . time() . "
                          )";

        return SqlHandler::insert($query);
    }

    public function setToken(Token $token): self
    {
        $this->token = $token;
        return $this;
    }
}