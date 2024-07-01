<?php

class LoyaltyResult
{
    private ?int $bitrixUserId;

    public function getBitrixUserId(): ?int
    {
        return $this->bitrixUserId;
    }

    public function setBitrixUserId(int $bitrixUserId): LoyaltyResult
    {
        $this->bitrixUserId = $bitrixUserId;
        return $this;
    }
}
