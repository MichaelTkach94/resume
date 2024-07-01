<?php

class LoyaltyService
{
    private MindboxWrapper $mindbox;

    /**
     * @throws LoaderException
     */
    public function __construct()
    {
        Loader::requireModule('mindbox.marketing');

        $this->mindbox = new MindboxWrapper();
    }

    /**
     * @throws UserNotFoundException
     * @throws CustomerNotFoundException
     */
    public function checkBeforeAuthorize(IdentifyInterface $dto): void
    {
        $response = $this->mindbox::checkCustomerByMobilePhone($dto);

        if ($response->getCustomer()->getProcessingStatus() === CustomEvent::CUSTOMER_NOT_FOUND) {
            throw new CustomerNotFoundException("Пользователь с номером {$dto->getPhone()->getRaw()} не найден!");
        }

        $bitrixUserId = $response->getCustomer()->getId(CustomEvent::getNameForBxUserIdField());
        if (empty($bitrixUserId)) {
            throw new UserNotFoundException("У пользователя с номером {$dto->getPhone()->getRaw()} не указан ID bitrix!");
        }
    }

    /**
     * @throws UserAlreadyAddedException
     */
    public function checkBeforeRegister(IdentifyInterface $dto): void
    {
        $customer = $this->mindbox::checkCustomerByMobilePhone($dto)->getCustomer();
        if ($customer->getProcessingStatus() === CustomEvent::CUSTOMER_FOUND) {
            $bitrixUserId = $customer->getId(CustomEvent::getNameForBxUserIdField());
            if (!empty($bitrixUserId)) {
                throw new UserAlreadyAddedException(
                    "Пользователь с номером {$dto->getPhone()->getRaw()} уже зарегистрирован в bitrix под ID - {$bitrixUserId}"
                );
            }
        }
    }

    public function sendAuthorizationCode(IdentifyInterface $dto): void
    {
        $smsAlreadySent = SmsTable::smsAlreadySent($dto);
        if ($smsAlreadySent) {
            throw new SendAuthenticationCodeException("На номер {$dto->getPhone()->getRaw()} уже отправлено sms!");
        }

        $this->mindbox::sendMobilePhoneAuthorizationCode($dto);
        SmsTable::writeRequestTime($dto);
    }

    public function sendRegistrationCode(IdentifyInterface $dto): void
    {
        $smsAlreadySent = SmsTable::smsAlreadySent($dto);
        if ($smsAlreadySent) {
            throw new SendAuthenticationCodeException("На номер {$dto->getPhone()->getRaw()} уже отправлено sms!");
        }

        $this->mindbox::sendMobilePhoneRegistrationCode($dto);
        SmsTable::writeRequestTime($dto);
    }

    /**
     * @throws ConfirmationException
     */
    public function confirmPhoneBeforeAuthorize(AuthenticationInterface $dto): LoyaltyResult
    {
        $response = $this->mindbox::checkMobilePhoneAuthorizationCode($dto);

        if ($response->getMindboxStatus() !== CustomEvent::SUCCESS) {
            throw new ConfirmationException("Неверный код подтверждения для номера {$dto->getPhone()->getRaw()}");
        }

        $bitrixUserId = $response->getCustomerIdentity()->getId(CustomEvent::getNameForBxUserIdField());

        return (new LoyaltyResult())->setBitrixUserId($bitrixUserId);
    }

    /**
     * @throws ConfirmationException
     */
    public function confirmPhoneBeforeRegisterFirstStep(AuthenticationInterface $dto): void
    {
        $response = $this->mindbox::checkMobilePhoneRegistrationCode($dto);

        if ($response->getSmsConfirmation()->getProcessingStatus() !== CustomEvent::PHONE_CONFIRMED) {
            throw new ConfirmationException("Неверный код подтверждения для номера {$dto->getPhone()->getRaw()}");
        }
    }

    public function confirmPhoneBeforeRegisterSecondStep(AuthenticationInterface $dto): void
    {
        $response = $this->mindbox::checkMobilePhoneRegistrationCode($dto);

        if ($response->getSmsConfirmation()->getProcessingStatus() !== CustomEvent::PHONE_ALREADY_CONFIRMED) {
            throw new RuntimeException("Телефон {$dto->getPhone()->getRaw()} не был подтвержден перед регистрацией!");
        }
    }

    public function update(UpdateInterface $dto): void
    {
        $response = $this->mindbox::update($dto);

        if ($response->getValidationErrors()) {
            throw new CustomerUpdateException($response->getValidationErrors());
        }

        if ($response->isError()) {
            throw new CustomerUpdateException(var_export([$dto, $response], true));
        }
    }

    /**
     * @throws CustomerRegisterException
     * @throws SynchronizeException
     * @throws UserNotFoundException
     * @throws UserNotUpdatedException
     */
    public function register(RegistrationInterface $dto): LoyaltyResult
    {
        $response = $this->mindbox::register($dto);

        if ($response->getValidationErrors()) {
            throw new CustomerRegisterException($response->getValidationErrors());
        }

        if ($response->isError()) {
            throw new CustomerRegisterException(var_export([$dto, $response], true));
        }

        $bitrixUserId = $this->synchronize($response);

        return (new LoyaltyResult())->setBitrixUserId($bitrixUserId);
    }

    /**
     * @throws SynchronizeException
     * @throws UserNotFoundException
     * @throws UserNotUpdatedException
     */
    private function synchronize(MindboxCustomerResponse $customer): int
    {
        $bitrixUserId = $customer->getCustomer()->getId(CustomEvent::getNameForBxUserIdField());
        if (empty($bitrixUserId)) {
            throw new SynchronizeException('Не указан ID bitrix!');
        }

        $mindboxCustomerId = $customer->getCustomer()->getId(CustomEvent::MB_CUSTOMER_ID);
        if (empty($mindboxCustomerId)) {
            throw new SynchronizeException('Не указан ID mindbox!');
        }

        (new UserRepository())->updateWithoutValidation($bitrixUserId, [
            'UF_MINDBOX_ID' => $mindboxCustomerId,
        ]);

        return $bitrixUserId;
    }
}
