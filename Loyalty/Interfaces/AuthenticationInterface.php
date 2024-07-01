<?php

interface AuthenticationInterface
{
    public function getPhone(): Phone;

    public function getAuthenticationCode(): AuthenticationCode;
}
