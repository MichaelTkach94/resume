<?php

class ConfirmVerificationCodeDTO implements AuthenticationInterface, IdentifyInterface
{
    private AuthenticationCode $authenticationCode;
    private Phone $phone;

    public function getAuthenticationCode(): AuthenticationCode
    {
        return $this->authenticationCode;
    }

    public function setAuthenticationCode(AuthenticationCode $authenticationCode): ConfirmVerificationCodeDTO
    {
        $this->authenticationCode = $authenticationCode;
        return $this;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone): ConfirmVerificationCodeDTO
    {
        $this->phone = $phone;
        return $this;
    }
}
