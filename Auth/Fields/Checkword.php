<?php

class Checkword
{
    private string $checkword;

    public function __construct(string $checkword)
    {
        $this->checkword = htmlspecialcharsbx($checkword);

        $this->validate();
    }

    public function getRaw(): string
    {
        return $this->checkword;
    }

    private function validate(): void
    {
        if (empty($this->checkword)) {
            throw new RuntimeException(FieldError::EMAIL, 'Checkword должен быть заполнен!');
        }
    }
}
