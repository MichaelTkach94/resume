<?php

class UpdateDTO implements UpdateInterface, IdentifyInterface
{
    private int $bitrixUserId;
    private Phone $phone;
    private Email $email;
    private Checkword $checkword;

    public function getBitrixUserId(): int
    {
        return $this->bitrixUserId;
    }

    public function setBitrixUserId(int $bitrixUserId): UpdateDTO
    {
        $this->bitrixUserId = $bitrixUserId;
        return $this;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone): UpdateDTO
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): UpdateDTO
    {
        $this->email = $email;
        return $this;
    }

    public function getCheckword(): Checkword
    {
        return $this->checkword;
    }

    public function setCheckword(Checkword $checkword): UpdateDTO
    {
        $this->checkword = $checkword;
        return $this;
    }
}
