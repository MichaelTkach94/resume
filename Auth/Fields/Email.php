<?php

class Email
{
    private string $email;

    /**
     * @throws ValidateFieldException
     */
    public function __construct(string $email)
    {
        $email = htmlspecialcharsbx($email);
        $this->email = str_replace('%40', '@', $email);

        $this->validate();
    }

    public function getRaw(): string
    {
        return $this->email;
    }

    /**
     * @throws ValidateFieldException
     */
    private function validate(): void
    {
        if (empty($this->email)) {
            throw new ValidateFieldException(FieldError::EMAIL, 'Email должен быть заполнен');
        }

        if (!check_email($this->email, true)) {
            throw new ValidateFieldException(FieldError::EMAIL, 'Email введен неверно');
        }
    }
}
