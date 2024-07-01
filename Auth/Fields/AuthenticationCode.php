<?php

class AuthenticationCode
{
    private string $authenticationCode;

    /**
     * @throws ValidateFieldException
     */
    public function __construct(string $authenticationCode)
    {
        $this->authenticationCode = htmlspecialcharsbx($authenticationCode);

        $this->validate();
    }

    public function getRaw(): string
    {
        return $this->authenticationCode;
    }

    /**
     * @throws ValidateFieldException
     */
    private function validate(): void
    {
        if (empty($this->authenticationCode)) {
            throw new ValidateFieldException(FieldError::CODE, 'Поле не может быть пустым!');
        }
    }
}
