<?php

class OnLoginDTO
{
    private Email $email;

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): OnLoginDTO
    {
        $this->email = $email;
        return $this;
    }
}
