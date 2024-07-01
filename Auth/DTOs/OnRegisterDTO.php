<?php

class OnRegisterDTO implements RegistrationInterface, AuthenticationInterface, IdentifyInterface
{
    private int $bitrixUserId;
    private Email $email;
    private Phone $phone;
    private Name $name;
    private Sex $sex;
    private bool $subscribe;
    private AuthenticationCode $authenticationCode;

    public function getBitrixUserId(): int
    {
        return $this->bitrixUserId;
    }

    public function setBitrixUserId(int $bitrixUserId): OnRegisterDTO
    {
        $this->bitrixUserId = $bitrixUserId;
        return $this;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): OnRegisterDTO
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone): OnRegisterDTO
    {
        $this->phone = $phone;
        return $this;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): OnRegisterDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getSex(): Sex
    {
        return $this->sex;
    }

    public function setSex(Sex $sex): OnRegisterDTO
    {
        $this->sex = $sex;
        return $this;
    }

    public function isSubscribe(): bool
    {
        return $this->subscribe;
    }

    public function setSubscribe(bool $subscribe): OnRegisterDTO
    {
        $this->subscribe = $subscribe;
        return $this;
    }

    public function getAuthenticationCode(): AuthenticationCode
    {
        return $this->authenticationCode;
    }

    public function setAuthenticationCode(AuthenticationCode $authenticationCode): OnRegisterDTO
    {
        $this->authenticationCode = $authenticationCode;
        return $this;
    }
}
