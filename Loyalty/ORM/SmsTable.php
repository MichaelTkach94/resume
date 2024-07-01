<?php

class SmsTable extends DataManager
{
    public const AUTH_SMS_WAITING_TIME = 25;

    public static function getTableName(): string
    {
        return 'ow_sms';
    }

    public static function getMap(): array
    {
        return [
            (new StringField('PHONE'))
                ->configurePrimary(),
            (new StringField('LAST_REQUEST_TIME')),
        ];
    }

    public static function writeRequestTime(IdentifyInterface $dto): void
    {
        if (static::getLastRequestTime($dto)) {
            static::update($dto->getPhone()->getForDb(), [
                'LAST_REQUEST_TIME' => time(),
            ]);
        } else {
            static::add([
                'PHONE'             => $dto->getPhone()->getForDb(),
                'LAST_REQUEST_TIME' => time(),
            ]);
        }
    }

    public static function smsAlreadySent(IdentifyInterface $dto): bool
    {
        return (time() - static::getLastRequestTime($dto)) < static::AUTH_SMS_WAITING_TIME;
    }

    public static function getLastRequestTime(IdentifyInterface $dto): ?int
    {
        return static::query()
            ->addFilter('PHONE', $dto->getPhone()->getForDb())
            ->addSelect('LAST_REQUEST_TIME')
            ->fetchAll()[0]['LAST_REQUEST_TIME'] ?: null;
    }
}
