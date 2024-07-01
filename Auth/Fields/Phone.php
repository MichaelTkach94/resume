<?php

class Phone
{
    private string $phone;

    /**
     * @throws ValidateFieldException
     */
    public function __construct(string $phone)
    {
        $this->phone = htmlspecialcharsbx($phone);

        $this->validate();
    }

    public function getRaw(): string
    {
        return $this->phone;
    }

    public function getForMindbox(): string
    {
        Loader::requireModule('mindbox.marketing');

        return Helper::formatPhone(
            $this->getRaw()
        );
    }

    public function getForDb(): string
    {
        return PhoneFormatter::formatForDb(
            $this->getRaw()
        );
    }

    /**
     * @throws ValidateFieldException
     */
    private function validate(): void
    {
        if (strlen($this->getForDb()) < 10) {
            throw new ValidateFieldException(FieldError::PHONE, 'Номер телефона в формате +7 (nnn) nnn-nn-nn');
        }
    }
}
