<?php

class Sex
{
    public const MALE = 'M';
    public const FEMALE = 'F';

    private string $sex;

    /**
     * @throws ValidateFieldException
     */
    public function __construct(string $sex)
    {
        $this->sex = htmlspecialcharsbx(strtoupper($sex));

        $this->validate();
    }

    public function getRaw(): string
    {
        return $this->sex;
    }

    public function getForMindbox(): string
    {
        return ($this->getRaw() === static::MALE) ? 'male' : 'female';
    }

    public function getPublicName(): string
    {
        return ($this->getRaw() === static::MALE) ? 'Мужской' : 'Женский';
    }

    /**
     * @throws ValidateFieldException
     */
    private function validate(): void
    {
        if (!in_array($this->getRaw(), [static::MALE, static::FEMALE])) {
            throw new ValidateFieldException(FieldError::SEX, "Неизвестный гендер - {$this->getRaw()}");
        }
    }
}
