<?php

interface RegistrationInterface
{
    public function getBitrixUserId(): int;

    public function getEmail(): Email;

    public function getName(): Name;

    public function getPhone(): Phone;

    public function getSex(): Sex;

    public function isSubscribe(): bool;
}
