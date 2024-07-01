<?php

class GetVerificationCodeDTO implements IdentifyInterface
{
    private Phone $phone;

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone): GetVerificationCodeDTO
    {
        $this->phone = $phone;
        return $this;
    }
}
