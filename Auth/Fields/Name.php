<?php

class Name
{
    private string $firstName;
    private string $lastName;
    private ?string $patronymic;

    /**
     * @throws ValidateFieldException
     */
    public function __construct(string $firstName, string $lastName, ?string $patronymic = null)
    {
        $this->firstName = htmlspecialcharsbx($firstName);
        $this->lastName = htmlspecialcharsbx($lastName);
        $this->patronymic = htmlspecialcharsbx($patronymic) ?: null;

        $this->validate();
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    /**
     * @throws ValidateFieldException
     */
    private function validate(): void
    {
        if (empty($this->firstName)) {
            throw new ValidateFieldException(FieldError::FIRST_NAME, 'Поле не может быть пустым!');
        }
        if (empty($this->lastName)) {
            throw new ValidateFieldException(FieldError::LAST_NAME, 'Поле не может быть пустым!');
        }
    }
}
