<?php

class VerificationDTO implements \JsonSerializable
{
    private bool $isVerified;
    private bool $hasLoyaltyAccount;
    private bool $showPhoneCodeField;

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): VerificationDTO
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function isHasLoyaltyAccount(): bool
    {
        return $this->hasLoyaltyAccount;
    }

    public function setHasLoyaltyAccount(bool $hasLoyaltyAccount): VerificationDTO
    {
        $this->hasLoyaltyAccount = $hasLoyaltyAccount;
        return $this;
    }

    public function isShowPhoneCodeField(): bool
    {
        return $this->showPhoneCodeField;
    }

    public function setShowPhoneCodeField(bool $showPhoneCodeField): VerificationDTO
    {
        $this->showPhoneCodeField = $showPhoneCodeField;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'isVerified'         => $this->isVerified,
            'hasLoyaltyAccount'  => $this->hasLoyaltyAccount,
            'showPhoneCodeField' => $this->showPhoneCodeField,
        ];
    }
}
